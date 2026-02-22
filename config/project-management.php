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
    | Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which layout the dashboard should use
    |
    | layout: The view path to your layout (e.g., 'layouts.app')
    | layout_type: 'extends' or 'component'
    |   - 'extends': Uses @extends() for traditional Blade layouts
    |   - 'component': Uses <x-{name}> for component-based layouts
    |   - null: Renders standalone (no parent layout)
    |
    */
    'layout' => env('PROJECT_MANAGEMENT_LAYOUT', 'layouts.app'),
    
    'layout_type' => env('PROJECT_MANAGEMENT_LAYOUT_TYPE', 'extends'),
    
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
