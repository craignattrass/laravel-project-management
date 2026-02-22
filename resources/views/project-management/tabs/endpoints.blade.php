<div>
    <h2 class="text-2xl font-semibold mb-4">API Endpoints Registry</h2>
    <p class="text-sm text-gray-600 mb-4">Auto-populated endpoints. Use the "Scan Project" button to update this list. Click module name to change assignment.</p>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">URI</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Controller</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auth</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($endpoints as $endpoint)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-mono font-semibold rounded
                                @if($endpoint->method === 'GET') bg-blue-100 text-blue-800
                                @elseif($endpoint->method === 'POST') bg-green-100 text-green-800
                                @elseif($endpoint->method === 'PUT') bg-yellow-100 text-yellow-800
                                @elseif($endpoint->method === 'DELETE') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $endpoint->method }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $endpoint->uri }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $endpoint->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($endpoint->controller, 30) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <button onclick="openAssignModuleModal({{ $endpoint->id }}, {{ $endpoint->module_id ?? 'null' }})" 
                                    class="text-purple-600 hover:text-purple-900 hover:underline">
                                {{ $endpoint->module->name ?? 'Unassigned' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($endpoint->requires_auth)
                                <svg class="h-5 w-5 text-green-600 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-gray-400 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No endpoints registered. Run <code class="bg-gray-100 px-2 py-1 rounded">php artisan project:scan</code> to populate.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Assign Module Modal -->
<div id="assignModuleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Assign to Module</h3>
        <form id="assignModuleForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Module</label>
                <select name="module_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                    <option value="">Unassigned</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('assignModuleModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Assign</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModuleModal(endpointId, currentModuleId) {
    const form = document.getElementById('assignModuleForm');
    // Use Laravel route helper to generate correct URL
    form.action = '{{ url('project-management/endpoint') }}/' + endpointId + '/assign-module';
    
    // Set current module as selected
    const select = form.querySelector('select[name="module_id"]');
    if (currentModuleId) {
        select.value = currentModuleId;
    } else {
        select.value = '';
    }
    
    document.getElementById('assignModuleModal').classList.remove('hidden');
}
</script>
