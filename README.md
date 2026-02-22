# Laravel Project Management Dashboard

[![Latest Version](https://img.shields.io/github/v/release/craignattrass/laravel-project-management)](https://github.com/craignattrass/laravel-project-management/releases)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A comprehensive Project Intelligence Dashboard for Laravel applications with auto-scanning, task tracking, bug reporting, and AI context awareness for GitHub Copilot.

## Features

- 📊 **Live Statistics Dashboard** - Real-time metrics for modules, endpoints, tasks, and bugs
- 🔍 **Auto-Scanning** - Automatically discovers and registers API endpoints and CLI commands
- 📦 **Module Organization** - Organize your features into logical groupings
- ✅ **Task Tracker** - Built-in TODO/task management with priority levels
- 🐛 **Bug Reporter** - Track bugs with severity levels and stack traces
- 📈 **Flow Diagrams** - Document complex features with Mermaid.js diagrams
- 🎨 **Beautiful UI** - Purple-themed dashboard with Tailwind CSS
- 🤖 **AI-Ready** - Provides context for GitHub Copilot for better code assistance

## Requirements

- PHP ^8.1
- Laravel ^10.0 or ^11.0
- Tailwind CSS (for styling)

## Installation

### Step 1: Install the Package

```bash
composer require craignattrass/laravel-project-management
```

The package will automatically register itself via Laravel's package auto-discovery.

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates 6 tables:
- `project_modules`
- `project_endpoints`
- `project_cli_commands`
- `project_tasks`
- `project_bugs`
- `project_flows`

### Step 3: Scan Your Project

```bash
php artisan project:scan
```

This will auto-populate your API endpoints and CLI commands.

### Step 4: Access Dashboard

Visit: **`/project-management`** in your browser

That's it! 🎉

## Layout Compatibility

This package automatically adapts to your application's layout structure:

### ✅ Works With Component-Based Layouts (Breeze, Jetstream)

If your app uses `<x-app-layout>` components (Laravel Breeze, Jetstream), the dashboard will automatically integrate with your existing layout, header, and navigation.

### ✅ Works Without Any Layout

If your app doesn't have `<x-app-layout>`, the dashboard renders with a standalone layout using Tailwind CSS from CDN. It works out-of-the-box without any configuration!

### Customize the Layout

If you want to customize how the dashboard integrates with your app:

```bash
php artisan vendor:publish --tag=project-management-views
```

Then edit `resources/views/vendor/project-management/project-management/index.blade.php` to match your app's layout structure.

## Optional: Publish Assets

If you want to customize views or config:

```bash
# Publish everything
php artisan vendor:publish --provider="CraigNattrass\ProjectManagement\ProjectManagementServiceProvider"

# Or publish selectively:
php artisan vendor:publish --tag=project-management-views
php artisan vendor:publish --tag=project-management-config
```

## Usage

### Artisan Command

```bash
# Scan and register all routes and commands
php artisan project:scan

# Fresh scan (clears existing data)
php artisan project:scan --fresh
```

### Workflow

1. **Click "Scan Project"** - Auto-populates endpoints and commands
2. **Create Modules** - Organize features (e.g., "User Management", "API Integration")
3. **Assign Endpoints** - Click module names in the endpoints table to assign
4. **Track Tasks** - Add TODOs with priority levels
5. **Report Bugs** - Track bugs with file paths and stack traces
6. **Document Flows** - Create Mermaid diagrams for complex features

## Dashboard Tabs

### 1. Overview
Project summary with module counts, priority tasks, and critical bug alerts

### 2. Modules
CRUD interface for managing feature modules with status tracking

### 3. API Endpoints
Auto-scanned registry of all routes with HTTP method badges, controller info, and module assignment

### 4. CLI Commands
Auto-scanned Artisan commands with signatures, descriptions, and module assignment

### 5. Tasks
TODO tracker with priority levels, status filtering, due dates, and module assignment

### 6. Bugs
Bug tracking system with severity levels, file path tracking, stack traces, and resolution tracking

### 7. Document Flows
Mermaid.js diagram viewer/editor for visual documentation

## Configuration

Publish the config file to customize:

```bash
php artisan vendor:publish --tag=project-management-config
```

Edit `config/project-management.php`:

```php
return [
    'route_prefix' => 'project-management',  // Change URL prefix
    'middleware' => ['web', 'auth'],        // Customize middleware
    'auto_scan' => [
        'enabled' => true,
        'exclude_routes' => ['_debugbar', '_ignition'],
    ],
];
```

## Customization

### Change Route Prefix

```php
// config/project-management.php
'route_prefix' => 'admin/projects',  // Access at /admin/projects
```

### Add Additional Middleware

```php
'middleware' => ['web', 'auth', 'admin'],
```

### Customize Views

```bash
php artisan vendor:publish --tag=project-management-views
```

Views will be copied to `resources/views/vendor/project-management`

## API Usage

All models are available for use in your code:

```php
use CraigNattrass\ProjectManagement\Models\ProjectModule;
use CraigNattrass\ProjectManagement\Models\ProjectEndpoint;
use CraigNattrass\ProjectManagement\Models\ProjectTask;
use CraigNattrass\ProjectManagement\Models\ProjectBug;
use CraigNattrass\ProjectManagement\Models\ProjectFlow;

// Get all modules
$modules = ProjectModule::with(['endpoints', 'tasks', 'bugs'])->get();

// Get critical bugs
$criticalBugs = ProjectBug::where('severity', 'critical')
    ->where('status', 'open')
    ->get();
```

## Updating

```bash
composer update craignattrass/laravel-project-management
php artisan migrate
php artisan project:scan
```

## Troubleshooting

**Issue: Routes not working**
```bash
php artisan route:clear
php artisan config:clear
```

**Issue: Views not found**
```bash
php artisan view:clear
```

**Issue: Styling missing**
- Ensure Tailwind CSS is installed and compiled
- Run `npm run build`

## License

MIT License - free to use in your projects.

## Support

- GitHub: https://github.com/craignattrass/laravel-project-management
- Email: craig@backuplogs.co.za

---

**Ready to get started?** `composer require craignattrass/laravel-project-management`
