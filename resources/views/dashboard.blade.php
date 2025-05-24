<x-layouts.app>
    @slot('title', 'Dashboard')
    <x-layouts.app.sections.header class="bg-primary text-light">
        @slot('title', config('app.name'))
        @slot('links')
            <x-user::header.links />
        @endslot
    </x-layouts.app.sections.header>

    <div id="appCapsule">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-3">Welcome to your dashboard!</h1>
                <p class="lead">This is the central dashboard.</p>
                <p>Here you can manage your central settings and view your data.</p>
            </div>
        </div>
    </div>
</x-layouts.app>
