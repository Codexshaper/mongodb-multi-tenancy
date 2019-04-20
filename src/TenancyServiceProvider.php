<?php

namespace Codexshaper\Tenancy;

use Codexshaper\Tenancy\WooCommerceApi;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Codexshaper\Tenancy\Commands\CreateTenant;
use Codexshaper\Tenancy\Commands\DeleteTenant;
use Codexshaper\Tenancy\Commands\MigrateTenant;

class TenancyServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    { 

        $this->bootCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        
        $this->mergeConfigFrom(
            __DIR__.'../../assets/config/tenancy.php', 'tenancy'
        );

        $this->publishes([
            __DIR__.'../../assets/config/tenancy.php' => config_path('tenancy.php'),
        ],'tenancy');

        $this->publishes(
            [__DIR__ . '../../assets/migrations' => database_path('migrations')],
            'tenancy'
        );

        // $this->app->singleton('WooCommerceApi', function(){
        //     return new WooCommerceApi(); 
        // });
        // $this->app->alias('Codexshaper\Woocommerce\WooCommerceApi', 'WoocommerceApi');

        $this->registerMiddleware();
    }

    protected function bootCommands()
    {
        $this->commands([
            CreateTenant::class,
            DeleteTenant::class,
            MigrateTenant::class,
        ]);
    }

    protected function registerMiddleware()
    {
        /** @var Kernel|\Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);

        $kernel->prependMiddleware(\Codexshaper\Tenancy\Middleware\IdentifyHostname::class);
    }
}
