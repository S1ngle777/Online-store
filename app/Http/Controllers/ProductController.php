<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Фильтр по категориям (множественный выбор)
        if ($request->has('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Фильтр "Только в наличии"
        if ($request->has('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Фильтр по цене
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Сортировка с дефолтным значением name_asc
        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $reviews = $product->reviews()
            ->with(['user', 'votes'])
            ->when(request('sort') === 'rating_desc', function ($query) {
                $query->orderBy('rating', 'desc');
            })
            ->when(request('sort') === 'rating_asc', function ($query) {
                $query->orderBy('rating', 'asc');
            })
            ->when(request('sort') === 'date_desc', function ($query) {
                $query->latest();
            })
            ->when(request('sort') === 'date_asc', function ($query) {
                $query->oldest();
            })
            ->when(!request('sort'), function ($query) {
                // По умолчанию сортировка по дате (сначала новые)
                $query->latest();
            })
            ->get();

        return view('products.show', compact('product', 'reviews'));
    }

    public function create()
    {
        $product = new Product();
        $categories = Category::all();
        return view('products.create', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'name_ru' => 'required|string|max:255',
                'name_ro' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ru' => 'required|string',
                'description_ro' => 'required|string',
                'description_en' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required_without:has_sizes|nullable|integer|min:0',
                'has_sizes' => 'boolean',
                'category_id' => 'required|exists:categories,id',
                'image' => 'required|image|max:2048',
                'sizes' => 'array|required_if:has_sizes,1',
                'size_stocks' => 'array|required_if:has_sizes,1',
                'discount' => 'nullable|numeric|min:0|max:100',
                'discount_ends_at' => 'nullable|date',
            ]);

            $has_sizes = $request->boolean('has_sizes');

            // Обработка изображения
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
            }

            // Создаем продукт с нетранслируемыми данными
            $product = new Product();
            $product->slug = \Str::slug($request->input('name_en'));
            $product->price = $validated['price'];
            $product->stock = $has_sizes ? 0 : $validated['stock'];
            $product->has_sizes = $has_sizes;
            $product->category_id = $validated['category_id'];
            $product->image = $path;

            // Добавляем скидку если есть
            if (isset($validated['discount']) && $validated['discount'] > 0) {
                $product->discount = $validated['discount'];
                $product->discount_ends_at = $validated['discount_ends_at'];
            }

            // Добавляем переводы
            $product->setTranslation('name', 'ru', $request->input('name_ru'));
            $product->setTranslation('name', 'ro', $request->input('name_ro'));
            $product->setTranslation('name', 'en', $request->input('name_en'));

            $product->setTranslation('description', 'ru', $request->input('description_ru'));
            $product->setTranslation('description', 'ro', $request->input('description_ro'));
            $product->setTranslation('description', 'en', $request->input('description_en'));

            $product->save();

            // Обработка размеров (если есть)
            if ($has_sizes) {
                $sizes = $request->input('sizes', []);
                $stocks = $request->input('size_stocks', []);

                // Подготовить данные размеров с запасами
                $sizesData = [];
                foreach ($sizes as $sizeId) {
                    if (isset($stocks[$sizeId])) {
                        $sizesData[$sizeId] = ['stock' => $stocks[$sizeId]];
                    }
                }

                // Синхронизировать размеры с их запасами
                $product->sizes()->sync($sizesData);

                // Обновить общую запас как сумму всех запасов по размерам
                $product->update([
                    'stock' => array_sum(array_values($stocks))
                ]);
            }

            \DB::commit();
            return redirect()->route('products.index')
                ->with('success', __('products.created_successfully'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->withInput()
                ->with('error', __('products.create_error', ['error' => $e->getMessage()]));
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'name_ru' => 'required|string|max:255',
                'name_ro' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ru' => 'required|string',
                'description_ro' => 'required|string',
                'description_en' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required_without:has_sizes|nullable|integer|min:0',
                'has_sizes' => 'boolean',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|max:2048',
                'discount' => 'nullable|numeric|min:0|max:100',
                'discount_ends_at' => 'nullable|date',
            ]);

            $has_sizes = $request->boolean('has_sizes');

            // Обновляем slug на основе английского имени
            $product->slug = \Str::slug($request->input('name_en'));

            // Обновляем нетранслируемые поля
            $product->price = $validated['price'];
            $product->has_sizes = $has_sizes;
            $product->category_id = $validated['category_id'];

            if (!$has_sizes) {
                $product->stock = $validated['stock'];
            }

            // Обработка загрузки изображения
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $request->file('image')->store('products', 'public');
            }

            // Обработка скидки
            if (isset($validated['discount']) && $validated['discount'] > 0) {
                $product->discount = $validated['discount'];
                $product->discount_ends_at = $validated['discount_ends_at'];
            } else {
                $product->discount = null;
                $product->discount_ends_at = null;
            }

            // Обновляем переводы
            $product->setTranslation('name', 'ru', $request->input('name_ru'));
            $product->setTranslation('name', 'ro', $request->input('name_ro'));
            $product->setTranslation('name', 'en', $request->input('name_en'));

            $product->setTranslation('description', 'ru', $request->input('description_ru'));
            $product->setTranslation('description', 'ro', $request->input('description_ro'));
            $product->setTranslation('description', 'en', $request->input('description_en'));

            $product->save();

            // Обработать размеры, если включено
            if ($has_sizes) {
                $sizesData = [];
                $sizes = $request->input('sizes', []);
                $sizeStocks = $request->input('size_stocks', []);

                foreach ($sizes as $sizeId) {
                    if (isset($sizeStocks[$sizeId])) {
                        $sizesData[$sizeId] = ['stock' => $sizeStocks[$sizeId]];
                    }
                }

                // Синхронизировать размеры с их запасами
                $product->sizes()->sync($sizesData);

                // Обновить общую запас как сумму всех запасов по размерам
                $product->update([
                    'stock' => array_sum($sizesData ? array_column($sizesData, 'stock') : [0])
                ]);
            } else {
                // Удалить все связи по размерам, если has_sizes равно false
                $product->sizes()->detach();
            }

            \DB::commit();
            return redirect()->route('products.index')
                ->with('success', __('products.updated_successfully'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->withInput()
                ->with('error', __('products.update_error', ['error' => $e->getMessage()]));
        }
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', __('products.deleted_successfully'));
    }
}
