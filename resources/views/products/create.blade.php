<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Добавление товара') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Название')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Описание')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Цена')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" step="0.01" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Количество на складе')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock')" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       name="has_sizes" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       onchange="document.getElementById('sizes-input').style.display = this.checked ? 'block' : 'none'">
                                <span class="ml-2 text-sm text-gray-600">Товар имеет разные размеры</span>
                            </label>
                        </div>

                        <div id="sizes-input" class="mt-4" style="display: none">
                            <x-input-label value="Размеры и количество" class="mb-3"/>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach(App\Models\Size::where('type', 'clothing')->get() as $size)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors">
                                        <label class="flex items-start">
                                            <input type="checkbox" 
                                                   name="sizes[]" 
                                                   value="{{ $size->id }}"
                                                   class="rounded border-gray-300 text-indigo-600 mt-1">
                                            <div class="ml-3 flex-1">
                                                <span class="block font-medium text-gray-700">{{ $size->name }}</span>
                                                <input type="number" 
                                                       name="size_stocks[{{ $size->id }}]"
                                                       value="0"
                                                       min="0"
                                                       class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                       placeholder="Количество">
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Категория')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Изображение')" />
                            <input type="file" id="image" name="image" class="mt-1" accept="image/*">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Добавить товар') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>