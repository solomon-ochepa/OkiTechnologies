<?php

namespace Modules\Tenant\App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;

class TenantController extends Controller
{
    public function dashboard()
    {
        return view('tenant::livewire.admin.dashboard');
    }
}
