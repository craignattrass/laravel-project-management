<?php

namespace CraigNattrass\ProjectManagement\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use CraigNattrass\ProjectManagement\Models\ProjectEndpoint;
use CraigNattrass\ProjectManagement\Models\ProjectCliCommand;
use CraigNattrass\ProjectManagement\Models\ProjectModule;

class ProjectScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:scan {--fresh : Delete existing records before scanning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan the project and populate API endpoints and CLI commands registry';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Scanning project...');
        
        if ($this->option('fresh')) {
            $this->warn('Deleting existing records...');
            ProjectEndpoint::truncate();
            ProjectCliCommand::truncate();
        }

        $this->scanRoutes();
        $this->scanCommands();

        $this->info('✓ Project scan completed!');
        return Command::SUCCESS;
    }

    protected function scanRoutes()
    {
        $this->info('Scanning API routes...');
        
        $routes = Route::getRoutes();
        $scanned = 0;
        $updated = 0;

        foreach ($routes as $route) {
            $methods = $route->methods();
            $uri = $route->uri();
            $name = $route->getName();
            $action = $route->getActionName();

            // Skip non-API helpful routes
            if (in_array($uri, ['sanctum/csrf-cookie', '_ignition/health-check', 'up'])) {
                continue;
            }

            foreach ($methods as $method) {
                if ($method === 'HEAD') continue;

                // Parse controller and action
                $controller = null;
                $actionMethod = null;
                if (str_contains($action, '@')) {
                    [$controller, $actionMethod] = explode('@', $action);
                } elseif (str_contains($action, '::')) {
                    $parts = explode('::', $action);
                    $controller = $parts[0];
                    $actionMethod = $parts[1] ?? null;
                }

                // Auto-assign module based on route patterns
                $moduleId = $this->guessModuleFromUri($uri, $name);

                // Check if endpoint exists
                $endpoint = ProjectEndpoint::where('method', $method)
                    ->where('uri', $uri)
                    ->first();

                $data = [
                    'method' => $method,
                    'uri' => $uri,
                    'name' => $name,
                    'controller' => $controller,
                    'action' => $actionMethod,
                    'middleware' => implode(', ', $route->middleware()),
                    'requires_auth' => in_array('auth', $route->middleware()),
                    'last_scanned_at' => now(),
                ];

                // Only set module_id if not already assigned (preserve manual assignments)
                if ($moduleId && (!$endpoint || !$endpoint->module_id)) {
                    $data['module_id'] = $moduleId;
                }

                if ($endpoint) {
                    $endpoint->update($data);
                    $updated++;
                } else {
                    ProjectEndpoint::create($data);
                    $scanned++;
                }
            }
        }

        $this->info("  • Scanned {$scanned} new endpoints");
        $this->info("  • Updated {$updated} existing endpoints");
    }

    /**
     * Guess module based on URI patterns
     */
    protected function guessModuleFromUri(string $uri, ?string $name): ?int
    {
        // Define mapping patterns (route prefix/name => module slug)
        $patterns = [
            'acronis' => ['uri' => 'acronis', 'name' => 'acronis'],
            'backup-management' => ['uri' => ['backup-log', 'backup-job'], 'name' => ['logs', 'jobs']],
            'freshdesk' => ['uri' => ['ticket', 'freshdesk'], 'name' => ['ticket']],
            'tenant-management' => ['uri' => ['tenant/dashboard', 'tenant/overview', 'tenant/settings'], 'name' => ['tenant.dashboard', 'tenant.overview', 'tenant.settings']],
            'client-management' => ['uri' => 'clients', 'name' => 'clients'],
            'server-management' => ['uri' => 'servers', 'name' => 'servers'],
            'authentication' => ['uri' => ['login', 'logout', 'register'], 'name' => ['login', 'logout', 'register']],
            'master-admin' => ['uri' => 'master/', 'name' => 'master.'],
        ];

        foreach ($patterns as $moduleSlug => $pattern) {
            // Check URI patterns
            if (isset($pattern['uri'])) {
                $uriPatterns = is_array($pattern['uri']) ? $pattern['uri'] : [$pattern['uri']];
                foreach ($uriPatterns as $uriPattern) {
                    if (str_contains($uri, $uriPattern)) {
                        return $this->getOrCreateModule($moduleSlug);
                    }
                }
            }

            // Check route name patterns
            if (isset($pattern['name']) && $name) {
                $namePatterns = is_array($pattern['name']) ? $pattern['name'] : [$pattern['name']];
                foreach ($namePatterns as $namePattern) {
                    if (str_contains($name, $namePattern)) {
                        return $this->getOrCreateModule($moduleSlug);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get or create module by slug
     */
    protected function getOrCreateModule(string $slug): ?int
    {
        $module = ProjectModule::where('slug', $slug)->first();
        
        if (!$module) {
            // Auto-create common modules
            $moduleNames = [
                'acronis' => 'Acronis Integration',
                'backup-management' => 'Backup Management',
                'freshdesk' => 'Freshdesk Integration',
                'tenant-management' => 'Tenant Management',
                'client-management' => 'Client Management',
                'server-management' => 'Server Management',
                'authentication' => 'Authentication',
                'master-admin' => 'Master Admin',
            ];

            if (isset($moduleNames[$slug])) {
                $module = ProjectModule::create([
                    'name' => $moduleNames[$slug],
                    'slug' => $slug,
                    'status' => 'active',
                    'category' => $slug === 'master-admin' ? 'admin' : 'feature',
                ]);
                $this->info("  • Auto-created module: {$moduleNames[$slug]}");
            }
        }

        return $module?->id;
    }

    protected function scanCommands()
    {
        $this->info('Scanning Artisan commands...');
        
        $commands = Artisan::all();
        $scanned = 0;
        $updated = 0;

        foreach ($commands as $name => $command) {
            // Skip Laravel default commands
            if (str_starts_with($name, 'make:') || 
                str_starts_with($name, 'migrate') || 
                str_starts_with($name, 'db:') ||
                str_starts_with($name, 'cache:') ||
                str_starts_with($name, 'config:') ||
                str_starts_with($name, 'route:') ||
                str_starts_with($name, 'view:') ||
                str_starts_with($name, 'queue:') ||
                str_starts_with($name, 'schedule:') ||
                str_starts_with($name, 'vendor:') ||
                str_starts_with($name, 'storage:') ||
                str_starts_with($name, 'optimize') ||
                str_starts_with($name, 'package:') ||
                str_starts_with($name, 'event:') ||
                str_starts_with($name, 'key:')
            ) {
                continue;
            }

            $signature = $command->getName();
            $description = $command->getDescription();
            $className = get_class($command);

            // Get arguments and options
            $definition = $command->getDefinition();
            $arguments = [];
            $options = [];

            foreach ($definition->getArguments() as $arg) {
                $arguments[] = [
                    'name' => $arg->getName(),
                    'required' => $arg->isRequired(),
                    'description' => $arg->getDescription(),
                ];
            }

            foreach ($definition->getOptions() as $opt) {
                if (!in_array($opt->getName(), ['help', 'quiet', 'verbose', 'version', 'ansi', 'no-ansi', 'no-interaction', 'env'])) {
                    $options[] = [
                        'name' => $opt->getName(),
                        'shortcut' => $opt->getShortcut(),
                        'description' => $opt->getDescription(),
                    ];
                }
            }

            // Check if command exists
            $existingCommand = ProjectCliCommand::where('signature', $signature)->first();

            $data = [
                'signature' => $signature,
                'name' => $name,
                'description' => $description,
                'class_name' => $className,
                'arguments' => !empty($arguments) ? $arguments : null,
                'options' => !empty($options) ? $options : null,
                'last_scanned_at' => now(),
            ];

            if ($existingCommand) {
                $existingCommand->update($data);
                $updated++;
            } else {
                ProjectCliCommand::create($data);
                $scanned++;
            }
        }

        $this->info("  • Scanned {$scanned} new commands");
        $this->info("  • Updated {$updated} existing commands");
    }
}
