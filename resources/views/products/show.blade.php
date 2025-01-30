<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
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
                                        {{ number_format($product->averageRating(), 1) }} из 5 ({{ $product->reviewsCount() }} отзывов)
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-600 mt-4">{{ $product->description }}</p>
                            <p class="text-2xl font-bold mt-4">{{ $product->price }} MDL</p>
                            <p class="text-gray-600 mt-2">В наличии: {{ $product->stock }}</p>
                            
                            @auth
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6">
                                    @csrf
                                    <div class="flex items-center gap-4">
                                        <x-text-input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" />
                                        <x-primary-button>Добавить в корзину</x-primary-button>
                                    </div>
                                </form>
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
                        <h3 class="text-xl font-semibold">Отзывы</h3>
                        <select name="sort" 
                                onchange="window.location.href=this.value"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}" 
                                    {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                                Сначала новые
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_asc']) }}"
                                    {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                                Сначала старые
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_desc']) }}"
                                    {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>
                                По рейтингу (высокий)
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_asc']) }}"
                                    {{ request('sort') === 'rating_asc' ? 'selected' : '' }}>
                                По рейтингу (низкий)
                            </option>
                        </select>
                    </div>

                    @auth
                        @if (!$product->reviews()->where('user_id', auth()->id())->exists())
                            @if (auth()->user()->hasPurchased($product))
                                <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="mb-6">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ваша оценка</label>
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
                                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Комментарий</label>
                                        <textarea id="comment" 
                                                name="comment" 
                                                rows="3" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('comment') }}</textarea>
                                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                    </div>

                                    <div class="flex justify-end">
                                        <x-primary-button type="submit">
                                            Отправить отзыв
                                        </x-primary-button>
                                    </div>
                                </form>
                            @else
                                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-600">Отзывы могут оставлять только пользователи, купившие этот товар</p>
                                </div>
                            @endif
                        @endif
                    @endauth

                    <!-- Список отзывов -->
                    @foreach ($reviews as $review)
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <!-- Рейтинг и информация о пользователе -->
                                    <div class="flex items-center">
                                        <!-- Звезды рейтинга -->
                                    </div>
                                    <p class="mt-2 text-gray-600">{{ $review->comment }}</p>
                                    
                                    <!-- Ответ администратора -->
                                    @if($review->admin_reply)
                                        <div class="mt-3 pl-4 border-l-4 border-primary">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Ответ администратора:</span>
                                                {{ $review->admin_reply }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $review->admin_reply_at->diffForHumans() }}</p>
                                        </div>
                                    @endif

                                    <!-- Лайки/дизлайки -->
                                    @auth
                                        <div class="mt-3 flex items-center space-x-4">
                                            <form action="{{ route('reviews.vote', $review) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="vote_type" value="like">
                                                <button type="submit" class="flex items-center space-x-1 text-sm {{ $review->userVote?->vote_type === 'like' ? 'text-green-600' : 'text-gray-500' }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                    </svg>
                                                    <span>{{ $review->likes }}</span>
                                                </button>
                                            </form>

                                            <form action="{{ route('reviews.vote', $review) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="hidden" name="vote_type" value="dislike">
                                                <button type="submit" class="flex items-center space-x-1 text-sm {{ $review->userVote?->vote_type === 'dislike' ? 'text-red-600' : 'text-gray-500' }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                                    </svg>
                                                    <span>{{ $review->dislikes }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endauth
                                </div>

                                <!-- Форма ответа администратора -->
                                @if(auth()->user()?->isAdmin() && !$review->admin_reply)
                                    <form action="{{ route('reviews.admin-reply', $review) }}" method="POST" class="mt-3">
                                        @csrf
                                        <textarea name="admin_reply" 
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  rows="2"
                                                  placeholder="Ответить на отзыв..."></textarea>
                                        <div class="mt-2 flex justify-end">
                                            <x-primary-button type="submit">
                                                Ответить
                                            </x-primary-button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>