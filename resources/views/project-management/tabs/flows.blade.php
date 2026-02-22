<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Document Flows (Mermaid.js)</h2>
        <button onclick="openModal('flowModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            + Add Flow Diagram
        </button>
    </div>

    <p class="text-sm text-gray-600 mb-6">Visual documentation of system flows using Mermaid.js. Perfect for keeping Copilot context-aware of application architecture.</p>

    <div class="space-y-6">
        @forelse($flows as $flow)
            <div class="border rounded-lg bg-white p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $flow->name }}</h3>
                        @if($flow->description)
                            <p class="text-sm text-gray-600 mb-2">{{ $flow->description }}</p>
                        @endif
                        <div class="flex gap-2 items-center">
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">{{ ucfirst($flow->type) }}</span>
                            @if($flow->module)
                                <span class="text-xs text-gray-600">Module: {{ $flow->module->name }}</span>
                            @endif
                        </div>
                    </div>
                    <button class="text-red-600 hover:text-red-900 text-sm" onclick="if(confirm('Delete flow diagram?')) document.getElementById('delete-flow-{{$flow->id}}').submit()">Delete</button>
                    <form id="delete-flow-{{$flow->id}}" method="POST" action="{{ route('project-management.flow.delete', $flow) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                
                <!-- Mermaid Diagram -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 overflow-x-auto">
                    <div class="mermaid">
{{ $flow->mermaid_diagram }}
                    </div>
                </div>
                
                <!-- Diagram Source Toggle -->
                <details class="mt-4">
                    <summary class="cursor-pointer text-sm text-gray-600 hover:text-gray-900">View Mermaid Source</summary>
                    <pre class="mt-2 bg-gray-900 text-gray-100 p-4 rounded-lg text-xs overflow-x-auto"><code>{{ $flow->mermaid_diagram }}</code></pre>
                </details>
            </div>
        @empty
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <p class="text-gray-700 mb-4">No flow diagrams yet. Create one to visualize your system architecture!</p>
                <p class="text-sm text-gray-600">Example Mermaid syntax:</p>
                <pre class="mt-3 bg-white border rounded-lg p-3 text-left inline-block text-xs"><code>graph TD
    A[User Request] --> B{Authenticated?}
    B -->|Yes| C[Process Request]
    B -->|No| D[Redirect to Login]
    C --> E[(Database)]
    E --> F[Return Response]</code></pre>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Flow Modal -->
<div id="flowModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Add Flow Diagram</h3>
        <form method="POST" action="{{ route('project-management.flow.create') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                    <input type="text" name="slug" required placeholder="authentication-flow" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="flowchart" selected>Flowchart</option>
                            <option value="sequence">Sequence</option>
                            <option value="class">Class</option>
                            <option value="state">State</option>
                            <option value="gantt">Gantt</option>
                            <option value="pie">Pie</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mermaid Diagram Code *</label>
                    <textarea name="mermaid_diagram" rows="10" required placeholder="graph TD
    A[Start] --> B{Decision}
    B -->|Yes| C[Action 1]
    B -->|No| D[Action 2]" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-purple-500 font-mono text-sm"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Learn Mermaid syntax: <a href="https://mermaid.js.org/intro/" target="_blank" class="text-purple-600 hover:underline">mermaid.js.org</a>
                    </p>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('flowModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Create Flow</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Mermaid.js CDN -->
<script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
    mermaid.initialize({ 
        startOnLoad: true,
        theme: 'default',
        securityLevel: 'loose'
    });
</script>
