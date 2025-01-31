<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Результаты поиска') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h3 class="text-lg font-medium">
                    Результаты поиска для: "{{ $query }}"
                </h3>
                <p class="text-gray-600">Найдено {{ $products->total() }} товаров</p>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                @if($product->image)
                                    <a href="{{ route('products.show', $product) }}" class="block mb-4">
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover mb-4 transition-opacity hover:opacity-75">
                                    </a>
                                @endif
                                
                                <h3 class="text-lg font-semibold mb-2">
                                    {{ $product->name }}
                                </h3>

                                <!-- Добавляем рейтинг -->
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $product->averageRating() ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-sm text-gray-600">({{ $product->reviewsCount() }})</span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($product->hasActiveDiscount())
                                            <span class="line-through text-gray-500 text-sm">{{ $product->price }} MDL</span>
                                            <span class="font-bold text-red-600">{{ number_format($product->discounted_price, 2) }} MDL</span>
                                            <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
                                                -{{ $product->discount }}%
                                            </span>
                                        @else
                                            <span class="font-bold">{{ $product->price }} MDL</span>
                                        @endif
                                    </div>
                                    @auth
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <x-primary-button>В корзину</x-primary-button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->appends(['query' => $query])->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-600">По вашему запросу ничего не найдено.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>