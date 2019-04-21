<?php

namespace Codexshaper\Tenancy\Models;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Website extends Eloquent
{
   public function createWebsite( $website, $command='migrate' )
   {
   		DB::purge('mongodb');
         
   		Config::set('database.connections.mongodb.database', $website);
   		  
   		DB::reconnect('mongodb');
   		  
   		Schema::connection('mongodb')->getConnection()->reconnect();

   		Artisan::call($command, [
   		    '--path' => config('tenancy.db.tenant_migrations_path'),
   		    '--force'     => true,
   		]);
   }

   public function migrate( $website, $command='migrate' )
   {
   		$this->createWebsite( $website, $command );
   }
}
