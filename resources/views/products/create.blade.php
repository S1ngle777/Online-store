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

                        <div>
                            <x-input-label for="name" :value="__('products.name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('products.description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
                                       name="has_sizes" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       onchange="document.getElementById('sizes-input').style.display = this.checked ? 'block' : 'none'">
                                <span class="ml-2 text-sm text-gray-600">{{ __('products.has_sizes') }}</span>
                            </label>
                        </div>

                        <div id="sizes-input" class="mt-4" style="display: none">
                            <x-input-label :value="__('products.sizes_and_quantity')" class="mb-3"/>
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
                            <x-input-label for="category_id" :value="__('products.category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
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
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>