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
                            {{ __('Редактировать') }}
                        </x-primary-button>
                        <form action="{{ route('blog.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('Вы уверены, что хотите удалить этот пост?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Удалить') }}
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
                        <span>{{ $post->published_at->format('d.m.Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $post->user->name }}</span>
                    </div>

                    <!-- Содержимое -->
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Навигация -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <a href="{{ route('blog.index') }}" 
                           class="text-primary hover:text-primary-dark">
                            ← Вернуться к списку постов
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>