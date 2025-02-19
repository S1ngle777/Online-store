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

                        <div>
                            <x-input-label for="title" :value="__('blog.title')" />
                            <x-text-input id="title" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="title" 
                                         :value="old('title')" 
                                         required 
                                         autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="content" :value="__('blog.content')" />
                            <textarea id="content"
                                    name="content"
                                    rows="10"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('blog.image')" />
                            <div class="relative">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       class="hidden"
                                       accept="image/*"
                                       onchange="updateFileName(this)">
                                <label for="image" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                                    {{ __('blog.select_file') }}
                                </label>
                                <span id="file-name" class="ml-3 text-gray-600">{{ __('blog.no_file_selected') }}</span>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="is_published"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       value="1"
                                       {{ old('is_published') ? 'checked' : '' }}>
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
    </script>
</x-app-layout>