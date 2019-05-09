<?php

namespace Codexshaper\Tenancy\Models;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use TenancyConnection;

class Website extends Eloquent
{
   public function createWebsite( $website, $command='migrate' )
   {
   		DB::purge('tenant');
         
   		Config::set('database.connections.tenant.database', $website);
   		  
   		DB::reconnect('tenant');
   		  
   		Schema::connection('tenant')->getConnection()->reconnect();

   		Artisan::call($command, [
          '--database' => 'tenant',
   		    '--path' => config('tenancy.db.tenant_migrations_path'),
   		    '--force'     => true,
   		]);

      Artisan::call("db:seed", [
         '--database' => 'tenant',
         '--force'     => true,
      ]);
   }

   public function deleteWebsite( $db )
   {
        $connection = TenancyConnection::getConenection();
        if( $connection->dropDatabase( $db ) ) {
         return true;
        }
      return false;
   }

   public function migrate( $website, $command='migrate' )
   {
   		$this->createWebsite( $website, $command );
   }
}
