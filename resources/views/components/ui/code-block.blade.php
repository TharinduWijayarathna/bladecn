@props([
    'code' => null,
    'language' => null,
    'filename' => null,
    'lineNumbers' => false,
    'copyable' => true,
    'class' => '',
])

@php
    // `code` is escaped for you. Slot content is printed as-is, so escape it yourself ({{ }} or @verbatim).
    $body = $code !== null ? e(rtrim((string) $code)) : rtrim((string) $slot);
    $lines = max(1, substr_count(html_entity_decode(strip_tags($body)), "\n") + 1);
    $lineNumbers = filter_var($lineNumbers, FILTER_VALIDATE_BOOLEAN);
    $copyable = filter_var($copyable, FILTER_VALIDATE_BOOLEAN);
@endphp

{{-- A code sample with a copy-to-clipboard button and optional filename header and line numbers. --}}
<figure data-slot="code-block" @if ($language) data-language="{{ $language }}" @endif
    x-data="{
        copied: false,
        timer: null,
        async copy() {
            const text = this.$refs.code.textContent;
            try {
                await navigator.clipboard.writeText(text);
            } catch (error) {
                const area = Object.assign(document.createElement('textarea'), { value: text });
                area.setAttribute('readonly', '');
                area.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(area);
                area.select();
                document.execCommand('copy');
                area.remove();
            }
            this.copied = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.copied = false, 2000);
            this.$root.dispatchEvent(new CustomEvent('copy', { bubbles: true, detail: { text } }));
        },
    }"
    {{ $attributes->merge(['class' => cn('group/code relative w-full overflow-hidden rounded-lg border bg-muted/50 text-sm dark:bg-muted/30', $class)]) }}>
    @if ($filename)
        <figcaption data-slot="code-block-header" class="flex h-10 items-center gap-2 border-b bg-muted/60 pl-4 {{ $copyable ? 'pr-11' : 'pr-3' }} text-xs text-muted-foreground dark:bg-muted/40">
            <span class="truncate font-mono">{{ $filename }}</span>
            @if ($language)
                <span class="ml-auto rounded bg-background/60 px-1.5 py-0.5 font-mono uppercase tracking-wide text-[0.65rem]">{{ $language }}</span>
            @endif
        </figcaption>
    @endif
    <div class="flex">
        @if ($lineNumbers)
            <div aria-hidden="true" data-slot="code-block-line-numbers" class="shrink-0 select-none border-r py-4 pl-4 pr-3 text-right font-mono leading-6 text-muted-foreground/70 tabular-nums">
                @for ($i = 1; $i <= $lines; $i++)<div>{{ $i }}</div>@endfor
            </div>
        @endif
        <pre class="min-w-0 flex-1 overflow-x-auto py-4 {{ $copyable && ! $filename ? 'pr-12' : 'pr-4' }} pl-4 font-mono leading-6"><code x-ref="code" class="{{ $language ? 'language-'.$language : '' }} !bg-transparent !p-0 font-mono">{!! $body !!}</code></pre>
    </div>
    @if ($copyable)
        <button type="button" data-slot="code-block-copy" hidden x-bind:hidden="false" @click="copy()"
            :aria-label="copied ? 'Copied' : 'Copy code'" aria-label="Copy code"
            class="absolute right-1.5 {{ $filename ? 'top-1.5' : 'top-2.5' }} inline-flex size-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-background hover:text-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50">
            <x-icons.copy class="size-3.5" aria-hidden="true" x-show="! copied" />
            <x-icons.check class="size-3.5" aria-hidden="true" x-show="copied" style="display: none;" />
        </button>
        <span class="sr-only" aria-live="polite" x-text="copied ? 'Copied to clipboard' : ''"></span>
    @endif
</figure>
