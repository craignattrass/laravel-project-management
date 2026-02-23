<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Bug Tracker</h2>
        <button onclick="openModal('bugModal')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            + Report Bug
        </button>
    </div>

    <div class="space-y-3">
        @forelse($bugs as $bug)
            <div class="border rounded-lg p-4 bg-white hover:shadow-md transition cursor-pointer bug-card"
                 data-bug-id="{{ $bug->id }}"
                 onclick="window.openEditBugModal && window.openEditBugModal({{ $bug->id }})">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @if($bug->severity === 'critical') bg-red-100 text-red-800
                                @elseif($bug->severity === 'high') bg-orange-100 text-orange-800
                                @elseif($bug->severity === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ strtoupper($bug->severity) }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @if($bug->status === 'resolved') bg-green-100 text-green-800
                                @elseif($bug->status === 'in_progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $bug->status)) }}
                            </span>
                            @if($bug->module)
                                <span class="text-xs text-gray-600">{{ $bug->module->name }}</span>
                            @endif
                            @if($bug->file_path)
                                <span class="text-xs font-mono text-gray-500">{{ basename($bug->file_path) }}:{{ $bug->line_number }}</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $bug->title }}</h3>
                        <p class="text-sm text-gray-600">{{ Str::limit($bug->description, 150) }}</p>
                        @if($bug->resolved_at)
                            <p class="text-xs text-green-600 mt-2">✓ Resolved {{ $bug->resolved_at->diffForHumans() }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-2">Reported {{ $bug->created_at->diffForHumans() }}</p>
                        @endif
                        @if($bug->resolution_notes)
                            <div class="mt-3 p-2 bg-green-50 border-l-4 border-green-400 rounded text-sm">
                                <strong class="text-green-800">Resolution:</strong>
                                <p class="text-gray-700 text-xs mt-1">{{ $bug->resolution_notes }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2" onclick="event.stopPropagation()">
                        <form method="POST" action="{{ route('project-management.bug.toggle-status', $bug) }}" class="inline" onclick="event.stopPropagation()">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 text-sm rounded
                                @if($bug->status === 'resolved') bg-gray-100 text-gray-600 hover:bg-gray-200
                                @elseif($bug->status === 'in_progress') bg-green-100 text-green-600 hover:bg-green-200
                                @elseif($bug->status === 'investigating') bg-blue-100 text-blue-600 hover:bg-blue-200
                                @else bg-yellow-100 text-yellow-600 hover:bg-yellow-200
                                @endif" title="Click to change status">
                                @if($bug->status === 'resolved') Reopen
                                @elseif($bug->status === 'in_progress') Resolve
                                @elseif($bug->status === 'investigating') Start Fix
                                @else Investigate
                                @endif
                            </button>
                        </form>
                        <button onclick="event.stopPropagation(); window.openEditBugModal({{ $bug->id }})" class="px-3 py-1 bg-blue-100 text-blue-600 hover:bg-blue-200 text-sm rounded">Edit</button>
                        <button class="text-red-600 hover:text-red-900 text-sm" onclick="event.stopPropagation(); if(confirm('Delete bug report?')) document.getElementById('delete-bug-{{$bug->id}}').submit()">Delete</button>
                        <form id="delete-bug-{{$bug->id}}" method="POST" action="{{ route('project-management.bug.delete', $bug) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No bugs reported. Awesome! 🎉</p>
        @endforelse
    </div>
</div>

<!-- Edit Bug Modal -->
<div id="editBugModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Edit Bug</h3>
        <form id="editBugForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" id="edit_bug_title" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="edit_bug_description" rows="3" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Severity *</label>
                        <select name="severity" id="edit_bug_severity" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="edit_bug_status" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="open">Open</option>
                            <option value="investigating">Investigating</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="wont_fix">Won't Fix</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" id="edit_bug_type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="bug">Bug</option>
                            <option value="issue">Issue</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                    <select name="module_id" id="edit_bug_module_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                        <option value="">None</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Path</label>
                        <input type="text" name="file_path" id="edit_bug_file_path" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Line Number</label>
                        <input type="number" name="line_number" id="edit_bug_line_number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Steps to Reproduce</label>
                    <textarea name="steps_to_reproduce" id="edit_bug_steps" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stack Trace</label>
                    <textarea name="stack_trace" id="edit_bug_stack_trace" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500 font-mono text-xs"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resolution Notes</label>
                    <textarea name="resolution_notes" id="edit_bug_resolution" rows="2" placeholder="How was this bug fixed?" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('editBugModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Update Bug</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const bugData = @json($bugs);

window.openModal = window.openModal || function(id) {
    document.getElementById(id).classList.remove('hidden');
}

window.closeModal = window.closeModal || function(id) {
    document.getElementById(id).classList.add('hidden');
}

window.openEditBugModal = function(bugId) {
    const bug = bugData.find(b => b.id === bugId);
    if (!bug) return;
    
    document.getElementById('edit_bug_title').value = bug.title || '';
    document.getElementById('edit_bug_description').value = bug.description || '';
    document.getElementById('edit_bug_severity').value = bug.severity || 'medium';
    document.getElementById('edit_bug_status').value = bug.status || 'open';
    document.getElementById('edit_bug_type').value = bug.type || 'bug';
    document.getElementById('edit_bug_module_id').value = bug.module_id || '';
    document.getElementById('edit_bug_file_path').value = bug.file_path || '';
    document.getElementById('edit_bug_line_number').value = bug.line_number || '';
    document.getElementById('edit_bug_steps').value = bug.steps_to_reproduce || '';
    document.getElementById('edit_bug_stack_trace').value = bug.stack_trace || '';
    document.getElementById('edit_bug_resolution').value = bug.resolution_notes || '';
    
    document.getElementById('editBugForm').action = `/project-management/bug/${bugId}`;
    openModal('editBugModal');
}
</script>

<!-- Add Bug Modal -->
<div id="bugModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Report Bug</h3>
        <form method="POST" action="{{ route('project-management.bug.create') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Severity *</label>
                        <select name="severity" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="open" selected>Open</option>
                            <option value="investigating">Investigating</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="wont_fix">Won't Fix</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                            <option value="bug" selected>Bug</option>
                            <option value="issue">Issue</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                    <select name="module_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                        <option value="">None</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Path</label>
                        <input type="text" name="file_path" placeholder="app/Http/Controllers/..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Line Number</label>
                        <input type="number" name="line_number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Steps to Reproduce</label>
                    <textarea name="steps_to_reproduce" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stack Trace</label>
                    <textarea name="stack_trace" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-red-500 font-mono text-xs"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('bugModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Report Bug</button>
                </div>
            </div>
        </form>
    </div>
</div>
