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
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

            // Move has_sizes processing before validation
            $has_sizes = $request->has('has_sizes');

            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'discount' => 'nullable|numeric|min:0|max:100',
                'discount_ends_at' => 'nullable|date'
            ]);

            // Add has_sizes to validated data
            $validated['has_sizes'] = $has_sizes;
            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            // Create product
            $product = Product::create($validated);

            // Handle sizes if enabled
            if ($has_sizes) {
                $sizes = $request->input('sizes', []);
                $stocks = $request->input('size_stocks', []);
                
                // Prepare sizes data with stocks
                $sizesData = [];
                foreach ($sizes as $sizeId) {
                    if (isset($stocks[$sizeId])) {
                        $sizesData[$sizeId] = ['stock' => $stocks[$sizeId]];
                    }
                }
                
                // Sync sizes with their stocks
                $product->sizes()->sync($sizesData);
                
                // Update total stock as sum of all size stocks
                $product->update([
                    'stock' => array_sum(array_values($stocks))
                ]);
            }

            \DB::commit();
            return redirect()->route('products.index')->with('success', 'Товар успешно создан');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Произошла ошибка при создании товара: ' . $e->getMessage());
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
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'discount' => 'nullable|numeric|min:0|max:100',
                'discount_ends_at' => 'nullable|date',
            ]);

            $validated['has_sizes'] = $request->has('has_sizes'); // Explicitly set boolean value
            $validated['slug'] = Str::slug($validated['name']);

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            // Process discount
            if (!isset($validated['discount'])) {
                $validated['discount'] = null;
                $validated['discount_ends_at'] = null;
            }

            // Update product basic info
            $product->update($validated);

            // Handle sizes if enabled
            if ($validated['has_sizes']) {
                $sizesData = [];
                $sizes = $request->input('sizes', []);
                $sizeStocks = $request->input('size_stocks', []);
                
                foreach ($sizes as $sizeId) {
                    if (isset($sizeStocks[$sizeId])) {
                        $sizesData[$sizeId] = ['stock' => $sizeStocks[$sizeId]];
                    }
                }
                
                // Sync sizes with their stocks
                $product->sizes()->sync($sizesData);
                
                // Update total stock as sum of all size stocks
                $product->update([
                    'stock' => array_sum($sizesData ? array_column($sizesData, 'stock') : [0])
                ]);
            } else {
                // Remove all size relationships if has_sizes is false
                $product->sizes()->detach();
            }

            \DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Товар успешно обновлен');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Произошла ошибка при обновлении товара: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Товар успешно удален');
    }
}
