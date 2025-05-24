<x-tenancy::layouts.app>
    @slot('title', 'Dashboard')
    <x-layouts.app.sections.header class="bg-primary text-light">
        @slot('title', config('app.name'))
        @slot('links')
            <x-header.notification-link />
            <x-header.user-link />
        @endslot
    </x-layouts.app.sections.header>

    <div id="appCapsule">
        <!-- Wallet Card -->
        <livewire:account::wallet />

        <livewire:account::widgets.slider />

        <!-- Transactions -->
        <livewire:transaction::recent />

        <!-- Stats -->
        <livewire:account::stats />
    </div>
</x-tenancy::layouts.app>
