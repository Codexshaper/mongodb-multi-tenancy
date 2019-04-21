<?php

namespace Codexshaper\Tenancy;
use MongoDB\Client;
class TenancyConnection
{
	public $mongo_connection;

    public function __construct() {
    	$host       = config('database.connections.mongodb.host');
    	$port       = config('database.connections.mongodb.port');
    	$options    = config('database.connections.mongodb.options');
    	$auth_db      = config('database.connections.mongodb.options.database') ? config('database.connections.mongodb.options.database') : null;
    	$dsn        = config('database.connections.mongodb.dsn');

    	if ( !$dsn ) {
    	    $dsn = 'mongodb://'. $host . ':' . $port . ($auth_db ? "/" . $auth_db : '');
    	}
    	
    	$this->mongo_connection = new Client( $dsn );
    }

    public function getConenection()
    {
        return $this->mongo_connection;
    }
}