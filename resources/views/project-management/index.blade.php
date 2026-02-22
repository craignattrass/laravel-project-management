@php
    $layoutType = config('project-management.layout_type');
    
    // Map layout types to their respective view files
    $layoutViews = [
        'component' => 'project-management::project-management.layouts.component',
        'extends' => 'project-management::project-management.layouts.extends',
        'standalone' => 'project-management::project-management.layouts.standalone',
    ];
    
    // Default to 'extends' if layout type is null or invalid
    $layoutView = $layoutViews[$layoutType] ?? $layoutViews['extends'];
@endphp

@include($layoutView)

