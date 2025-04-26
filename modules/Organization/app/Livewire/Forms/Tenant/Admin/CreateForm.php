<?php

namespace Modules\Organization\app\Livewire\Forms\Admin;

use Livewire\Form;
use Modules\Organization\App\Http\Requests\OrganizationRequest;
use Modules\Organization\App\Models\Organization;

class CreateForm extends Form
{
    /**
     * The organization name.
     */
    public ?string $name = '';

    /**
     * The organization name slug.
     */
    public ?string $slug = '';

    /**
     * A organization object.
     */
    public ?Organization $organization;

    /**
     * Indicate if the form is being edited.
     */
    public bool $editing = false;

    public function rules()
    {
        return (new OrganizationRequest($this->organization->only(['id'])))->rules();
    }

    /**
     * Save a new organization.
     */
    public function save()
    {
        $this->validate();

        // ###############################
        // Organization
        // ###############################
        $exists = Organization::where(['name' => $this->name])->exists();
        if ($exists) {
            // Add an error message to the form name field.
            $this->addError('name', 'Organization already exists.');

            return;
        }

        // Create a new organization.
        Organization::create(['name' => $this->name]);
    }

    /**
     * Update a organization record.
     */
    public function update()
    {
        $this->validate();

        // Update organization's information.
        $this->organization->update(['name' => $this->name]);
    }
}
