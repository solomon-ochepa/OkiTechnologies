<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FilePreviewController;
use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Features\TenantConfig;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenancyServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Tenancy';

    protected string $nameLower = 'tenancy';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // ################################
        // Tenancy
        // ################################
        TenantConfig::$storageToConfigMap = [
            'name' => 'app.name',
        ];

        // Livewire integration
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)->middleware('web', 'universal', InitializeTenancyByDomainOrSubdomain::class);
        });

        FilePreviewController::$middleware = ['web', 'universal', InitializeTenancyByDomainOrSubdomain::class];

        $this->bootEvents();
        $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();
        // END: Tenancy
        // ################################

        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * @source Tenancy
     */
    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    // Jobs\CreateDatabase::class,
                    // Jobs\MigrateDatabase::class,
                    // Jobs\SeedDatabase::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!

                ])->send(function (Events\TenantCreated $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                JobPipeline::make([
                    // Jobs\DeleteDatabase::class, // TODO: Delete the database on forced deletion only.
                ])->send(function (Events\TenantDeleted $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    /**
     * @source Tenancy
     */
    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    /**
     * @source Tenancy
     */
    protected function mapRoutes()
    {
        $this->app->booted(function () {
            $this->central_domains();
            $this->tenant_domains();
        });
    }

    /**
     * Define the central domains' routes for the application.
     *
     * @source Tenancy
     */
    protected function central_domains(): void
    {
        $config = config('tenancy.central_domains');
        $domains = settings(['multiple.central_domains' => false]) ? $config : (array) Arr::first($config) ?? [];

        foreach ($domains ?? [] as $domain) {
            Route::domain($domain)->group(function () {
                // Web
                Route::middleware(['web'])->group(function () {
                    // Root
                    if (file_exists($route = base_path('routes/web.php'))) {
                        Route::group([], $route);
                    }

                    // Modules
                    collect(Module::all())->each(function ($module) {
                        if (file_exists($route = module_path($module->getName(), 'routes/web.php'))) {
                            Route::group([], $route);
                        }
                    });
                });

                // API
                Route::middleware('api')->prefix('api')->name('api.')->group(function () {
                    // Root
                    if (file_exists($route = base_path('routes/api.php'))) {
                        Route::group([], $route);
                    }

                    // Modules
                    collect(Module::all())->each(function ($module) {
                        if (file_exists($route = module_path($module->getName(), 'routes/api.php'))) {
                            Route::group([], $route);
                        }
                    });
                });
            });
        }
    }

    /**
     * Define the tenant domains' routes for the application.
     *
     * @source Tenancy
     */
    protected function tenant_domains(): void
    {
        Route::middleware([InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])->group(function () {
            // Web
            Route::middleware(['web'])->group(function () {
                // Root
                if (file_exists($route = base_path('routes/tenant/web.php'))) {
                    Route::group([], $route);
                }

                // Modules
                collect(Module::all())->each(function ($module) {
                    if (file_exists($route = module_path($module->getName(), 'routes/tenant/web.php'))) {
                        Route::group([], $route);
                    }
                });
            });

            // API
            Route::middleware('api')->prefix('api')->name('api.')->group(function () {
                // Root
                if (file_exists($route = base_path('routes/tenant/api.php'))) {
                    Route::group([], $route);
                }

                // Modules
                collect(Module::all())->each(function ($module) {
                    if (file_exists($route = module_path($module->getName(), 'routes/tenant/api.php'))) {
                        Route::group([], $route);
                    }
                });
            });
        });
    }

    /**
     * Define the tenant domains' routes for the application.
     *
     * @source Tenancy
     */
    protected function tenant_domains(): void
    {
        $tenant_web_routes = [];
        if (file_exists(base_path('routes/tenant/web.php'))) {
            $tenant_web_routes[] = glob(base_path('routes/tenant/web.php'));
        }
        $tenant_web_routes[] = glob(base_path('modules/*/routes/tenant/web.php'));
        foreach ($tenant_web_routes as $web) {
            Route::middleware(['web', InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])
                ->group($web);
        }

        $tenant_api_routes = [];
        if (file_exists(base_path('routes/tenant/api.php'))) {
            $tenant_api_routes[] = glob(base_path('routes/tenant/api.php'));
        }
        $tenant_api_routes[] = glob(base_path('modules/*/routes/tenant/api.php'));
        foreach ($tenant_api_routes as $api) {
            Route::middleware(['api', InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])
                ->prefix('api')
                ->name('api.')
                ->group($api);
        }
    }

    /**
     * @source Tenancy
     */
    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->module_namespace($this->name, $this->app_path(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
