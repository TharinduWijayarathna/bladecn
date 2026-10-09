{{-- One example = one Blade file. The Preview tab renders it; the Code tab prints the same file verbatim. --}}
<div x-data="{ tab: 'preview' }" class="docs-example overflow-hidden rounded-xl border">
    <div class="flex items-center justify-between border-b bg-muted/30 px-2 py-1.5 dark:bg-white/[0.02]">
        <div class="inline-flex items-center gap-0.5 rounded-lg bg-muted p-0.5 text-xs" role="tablist">
            <button type="button" role="tab" @click="tab = 'preview'" :aria-selected="tab === 'preview'"
                :class="tab === 'preview' ? 'bg-background text-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground'"
                class="rounded-md bg-background px-2.5 py-1 font-medium text-foreground shadow-xs transition-colors">Preview</button>
            <button type="button" role="tab" @click="tab = 'code'" :aria-selected="tab === 'code'"
                :class="tab === 'code' ? 'bg-background text-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground'"
                class="rounded-md px-2.5 py-1 font-medium text-muted-foreground transition-colors">Code</button>
        </div>
    </div>

    <div x-show="tab === 'preview'" role="tabpanel" data-example-preview="{{ $example['key'] }}"
        @class([
            'docs-preview p-6 md:p-10',
            'flex min-h-[200px] flex-wrap items-center justify-center gap-4' => $example['preview'] === 'center',
            'min-h-[200px]' => $example['preview'] === 'block',
        ])>
        @if ($example['preview'] === 'block')
            @include($example['view'])
        @else
            <div class="w-full">
                <div class="flex flex-wrap items-center justify-center gap-4">
                    @include($example['view'])
                </div>
            </div>
        @endif
    </div>

    <div x-show="tab === 'code'" x-cloak role="tabpanel" class="[&_.docs-code-block]:my-0 [&_.docs-code-block]:rounded-none [&_.docs-code-block]:border-0">
        @include('docs::partials.code', ['code' => $example['source']])
    </div>
</div>
