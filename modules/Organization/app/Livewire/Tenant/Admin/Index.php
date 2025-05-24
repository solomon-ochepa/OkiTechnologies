<?php

namespace Modules\Organization\app\Livewire\Tenant\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Organization\App\Models\Organization;

#[Layout('tenancy::components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public int $perPage = 25;

    public ?string $search = '';

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    public $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->search = request('search');
    }

    public function render()
    {
        $data = [];
        $data['organizations'] = Organization::search($this->search)->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        return view('organization::livewire.tenant.admin.index', $data);
    }

    #[On('page.search')]
    public function search(array $data): void
    {
        $this->search = $data['search'];
    }
}
