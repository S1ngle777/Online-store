<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('products.add_product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Название товара - Многоязычное -->
                        <div class="mb-6">
                            <x-input-label :value="__('products.name')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'en')">English</button>
                                </div>
                            </div>

                            <div id="name_ru_container" class="lang-content">
                                <x-text-input id="name_ru" class="block mt-1 w-full" type="text" name="name_ru" :value="old('name_ru')" required autocomplete="off" placeholder="Название на русском" />
                                <x-input-error :messages="$errors->get('name_ru')" class="mt-2" />
                            </div>
                            <div id="name_ro_container" class="lang-content hidden">
                                <x-text-input id="name_ro" class="block mt-1 w-full" type="text" name="name_ro" :value="old('name_ro')" required autocomplete="off" placeholder="Denumire în română" />
                                <x-input-error :messages="$errors->get('name_ro')" class="mt-2" />
                            </div>
                            <div id="name_en_container" class="lang-content hidden">
                                <x-text-input id="name_en" class="block mt-1 w-full" type="text" name="name_en" :value="old('name_en')" required autocomplete="off" placeholder="English name" />
                                <x-input-error :messages="$errors->get('name_en')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Описание товара - Многоязычное -->
                        <div class="mb-6">
                            <x-input-label :value="__('products.description')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'en')">English</button>
                                </div>
                            </div>

                            <div id="description_ru_container" class="lang-content">
                                <textarea id="description_ru" name="description_ru" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="Описание на русском">{{ old('description_ru') }}</textarea>
                                <x-input-error :messages="$errors->get('description_ru')" class="mt-2" />
                            </div>
                            <div id="description_ro_container" class="lang-content hidden">
                                <textarea id="description_ro" name="description_ro" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="Descriere în română">{{ old('description_ro') }}</textarea>
                                <x-input-error :messages="$errors->get('description_ro')" class="mt-2" />
                            </div>
                            <div id="description_en_container" class="lang-content hidden">
                                <textarea id="description_en" name="description_en" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="English description">{{ old('description_en') }}</textarea>
                                <x-input-error :messages="$errors->get('description_en')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('products.price')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" step="0.01" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('products.stock')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock')" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       id="has_sizes_checkbox"
                                       name="has_sizes" 
                                       value="1"
                                       {{ old('has_sizes') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       onchange="document.getElementById('sizes-input').style.display = this.checked ? 'block' : 'none'">
                                <span class="ml-2 text-sm text-gray-600">{{ __('products.has_sizes') }}</span>
                            </label>
                        </div>

                        <div id="sizes-input" class="mt-4" style="display: {{ old('has_sizes') ? 'block' : 'none' }}">
                            <x-input-label :value="__('products.sizes_and_quantity')" class="mb-3"/>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach(App\Models\Size::where('type', 'clothing')->get() as $size)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors">
                                        <label class="flex items-start">
                                            <input type="checkbox" 
                                                   name="sizes[]" 
                                                   value="{{ $size->id }}"
                                                   {{ in_array($size->id, old('sizes', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 mt-1">
                                            <div class="ml-3 flex-1">
                                                <span class="block font-medium text-gray-700">{{ $size->name }}</span>
                                                <input type="number" 
                                                       name="size_stocks[{{ $size->id }}]"
                                                       value="{{ old('size_stocks.'.$size->id, 0) }}"
                                                       min="0"
                                                       class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                       placeholder="{{ __('products.quantity') }}">
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('products.category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Скидочные поля -->
                        <div class="mt-4">
                            <x-input-label for="discount" :value="__('products.discount')" />
                            <x-text-input id="discount" class="block mt-1 w-full" type="number" name="discount" min="0" max="100" :value="old('discount')" />
                            <x-input-error :messages="$errors->get('discount')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="discount_ends_at" :value="__('products.discount_ends_at')" />
                            <x-text-input id="discount_ends_at" class="block mt-1 w-full" type="date" name="discount_ends_at" :value="old('discount_ends_at')" />
                            <x-input-error :messages="$errors->get('discount_ends_at')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('products.image')" />
                            <div class="relative">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       class="hidden"
                                       accept="image/*"
                                       onchange="updateFileName(this)">
                                <label for="image" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                                    {{ __('products.select_file') }}
                                </label>
                                <span id="file-name" class="ml-3 text-gray-600">{{ __('products.no_file_selected') }}</span>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('products.add_product') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <script>
                        function updateFileName(input) {
                            const fileName = input.files[0] ? input.files[0].name : "{{ __('products.no_file_selected') }}";
                            document.getElementById('file-name').textContent = fileName;
                        }

                        function switchLanguageTab(fieldName, lang) {
                            // Скрыть все контейнеры с содержимым для этого поля
                            document.querySelectorAll(`[id^="${fieldName}_"][id$="_container"]`).forEach(el => {
                                el.classList.add('hidden');
                            });
                            
                            // Показать содержимое выбранного языка
                            document.getElementById(`${fieldName}_${lang}_container`).classList.remove('hidden');
                            
                            // Найти родительский контейнер с вкладками
                            const parentDiv = document.getElementById(`${fieldName}_${lang}_container`).closest('.mb-6').querySelector('.flex');
                            
                            // Сбросить все вкладки в неактивное состояние
                            parentDiv.querySelectorAll('button').forEach(tab => {
                                tab.classList.remove('border-b-2', 'border-indigo-500', 'text-indigo-500');
                                tab.classList.add('text-gray-500', 'hover:text-indigo-500');
                            });
                            
                            // Установить активную вкладку
                            const activeTab = parentDiv.querySelector(`[onclick="switchLanguageTab('${fieldName}', '${lang}')"]`);
                            activeTab.classList.remove('text-gray-500', 'hover:text-indigo-500');
                            activeTab.classList.add('border-b-2', 'border-indigo-500', 'text-indigo-500');
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>