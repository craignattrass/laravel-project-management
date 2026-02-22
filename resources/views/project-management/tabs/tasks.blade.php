<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Project Tasks & TODOs</h2>
        <button onclick="openModal('taskModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            + Add Task
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4 flex gap-2">
        <button onclick="filterTasks('all')" class="task-filter-btn px-4 py-2 rounded-lg bg-purple-100 text-purple-700 font-semibold">All</button>
        <button onclick="filterTasks('pending')" class="task-filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700">Pending</button>
        <button onclick="filterTasks('in_progress')" class="task-filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700">In Progress</button>
        <button onclick="filterTasks('completed')" class="task-filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700">Completed</button>
    </div>

    <div class="space-y-3">
        @forelse($tasks as $task)
            <div class="task-item border rounded-lg p-4 bg-white hover:shadow-md transition" data-status="{{ $task->status }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @if($task->priority === 'critical') bg-red-100 text-red-800
                                @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ strtoupper($task->priority) }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @if($task->status === 'completed') bg-green-100 text-green-800
                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($task->status === 'blocked') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $task->status)) }}
                            </span>
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">{{ ucfirst($task->type) }}</span>
                            @if($task->module)
                                <span class="text-xs text-gray-600">{{ $task->module->name }}</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $task->title }}</h3>
                        @if($task->description)
                            <p class="text-sm text-gray-600">{{ Str::limit($task->description, 100) }}</p>
                        @endif
                        @if($task->due_date)
                            <p class="text-xs text-gray-500 mt-2">Due: {{ $task->due_date->format('Y-m-d') }}</p>
                        @endif
                    </div>
                    <div>
                        <button class="text-red-600 hover:text-red-900 text-sm" onclick="if(confirm('Delete task?')) document.getElementById('delete-task-{{$task->id}}').submit()">Delete</button>
                        <form id="delete-task-{{$task->id}}" method="POST" action="{{ route('project-management.task.delete', $task) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No tasks yet. Create one to get started!</p>
        @endforelse
    </div>
</div>

<!-- Add Task Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Add New Task</h3>
        <form method="POST" action="{{ route('project-management.task.create') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                        <select name="priority" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="feature">Feature</option>
                            <option value="enhancement">Enhancement</option>
                            <option value="refactor">Refactor</option>
                            <option value="documentation">Documentation</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                        <select name="module_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="">None</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                    <input type="date" name="due_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('taskModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Create Task</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function filterTasks(status) {
    document.querySelectorAll('.task-filter-btn').forEach(btn => {
        btn.classList.remove('bg-purple-100', 'text-purple-700', 'font-semibold');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    event.target.classList.add('bg-purple-100', 'text-purple-700', 'font-semibold');
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    
    document.querySelectorAll('.task-item').forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}
</script>
