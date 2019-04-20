<?php

return [

    'models' => [
    ],
    'middleware' => [
        \Codexshaper\Tenancy\Middleware\IdentifyHostname::class
    ],
    'website' => [
  
    ],
    'hostname' => [

        'system_hostname' => env('TENANT_SYSTEM_HOSTNAME', 'example.com'),
        
    ],
    'db' => [

        'system_db' => env('TENANT_SYSTEM_DATABASE', 'mongodb'),

        'tenant_migrations_path' => 'database/migrations/tenant',
    ],
];
