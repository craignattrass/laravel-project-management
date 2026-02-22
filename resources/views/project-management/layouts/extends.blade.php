@php
    $layout = config('project-management.layout');
@endphp

@extends($layout)

@section('content')
    @include('project-management::project-management.partials.content')
@endsection
