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
        
    ],
    'db' => [

        'tenant-migrations-path' => database_path('migrations/tenant'),
    ],
];
