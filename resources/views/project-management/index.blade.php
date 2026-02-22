@php
    $layout = config('project-management.layout');
    $layoutType = config('project-management.layout_type');
@endphp

@if($layoutType === 'component' && $layout)
    {{-- Component-based layout (e.g., Breeze/Jetstream) --}}
    <x-dynamic-component :component="$layout">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Project Intelligence Dashboard') }}
                </h2>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('project-management.scan') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Scan Project
                        </button>
                    </form>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
@elseif($layoutType === 'extends' && $layout)
    {{-- Traditional Blade layout with @extends --}}
    @extends($layout)

    @section('content')
@else
    {{-- Standalone layout (no parent) --}}
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Intelligence Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen">
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">Project Management</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('project-management.scan') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Scan Project
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
@endif

<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Project Intelligence Dashboard</h1>
        <p class="text-gray-600 mt-1">Track modules, APIs, CLI commands, tasks, bugs, and document flows</p>
    </div>

    @if(session('scan_output'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-green-900 mb-2">Scan Results:</h3>
            <pre class="text-sm text-green-800 whitespace-pre-wrap">{{ session('scan_output') }}</pre>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Modules</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_modules'] }}</p>
                </div>
                <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">API Endpoints</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_endpoints'] }}</p>
                </div>
                <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Tasks</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_tasks'] + $stats['in_progress_tasks'] }}</p>
                </div>
                <svg class="h-12 w-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open Bugs</p>
                    <p class="text-3xl font-bold {{ $stats['critical_bugs'] > 0 ? 'text-red-600' : 'text-orange-600' }}">{{ $stats['open_bugs'] }}</p>
                    @if($stats['critical_bugs'] > 0)
                        <p class="text-xs text-red-600 font-semibold">{{ $stats['critical_bugs'] }} Critical!</p>
                    @endif
                </div>
                <svg class="h-12 w-12 {{ $stats['critical_bugs'] > 0 ? 'text-red-600' : 'text-orange-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('overview')" id="tab-overview" class="tab-button active py-4 px-6 text-center border-b-2 font-medium text-sm">
                    Overview
                </button>
                <button onclick="showTab('modules')" id="tab-modules" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    Modules
                </button>
                <button onclick="showTab('endpoints')" id="tab-endpoints" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    API Endpoints
                </button>
                <button onclick="showTab('commands')" id="tab-commands" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    CLI Commands
                </button>
                <button onclick="showTab('tasks')" id="tab-tasks" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    Tasks
                </button>
                <button onclick="showTab('bugs')" id="tab-bugs" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    Bugs
                </button>
                <button onclick="showTab('flows')" id="tab-flows" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm">
                    Document Flows
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div id="tab-content-overview" class="tab-content p-6">
            @include('project-management::project-management.tabs.overview')
        </div>

        <div id="tab-content-modules" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.modules')
        </div>

        <div id="tab-content-endpoints" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.endpoints')
        </div>

        <div id="tab-content-commands" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.commands')
        </div>

        <div id="tab-content-tasks" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.tasks')
        </div>

        <div id="tab-content-bugs" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.bugs')
        </div>

        <div id="tab-content-flows" class="tab-content hidden p-6">
            @include('project-management::project-management.tabs.flows')
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-purple-600', 'text-purple-600');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    // Show selected tab content
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-purple-600', 'text-purple-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
}

// Initialize tabs on page load
document.addEventListener('DOMContentLoaded', function() {
    showTab('overview');
});
</script>

<style>
.tab-button {
    transition: all 0.3s;
}
.tab-button:not(.active) {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}
.tab-button.active {
    @apply border-purple-600 text-purple-600;
}
</style>

</div>

@if($layoutType === 'component' && $layout)
    {{-- Close component layout --}}
            </div>
        </div>
    </x-dynamic-component>
@elseif($layoutType === 'extends' && $layout)
    {{-- Close @extends layout --}}
    @endsection
@else
    {{-- Close standalone layout --}}
                </div>
            </div>
        </div>
    </body>
    </html>
@endif
