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
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        
        if ($request->is_published) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($validated);

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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'nullable|boolean'
        ]);

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
            $validated['image'] = $request->file('image')->store('posts', 'public');
            $post->image = $validated['image'];
        }

        $post->title = $validated['title'];
        $post->content = $validated['content'];
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
