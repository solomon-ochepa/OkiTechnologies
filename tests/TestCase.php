<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Modules\Tenant\App\Models\Tenant;

abstract class TestCase extends BaseTestCase
{
    /**
     * Indicates if the test case is tenant based.
     */
    protected bool $tenancy = true;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        // $this->tenancy();

        parent::setUp();

        // if ($this->tenancy) {
        //     $this->initializeTenancy();
        // }
    }

    public function initializeTenancy()
    {
        $this->tenant = Tenant::factory()->create();
        // $this->tenant->domains()->create([
        //     'domain' => Str::slug($this->tenant->name),
        // ]);

        // tenancy()->initialize($this->tenant);
    }

    protected function tenancy(): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

        foreach ($backtrace as $trace) {
            if (isset($trace['file'])) {
                if (str_contains($trace['file'], DIRECTORY_SEPARATOR . 'Tenant' . DIRECTORY_SEPARATOR)) {
                    $this->tenancy = true;
                    break;
                }
            }
        }
    }
}
