<?php

namespace Codexshaper\Tenancy\Models;

use Codexshaper\Tenancy\Models\Website;
use Codexshaper\Tenancy\Traits\TenancyConnectionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use TenancyConnection;

class Hostname extends Eloquent
{

    public function website()
    {
    	return $this->belongsTo(Website::class);
    }

    public static function switch( $website )
    {
        $mongoDatabases = TenancyConnection::getConenection()->listDatabases();

        foreach ($mongoDatabases as $mongoDatabase) {
            
            if ( $mongoDatabase['name'] == $website ) {
                // Erase the tenant connection, thus making Laravel get the default values all over again.
                DB::purge('tenant');
                // Make sure to use the database name we want to establish a connection.
                Config::set('database.connections.tenant.host', 'localhost');
                Config::set('database.connections.tenant.database', $website);
                Config::set('database.connections.tenant.username', env('DB_USERNAME'));
                Config::set('database.connections.tenant.password', env('DB_PASSWORD'));
                // Rearrange the connection data
                DB::reconnect('tenant');
                // Ping the database. This will throw an exception in case the database does not exists.
                Schema::connection('tenant')->getConnection()->reconnect();

                return true;
            }
        }

        return false;
    }

    public static function switchTenant( $website, $connection = 'tenant' )
    {
        $mongoDatabases = TenancyConnection::getConenection()->listDatabases();

        foreach ($mongoDatabases as $mongoDatabase) {
            
            if ( $mongoDatabase['name'] == $website ) {
                // Erase the tenant connection, thus making Laravel get the default values all over again.
                DB::purge($connection);
                // Make sure to use the database name we want to establish a connection.
                Config::set("database.connections.{$connection}.host", env('DB_HOST', 'localhost'));
                Config::set("database.connections.{$connection}.database", $website);
                Config::set("database.connections.{$connection}.username", env('DB_USERNAME'));
                Config::set("database.connections.{$connection}.password", env('DB_PASSWORD'));
                // Rearrange the connection data
                DB::reconnect($connection);
                // Ping the database. This will throw an exception in case the database does not exists.
                Schema::connection($connection)->getConnection()->reconnect();

                return true;
            }
        }

        return false;
    }

    public static function switchSystem( $website, $connection = 'tenant' )
    {
        $mongoDatabases = TenancyConnection::getConenection()->listDatabases();

        foreach ($mongoDatabases as $mongoDatabase) {
            
            if ( $mongoDatabase['name'] == $website ) {
                // Erase the tenant connection, thus making Laravel get the default values all over again.
                DB::purge($connection);
                // Make sure to use the database name we want to establish a connection.
                Config::set("database.connections.{$connection}.host", env('DB_HOST', 'localhost'));
                Config::set("database.connections.{$connection}.database", $website);
                Config::set("database.connections.{$connection}.username", env('DB_USERNAME'));
                Config::set("database.connections.{$connection}.password", env('DB_PASSWORD'));
                // Rearrange the connection data
                DB::reconnect($connection);
                // Ping the database. This will throw an exception in case the database does not exists.
                Schema::connection($connection)->getConnection()->reconnect();

                return true;
            }
        }

        return false;
    }

    public static function identifyHostname( Request $request )
    {
    	$current_hostname = $request->getHost();
        $hostnames = Hostname::all();

        $system_hostname = config('tenancy.hostname.system_hostname');
        $system_db = config('tenancy.db.system_db');

        if( $system_hostname ==  $current_hostname ) {
            
            return self::switch( $system_db );
        }else {
            foreach ($hostnames as $hostname) {
                if ( $hostname->fqdn == $current_hostname ) {

                    $website = $hostname->website;

                    if( $website ) {
                        $database = $website->name;

                        return self::switch( $database );
                    }
                    
                }
            }
        }

        return false;
    }
}
