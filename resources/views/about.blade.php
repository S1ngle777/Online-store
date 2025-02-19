<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('about.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Левая колонка с текстом -->
                    <div class="space-y-6">
                        <h3 class="text-2xl font-bold text-primary">{{ __('about.main_heading') }}</h3>
                        <p class="text-gray-600">
                            {{ __('about.description_1') }}
                        </p>
                        <p class="text-gray-600">
                            {{ __('about.description_2') }}
                        </p>
                        
                        <div class="border-l-4 border-primary pl-4 mt-6">
                            <h4 class="text-lg font-semibold mb-2">{{ __('about.mission_title') }}</h4>
                            <p class="text-gray-600">
                                {{ __('about.mission_text') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg mt-6">
                            <h4 class="text-lg font-semibold mb-4">{{ __('about.why_choose_us') }}</h4>
                            <ul class="list-disc list-inside space-y-2 text-gray-600">
                                <li>{{ __('about.features.unique_works') }}</li>
                                <li>{{ __('about.features.traditional_motifs') }}</li>
                                <li>{{ __('about.features.quality_materials') }}</li>
                                <li>{{ __('about.features.handmade') }}</li>
                                <li>{{ __('about.features.individual_approach') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Правая колонка с изображениями -->
                    <div class="space-y-6">
                        <img src="{{ asset('storage/about/workshop.jpg') }}" 
                             alt="{{ __('about.images.workshop') }}" 
                             class="rounded-lg shadow-md w-full h-64 object-cover mb-4">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <img src="{{ asset('storage/about/craftsman1.jpg') }}" 
                                 alt="{{ __('about.images.craftsman1') }}" 
                                 class="rounded-lg shadow-md w-full h-48 object-cover">
                            <img src="{{ asset('storage/about/craftsman2.jpg') }}" 
                                 alt="{{ __('about.images.craftsman2') }}" 
                                 class="rounded-lg shadow-md w-full h-48 object-cover">
                        </div>

                        <div class="bg-primary bg-opacity-10 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold mb-4">{{ __('about.contact_info') }}</h4>
                            <div class="space-y-2 text-gray-600">
                                <p>📍 {{ __('footer.location') }}</p>
                                <p>📞 +373 77 777 777</p>
                                <p>✉️ info@handmade.md</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>