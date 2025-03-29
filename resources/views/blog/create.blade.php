<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('blog.create_post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Заголовок блога - Многоязычный -->
                        <div class="mb-6">
                            <x-input-label :value="__('blog.title')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('title', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('title', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('title', 'en')">English</button>
                                </div>
                            </div>

                            <div id="title_ru_container" class="lang-content">
                                <x-text-input id="title_ru" class="block mt-1 w-full" type="text" name="title_ru" :value="old('title_ru')" required autofocus placeholder="Заголовок на русском" />
                                <x-input-error :messages="$errors->get('title_ru')" class="mt-2" />
                            </div>
                            <div id="title_ro_container" class="lang-content hidden">
                                <x-text-input id="title_ro" class="block mt-1 w-full" type="text" name="title_ro" :value="old('title_ro')" required placeholder="Titlu în română" />
                                <x-input-error :messages="$errors->get('title_ro')" class="mt-2" />
                            </div>
                            <div id="title_en_container" class="lang-content hidden">
                                <x-text-input id="title_en" class="block mt-1 w-full" type="text" name="title_en" :value="old('title_en')" required placeholder="English title" />
                                <x-input-error :messages="$errors->get('title_en')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Содержание блога - Многоязычное -->
                        <div class="mb-6">
                            <x-input-label :value="__('blog.content')" />
                            
                            <div class="mb-2">
                                <div class="flex border-b border-gray-200">
                                    <button type="button" class="px-4 py-2 border-b-2 border-indigo-500 text-indigo-500 lang-tab" onclick="switchLanguageTab('content', 'ru')">Русский</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('content', 'ro')">Română</button>
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-indigo-500 lang-tab" onclick="switchLanguageTab('content', 'en')">English</button>
                                </div>
                            </div>

                            <div id="content_ru_container" class="lang-content">
                                <textarea id="content_ru" name="content_ru" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Содержание на русском">{{ old('content_ru') }}</textarea>
                                <x-input-error :messages="$errors->get('content_ru')" class="mt-2" />
                            </div>
                            <div id="content_ro_container" class="lang-content hidden">
                                <textarea id="content_ro" name="content_ro" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Conținut în română">{{ old('content_ro') }}</textarea>
                                <x-input-error :messages="$errors->get('content_ro')" class="mt-2" />
                            </div>
                            <div id="content_en_container" class="lang-content hidden">
                                <textarea id="content_en" name="content_en" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="English content">{{ old('content_en') }}</textarea>
                                <x-input-error :messages="$errors->get('content_en')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('blog.image')" />
                            <div class="relative">
                                <input type="file" id="image" name="image" class="hidden" accept="image/*"
                                    onchange="updateFileName(this)">
                                <label for="image"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                                    {{ __('blog.select_file') }}
                                </label>
                                <span id="file-name" class="ml-3 text-gray-600">{{ __('blog.no_file_selected') }}</span>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_published"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('blog.publish_now') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('blog.create_post_button') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "{{ __('blog.no_file_selected') }}";
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
</x-app-layout>