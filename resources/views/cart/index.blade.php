<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('cart.cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (count($cartItems) > 0)
                        <div class="flow-root">
                            <ul role="list" class="-my-6 divide-y divide-gray-200">
                                @foreach ($cartItems as $id => $item)
                                    <li class="flex py-6">
                                        <div
                                            class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            @if ($item['image'])
                                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                                    class="h-full w-full object-cover object-center">
                                            @endif
                                        </div>
                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3>{{ $item['name'] }}</h3>
                                                    <div class="text-right">
                                                        @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                            <span class="line-through text-sm text-gray-500">{{ $item['original_price'] * $item['quantity'] }} MDL</span>
                                                            <p class="text-red-600">{{ $item['price'] * $item['quantity'] }} MDL</p>
                                                        @else
                                                            <p>{{ $item['price'] * $item['quantity'] }} MDL</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                <p class="text-gray-500">
                                                    {{ __('cart.quantity') }} {{ $item['quantity'] }}
                                                    @if(isset($item['size_name']))
                                                        <span class="ml-2">{{ __('cart.size') }}: {{ $item['size_name'] }}</span>
                                                    @endif
                                                </p>
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-500 font-medium">
                                                        {{ __('cart.remove') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <hr class="my-6 border-t border-gray-200">

                        <div class="mt-6">
                            @if($totalSaving > 0)
                                <div class="bg-green-50 p-4 rounded-md mb-4">
                                    <div class="text-green-700">
                                        <p>{{ __('cart.savings') }}: <span class="font-bold">{{ number_format($totalSaving, 2) }} MDL</span></p>
                                        <p class="text-sm mt-1">
                                            <span class="line-through">{{ number_format($totalOriginalPrice, 2) }} MDL</span> → 
                                            <span class="font-semibold">{{ number_format($totalPrice, 2) }} MDL</span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <p>{{ __('cart.total') }}</p>
                                <div class="text-right">
                                    @if($totalSaving > 0)
                                        <span class="line-through text-sm text-gray-500">{{ number_format($totalOriginalPrice, 2) }} MDL</span><br>
                                    @endif
                                    <span class="font-bold {{ $totalSaving > 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($totalPrice, 2) }} MDL
                                    </span>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('orders.checkout') }}"
                                    class="flex items-center justify-center rounded-md border border-transparent bg-primary px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-primary-dark">
                                    {{ __('cart.checkout') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center">{{ __('cart.empty_cart') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
