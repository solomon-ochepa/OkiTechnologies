<?php

namespace Modules\Organization\app\Livewire\Tenant\Admin\Modals;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Organization\app\Livewire\Forms\Admin\CreateForm;
use Modules\Organization\App\Models\Organization;

class Create extends Component
{
    use WithPagination;

    public CreateForm $form;

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->form->organization = new Organization;
    }

    public function render()
    {
        $data = [];
        $data['organizations'] = Organization::paginate();

        return view('organization::livewire.tenant.admin.modals.create', $data);
    }

    public function close()
    {
        $this->form->reset();
        $this->resetErrorBag();

        $this->dispatch('close-modal', 'add-organization');
        $this->dispatch('refresh');
    }

    public function updatedFormName()
    {
        $this->form->slug = Str::slug($this->form->name);
    }

    /**
     * Edit an existing organization.
     */
    #[On('organization.edit')]
    public function edit(Organization $organization)
    {
        $this->form->editing = true;
        $this->form->organization = $organization;

        $this->form->fill([
            'name' => $organization->name,
            'slug' => $organization->slug,
        ]);

        $this->dispatch('open-modal', 'add-organization');
    }

    public function store()
    {
        if ($this->form->editing) {
            $this->form->update();
        } else {
            $this->form->save();
        }

        $this->close();
    }
}
