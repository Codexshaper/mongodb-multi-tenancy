<?php

namespace Codexshaper\Tenancy\Models;

use Codexshaper\Tenancy\Models\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Hostname extends Eloquent
{
    public function website()
    {
    	return $this->belongsTo(Website::class);
    }

    public static function switch( $website )
    {
        DB::purge('mongodb');
        Config::set('database.connections.mongodb.database', $website);
        if( DB::reconnect() ) {
            return true;
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
