<?php

namespace CraigNattrass\ProjectManagement;

use Illuminate\Support\ServiceProvider;
use CraigNattrass\ProjectManagement\Console\Commands\ProjectScanCommand;

class ProjectManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package config with application config
        $this->mergeConfigFrom(
            __DIR__.'/../config/project-management.php', 'project-management'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'project-management');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProjectScanCommand::class,
            ]);

            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'project-management-migrations');

            // Publish views (optional - allows customization)
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/project-management'),
            ], 'project-management-views');

            // Publish config
            $this->publishes([
                __DIR__.'/../config/project-management.php' => config_path('project-management.php'),
            ], 'project-management-config');
        }
    }
}
