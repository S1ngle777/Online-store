<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Молдавские изделия ручной работы') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Последние записи блога -->
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold">Наш блог</h3>
                    <a href="{{ route('blog.index') }}" class="text-primary hover:text-primary-dark">
                        Все записи →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($posts as $post)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                @if ($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover mb-4">
                                @endif
                                <h3 class="text-xl font-semibold mb-2">{{ $post->title }}</h3>
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit(strip_tags($post->content), 100) }}
                                </p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>{{ $post->published_at->format('d.m.Y') }}</span>
                                    <a href="{{ route('blog.show', $post) }}"
                                        class="text-primary hover:text-primary-dark">
                                        Читать далее →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Рекомендуемые категории -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold mb-6">Категории</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @foreach ($categories as $category)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold">{{ $category->name }}</h4>
                                <p class="text-gray-600 mt-2">{{ Str::limit($category->description, 100) }}</p>
                                <a href="{{ route('categories.show', $category) }}"
                                    class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">
                                    Просмотреть категорию →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Рекомендуемые продукты -->
            <div>
                <h3 class="text-2xl font-bold mb-6">Рекомендуемые продукты</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                @if ($product->image)
                                    <a href="{{ route('products.show', $product) }}" class="block mb-4">
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover transition-opacity hover:opacity-75">
                                    </a>
                                @endif
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                                <div class="mt-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            @if ($product->hasActiveDiscount())
                                                <span class="line-through text-sm text-gray-500">{{ $product->price }}
                                                    MDL</span>
                                                <span
                                                    class="font-bold text-red-600">{{ number_format($product->discounted_price, 2) }}
                                                    MDL</span>
                                                <span
                                                    class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
