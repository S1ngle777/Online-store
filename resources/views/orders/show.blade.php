<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('order_number #') }}{{ $order->id }}
            </h2>
            @if (auth()->user()->isAdmin())
                <div class="flex items-center space-x-4">
                    <form action="{{ route('orders.update-status', $order) }}" method="POST"
                        class="flex items-center space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('orders.pending') }}
                            </option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}> {{ __('orders.processing') }}</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}> {{ __('orders.completed') }}</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}> {{ __('orders.cancelled') }}</option>
                        </select>
                        <x-primary-button>{{ __('orders.update_status') }}</x-primary-button>
                    </form>
                    
                    <form action="{{ route('orders.destroy', $order) }}" method="POST"
                        onsubmit="return confirm('{{ __('orders.delete_confirmation') }}');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>{{ __('orders.delete_order') }}</x-danger-button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('orders.order_info') }}</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">{{ __('orders.order_status') }}:</span> 
                                    <span class="px-2 inline-flex text-s leading-5 font-semibold rounded-full {{ $order->statusBadge['class'] }}">
                                        {{ $order->statusBadge['text'] }}
                                    </span>
                                </p>
                                <p><span class="font-medium">{{ __('orders.order_date') }}:</span> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                
                                <!-- Информация о стоимости -->
                                <div class="mt-4 space-y-2">
                                    <p><span class="font-medium">{{ __('orders.product_cost') }}:</span> 
                                        {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }} MDL
                                    </p>
                                    <p><span class="font-medium">{{ __('orders.delivery_method') }}:</span> 
                                        @if($order->deliveryMethod)
                                            {{ $order->deliveryMethod->name }}
                                            <span class="text-sm text-gray-600 ml-2">
                                                ({{ $order->deliveryMethod->price }} MDL)
                                            </span>
                                        @else
                                            <span class="text-gray-500">{{ __('delivery.not_specified.name') }}</span>
                                        @endif
                                    </p>
                                    <p class="pt-2 border-t"><span class="font-medium">{{ __('orders.total_payment') }}:</span> 
                                        <span class="font-bold">{{ number_format($order->total_amount, 2) }} MDL</span>
                                    </p>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold mt-6 mb-4">{{ __('orders.contacts') }}</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">{{ __('orders.name') }}:</span> {{ $order->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $order->email }}</p>
                                <p><span class="font-medium">{{ __('orders.phone') }}:</span> {{ $order->phone }}</p>
                                <p><span class="font-medium">{{ __('orders.address') }}:</span> {{ $order->address }}</p>
                                @if ($order->notes)
                                    <p><span class="font-medium">{{ __('orders.notes') }}:</span> {{ $order->notes }}</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('orders.products') }}</h3>
                            <div class="space-y-4">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between items-center border-b pb-4">
                                        <div>
                                            <h4 class="font-medium">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $item->quantity }} x {{ $item->price }} MDL
                                                @if($item->product->has_sizes)
                                                    @php
                                                        $cartKey = $item->product_id . '-' . $item->size_id;
                                                        $cartItem = session()->get('cart.' . $cartKey);
                                                        $size = App\Models\Size::find($item->size_id);
                                                    @endphp
                                                    @if($size)
                                                        <span class="ml-2">{{ __('orders.size') }}: {{ $size->name }}</span>
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $item->quantity * $item->price }} MDL</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
