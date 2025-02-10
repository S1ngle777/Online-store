<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Изменение товара') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Название')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Описание')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Цена')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" step="1" :value="old('price', $product->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Количество на складе')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock)" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Категория')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Изображение')" />
                            @if ($product->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="mt-1" accept="image/*">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="discount" :value="__('Скидка (%)')" />
                            <x-text-input id="discount" 
                                          class="block mt-1 w-full" 
                                          type="number" 
                                          name="discount" 
                                          min="0"
                                          max="100"
                                          step="0.01" 
                                          :value="old('discount', $product->discount)" />
                            <x-input-error :messages="$errors->get('discount')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="discount_ends_at" :value="__('Действует до')" />
                            <x-text-input id="discount_ends_at" 
                                          class="block mt-1 w-full" 
                                          type="datetime-local" 
                                          name="discount_ends_at" 
                                          :value="old('discount_ends_at', $product->discount_ends_at?->format('Y-m-d\TH:i'))" />
                            <x-input-error :messages="$errors->get('discount_ends_at')" class="mt-2" />
                        </div>

                        <!-- Чекбокс для включения размеров -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       name="has_sizes" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       {{ $product->has_sizes ? 'checked' : '' }}
                                       onchange="document.getElementById('sizes-input').style.display = this.checked ? 'block' : 'none'">
                                <span class="ml-2 text-sm text-gray-600">Товар имеет разные размеры</span>
                            </label>
                        </div>

                        <!-- Секция размеров -->
                        <div id="sizes-input" class="mt-4" style="display: {{ $product->has_sizes ? 'block' : 'none' }}">
                            <x-input-label value="Размеры и количество" class="mb-3"/>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach(App\Models\Size::where('type', 'clothing')->get() as $size)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors {{ $product->sizes->contains($size->id) ? 'border-indigo-500 ring-2 ring-indigo-500/20' : '' }}">
                                        <label class="flex items-start">
                                            <input type="checkbox" 
                                                   name="sizes[]" 
                                                   value="{{ $size->id }}"
                                                   {{ $product->sizes->contains($size->id) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 mt-1">
                                            <div class="ml-3 flex-1">
                                                <span class="block font-medium text-gray-700">{{ $size->name }}</span>
                                                <input type="number" 
                                                       name="size_stocks[{{ $size->id }}]"
                                                       value="{{ $product->getSizeStock($size->id) }}"
                                                       min="0"
                                                       class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                       placeholder="Количество">
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Изменить товар') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>