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

    public static function identifyHostname( Request $request )
    {
    	$current_hostname = $request->getHost();
        $hostnames = Hostname::all();

        foreach ($hostnames as $hostname) {
            if ( $hostname->fqdn == $current_hostname ) {

                $website = $hostname->website;

                if( $website ) {
                    $database = $website->name;

                    DB::purge('mongodb');
                    Config::set('database.connections.mongodb.database', $database);
                    DB::reconnect();
                    return true;
                }
                
            }
        }

        return false;
    }
}
