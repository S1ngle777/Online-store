<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>

            @auth
                @if (auth()->user()->isAdmin())
                    <div class="flex space-x-2">
                        <a href="{{ route('categories.edit', $category) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('categories.edit_category') }}
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                            onsubmit="return confirm('{{ __('categories.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('categories.delete_category') }}</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h3 class="text-lg text-gray-600">{{ $category->description }}</h3>
            </div>

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
                            <p class="text-lg font-bold mt-2 text-right"><?php echo e($product->price); ?> MDL</p>
                            @auth
                                <div class="mt-4 text-right">
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <x-primary-button>{{ __('categories.add_to_cart') }}</x-primary-button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
