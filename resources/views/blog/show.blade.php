<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="flex space-x-2">
                        <x-primary-button onclick="window.location='{{ route('blog.edit', $post) }}'">
                            {{ __('blog.edit_post') }}
                        </x-primary-button>
                        <form action="{{ route('blog.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('{{ __('blog.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('blog.delete_post') }}
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Изображение -->
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-96 object-cover mb-8 rounded-lg">
                    @endif

                    <!-- Мета-информация -->
                    <div class="flex items-center text-gray-500 text-sm mb-6">
                        <span>
                            @if($post->is_published && $post->published_at)
                                {{ $post->published_at->format('d.m.Y') }}
                            @else
                                Создан: {{ $post->created_at->format('d.m.Y') }}
                            @endif
                        </span>
                        <span class="mx-2">•</span>
                        <span>{{ $post->user->name }}</span>
                        @if(auth()->user()?->isAdmin())
                            <span class="mx-2">•</span>
                            <span class="text-yellow-600">
                                {{ $post->is_published ? __('blog.published') : __('blog.draft') }}
                            </span>
                        @endif
                    </div>

                    <!-- Содержимое -->
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Навигация -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <a href="{{ route('blog.index') }}" 
                           class="text-primary hover:text-primary-dark">
                            ← {{ __('blog.return_to_post_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>