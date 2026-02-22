@php
    $layout = config('project-management.layout');
@endphp

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
            @include('project-management::project-management.partials.content')
        </div>
    </div>
</x-dynamic-component>
