@php
    $language ??= 'xml';
    $label = $title ?? (['xml' => 'Blade', 'bash' => 'Terminal', 'javascript' => 'JavaScript', 'css' => 'CSS', 'php' => 'PHP'][$language] ?? strtoupper($language));
@endphp
<div class="docs-code-block group/code my-4 overflow-hidden rounded-xl border bg-muted/30 dark:bg-white/[0.02]" x-data="{ copied: false }">
    <div class="flex h-10 items-center justify-between border-b bg-muted/40 pr-1.5 pl-4 dark:bg-white/[0.03]">
        <span class="flex items-center gap-2 text-xs font-medium text-muted-foreground">
            @if ($language === 'bash')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5" aria-hidden="true"><path d="m4 17 6-6-6-6M12 19h8" /></svg>
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5" aria-hidden="true"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6" /></svg>
            @endif
            {{ $label }}
        </span>
        <button type="button"
            class="inline-flex h-7 items-center gap-1.5 rounded-md px-2 text-xs font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
            @click="navigator.clipboard.writeText($refs.code.textContent).then(() => { copied = true; setTimeout(() => copied = false, 1500) })"
            :aria-label="copied ? 'Copied' : 'Copy code'">
            <x-icons.copy class="size-3.5" x-show="!copied" />
            <x-icons.check class="size-3.5 text-emerald-500" x-show="copied" x-cloak />
            <span x-text="copied ? 'Copied' : 'Copy'">Copy</span>
        </button>
    </div>
    <pre class="docs-code max-h-[480px] overflow-auto p-4 text-[13px] leading-relaxed"><code x-ref="code" class="language-{{ $language }}">{{ $code }}</code></pre>
</div>
