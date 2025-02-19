<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('products.title') }}
            </h2>
            @auth
                @if (auth()->user()->isAdmin())
                    <x-primary-button onclick="window.location='{{ route('products.create') }}'">
                        {{ __('products.add_product') }}
                    </x-primary-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white p-6 mb-6 rounded-lg shadow">
                <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Categories -->
                        <div>
                            <div x-data="{
                                open: false,
                                selected: {{ json_encode(request('categories', [])) }}
                            }" class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.categories') }}</label>
                                <button @click="open = !open" type="button"
                                    class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 relative">
                                    <span x-text="selected.length ? '{{ __('products.selected_count') }}'.replace(':count', selected.length) : '{{ __('products.select_categories') }}'"
                                        class="block pr-8"></span>
                                    <span class="absolute top-1/2 right-3 transform -translate-y-1/2">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm">
                                    @foreach ($categories as $category)
                                        <div class="relative flex items-center px-4 py-2 hover:bg-gray-100">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                id="category_{{ $category->id }}" x-model="selected"
                                                {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="category_{{ $category->id }}"
                                                class="ml-2 block text-sm text-gray-600">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="price_from" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.price_from') }}</label>
                                    <input type="number" name="price_from" id="price_from" min="0"
                                        value="{{ request('price_from') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="price_to" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.price_to') }}</label>
                                    <input type="number" name="price_to" id="price_to" min="0"
                                        value="{{ request('price_to') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Sorting -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.sort') }}</label>
                            <select name="sort" id="sort"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['name_asc', 'name_desc', 'price_asc', 'price_desc', 'newest', 'oldest'] as $sortOption)
                                    <option value="{{ $sortOption }}" {{ request('sort') == $sortOption ? 'selected' : '' }}>
                                        {{ __("products.sort_options.{$sortOption}") }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex justify-between items-center pt-4 border-t">
                        <div class="flex items-center">
                            <input type="checkbox" name="in_stock" id="in_stock" value="1"
                                {{ request('in_stock') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="in_stock" class="ml-2 text-sm text-gray-600">
                                {{ __('products.in_stock_only') }}
                            </label>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('products.reset_filters') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('products.apply_filters') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Product List -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex flex-col h-full">
                            <!-- Изображение -->
                            @if ($product->image)
                                <a href="{{ route('products.show', $product) }}" class="block mb-4">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover transition-opacity hover:opacity-75">
                                </a>
                            @endif

                            <!-- Основная информация -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <div class="flex items-center mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $product->averageRating() ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-sm text-gray-600">({{ $product->reviewsCount() }})</span>
                                </div>
                                <p class="text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                            </div>

                            <!-- Цена и кнопка (всегда внизу) -->
                            <div class="mt-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if ($product->hasActiveDiscount())
                                            <span class="line-through text-gray-500 text-sm">{{ $product->price }}
                                                MDL</span>
                                            <div>
                                                <span
                                                    class="font-bold text-red-600">{{ number_format($product->discounted_price, 2) }}
                                                    MDL</span>
                                                <span
                                                    class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                    -{{ $product->discount }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="font-bold">{{ $product->price }} MDL</span>
                                        @endif
                                    </div>
                                    @auth
                                        @if($product->has_sizes)
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                                {{ __('products.select_size') }}
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                                @csrf
                                                <x-primary-button>{{ __('products.add_to_cart') }}</x-primary-button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                @auth
                                    @if (auth()->user()->isAdmin())
                                        <div class="mt-2 flex space-x-2 justify-end">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-bold">{{ __('products.edit') }}</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-bold">{{ __('products.delete') }}</button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
