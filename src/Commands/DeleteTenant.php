<?php

namespace Codexshaper\Tenancy\Commands;

use Hyn\Tenancy\Environment;
use Codexshaper\Tenancy\Models\Website;
use Codexshaper\Tenancy\Models\Hostname;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DeleteTenant extends Command
{
    protected $signature = 'tenant:delete {host}';
    protected $description = 'Deletes a tenant of the provided website. Only available on the local environment e.g. php artisan tenant:delete dev_test';

    public function handle()
    {
        // because this is a destructive command, we'll only allow to run this command
        // if you are on the local environment
        if (!app()->isLocal()) {
            $this->error('This command is only avilable on the local environment.');

            return;
        }

        $host = $this->argument('host');
        $this->deleteTenant($host);
    }

    private function deleteTenant($host)
    {

        if ($hostname = Hostname::where('fqdn', $host)->firstOrFail()) {
            $website = $hostname->website;
            $db_name = $website->name;
            var_dump( $website->name );
            die();
            $this->info("Tenant {$uuid} successfully deleted.");
        }
    }
}