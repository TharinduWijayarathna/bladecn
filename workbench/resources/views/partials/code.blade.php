<div class="relative" x-data="{ copied: false }">
    <button type="button"
        class="absolute top-2.5 right-2.5 z-10 inline-flex h-7 items-center gap-1 rounded-md border bg-background px-2 text-xs font-medium text-muted-foreground hover:bg-accent hover:text-foreground"
        @click="navigator.clipboard.writeText($refs.code.textContent).then(() => { copied = true; setTimeout(() => copied = false, 1500) })"
        aria-label="Copy code">
        <span x-show="!copied">Copy</span>
        <span x-show="copied" x-cloak>Copied</span>
    </button>
    <pre class="docs-code max-h-[480px] overflow-auto rounded-lg border bg-muted/40 p-4 pr-16 text-[13px] leading-relaxed"><code x-ref="code" class="language-{{ $language ?? 'xml' }}">{{ $code }}</code></pre>
</div>
