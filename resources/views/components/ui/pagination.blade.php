@props([
    'paginator' => null,
    'onEachSide' => 1,
    'class' => '',
])

{{--
    Compose it yourself with pagination-content / -item / -link / -previous / -next / -ellipsis,
    or pass a Laravel paginator (`:paginator="$users"`) to render the links for you.
--}}
<nav role="navigation" aria-label="pagination" data-slot="pagination"
    {{ $attributes->merge(['class' => cn('mx-auto flex w-full justify-center', $class)]) }}>
    @if ($paginator instanceof \Illuminate\Contracts\Pagination\Paginator)
        @php
            $isLengthAware = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
            $window = $isLengthAware && method_exists($paginator, 'onEachSide')
                ? \Illuminate\Pagination\UrlWindow::make($paginator->onEachSide($onEachSide))
                : null;
            $elements = $window ? array_filter([
                $window['first'],
                is_array($window['slider']) ? '...' : null,
                $window['slider'],
                is_array($window['last']) ? '...' : null,
                $window['last'],
            ]) : [];
        @endphp

        @if ($paginator->hasPages())
            <x-ui.pagination-content>
                <x-ui.pagination-item>
                    <x-ui.pagination-previous :href="$paginator->previousPageUrl()" :disabled="$paginator->onFirstPage()" />
                </x-ui.pagination-item>

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <x-ui.pagination-item><x-ui.pagination-ellipsis /></x-ui.pagination-item>
                    @else
                        @foreach ($element as $page => $url)
                            <x-ui.pagination-item>
                                <x-ui.pagination-link :href="$url" :is-active="$page == $paginator->currentPage()">{{ $page }}</x-ui.pagination-link>
                            </x-ui.pagination-item>
                        @endforeach
                    @endif
                @endforeach

                <x-ui.pagination-item>
                    <x-ui.pagination-next :href="$paginator->nextPageUrl()" :disabled="! $paginator->hasMorePages()" />
                </x-ui.pagination-item>
            </x-ui.pagination-content>
        @endif
    @else
        {{ $slot }}
    @endif
</nav>
