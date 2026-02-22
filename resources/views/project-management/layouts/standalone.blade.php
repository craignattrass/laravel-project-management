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
                @include('project-management::project-management.partials.content')
            </div>
        </div>
    </div>
</body>
</html>
