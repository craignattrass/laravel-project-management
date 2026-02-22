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
- Laravel ^10.0 or ^11.0 or ^12.0
- **Tailwind CSS (Highly Recommended)** - For optimal performance and styling. Standalone mode is available but provides a degraded experience.

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

### Step 3: Configure Route Prefix (Optional)

By default, the dashboard is accessible at `/project-management`.

To use a different URL (like `/super-admin/project-management`), add to your `.env`:

```env
PROJECT_MANAGEMENT_ROUTE_PREFIX=super-admin/project-management
```

Or publish and edit the config file:

```bash
php artisan vendor:publish --tag=project-management-config
```

Then edit `config/project-management.php`:
```php
'route_prefix' => 'super-admin/project-management',
```

### Step 4: Scan Your Project

```bash
php artisan project:scan
```

This will auto-populate your API endpoints and CLI commands.

### Step 5: Access Dashboard

Visit the dashboard at the configured route prefix:
- Default: **`/project-management`**
- Custom: **`/your-custom-prefix`** (if you configured it in Step 3)

### Step 6: Verify Styling (Important!)

**If you see plain text with no styling:**

Your app needs Tailwind CSS configured properly.

**⚠️ RECOMMENDED: Install Tailwind CSS (Best Performance)**

Tailwind CSS is **strongly recommended** for:
✅ Faster page loads (compiled CSS is much smaller than CDN)
✅ Better user experience (no flash of unstyled content)
✅ Consistent styling with your application
✅ Production-ready performance

**If your app doesn't have Tailwind, install it now:**
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**IMPORTANT:** Add the package views to your `tailwind.config.js`:
```javascript
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './vendor/craignattrass/laravel-project-management/resources/views/**/*.blade.php', // Add this line
  ],
  // ... rest of config
}
```

Then build your assets:
```bash
npm run build
```

And ensure your `resources/views/layouts/app.blade.php` includes:
```html
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**If your app already has Tailwind:** Just add the package path to `tailwind.config.js` and rebuild:
```bash
npm run build
```

**🎉 Done!** Your dashboard will now have optimal performance.

---

**Option B: Standalone Mode (Fallback Only)**

⚠️ **Only use this if you cannot install Tailwind CSS.**

Add to your `.env`:
```env
PROJECT_MANAGEMENT_LAYOUT_TYPE=standalone
```

**Note:** Standalone mode uses Tailwind CDN which:
- ❌ Loads a large CSS file (~3MB) on every page load
- ❌ May cause flash of unstyled content (FOUC)
- ❌ Not recommended for production
- ✅ Works as a quick test/demo option

**💡 You should install Tailwind CSS instead for production use.**

## Using with Tailwind CSS (Recommended Setup)

**✅ This is the recommended approach for all production applications.**

If you're using `extends` or `component` layout mode (default), you **must** add the package views to your Tailwind configuration so it can scan and compile the utility classes.

Edit your `tailwind.config.js`:

```javascript
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './vendor/craignattrass/laravel-project-management/resources/views/**/*.blade.php', // Add this
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Then rebuild your assets:
```bash
npm run build
```

**Why is this needed?** Tailwind only includes CSS for classes it finds during the build process. Without adding the package path, Tailwind won't scan the package views, and all the styling (flex, grid, colors, etc.) will be missing.

**Performance Benefits:**
- ⚡ Compiled CSS is typically 10-50KB vs 3MB+ with CDN
- 🚀 No flash of unstyled content (FOUC)
- 🎯 Only includes classes you actually use
- 🔒 Production-ready and cacheable

## Layout Configuration

The package is **layout-agnostic** and works with any Laravel application structure.

### Default Configuration

By default, the package uses `@extends('layouts.app')` which works with most Laravel applications **that have Tailwind CSS installed**.

### Customize the Layout

Edit your `.env` file:

```env
# Use a different layout file
PROJECT_MANAGEMENT_LAYOUT=layouts.master

# Or use component-based layout (Breeze/Jetstream)
PROJECT_MANAGEMENT_LAYOUT=app-layout
PROJECT_MANAGEMENT_LAYOUT_TYPE=component

# Or standalone mode (not recommended - for testing only)
PROJECT_MANAGEMENT_LAYOUT_TYPE=standalone
```

Or publish and edit the config file:

```bash
php artisan vendor:publish --tag=project-management-config
```

Then edit `config/project-management.php`:

```php
return [
    // Traditional Blade layout with @extends (default)
    'layout' => 'layouts.app',
    'layout_type' => 'extends',
    
    // OR component-based layout (Breeze/Jetstream)
    // 'layout' => 'app-layout',
    // 'layout_type' => 'component',
    
    // OR standalone (no parent layout)
    // 'layout' => null,
    // 'layout_type' => null,
];
```

### Layout Types

**`extends` (Default)** - Traditional Blade layout
```php
'layout' => 'layouts.app',
'layout_type' => 'extends',
```
Uses `@extends('layouts.app')` - works with standard Laravel layouts

**`component`** - Component-based layout
```php
'layout' => 'app-layout',
'layout_type' => 'component',
```
Uses `<x-app-layout>` - works with Breeze, Jetstream, or custom components

**`standalone`** - Standalone (Not Recommended)
```php
'layout' => null,
'layout_type' => 'standalone',
```
Renders with its own HTML structure and Tailwind CDN. Only use for testing/demos, not production.

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
    // Route configuration
    'route_prefix' => 'project-management',     // Change URL prefix (supports nested: 'admin/projects')
    'middleware' => ['web', 'auth'],            // Customize middleware
    
    // Layout configuration
    'layout' => 'layouts.app',               // Your layout file
    'layout_type' => 'extends',              // 'extends', 'component', or null
    
    // Auto-scan configuration
    'auto_scan' => [
        'enabled' => true,
        'exclude_routes' => ['_debugbar', '_ignition'],
    ],
];
```

## Customization

### Change Route Prefix

You can nest the dashboard under any URL structure:

**Via .env:**
```env
PROJECT_MANAGEMENT_ROUTE_PREFIX=super-admin/project-management
```

**Via config file:**
```php
// config/project-management.php
'route_prefix' => 'super-admin/project-management',  // Access at /super-admin/project-management
```

**Examples:**
- `'admin/projects'` → `/admin/projects`
- `'super-admin/project-management'` → `/super-admin/project-management`
- `'dev/dashboard'` → `/dev/dashboard`
- `'project-management'` → `/project-management` (default)

### Use Component Layout (Breeze/Jetstream)

```php
// config/project-management.php
'layout' => 'app-layout',
'layout_type' => 'component',
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

### Recent Updates / Changelog

#### Tab Persistence Feature (Latest)
**What Changed:**
- Forms now stay on the current tab after adding/deleting tasks, bugs, or flows
- Uses URL hash fragments (`#tasks`, `#bugs`, `#flows`, etc.) to maintain tab state
- Controllers redirect with `->withFragment('tabname')` method
- JavaScript updated to read URL hash on page load and restore the correct tab

**Upgrade Instructions:**
1. Pull latest package code: `composer update craignattrass/laravel-project-management`
2. If you have custom controllers that extend the package:
   - Update your redirects to use `->route('project-management.index')->withFragment('tabname')`
   - Example: `return redirect()->route('project-management.index')->with('success', 'Task created')->withFragment('tasks');`
3. If you copied the view files, update the JavaScript in your view to handle URL hash:
   ```javascript
   // On page load, check for hash
   let activeTab = window.location.hash.substring(1);
   const validTabs = ['overview', 'modules', 'endpoints', 'commands', 'tasks', 'bugs', 'flows'];
   if (!activeTab || !validTabs.includes(activeTab)) {
       activeTab = 'overview';
   }
   showTab(activeTab);
   
   // In showTab function, add:
   history.replaceState(null, null, '#' + tabName);
   ```

#### Route Method Name Fix
**What Changed:**
- Fixed route definitions to match actual controller method names
- Changed non-standard names (createTask, deleteTask) to Laravel conventions (storeTask, destroyTask)

**Fixed Routes:**
- `createTask` → `storeTask`
- `deleteTask` → `destroyTask`
- `createModule` → `storeModule`
- `deleteModule` → `destroyModule`
- `createBug` → `storeBug`
- `deleteBug` → `destroyBug`
- `createFlow` → `storeFlow`
- `deleteFlow` → `destroyFlow`
- `scan` → `scanProject`
- `assignModuleToEndpoint` → `updateEndpoint`

**Note:** This is automatically fixed when you update the package. No manual changes needed unless you have custom route definitions.

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

**Issue: No styling / Plain text dashboard**

This happens when your app doesn't have Tailwind CSS configured properly.

**✅ RECOMMENDED FIX: Install/Configure Tailwind CSS**

**Most Common Cause:** Package views not in `tailwind.config.js`

Add this to your `tailwind.config.js` content array:
```javascript
'./vendor/craignattrass/laravel-project-management/resources/views/**/*.blade.php'
```

Then rebuild:
```bash
npm run build
```

**If Tailwind not installed:** Install it now (strongly recommended)
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
# Add package path to tailwind.config.js content array
npm run build
```

Then verify your `resources/views/layouts/app.blade.php` has:
```html
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**⚠️ Last Resort: Standalone Mode (Not Recommended for Production)**

Only if you absolutely cannot install Tailwind:
```env
# Add to .env
PROJECT_MANAGEMENT_LAYOUT_TYPE=standalone
```
Note: Uses 3MB+ Tailwind CDN, not recommended for production.

**Issue: Buttons not showing in header**

Your app's `layouts.app` might not have proper sections. Use standalone mode:
```env
PROJECT_MANAGEMENT_LAYOUT_TYPE=standalone
```

## License

MIT License - free to use in your projects.

## Support

- GitHub: https://github.com/craignattrass/laravel-project-management

---

**Ready to get started?** `composer require craignattrass/laravel-project-management`
