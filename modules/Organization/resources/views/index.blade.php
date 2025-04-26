<x-tenancy::layouts.app>
    @slot('title', config('$LOWER_NAME$.name'))
    <x-layouts.app.sections.header class="bg-primary text-light">
        @slot('title', config('app.name'))
        @slot('links')
            <x-header.notification-link />
            <x-header.user-link />
        @endslot
    </x-layouts.app.sections.header>

    <div id="appCapsule">
        ...
    </div>
</x-tenancy::layouts.app>
