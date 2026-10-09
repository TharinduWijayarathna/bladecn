@extends('docs::layout')

@section('content')
    @if ($page['slug'] === 'introduction')
        @include('docs::partials.hero')
    @else
        @include('docs::partials.page-header', ['page' => $page])
    @endif

    <div class="mt-8" data-docs-body>
        @include('docs::pages.'.$page['slug'])
    </div>
@endsection
