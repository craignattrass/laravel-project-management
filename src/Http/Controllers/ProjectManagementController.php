<?php

namespace CraigNattrass\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use CraigNattrass\ProjectManagement\Models\ProjectModule;
use CraigNattrass\ProjectManagement\Models\ProjectEndpoint;
use CraigNattrass\ProjectManagement\Models\ProjectCliCommand;
use CraigNattrass\ProjectManagement\Models\ProjectTask;
use CraigNattrass\ProjectManagement\Models\ProjectBug;
use CraigNattrass\ProjectManagement\Models\ProjectFlow;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{
    public function index()
    {
        $modules = ProjectModule::withCount(['endpoints', 'cliCommands', 'tasks', 'bugs', 'flows'])->orderBy('order')->get();
        $endpoints = ProjectEndpoint::with('module')->orderBy('method')->orderBy('uri')->get();
        $cliCommands = ProjectCliCommand::with('module')->orderBy('name')->get();
        $tasks = ProjectTask::with('module')->orderBy('order')->get();
        $bugs = ProjectBug::with('module')->orderBy('severity', 'desc')->orderBy('created_at', 'desc')->get();
        $flows = ProjectFlow::with('module')->where('is_active', true)->orderBy('order')->get();

        $stats = [
            'total_modules' => $modules->count(),
            'total_endpoints' => $endpoints->count(),
            'total_commands' => $cliCommands->count(),
            'pending_tasks' => ProjectTask::where('status', 'pending')->count(),
            'in_progress_tasks' => ProjectTask::where('status', 'in_progress')->count(),
            'open_bugs' => ProjectBug::where('status', 'open')->count(),
            'critical_bugs' => ProjectBug::where('severity', 'critical')->where('status', '!=', 'resolved')->count(),
        ];

        return view('project-management::project-management.index', compact('modules', 'endpoints', 'cliCommands', 'tasks', 'bugs', 'flows', 'stats'));
    }

    // Module CRUD
    public function storeModule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:project_modules,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:active,deprecated,planned',
            'category' => 'nullable|string',
        ]);

        ProjectModule::create($validated);

        return redirect()->route('master.project-management')->with('success', 'Module created successfully');
    }

    public function updateModule(Request $request, ProjectModule $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:project_modules,slug,' . $module->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,deprecated,planned',
            'category' => 'nullable|string',
        ]);

        $module->update($validated);

        return redirect()->route('master.project-management')->with('success', 'Module updated successfully');
    }

    public function destroyModule(ProjectModule $module)
    {
        $module->delete();
        return redirect()->route('master.project-management')->with('success', 'Module deleted successfully');
    }

    // Task CRUD
    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,blocked',
            'priority' => 'required|in:low,medium,high,critical',
            'type' => 'required|in:feature,enhancement,refactor,documentation',
            'due_date' => 'nullable|date',
        ]);

        ProjectTask::create($validated);

        return redirect()->route('master.project-management')->with('success', 'Task created successfully');
    }

    public function updateTask(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,blocked',
            'priority' => 'required|in:low,medium,high,critical',
            'type' => 'required|in:feature,enhancement,refactor,documentation',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && !$task->completed_at) {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('master.project-management')->with('success', 'Task updated successfully');
    }

    public function destroyTask(ProjectTask $task)
    {
        $task->delete();
        return redirect()->route('master.project-management')->with('success', 'Task deleted successfully');
    }

    // Bug CRUD
    public function storeBug(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,investigating,in_progress,resolved,wont_fix',
            'type' => 'required|in:bug,issue,error',
            'steps_to_reproduce' => 'nullable|string',
            'expected_behavior' => 'nullable|string',
            'actual_behavior' => 'nullable|string',
            'file_path' => 'nullable|string',
            'line_number' => 'nullable|integer',
            'stack_trace' => 'nullable|string',
        ]);

        ProjectBug::create($validated);

        return redirect()->route('master.project-management')->with('success', 'Bug reported successfully');
    }

    public function updateBug(Request $request, ProjectBug $bug)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,investigating,in_progress,resolved,wont_fix',
            'type' => 'required|in:bug,issue,error',
            'steps_to_reproduce' => 'nullable|string',
            'expected_behavior' => 'nullable|string',
            'actual_behavior' => 'nullable|string',
            'file_path' => 'nullable|string',
            'line_number' => 'nullable|integer',
            'stack_trace' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && !$bug->resolved_at) {
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] !== 'resolved') {
            $validated['resolved_at'] = null;
        }

        $bug->update($validated);

        return redirect()->route('master.project-management')->with('success', 'Bug updated successfully');
    }

    public function destroyBug(ProjectBug $bug)
    {
        $bug->delete();
        return redirect()->route('master.project-management')->with('success', 'Bug deleted successfully');
    }

    // Flow CRUD
    public function storeFlow(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:project_flows,slug',
            'description' => 'nullable|string',
            'mermaid_diagram' => 'required|string',
            'type' => 'required|in:flowchart,sequence,class,state,gantt,pie',
        ]);

        ProjectFlow::create($validated);

        return redirect()->route('master.project-management')->with('success', 'Flow diagram created successfully');
    }

    public function updateFlow(Request $request, ProjectFlow $flow)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:project_flows,slug,' . $flow->id,
            'description' => 'nullable|string',
            'mermaid_diagram' => 'required|string',
            'type' => 'required|in:flowchart,sequence,class,state,gantt,pie',
            'is_active' => 'boolean',
        ]);

        $flow->update($validated);

        return redirect()->route('master.project-management')->with('success', 'Flow diagram updated successfully');
    }

    public function destroyFlow(ProjectFlow $flow)
    {
        $flow->delete();
        return redirect()->route('master.project-management')->with('success', 'Flow diagram deleted successfully');
    }

    // Endpoint CRUD
    public function updateEndpoint(Request $request, ProjectEndpoint $endpoint)
    {
        $validated = $request->validate([
            'module_id' => 'nullable|exists:project_modules,id',
        ]);

        $endpoint->update($validated);

        return redirect()->route('master.project-management')->with('success', 'Endpoint module updated successfully');
    }

    // Scan Project
    public function scanProject(Request $request)
    {
        $fresh = $request->has('fresh');
        
        try {
            \Artisan::call('project:scan', $fresh ? ['--fresh' => true] : []);
            $output = \Artisan::output();
            
            return redirect()->route('master.project-management')
                ->with('success', 'Project scan completed successfully!')
                ->with('scan_output', $output);
        } catch (\Exception $e) {
            return redirect()->route('master.project-management')
                ->with('error', 'Scan failed: ' . $e->getMessage());
        }
    }
}
