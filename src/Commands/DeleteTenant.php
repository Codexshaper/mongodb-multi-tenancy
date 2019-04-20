<?php

namespace Codexshaper\Tenancy\Commands;

use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DeleteTenant extends Command
{
    protected $signature = 'tenant:delete {website}';
    protected $description = 'Deletes a tenant of the provided website. Only available on the local environment e.g. php artisan tenant:delete dev_test';

    public function handle()
    {
        // because this is a destructive command, we'll only allow to run this command
        // if you are on the local environment
        if (!app()->isLocal()) {
            $this->error('This command is only avilable on the local environment.');

            return;
        }

        $website = $this->argument('website');
        $this->deleteTenant($website);
    }

    private function deleteTenant($uuid)
    {
        if ($website = Website::where('uuid', $uuid)->with(['hostnames'])->firstOrFail()) {
            $hostname = $website->hostnames->first();
            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);
            $this->info("Tenant {$uuid} successfully deleted.");
        }
    }
}