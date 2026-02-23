<?php

use CraigNattrass\ProjectManagement\Http\Controllers\ProjectManagementController;

/*
|--------------------------------------------------------------------------
| Project Management Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes are automatically loaded by the package service provider.
| The route prefix and middleware are configurable in config/project-management.php
|
*/

Route::middleware(config('project-management.middleware', ['web', 'auth']))
    ->prefix(config('project-management.route_prefix', 'project-management'))
    ->name('project-management.')
    ->group(function () {
    // Main dashboard
    Route::get('/', [ProjectManagementController::class, 'index'])
        ->name('index');
    
    // Scan project
    Route::post('/scan', [ProjectManagementController::class, 'scanProject'])
        ->name('scan');
    
    // Module routes
    Route::post('/module', [ProjectManagementController::class, 'storeModule'])
        ->name('module.create');
    Route::delete('/module/{module}', [ProjectManagementController::class, 'destroyModule'])
        ->name('module.delete');
    
    // Endpoint routes
    Route::put('/endpoint/{endpoint}/assign-module', [ProjectManagementController::class, 'updateEndpoint'])
        ->name('endpoint.assign-module');
    
    // Task routes
    Route::post('/task', [ProjectManagementController::class, 'storeTask'])
        ->name('task.create');
    Route::put('/task/{task}', [ProjectManagementController::class, 'updateTask'])
        ->name('task.update');
    Route::patch('/task/{task}/toggle-status', [ProjectManagementController::class, 'toggleTaskStatus'])
        ->name('task.toggle-status');
    Route::delete('/task/{task}', [ProjectManagementController::class, 'destroyTask'])
        ->name('task.delete');
    
    // Bug routes
    Route::post('/bug', [ProjectManagementController::class, 'storeBug'])
        ->name('bug.create');
    Route::put('/bug/{bug}', [ProjectManagementController::class, 'updateBug'])
        ->name('bug.update');
    Route::patch('/bug/{bug}/toggle-status', [ProjectManagementController::class, 'toggleBugStatus'])
        ->name('bug.toggle-status');
    Route::delete('/bug/{bug}', [ProjectManagementController::class, 'destroyBug'])
        ->name('bug.delete');
    
    // Flow routes
    Route::post('/flow', [ProjectManagementController::class, 'storeFlow'])
        ->name('flow.create');
    Route::put('/flow/{flow}', [ProjectManagementController::class, 'updateFlow'])
        ->name('flow.update');
    Route::delete('/flow/{flow}', [ProjectManagementController::class, 'destroyFlow'])
        ->name('flow.delete');
});
