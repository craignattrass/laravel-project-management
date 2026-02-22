<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the route prefix and middleware for the dashboard
    |
    */
    'route_prefix' => 'project-management',
    
    'middleware' => ['web', 'auth'],
    
    /*
    |--------------------------------------------------------------------------
    | Auto-scan Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic project scanning behavior
    |
    */
    'auto_scan' => [
        'enabled' => true,
        'exclude_routes' => [
            '_debugbar',
            '_ignition',
            'sanctum',
            'livewire',
        ],
    ],
];
