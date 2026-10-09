@extends('docs::layout')

@section('content')
    <div class="mb-2 text-sm text-muted-foreground">
        <a href="{{ $docs->url() }}" class="hover:text-foreground">Docs</a>
        <span class="mx-1">/</span>
        <span>{{ $page['group'] }}</span>
    </div>
    <h1 class="scroll-m-20 text-3xl font-bold tracking-tight">{{ $page['title'] }}</h1>
    <p class="mt-2 text-lg text-muted-foreground">{{ $page['description'] }}</p>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($page['components'] as $tag)
            <code class="rounded-md bg-muted px-2 py-0.5 font-mono text-xs">&lt;x-{{ $tag }}&gt;</code>
        @endforeach
    </div>

    @php $unpublished = $docs->unpublished($page); @endphp
    @if (count($page['components']) && count($unpublished))
        <div class="mt-5 rounded-lg border border-amber-300/70 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
            <p>
                <span class="font-medium">Not published by <code class="font-mono">bladecn:install</code> yet.</span>
                Copy
                @foreach ($unpublished as $tag)
                    <code class="font-mono">{{ str_replace('.', '/', $tag) }}</code>{{ $loop->last ? '' : ',' }}
                @endforeach
                from <code class="font-mono">vendor/bladecn/bladecn/resources/views/components/</code> into
                <code class="font-mono">resources/views/components/</code>, plus any matching class from
                <code class="font-mono">src/View/Components/Ui/</code> into <code class="font-mono">app/View/Components/Ui/</code>
                (namespace <code class="font-mono">App\View\Components\Ui</code>, view <code class="font-mono">components.ui.*</code>).
            </p>
        </div>
    @elseif (count($page['components']))
        <p class="mt-4 text-sm text-muted-foreground">Published to your app by <code class="font-mono">php artisan bladecn:install</code>.</p>
    @endif

    @if (! empty($page['intro']))
        <div class="docs-prose mt-6">{!! $docs->markdown($page['intro']) !!}</div>
    @endif

    @foreach ($examples as $example)
        <section class="mt-10" id="example-{{ $example['key'] }}">
            @unless ($loop->first && $example['key'] === 'default')
                <h2 class="mb-1 scroll-m-20 text-xl font-semibold tracking-tight">{{ $example['title'] }}</h2>
            @endunless
            @if ($example['description'])
                <div class="docs-prose mb-3 text-muted-foreground">{!! $docs->markdown($example['description']) !!}</div>
            @endif
            @include('docs::partials.example', ['example' => $example])
        </section>
    @endforeach

    @if (! empty($page['anatomy']))
        <section class="mt-12" id="composition">
            <h2 class="mb-3 scroll-m-20 text-xl font-semibold tracking-tight">Composition</h2>
            @if (! empty($page['composition']))
                <div class="docs-prose mb-4">{!! $docs->markdown($page['composition']) !!}</div>
            @endif
            @include('docs::partials.code', ['code' => trim($page['anatomy'])."\n"])
        </section>
    @elseif (! empty($page['composition']))
        <section class="mt-12" id="composition">
            <h2 class="mb-3 scroll-m-20 text-xl font-semibold tracking-tight">Composition</h2>
            <div class="docs-prose">{!! $docs->markdown($page['composition']) !!}</div>
        </section>
    @endif

    <section class="mt-12" id="props">
        <h2 class="mb-3 scroll-m-20 text-xl font-semibold tracking-tight">Props</h2>
        <p class="mb-4 text-sm text-muted-foreground">
            Generated from each component's constructor or <code class="font-mono">@@props</code> directive.
            Pass props as kebab-case attributes; prefix with <code class="font-mono">:</code> to pass PHP expressions.
        </p>
        @foreach ($propTables as $table)
            @include('docs::partials.props', ['table' => $table])
        @endforeach
    </section>

    @if (! empty($page['notes']))
        <section class="mt-12" id="notes">
            <h2 class="mb-3 scroll-m-20 text-xl font-semibold tracking-tight">Notes</h2>
            <div class="docs-prose">{!! $docs->markdown($page['notes']) !!}</div>
        </section>
    @endif
@endsection

@section('toc')
    @foreach ($examples as $example)
        <li><a class="hover:text-foreground" href="#example-{{ $example['key'] }}">{{ $example['title'] }}</a></li>
    @endforeach
    @if (! empty($page['anatomy']) || ! empty($page['composition']))
        <li><a class="hover:text-foreground" href="#composition">Composition</a></li>
    @endif
    <li><a class="hover:text-foreground" href="#props">Props</a></li>
    @if (! empty($page['notes']))
        <li><a class="hover:text-foreground" href="#notes">Notes</a></li>
    @endif
@endsection
