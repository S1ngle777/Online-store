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
                                
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                                
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">{{ $product->price }} MDL</span>
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