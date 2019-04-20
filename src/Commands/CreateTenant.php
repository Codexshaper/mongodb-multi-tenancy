<?php

namespace Codexshaper\Tenancy\Commands;

use Codexshaper\Tenancy\Models\Hostname;
use Codexshaper\Tenancy\Models\Website;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {website} {hostname}';

    protected $description = 'Creates a tenant with the provided website and hostname address e.g. php artisan tenant:create dev dev.example.com';

    public function handle()
    {
        $website = $this->argument('website');
        $hostname = $this->argument('hostname');

        if ($this->websiteExists($website)) {
            $this->error("A tenant with website '{$website}' already exists.");

            return;
        }

        if ($this->hostExists($hostname)) {
            $this->error("A tenant with hostname '{$hostname}' already exists.");

            return;
        }

        $hostname = $this->registerTenant($website, $hostname);

        $this->info("Tenant '{$website}' is created and is now accessible at {$hostname->fqdn}");
    }

    private function websiteExists($website)
    {
        return Website::where('name', $website)->exists();
    }

    private function hostExists($hostname)
    {
        return Hostname::where('fqdn', $hostname)->exists();
    }

    private function registerTenant($name, $host)
    {
        // $this->error("A tenant with website '{$website}' already exists.");
        // $this->error("A tenant with website '{$hostname}' already exists.");
        /*
            |--------------------------------------------------------------------------
            | CREATE THE WEBSITE
            |--------------------------------------------------------------------------
             */
            $name = $name.str_random(5);
            $website = new Website;
            $website->name = $name;
            $website->save();

            /*
            |--------------------------------------------------------------------------
            | CREATE THE HOSTNAME
            |--------------------------------------------------------------------------
             */
            $hostname = new Hostname;
            $hostname->fqdn = $host;
            $hostname->website_id = $website->id;
            $hostname->save();

            $website->createWebsite($name);
            
        return $hostname;
    }
}