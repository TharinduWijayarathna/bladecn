@extends('docs::layout')

@section('content')
    <div class="mb-2 text-sm text-muted-foreground">Docs <span class="mx-1">/</span> {{ $page['group'] }}</div>
    <h1 class="scroll-m-20 text-3xl font-bold tracking-tight">{{ $page['title'] }}</h1>
    <p class="mt-2 text-lg text-muted-foreground">{{ $page['description'] }}</p>

    <div class="mt-8">
        @include('docs::pages.'.$page['slug'])
    </div>
@endsection
