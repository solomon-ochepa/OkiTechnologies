<?php

namespace Modules\Tenant\app\Livewire\Forms\Admin;

use Illuminate\Support\Str;
use Livewire\Form;
use Modules\Role\App\Models\Role;
use Modules\Tenant\App\Http\Requests\TenantRequest;
use Modules\Tenant\App\Models\Tenant;
use Modules\User\App\Models\User;

class CreateForm extends Form
{
    /**
     * A tenant object.
     */
    public ?Tenant $tenant;

    /**
     * Indicate if the form is being edited.
     */
    public bool $editing = false;

    /**
     * The primary admin for the tenant.
     */
    public ?string $admin;

    /**
     * The tenant name.
     */
    public ?string $name = '';

    /**
     * The tenant default subdomain.
     */
    public ?string $subdomain = '';

    /**
     * The tenant domain.
     */
    public ?string $domain = '';

    public function rules()
    {
        return (new TenantRequest($this->tenant->toArray()))->rules();
    }

    /**
     * Save a new tenant.
     */
    public function save()
    {
        $this->validate();

        // ###############################
        // Tenant
        // ###############################
        // Check for unique tenant.
        $exists = Tenant::where([
            'name' => $this->name,
            'user_id' => $this->admin,
        ])->exists();

        if ($exists) {
            // Add an error message to the form name field.
            $this->addError('name', 'Tenant already exists.');

            return;
        }

        // Create a new tenant.
        $tenant = Tenant::create([
            'name' => $this->name,
            'user_id' => $this->admin,
            'tenancy_db_name' => config('tenancy.database.prefix').Str::slug($this->name),
        ]);

        // ###############################
        // Domain
        // ###############################
        // Create a new subdomain.
        $tenant->domains()->create([
            'domain' => $this->subdomain,
        ]);

        // Create a new domain.
        if ($this->domain) {
            $tenant->domains()->create([
                'domain' => $this->domain,
            ]);
        }

        $admin = User::find($this->admin);

        // Create tenant's default admin.
        $tenant->run(function () use ($admin) {
            $user = $admin->replicate();
            $user->id = $admin->id;
            $user->save();

            $user->assignRole(Role::findOrCreate('admin'));
        });
    }

    /**
     * Update a tenant record.
     */
    public function update()
    {
        $this->validate();

        // Update tenant's information.
        $this->tenant->update([
            'name' => $this->name,
            'user_id' => $this->admin,
        ]);

        // Update tenant's subdomain.
        if ($this->tenant->subdomain->domain !== $this->subdomain) {
            $this->tenant->subdomain->domain = $this->subdomain;
            $this->tenant->subdomain->save();
        }

        // Update tenant's primary domain.
        if ($this->tenant->domain and $this->tenant->domain->domain !== $this->domain) {
            $this->tenant->domain->domain = $this->domain;
            $this->tenant->domain->save();
        }
    }
}
