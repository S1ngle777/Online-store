<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="flex space-x-4">
                        <a href="{{ route('products.edit', $product) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('products.edit_product') }}
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" 
                              onsubmit="return confirm('{{ __('products.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('products.delete_product') }}
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Левая колонка с изображением -->
                        <div class="w-full md:w-1/2">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                            @endif
                        </div>
                        
                        <!-- Правая колонка с информацией -->
                        <div class="w-full md:w-1/2">
                            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                            <!-- Добавляем рейтинг сразу под названием -->
                            <div class="flex items-center mt-2">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $product->averageRating() ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">
                                        {{ number_format($product->averageRating(), 1) }} {{ __('products.out_of_five') }} 
                                        ({{ $product->reviewsCount() }} {{ __('products.reviews_count') }})
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-600 mt-4">{{ $product->description }}</p>
                            @if($product->hasActiveDiscount())
                                <div class="mt-4">
                                    <span class="line-through text-gray-500">{{ $product->price }} MDL</span>
                                    <span class="text-2xl font-bold text-red-600 ml-2">
                                        {{ number_format($product->discounted_price, 2) }} MDL
                                    </span>
                                    <span class="ml-2 bg-red-100 text-red-800 text-sm font-semibold px-2.5 py-0.5 rounded">
                                        -{{ $product->discount }}%
                                    </span>
                                    @if($product->discount_ends_at)
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ __('products.promotion_ends') }} {{ $product->discount_ends_at->format('d.m.Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="text-2xl font-bold mt-4">{{ $product->price }} MDL</p>
                            @endif
                            <p class="text-gray-600 mt-2">{{ __('products.in_stock') }}: {{ $product->stock }}</p>
                            
                            {{-- Отображение размеров для неавторизованных пользователей --}}
                            @guest
                                @if($product->has_sizes)
                                    <div class="mt-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($product->sizes as $size)
                                                <div class="relative">
                                                    <span class="block min-w-[3rem] text-center py-2 px-3 border rounded-md 
                                                           {{ $size->pivot->stock <= 0 
                                                               ? 'bg-gray-100 text-gray-400 border-gray-200' 
                                                               : 'bg-white text-gray-700 border-gray-300' }}">
                                                        {{ $size->name }}
                                                    </span>
                                                    @if($size->pivot->stock > 0)
                                                        <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs px-1 rounded-full">
                                                            {{ $size->pivot->stock }} {{ __('products.pieces') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endguest
                            
                            @auth
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6">
                                    @csrf
                                    <div class="flex flex-col gap-4">
                                        @if($product->has_sizes)
                                            <div>
                                                <label for="size" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.select_size') }}</label>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($product->sizes as $size)
                                                        <label class="relative">
                                                            <input type="radio" 
                                                                   name="size" 
                                                                   value="{{ $size->id }}"
                                                                   {{ $size->pivot->stock <= 0 ? 'disabled' : '' }}
                                                                   class="peer absolute opacity-0 w-full h-full cursor-pointer"
                                                                   required>
                                                            <span class="block min-w-[3rem] text-center py-2 px-3 border rounded-md 
                                                                       {{ $size->pivot->stock <= 0 
                                                                           ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' 
                                                                           : 'peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white hover:border-primary cursor-pointer' }}
                                                                       transition-colors">
                                                                {{ $size->name }}
                                                            </span>
                                                            @if($size->pivot->stock > 0)
                                                                <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs px-1 rounded-full">
                                                                    {{ $size->pivot->stock }} {{ __('products.pieces') }}
                                                                </span>
                                                            @endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('size')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center border border-gray-300 rounded-md">
                                                <button type="button" 
                                                        class="px-3 py-3 text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-l-md"
                                                        onclick="decrementQuantity()">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                
                                                <input type="number" 
                                                       id="quantity" 
                                                       name="quantity" 
                                                       value="1" 
                                                       min="1" 
                                                       max="{{ $product->has_sizes ? ($size->pivot->stock ?? 0) : $product->stock }}"
                                                       class="w-14 py-2 text-right border-0 focus:outline-none focus:ring-0 focus:border-gray-300"
                                                       readonly>
                                                
                                                <button type="button"
                                                        class="px-3 py-3 text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-r-md"
                                                        onclick="incrementQuantity()">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <x-primary-button>{{ __('products.add_to_cart') }}</x-primary-button>
                                        </div>
                                    </div>
                                </form>

                                <script>
                                    // Add this to update max quantity when size is selected
                                    document.querySelectorAll('input[name="size"]').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            const stockSpan = this.closest('label').querySelector('span[class*="bg-gray-500"]');
                                            const stock = stockSpan ? parseInt(stockSpan.textContent) : 0;
                                            document.getElementById('quantity').setAttribute('max', stock);
                                            // Reset quantity if it's more than available stock
                                            const quantityInput = document.getElementById('quantity');
                                            if (parseInt(quantityInput.value) > stock) {
                                                quantityInput.value = stock;
                                            }
                                        });
                                    });

                                    function incrementQuantity() {
                                        const input = document.getElementById('quantity');
                                        const maxValue = parseInt(input.getAttribute('max'));
                                        const currentValue = parseInt(input.value);
                                        if (currentValue < maxValue) {
                                            input.value = currentValue + 1;
                                        }
                                    }

                                    function decrementQuantity() {
                                        const input = document.getElementById('quantity');
                                        const currentValue = parseInt(input.value);
                                        if (currentValue > 1) {
                                            input.value = currentValue - 1;
                                        }
                                    }
                                </script>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Отзывы в отдельной карточке -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <!-- Сортировка отзывов -->
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-xl font-semibold">{{ __('products.reviews') }}</h3>
                        <select name="sort" 
                                onchange="window.location.href=this.value"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}" 
                                    {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                                {{ __('products.sort_reviews.date_desc') }}
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_asc']) }}"
                                    {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                                {{ __('products.sort_reviews.date_asc') }}
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_desc']) }}"
                                    {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>
                                {{ __('products.sort_reviews.rating_desc') }}
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_asc']) }}"
                                    {{ request('sort') === 'rating_asc' ? 'selected' : '' }}>
                                {{ __('products.sort_reviews.rating_asc') }}
                            </option>
                        </select>
                    </div>

                    @auth
                        @if (!$product->reviews()->where('user_id', auth()->id())->exists())
                            @if (auth()->user()->hasPurchased($product))
                                <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="mb-6">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.your_rating') }}</label>
                                        <div class="flex items-center space-x-1" x-data="{ rating: 0, hoverRating: 0 }">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           name="rating" 
                                                           value="{{ $i }}" 
                                                           class="hidden" 
                                                           x-on:click="rating = {{ $i }}"
                                                           {{ old('rating') == $i ? 'checked' : '' }}>
                                                    <svg class="w-8 h-8 transition-colors duration-200"
                                                         x-on:mouseover="hoverRating = {{ $i }}"
                                                         x-on:mouseleave="hoverRating = 0"
                                                         :class="{
                                                             'text-yellow-400': hoverRating >= {{ $i }} || rating >= {{ $i }},
                                                             'text-gray-300': hoverRating < {{ $i }} && rating < {{ $i }}
                                                         }"
                                                         fill="currentColor" 
                                                         viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </label>
                                            @endfor
                                        </div>
                                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                                    </div>

                                    <div class="mb-4">
                                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">{{ __('products.comment') }}</label>
                                        <textarea id="comment" 
                                                name="comment" 
                                                rows="3" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('comment') }}</textarea>
                                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                    </div>

                                    <div class="flex justify-end">
                                        <x-primary-button type="submit">
                                            {{ __('products.submit_review') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            @else
                                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-600">{{ __('products.purchase_required') }}</p>
                                </div>
                            @endif
                        @endif
                    @endauth

                    <!-- Список отзывов -->
                    @foreach ($reviews as $review)
                        <div class="border-b pb-4 mb-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor"
                                                 viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>

                                    <!-- Кнопка удаления -->
                                    @if(auth()->id() === $review->user_id || (auth()->user() && auth()->user()->isAdmin()))
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                                    onclick="return confirm('{{ __('products.delete_review_confirm') }}')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Имя пользователя и время -->
                                <div class="flex items-center mt-1">
                                    <span class="text-sm text-gray-500">{{ $review->user->name }}</span>
                                    <span class="mx-2 text-gray-500">•</span>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>

                                <p class="mt-2 text-gray-600">{{ $review->comment }}</p>

                                <!-- Ответ администратора -->
                                @if($review->admin_reply)
                                    <div class="mt-3 pl-4 border-l-4 border-primary">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold">{{ __('products.admin_reply') }}:</span>
                                            {{ $review->admin_reply }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $review->admin_reply_at->diffForHumans() }}</p>
                                    </div>
                                @endif

                                <!-- Форма ответа администратора -->
                                @if(auth()->check() && auth()->user()->isAdmin() && !$review->admin_reply)
                                    <form action="{{ route('reviews.reply', $review) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="flex items-start space-x-2">
                                            <textarea name="admin_reply" 
                                                    rows="2" 
                                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="{{ __('products.reply_placeholder') }}"></textarea>
                                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                                                {{ __('products.reply') }}
                                            </button>
                                        </div>
                                    </form>
                                @endif

                                <!-- Лайки и дизлайки -->
                                @auth
                                    <div class="mt-3">
                                        <div class="flex items-center space-x-4">
                                            <form action="{{ route('reviews.vote', $review) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="vote_type" value="like">
                                                <button type="submit" class="flex items-center space-x-1 text-sm {{ $review->userVote?->vote_type === 'like' ? 'text-green-600' : 'text-gray-500' }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                    </svg>
                                                    <span>{{ $review->votes->where('vote_type', 'like')->count() }}</span>
                                                </button>
                                            </form>

                                            <form action="{{ route('reviews.vote', $review) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="vote_type" value="dislike">
                                                <button type="submit" class="flex items-center space-x-1 text-sm {{ $review->userVote?->vote_type === 'dislike' ? 'text-red-600' : 'text-gray-500' }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                                    </svg>
                                                    <span>{{ $review->votes->where('vote_type', 'dislike')->count() }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>