<div>
    {{-- @slot('title', 'Organizations') --}}
    <x-layouts.app.sections.header back='admin.dashboard' class="bg-primary text-light">
        @slot('title', 'Organizations')
        @slot('links')
            <x-header.notification-link />
            <x-header.user-link />
        @endslot
    </x-layouts.app.sections.header>

    <div id="appCapsule">
        <div class="section my-3">
            {{-- Header --}}
            <div class="section-heading -padding">
                <h2 class="title">
                    <i class="fas fa-network-wired me-1"></i>
                    {{ __($title ?? 'Organizations') }}
                </h2>

                @feature(\App\Features\OrganizationCreate::class)
                    @can('admin.organizations.create')
                        <div>
                            <button class="btn btn-primary me-3" data-bs-target="#add-organization" data-bs-toggle="modal"
                                type="button" wire:click="$dispatch('open-modal')">
                                <i class="fas fa-plus-circle"></i>
                            </button>

                            @pushOnce('modals')
                                <livewire:organization::tenant.admin.modals.create />
                            @endPushOnce
                        </div>
                    @endcan
                @endfeature
            </div>

            @feature(\App\Features\OrganizationIndexSearch::class)
                <!-- Search, Filters & Quick Actions -->
                <div class="mb-4">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col">
                            <livewire:page.search />
                        </div>

                        <!-- Filters -->
                        {{-- <div class="col-auto"></div> --}}
                    </div>
                </div>
            @endfeature

            <x-alert />

            <div class="tab-content" id="myTabContent">
                <div aria-labelledby="all-tab" class="tab-pane fade show active"
                    data-list='{"valueNames":["name"],"page":{{ $limit ?? 15 }},"pagination":true}' id="all"
                    organization="tabpanel">
                    <div
                        class="mx-n4 mx-lg-n6 px-lg-6 border-top border-bottom border-200 position-relative top-1 bg-white px-4">
                        <div class="table-responsive scrollbar-overlay mx-n1 px-1">
                            <table class="table-sm fs--1 mb-0 table">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th class="sort align-middle" data-sort="name" scope="col">
                                            {{ __('Name') }}
                                        </th>

                                        <th class="text-end align-middle" scope="col"></th>
                                    </tr>
                                </thead>

                                <tbody class="list" id="organizations-table-body">
                                    @foreach ($organizations ?? [] as $organization)
                                        <tr class="hover-actions-trigger btn-reveal-trigger position-static"
                                            wire:key="{{ $organization->id }}">
                                            {{-- Name --}}
                                            <td class="name white-space-nowrap pe-5 align-middle">
                                                <a class="d-flex align-items-center text-1100"
                                                    href="{{ Route::has('admin.organization.show') ? route('admin.organization.show', $organization->slug) : 'javascript://' }} ">
                                                    <p class="text-1100 fw-bold mb-0">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $organization->name }} <x-link-icon />
                                                    </p>
                                                </a>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="white-space-nowrap pe-0 text-end align-middle">
                                                <div class="font-sans-serif btn-reveal-trigger position-static">
                                                    @if (Route::has('admin.organization.show'))
                                                        @feature(\App\Features\OrganizationShow::class)
                                                            @can('admin.organization.show')
                                                                <a class="btn btn-sm btn-reveal fs--2"
                                                                    data-bs-original-title="{{ __('View') }}"
                                                                    data-bs-toggle="tooltip"
                                                                    href="{{ route('admin.organization.show', $organization->slug) }}">
                                                                    <span class="fas fa-eye fs--2 text-muted"></span>
                                                                </a>
                                                            @endcan
                                                        @endfeature
                                                    @endif

                                                    @feature(\App\Features\OrganizationUpdate::class)
                                                        @can('admin.organization.edit')
                                                            <button aria-label="{{ __('Edit') }}"
                                                                class="btn btn-sm btn-reveal fs--2"
                                                                data-bs-original-title="{{ __('Edit') }}"
                                                                data-bs-toggle="tooltip" type="button"
                                                                wire:click="$dispatch('organization.edit', {'organization': @js($organization->slug)})">
                                                                <span class="fa-solid fa-edit fs--2"></span>
                                                            </button>
                                                        @endcan
                                                    @endfeature

                                                    @feature(\App\Features\OrganizationDelete::class)
                                                        @canAny(['admin.organization.delete.trash',
                                                            'admin.organization.delete'])
                                                            <button aria-label="{{ __('Trash') }}"
                                                                class="btn btn-sm btn-reveal fs--2"
                                                                data-bs-original-title="{{ __('Trash') }}"
                                                                data-bs-toggle="tooltip" type="button"
                                                                wire:click="$dispatch('confirm', {
                                                            data: {
                                                                id: @js($organization->id),
                                                                icon: '<i aria-hidden=\'true\' class=\'fa-solid fa-trash text-danger\'></i>',
                                                                title: '{{ $organization->name }}',
                                                                description: 'Are you sure you want to Trash this record?',
                                                                detail: '',
                                                                button_label: 'Trash',
                                                                color: 'danger',
                                                                action: 'organization.trash',
                                                            }
                                                        })">
                                                                <span class="fas fa-trash fs--2 text-danger"></span>
                                                            </button>
                                                        @endcan
                                                    @endfeature
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @isset($organizations)
                            <!-- Pagination -->
                            {{ $organizations->links() }}
                        @endisset
                    </div>
                </div>

                <!-- Trashed -->
                <div aria-labelledby="trashed-tab" class="tab-pane fade"
                    data-list='{"valueNames":["name"],"page":{{ $limit ?? 15 }},"pagination":true}' id="trashed"
                    organization="tabpanel">
                    <div
                        class="mx-n4 mx-lg-n6 px-lg-6 border-top border-bottom border-200 position-relative top-1 bg-white px-4">
                        <div class="table-responsive scrollbar-overlay mx-n1 px-1">
                            <table class="table-sm fs--1 mb-0 table">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th class="white-space-nowrap fs--1 ps-0 align-middle">
                                            <div class="form-check fs-0 mb-0">
                                                <input class="form-check-input"
                                                    data-bulk-select='{"body":"trashed-organizations-table-body"}'
                                                    id="checkbox-bulk-customers-select" type="checkbox" />
                                            </div>
                                        </th>

                                        <th class="sort align-middle" data-sort="name" scope="col">
                                            {{ __('Name') }}
                                        </th>

                                        <th class="sort align-middle" data-sort="user" scope="col">
                                            {{ __('Created By') }}
                                        </th>

                                        <th class="sort align-middle" data-sort="domains" scope="col">
                                            {{ __('Domains') }}
                                        </th>

                                        <th class="text-end align-middle" scope="col"></th>
                                    </tr>
                                </thead>

                                <tbody class="list" id="trashed-organizations-table-body">
                                    @foreach ($trashed ?? [] as $organization)
                                        <tr class="hover-actions-trigger btn-reveal-trigger position-static"
                                            wire:key="{{ $organization->id }}">
                                            <td class="fs--1 py-3 ps-0 align-middle">
                                                <div class="form-check fs-0 mb-0">
                                                    <input class="form-check-input"
                                                        data-bulk-select-row='@json($organization)'
                                                        type="checkbox" />
                                                </div>
                                            </td>

                                            {{-- Name --}}
                                            <td class="name white-space-nowrap pe-5 align-middle">
                                                <a class="d-flex align-items-center text-1100" href="javascript://">
                                                    <p class="text-1100 fw-bold mb-0">
                                                        <i class="fas fa-desktop me-1"></i>
                                                        {{ $organization->name }}
                                                    </p>
                                                </a>
                                            </td>

                                            {{-- Created By --}}
                                            <td class="user white-space-nowrap text-1000 align-middle">
                                                <a class="d-flex align-items-center text-1100"
                                                    href="{{ route('user.show', $organization->admin->id) }}"
                                                    target="__blank">
                                                    <p class="text-1100 fw-bold mb-0">
                                                        <i aria-hidden="true" class="fa fa-user-tie me-1"></i>
                                                        {{ $organization->admin->name }}
                                                    </p>
                                                </a>
                                            </td>

                                            {{-- Domains --}}
                                            <td class="domain white-space-nowrap text-1000 align-middle">
                                                @forelse ($organization->domains->pluck('domain') as $domain)
                                                    @php
                                                        $url = Str::contains($domain, '.')
                                                            ? $domain
                                                            : $domain . '.' . Str::after(config('app.url'), '://');
                                                    @endphp
                                                    <span class="badge text-bg-light">
                                                        <i aria-hidden="true" class="fa fa-globe me-1"></i>
                                                        <a class="text-bg-light" href="//{{ $url }}"
                                                            target="_blank">
                                                            {{ $url }}
                                                        </a>
                                                    </span>
                                                @empty
                                                    <span class="">-</span>
                                                @endforelse
                                            </td>

                                            <td class="white-space-nowrap pe-0 text-end align-middle">
                                                <div class="font-sans-serif btn-reveal-trigger position-static">
                                                    @canAny(['admin.organization.delete.restore',
                                                        'admin.organization.delete'])
                                                        <button aria-label="{{ __('Restore') }}"
                                                            class="btn btn-sm btn-reveal fs--2"
                                                            data-bs-original-title="{{ __('Restore') }}"
                                                            data-bs-toggle="tooltip" type="button"
                                                            wire:click="$dispatch('confirm', {
                                                            data: {
                                                                id: @js($organization->id),
                                                                icon: '<i aria-hidden=\'true\' class=\'fa-solid fa-undo text-success\'></i>',
                                                                title: '{{ $organization->name }}',
                                                                description: 'Are you sure you want to Restore this record?',
                                                                detail: '',
                                                                button_label: 'Restore',
                                                                color: 'success',
                                                                action: 'organization.restore',
                                                            }
                                                        })">
                                                            <span class="fas fa-undo fs--2 text-success"></span>
                                                        </button>
                                                    @endcanAny

                                                    @canAny(['admin.organization.delete.permanent',
                                                        'admin.organization.delete'])
                                                        <button aria-label="{{ __('Permanently delete') }}"
                                                            class="btn btn-sm btn-reveal fs--2"
                                                            data-bs-original-title="{{ __('Permanently delete') }}"
                                                            data-bs-toggle="tooltip" type="button"
                                                            wire:click="$dispatch('confirm', {
                                                            data: {
                                                                id: @js($organization->id),
                                                                icon: '<i aria-hidden=\'true\' class=\'fa-solid fa-trash-alt text-danger\'></i>',
                                                                title: '{{ $organization->name }}',
                                                                description: 'Are you sure you want to Permanently delete this record?',
                                                                detail: '',
                                                                button_label: 'Delete Permanently',
                                                                color: 'danger',
                                                                action: 'organization.delete',
                                                            }
                                                        })">
                                                            <span class="fas fa-trash-alt fs--2 text-danger"></span>
                                                        </button>
                                                    @endcanAny
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @isset($trashed)
                            <!-- Pagination -->
                            {{ $trashed->links() }}
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
