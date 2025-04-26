<x-tenancy::layouts.app>
    @slot('title', 'Dashboard')
    <x-layouts.app.sections.header class="bg-primary text-light">
        @slot('title', config('app.name'))
        @slot('links')
            <x-user::header.links />
        @endslot
    </x-layouts.app.sections.header>

    <div id="appCapsule">
        <div class="row section gy-3 mb-3 mt-0">
            <div class="col-md-auto">
                <x-a class="card text-dark" route='wallet'>
                    <div class="-img-wrapper card-img card-img-top bg-dark -position-absolute">
                        <i class="fas text-light fa-wallet fa-xl image-block imaged w48"></i>
                    </div>
                    <div class="card-body text-center">
                        <div class="card-title fw-bold text-body">
                            <i class="fas fa-paper-plane fa-beat"></i>
                            {{ __('Wallet') }}
                        </div>
                        <p class="card-text small">{{ __('Manage your money wisely!') }}</p>
                    </div>
                </x-a>
            </div>
        </div>
</x-tenancy::layouts.app>
