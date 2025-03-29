<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('categories.edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @csrf
                        @method('PUT')

                        <!-- Название категории - Многоязычное -->
                        <div class="mb-6">
                            <x-input-label :value="__('categories.name')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('name', 'en')">English</button>
                                </div>
                            </div>

                            <div id="name_ru_container" class="lang-content">
                                <x-text-input id="name_ru" class="block mt-1 w-full" type="text" name="name_ru" :value="old('name_ru', $category->getTranslation('name', 'ru', false))" required autofocus placeholder="Название на русском" />
                                <x-input-error :messages="$errors->get('name_ru')" class="mt-2" />
                            </div>
                            <div id="name_ro_container" class="lang-content hidden">
                                <x-text-input id="name_ro" class="block mt-1 w-full" type="text" name="name_ro" :value="old('name_ro', $category->getTranslation('name', 'ro', false))" required placeholder="Denumire în română" />
                                <x-input-error :messages="$errors->get('name_ro')" class="mt-2" />
                            </div>
                            <div id="name_en_container" class="lang-content hidden">
                                <x-text-input id="name_en" class="block mt-1 w-full" type="text" name="name_en" :value="old('name_en', $category->getTranslation('name', 'en', false))" required placeholder="English name" />
                                <x-input-error :messages="$errors->get('name_en')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Описание категории - Многоязычное -->
                        <div class="mb-6">
                            <x-input-label :value="__('categories.description')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('description', 'en')">English</button>
                                </div>
                            </div>

                            <div id="description_ru_container" class="lang-content">
                                <textarea id="description_ru" name="description_ru" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="Описание на русском">{{ old('description_ru', $category->getTranslation('description', 'ru', false)) }}</textarea>
                                <x-input-error :messages="$errors->get('description_ru')" class="mt-2" />
                            </div>
                            <div id="description_ro_container" class="lang-content hidden">
                                <textarea id="description_ro" name="description_ro" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="Descriere în română">{{ old('description_ro', $category->getTranslation('description', 'ro', false)) }}</textarea>
                                <x-input-error :messages="$errors->get('description_ro')" class="mt-2" />
                            </div>
                            <div id="description_en_container" class="lang-content hidden">
                                <textarea id="description_en" name="description_en" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="English description">{{ old('description_en', $category->getTranslation('description', 'en', false)) }}</textarea>
                                <x-input-error :messages="$errors->get('description_en')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('categories.update') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <script>
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