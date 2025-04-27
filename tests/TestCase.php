<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Tenant\App\Models\Tenant;

abstract class TestCase extends BaseTestCase
{
    /**
     * Indicates if the test case is tenant based.
     */
    protected bool $tenancy = true;

    protected function setUp(): void
    {
        $this->tenancy();

        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    public function initializeTenancy()
    {
        $tenant = Tenant::factory()->create();

        tenancy()->initialize($tenant);
    }

    protected function tenancy(): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

        foreach ($backtrace as $trace) {
            if (isset($trace['file'])) {
                if (str_contains($trace['file'], DIRECTORY_SEPARATOR.'Tenant'.DIRECTORY_SEPARATOR)) {
                    $this->tenancy = true;
                    break;
                }
            }
        }
    }
}
