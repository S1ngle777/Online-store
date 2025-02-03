<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Редактирование поста') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('blog.update', $post) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Заголовок')" />
                            <x-text-input id="title" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="title" 
                                         :value="old('title', $post->title)" 
                                         required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Содержание')" />
                            <textarea id="content"
                                    name="content"
                                    rows="10"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>{{ old('content', $post->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Изображение')" />
                            @if($post->image)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($post->image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-48 h-32 object-cover rounded">
                                </div>
                            @endif
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="mt-1"
                                   accept="image/*">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="is_published"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       value="1"
                                       {{ $post->is_published ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Опубликован') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Сохранить изменения') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>