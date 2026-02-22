<?php

use CraigNattrass\ProjectManagement\Http\Controllers\ProjectManagementController;

/*
|--------------------------------------------------------------------------
| Project Management Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes are automatically loaded by the package service provider.
| The routes use 'auth' middleware by default.
|
*/

Route::middleware(['web', 'auth'])->prefix('project-management')->name('project-management.')->group(function () {
    // Main dashboard
    Route::get('/', [ProjectManagementController::class, 'index'])
        ->name('index');
    
    // Scan project
    Route::post('/scan', [ProjectManagementController::class, 'scan'])
        ->name('scan');
    
    // Module routes
    Route::post('/module', [ProjectManagementController::class, 'createModule'])
        ->name('module.create');
    Route::delete('/module/{module}', [ProjectManagementController::class, 'deleteModule'])
        ->name('module.delete');
    
    // Endpoint routes
    Route::put('/endpoint/{endpoint}/assign-module', [ProjectManagementController::class, 'assignModuleToEndpoint'])
        ->name('endpoint.assign-module');
    
    // Task routes
    Route::post('/task', [ProjectManagementController::class, 'createTask'])
        ->name('task.create');
    Route::delete('/task/{task}', [ProjectManagementController::class, 'deleteTask'])
        ->name('task.delete');
    
    // Bug routes
    Route::post('/bug', [ProjectManagementController::class, 'createBug'])
        ->name('bug.create');
    Route::delete('/bug/{bug}', [ProjectManagementController::class, 'deleteBug'])
        ->name('bug.delete');
    
    // Flow routes
    Route::post('/flow', [ProjectManagementController::class, 'createFlow'])
        ->name('flow.create');
    Route::delete('/flow/{flow}', [ProjectManagementController::class, 'deleteFlow'])
        ->name('flow.delete');
});
