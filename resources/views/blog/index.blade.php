<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Блог') }}
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <x-primary-button onclick="window.location='{{ route('blog.create') }}'">
                        {{ __('Создать пост') }}
                    </x-primary-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @if (!$post->is_published)
                                <div class="mb-4">
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                        Не опубликовано
                                    </span>
                                </div>
                            @endif
                            
                            @if ($post->image)
                                <img src="{{ Storage::url($post->image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-48 object-cover mb-4">
                            @endif
                            
                            <h3 class="text-xl font-semibold mb-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <span>
                                        @if($post->is_published && $post->published_at)
                                            {{ $post->published_at->format('d.m.Y') }}
                                        @else
                                            Создан: {{ $post->created_at->format('d.m.Y') }}
                                        @endif
                                    </span>
                                    @if(auth()->user()?->isAdmin())
                                        <span class="text-gray-400">•</span>
                                        <span class="text-gray-400">
                                            {{ $post->is_published ? 'Опубликован' : 'Черновик' }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('blog.show', $post) }}" 
                                   class="text-primary hover:text-primary-dark">
                                    Читать далее →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>