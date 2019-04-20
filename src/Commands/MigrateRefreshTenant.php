<?php

namespace Codexshaper\Tenancy\Commands;

use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Codexshaper\Tenancy\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrateRefreshTenant extends Command
{
    protected $signature = 'tenant:migrate:refresh';
    protected $description = 'Deletes a tenant of the provided website. Only available on the local environment e.g. php artisan tenant:delete dev_test';

    public function handle()
    {
        // because this is a destructive command, we'll only allow to run this command
        // if you are on the local environment
        if (!app()->isLocal()) {
            $this->error('This command is only avilable on the local environment.');

            return;
        }

        $this->migrateTenant();
    }

    private function migrateTenant()
    {
        $websites = Website::all();

        if( $websites ){
            foreach ($websites as $website) {
               $website->migrate( $website->name, 'migrate:refresh' );
            }
            
        } 
        $this->info("Tenant migrated successfully.");
    }
}