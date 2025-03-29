<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        if (auth()->user()?->isAdmin()) {
            // Для админа показываем все посты
            $posts = Post::orderBy('published_at', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);
        } else {
            // Для остальных только опубликованные
            $posts = Post::where('is_published', true)
                        ->orderBy('published_at', 'desc')
                        ->paginate(6);
        }
        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if (!$post->is_published && !auth()->user()?->isAdmin()) {
            abort(404);
        }
        return view('blog.show', compact('post'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ru' => 'required|max:255',
            'title_ro' => 'required|max:255',
            'title_en' => 'required|max:255',
            'content_ru' => 'required',
            'content_ro' => 'required',
            'content_en' => 'required',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean'
        ]);

        // Создаем новый экземпляр поста
        $post = new Post();
        
        // Устанавливаем переводы для заголовка
        $post->setTranslation('title', 'ru', $request->title_ru);
        $post->setTranslation('title', 'ro', $request->title_ro);
        $post->setTranslation('title', 'en', $request->title_en);
        
        // Устанавливаем переводы для содержимого
        $post->setTranslation('content', 'ru', $request->content_ru);
        $post->setTranslation('content', 'ro', $request->content_ro);
        $post->setTranslation('content', 'en', $request->content_en);
        
        // Создаем ЧПУ (slug) из русского заголовка (или можно выбрать любой язык)
        $post->slug = Str::slug($request->title_ru);
        $post->user_id = auth()->id();
        
        // Обработка статуса публикации
        $post->is_published = $request->boolean('is_published');
        if ($post->is_published) {
            $post->published_at = now();
        }

        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        return redirect()->route('blog.index')
            ->with('success', __('blog.created_successfully'));
    }

    public function edit(Post $post)
    {
        return view('blog.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title_ru' => 'required|max:255',
            'title_ro' => 'required|max:255',
            'title_en' => 'required|max:255',
            'content_ru' => 'required',
            'content_ro' => 'required',
            'content_en' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'nullable|boolean'
        ]);

        // Устанавливаем переводы для заголовка
        $post->setTranslation('title', 'ru', $request->title_ru);
        $post->setTranslation('title', 'ro', $request->title_ro);
        $post->setTranslation('title', 'en', $request->title_en);
        
        // Устанавливаем переводы для содержимого
        $post->setTranslation('content', 'ru', $request->content_ru);
        $post->setTranslation('content', 'ro', $request->content_ro);
        $post->setTranslation('content', 'en', $request->content_en);
        
        // Обновляем slug, если изменился русский заголовок (можно выбрать любой язык)
        $post->slug = Str::slug($request->title_ru);

        // Обработка статуса публикации и даты публикации
        $isNowPublished = $request->boolean('is_published');
        $wasPublished = $post->is_published;

        if ($isNowPublished && !$wasPublished) {
            // Если публикуется впервые
            $post->published_at = now();
        } elseif (!$isNowPublished && $wasPublished) {
            // Если снимается с публикации
            $post->published_at = null;
        }
        
        // Если статус публикации не изменился, оставляем существующий published_at
        $post->is_published = $isNowPublished;

        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        return redirect()->route('blog.show', $post)
            ->with('success', __('blog.updated_successfully'));
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        
        return redirect()->route('blog.index')
            ->with('success', __('blog.deleted_successfully'));
    }
}
