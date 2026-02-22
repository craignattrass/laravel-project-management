<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Project Modules</h2>
        <button onclick="openModal('moduleModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            + Add Module
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($modules as $module)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $module->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $module->category ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded 
                                @if($module->status === 'active') bg-green-100 text-green-800
                                @elseif($module->status === 'deprecated') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($module->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $module->endpoints_count }} APIs, {{ $module->cli_commands_count }} CLI
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($module->description, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="text-red-600 hover:text-red-900" onclick="if(confirm('Delete module?')) document.getElementById('delete-module-{{$module->id}}').submit()">Delete</button>
                            <form id="delete-module-{{$module->id}}" method="POST" action="{{ route('project-management.module.delete', $module) }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No modules yet. Click "Add Module" to get started.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Module Modal -->
<div id="moduleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Add New Module</h3>
        <form method="POST" action="{{ route('project-management.module.create') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                    <input type="text" name="slug" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                        <option value="">Select...</option>
                        <option value="service">Service</option>
                        <option value="api">API</option>
                        <option value="ui">UI</option>
                        <option value="background">Background</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                        <option value="active">Active</option>
                        <option value="deprecated">Deprecated</option>
                        <option value="planned">Planned</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('moduleModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Create Module</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
