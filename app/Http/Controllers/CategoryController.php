<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $products = $category->products()->paginate(12);
        return view('categories.show', compact('category', 'products'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ru' => 'required|string|max:255',
            'name_ro' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ru' => 'required|string',
            'description_ro' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Создаем slug из английского названия
        $slug = \Str::slug($request->input('name_en'));
        
        // Создаем категорию
        $category = new Category();
        $category->slug = $slug;
        
        // Устанавливаем переводы
        $category->setTranslation('name', 'ru', $request->input('name_ru'));
        $category->setTranslation('name', 'ro', $request->input('name_ro'));
        $category->setTranslation('name', 'en', $request->input('name_en'));
        
        $category->setTranslation('description', 'ru', $request->input('description_ru'));
        $category->setTranslation('description', 'ro', $request->input('description_ro'));
        $category->setTranslation('description', 'en', $request->input('description_en'));
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('categories', 'public');
        }
        
        $category->save();
        
        return redirect()->route('categories.index')
            ->with('success', __('categories.created_successfully'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_ru' => 'required|string|max:255',
            'name_ro' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ru' => 'required|string',
            'description_ro' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Обновляем slug из английского названия
        $category->slug = \Str::slug($request->input('name_en'));
        
        // Устанавливаем переводы
        $category->setTranslation('name', 'ru', $request->input('name_ru'));
        $category->setTranslation('name', 'ro', $request->input('name_ro'));
        $category->setTranslation('name', 'en', $request->input('name_en'));
        
        $category->setTranslation('description', 'ru', $request->input('description_ru'));
        $category->setTranslation('description', 'ro', $request->input('description_ro'));
        $category->setTranslation('description', 'en', $request->input('description_en'));
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
        }
        
        $category->save();
        
        return redirect()->route('categories.index')
            ->with('success', __('categories.updated_successfully'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', __('categories.deleted'));
    }
}
