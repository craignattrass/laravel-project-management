<div>
    <h2 class="text-2xl font-semibold mb-4">CLI Commands Registry</h2>
    <p class="text-sm text-gray-600 mb-4">Artisan commands available in this project. Auto-populated via scanning.</p>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Command</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cliCommands as $command)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap font-mono text-sm text-purple-700">
                            {{ $command->signature }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ Str::limit($command->description, 60) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $command->module->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            @if($command->schedule)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">{{ $command->schedule }}</span>
                            @else
                                <span class="text-gray-400">Manual</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            No commands registered. Run <code class="bg-gray-100 px-2 py-1 rounded">php artisan project:scan</code> to populate.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
