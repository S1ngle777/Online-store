<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('privacy.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="mb-4 text-gray-600">{{ __('privacy.last_updated') }}: 14.02.2025</p>
                    
                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.introduction_title') }}</h2>
                    <p class="mb-4">
                        {!! __('privacy.introduction_text', ['store_name' => config('app.name', 'Handmade.md')]) !!}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.data_controller_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.data_controller_text') }}
                        <br>
                        <span class="font-medium">Handmade.md SRL</span><br>
                        ул. Штефана чел Маре, 1<br>
                        Кишинёв, MD-2001<br>
                        Молдова<br>
                        support@handmade.md
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.data_collected_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.data_collected_text') }}
                    </p>
                    <ul class="list-disc list-inside mb-4 pl-4 space-y-2">
                        <li>{{ __('privacy.data_types.personal_info') }}</li>
                        <li>{{ __('privacy.data_types.contact_info') }}</li>
                        <li>{{ __('privacy.data_types.account_info') }}</li>
                        <li>{{ __('privacy.data_types.order_info') }}</li>
                        <li>{{ __('privacy.data_types.review_info') }}</li>
                        <li>{{ __('privacy.data_types.usage_data') }}</li>
                        <li>{{ __('privacy.data_types.cookies') }}</li>
                    </ul>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.how_data_collected_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.how_data_collected_text') }}
                    </p>
                    <ul class="list-disc list-inside mb-4 pl-4 space-y-2">
                        <li>{{ __('privacy.collection_methods.register') }}</li>
                        <li>{{ __('privacy.collection_methods.place_order') }}</li>
                        <li>{{ __('privacy.collection_methods.contact_us') }}</li>
                        <li>{{ __('privacy.collection_methods.leave_review') }}</li>
                        <li>{{ __('privacy.collection_methods.browse') }}</li>
                    </ul>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.how_data_used_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.how_data_used_text') }}
                    </p>
                    <ul class="list-disc list-inside mb-4 pl-4 space-y-2">
                        <li>{{ __('privacy.usage_purposes.process_orders') }}</li>
                        <li>{{ __('privacy.usage_purposes.communication') }}</li>
                        <li>{{ __('privacy.usage_purposes.account_management') }}</li>
                        <li>{{ __('privacy.usage_purposes.improvement') }}</li>
                        <li>{{ __('privacy.usage_purposes.personalization') }}</li>
                        <li>{{ __('privacy.usage_purposes.marketing') }}</li>
                        <li>{{ __('privacy.usage_purposes.security') }}</li>
                        <li>{{ __('privacy.usage_purposes.legal') }}</li>
                    </ul>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.data_sharing_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.data_sharing_text') }}
                    </p>
                    <ul class="list-disc list-inside mb-4 pl-4 space-y-2">
                        <li>{{ __('privacy.sharing_partners.delivery') }}</li>
                        <li>{{ __('privacy.sharing_partners.payment') }}</li>
                        <li>{{ __('privacy.sharing_partners.analytics') }}</li>
                        <li>{{ __('privacy.sharing_partners.legal') }}</li>
                    </ul>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.data_retention_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.data_retention_text') }}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.user_rights_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.user_rights_text') }}
                    </p>
                    <ul class="list-disc list-inside mb-4 pl-4 space-y-2">
                        <li>{{ __('privacy.rights.access') }}</li>
                        <li>{{ __('privacy.rights.correction') }}</li>
                        <li>{{ __('privacy.rights.deletion') }}</li>
                        <li>{{ __('privacy.rights.objection') }}</li>
                        <li>{{ __('privacy.rights.restriction') }}</li>
                    </ul>
                    <p class="mb-4">
                        {{ __('privacy.exercise_rights_text', ['contact_email' => 'support@handmade.md']) }}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.cookies_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.cookies_text') }}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.security_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.security_text') }}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.policy_changes_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.policy_changes_text') }}
                    </p>

                    <h2 class="text-2xl font-semibold mt-6 mb-3 text-accent">{{ __('privacy.contact_us_title') }}</h2>
                    <p class="mb-4">
                        {{ __('privacy.contact_us_text', ['contact_email' => 'support@handmade.md']) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>