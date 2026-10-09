@props(['breadcrumbs' => []])

@php
    $count = count($breadcrumbs);
@endphp

@if ($count > 0)
    <nav aria-label="breadcrumb" data-slot="breadcrumb" class="max-w-full overflow-hidden">
        <ol data-slot="breadcrumb-list"
            class="text-muted-foreground flex flex-wrap items-center gap-1.5 text-sm sm:gap-2.5">
            @foreach ($breadcrumbs as $index => $breadcrumb)
                @php
                    $isFirst = $loop->first;
                    $isLast = $loop->last;
                    $isSecondLast = $index === $count - 2;
                    $shouldShow = false;

                    if ($count <= 2) {
                        // 1 or 2 breadcrumbs → show all
                        $shouldShow = true;
                    } elseif ($count === 3) {
                        // Show first and last only, but we’ll add ellipsis separately
                        $shouldShow = $isFirst || $isLast;
                    } else {
                        // 4+ → show first, second-last, and last
                        $shouldShow = $isFirst || $isSecondLast || $isLast;
                    }
                @endphp

                {{-- Visible breadcrumb items --}}
                @if ($shouldShow)
                    <li data-slot="breadcrumb-item"
                        class="inline-flex items-center gap-1.5 max-w-[120px] sm:max-w-[160px] truncate">
                        @if ($isLast)
                            <span data-slot="breadcrumb-page" role="link" aria-disabled="true" aria-current="page"
                                class="text-foreground font-normal block truncate">
                                {{ $breadcrumb['label'] ?? $breadcrumb['title'] ?? '' }}
                            </span>
                        @else
                            <a data-slot="breadcrumb-link" href="{{ $breadcrumb['href'] ?? '#' }}"
                                class="hover:text-foreground transition-colors block truncate">
                                {{ $breadcrumb['label'] ?? $breadcrumb['title'] ?? '' }}
                            </a>
                        @endif
                    </li>

                    {{-- Separator --}}
                    @if (!$isLast)
                        <li data-slot="breadcrumb-separator" role="presentation" aria-hidden="true"
                            class="[&>svg]:size-3.5 shrink-0">
                            <x-icons.chevron-right />
                        </li>
                    @endif
                @elseif ($index === 1 && $count >= 3)
                    {{-- Ellipsis shown for 3 or more items --}}
                    <li data-slot="breadcrumb-item" class="inline-flex items-center gap-1.5 shrink-0">
                        <span data-slot="breadcrumb-ellipsis" role="presentation" aria-hidden="true"
                            class="flex items-center justify-center size-4">
                            <x-icons.ellipsis class="size-4" />
                            <span class="sr-only">More</span>
                        </span>
                    </li>
                    <li data-slot="breadcrumb-separator" role="presentation" aria-hidden="true"
                        class="[&>svg]:size-3.5 shrink-0">
                        <x-icons.chevron-right />
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
