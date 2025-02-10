<x-app-layout>
    <script>
        function updateTotal() {
            const selectedDelivery = document.querySelector('input[name="delivery_method_id"]:checked');
            if (!selectedDelivery) return;
            
            const deliveryPrice = parseFloat(selectedDelivery.dataset.price) || 0;
            const subtotal = {{ $totalPrice }}; // Original items total
            const total = subtotal + deliveryPrice;
            
            // Update delivery cost display
            document.getElementById('delivery-cost').textContent = 
                deliveryPrice.toFixed(2) + ' MDL';
            
            // Update total amount display
            document.getElementById('total-amount').textContent = 
                total.toFixed(2) + ' MDL';
                
            // Update hidden input for form submission
            document.getElementById('total_amount').value = total;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const preselectedDelivery = document.querySelector('input[name="delivery_method_id"]:checked');
            if (preselectedDelivery) {
                updateTotal();
            }
            
            document.querySelectorAll('input[name="delivery_method_id"]').forEach(radio => {
                radio.addEventListener('change', updateTotal);
            });
        });
    </script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Оформление заказа
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->has('items.*'))
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
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name', auth()->user()->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email', auth()->user()->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="phone" value="Телефон" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                                        :value="old('phone')" required />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="address" value="Адрес доставки" />
                                    <textarea id="address" 
                                              name="address"
                                              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                              rows="3" 
                                              required>{{ old('address', $defaultAddress) }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="notes" value="Примечания к заказу" />
                                    <textarea id="notes" name="notes"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>

                                <!-- Способ доставки -->
                                <div class="mb-4">
                                    <x-input-label value="Способ доставки" />
                                    <div class="space-y-2 mt-2">
                                        @foreach ($deliveryMethods as $method)
                                            <div>
                                                <label class="flex items-start">
                                                    <input type="radio" 
                                                           name="delivery_method_id"
                                                           value="{{ $method->id }}" 
                                                           data-price="{{ $method->price }}"
                                                           class="mt-1 form-radio border-gray-300 text-primary focus:ring-0"
                                                           {{ (old('delivery_method_id') ?? session('delivery_method_id')) == $method->id ? 'checked' : '' }}
                                                           onchange="updateTotal()" 
                                                           required>
                                                    <div class="ml-2">
                                                        <span class="font-medium block">{{ $method->name }}</span>
                                                        <span class="text-gray-500 text-sm">
                                                            {{ $method->price }} MDL, {{ $method->delivery_time }} дн.
                                                        </span>
                                                        <p class="text-sm text-gray-500">{{ $method->description }}</p>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Способ оплаты -->
                                <div class="mb-4">
                                    <x-input-label value="Способ оплаты" />
                                    <div class="space-y-2 mt-2">
                                        <div>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="payment_method" 
                                                       value="cash"
                                                       class="form-radio border-gray-300 text-primary focus:ring-0"
                                                       {{ (old('payment_method') ?? session('payment_method')) == 'cash' ? 'checked' : '' }} 
                                                       required>
                                                <span class="ml-2">Наличными при получении</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="payment_method" 
                                                       value="card"
                                                       class="form-radio border-gray-300 text-primary focus:ring-0"
                                                       {{ (old('payment_method') ?? session('payment_method')) == 'card' ? 'checked' : '' }} 
                                                       required>
                                                <span class="ml-2">Банковской картой онлайн</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Сохранение адреса -->
                                @auth
                                    <div class="mb-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="save_address" class="form-checkbox text-primary"
                                                value="1">
                                            <span class="ml-2 text-sm text-gray-600">
                                                Сохранить этот адрес для будущих заказов
                                            </span>
                                        </label>
                                    </div>
                                @endauth
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
                                                    @if(isset($item['size_name']))
                                                        <span class="ml-2">Размер: {{ $item['size_name'] }}</span>
                                                    @endif
                                                    @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                        <span class="text-green-600 ml-2">
                                                            -{{ number_format((1 - $item['price'] / $item['original_price']) * 100, 0) }}%
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                    <span class="line-through text-sm text-gray-500">
                                                        {{ number_format($item['original_price'] * $item['quantity'], 2) }} MDL
                                                    </span>
                                                    <br>
                                                    <span class="text-red-600">
                                                        {{ number_format($item['price'] * $item['quantity'], 2) }} MDL
                                                    </span>
                                                @else
                                                    <span>{{ number_format($item['price'] * $item['quantity'], 2) }} MDL</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t pt-4 mt-4">
                                    @if ($totalSaving > 0)
                                        <div class="bg-green-50 p-4 rounded-md mb-4">
                                            <div class="text-green-700">
                                                <p>Ваша экономия: <span
                                                        class="font-bold">{{ number_format($totalSaving, 2) }}
                                                        MDL</span></p>
                                                <p class="text-sm mt-1">
                                                    <span
                                                        class="line-through">{{ number_format($totalOriginalPrice, 2) }}
                                                        MDL</span> →
                                                    <span
                                                        class="font-semibold">{{ number_format($totalPrice, 2) }}
                                                        MDL</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span>Стоимость товаров:</span>
                                            <span>{{ number_format($totalPrice, 2) }} MDL</span>
                                        </div>

                                        <div class="flex justify-between text-sm">
                                            <span>Стоимость доставки:</span>
                                            <span id="delivery-cost">0.00 MDL</span>
                                        </div>

                                        <div class="flex justify-between items-center font-bold pt-2 border-t">
                                            <p>Итого к оплате:</p>
                                            <div class="text-right">
                                                <span
                                                    class="font-bold {{ $totalSaving > 0 ? 'text-red-600' : '' }}"
                                                    id="total-amount">
                                                    {{ number_format($totalPrice, 2) }} MDL
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Добавляем скрытое поле для передачи итоговой суммы -->
                        <input type="hidden" id="total_amount" name="total_amount" value="{{ $totalPrice }}">

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
