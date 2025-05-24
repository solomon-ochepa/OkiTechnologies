<div class="appBottomMenu">
    <x-a route="dashboard">
        <div class="col">
            <ion-icon name="home"></ion-icon>
            <strong>Dashboard</strong>
        </div>
    </x-a>

    <x-a route="wallet">
        <div class="col">
            <ion-icon name="wallet-outline"></ion-icon>
            <strong>Wallet</strong>
        </div>
    </x-a>

    <x-a route="settings">
        <div class="col">
            <ion-icon name="settings-outline"></ion-icon>
            <strong>Settings</strong>
        </div>
    </x-a>

    @can('dashboard', '')
        <x-a can="admin.dashboard" route="admin.dashboard">
            <div class="col">
                <ion-icon name="settings-outline"></ion-icon>
                <strong>Admin</strong>
            </div>
        </x-a>
    @endcan
</div>
