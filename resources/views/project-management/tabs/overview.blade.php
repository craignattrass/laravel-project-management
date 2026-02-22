<div>
    <h2 class="text-2xl font-semibold mb-4">Project Overview</h2>
    <p class="text-gray-600 mb-6">Welcome to the Project Intelligence Dashboard. This system tracks all modules, APIs, CLI commands, tasks, bugs, and document flows for GitHub Copilot context awareness.</p>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Copilot Instructions</h3>
        <p class="text-blue-800 mb-4">To help GitHub Copilot maintain this dashboard:</p>
        <ul class="list-disc list-inside text-blue-800 space-y-2">
            <li>When creating new routes, controllers, or commands, update the relevant sections</li>
            <li>Document all API endpoints with parameters and response examples</li>
            <li>Keep flow diagrams updated using Mermaid.js syntax</li>
            <li>Track bugs and tasks to maintain project visibility</li>
            <li>Use the "Scan Project" command to auto-populate APIs and CLI commands</li>
        </ul>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">📦 Modules ({{ $modules->count() }})</h3>
            <div class="space-y-2">
                @forelse($modules as $module)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div>
                            <span class="font-medium">{{ $module->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $module->status }})</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $module->endpoints_count }} APIs, {{ $module->cli_commands_count }} Commands
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No modules yet. Create one in the Modules tab.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">🔥 Priority Tasks</h3>
            <div class="space-y-2">
                @forelse($tasks->where('priority', 'critical')->take(5) as $task)
                    <div class="flex items-center py-2 border-b border-gray-100">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800 mr-2">CRITICAL</span>
                        <span class="text-sm">{{ $task->title }}</span>
                    </div>
                @empty
                    @forelse($tasks->where('priority', 'high')->take(5) as $task)
                        <div class="flex items-center py-2 border-b border-gray-100">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800 mr-2">HIGH</span>
                            <span class="text-sm">{{ $task->title }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No high-priority tasks. Great job! 🎉</p>
                    @endforelse
                @endforelse
            </div>
        </div>
    </div>

    @if($stats['critical_bugs'] > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-red-900 mb-3">⚠️ Critical Bugs Require Immediate Attention!</h3>
            <div class="space-y-2">
                @foreach($bugs->where('severity', 'critical')->where('status', '!=', 'resolved') as $bug)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-red-800">{{ $bug->title }}</span>
                        <span class="text-xs text-red-600">{{ ucfirst($bug->status) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
