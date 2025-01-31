<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Оформление заказа
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg">
                            <p>Невозможно оформить заказ: недостаточное количество товара на складе</p>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Контактная информация</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="name" value="Имя" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', auth()->user()->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', auth()->user()->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="phone" value="Телефон" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="address" value="Адрес доставки" />
                                    <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required>{{ old('address') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="notes" value="Примечания к заказу" />
                                    <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">Ваш заказ</h3>
                                
                                <div class="space-y-4">
                                    @foreach ($cartItems as $id => $item)
                                        <div class="flex justify-between items-center border-b pb-4">
                                            <div>
                                                <h4 class="font-medium">{{ $item['name'] }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    Кл-во {{ $item['quantity'] }}
                                                    @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                        <span class="text-green-600 ml-2">
                                                            -{{ number_format((1 - $item['price'] / $item['original_price']) * 100, 0) }}%
                                                        </span>
                                                    @endif
                                                    @if($errors->has("items.$id.quantity"))
                                                        <span class="text-red-600 text-sm">
                                                            (Доступно только {{ $available[$id] ?? 0 }} шт.)
                                                        </span>
                                                    @endif
                                                </p>
                                                <div class="mt-1">
                                                    @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                        <span class="line-through text-sm text-gray-500">{{ number_format($item['original_price'] * $item['quantity'], 2) }} MDL</span>
                                                        <span class="text-red-600 ml-2">{{ number_format($item['price'] * $item['quantity'], 2) }} MDL</span>
                                                    @else
                                                        <span>{{ number_format($item['price'] * $item['quantity'], 2) }} MDL</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <div class="border-t pt-4 mt-4">
                                        @if($totalSaving > 0)
                                            <div class="bg-green-50 p-4 rounded-md mb-4">
                                                <div class="text-green-700">
                                                    <p>Ваша экономия: <span class="font-bold">{{ number_format($totalSaving, 2) }} MDL</span></p>
                                                    <p class="text-sm mt-1">
                                                        <span class="line-through">{{ number_format($totalOriginalPrice, 2) }} MDL</span> → 
                                                        <span class="font-semibold">{{ number_format($totalPrice, 2) }} MDL</span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="flex justify-between items-center font-bold">
                                            <p>Итого:</p>
                                            <div class="text-right">
                                                @if($totalSaving > 0)
                                                    <span class="line-through text-sm text-gray-500">{{ number_format($totalOriginalPrice, 2) }} MDL</span><br>
                                                @endif
                                                <span class="font-bold {{ $totalSaving > 0 ? 'text-red-600' : '' }}">
                                                    {{ number_format($totalPrice, 2) }} MDL
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <x-primary-button class="ml-4">
                                Оформить заказ
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
