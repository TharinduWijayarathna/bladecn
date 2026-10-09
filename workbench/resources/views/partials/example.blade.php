{{-- One example = one Blade file. The Preview tab renders it; the Code tab prints the same file verbatim. --}}
<div x-data="{ tab: 'preview' }" class="docs-example">
    <div class="flex items-center gap-4 border-b text-sm" role="tablist">
        <button type="button" role="tab" @click="tab = 'preview'" :aria-selected="tab === 'preview'"
            :class="tab === 'preview' ? 'border-foreground text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground'"
            class="-mb-px border-b-2 border-foreground px-1 pb-2 font-medium transition-colors">Preview</button>
        <button type="button" role="tab" @click="tab = 'code'" :aria-selected="tab === 'code'"
            :class="tab === 'code' ? 'border-foreground text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground'"
            class="-mb-px border-b-2 border-transparent px-1 pb-2 font-medium text-muted-foreground transition-colors">Code</button>
    </div>

    <div x-show="tab === 'preview'" role="tabpanel" data-example-preview="{{ $example['key'] }}"
        @class([
            'mt-4 rounded-lg border p-6 md:p-10',
            'flex min-h-[180px] flex-wrap items-center justify-center gap-4' => $example['preview'] === 'center',
            'min-h-[180px]' => $example['preview'] === 'block',
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

    <div x-show="tab === 'code'" x-cloak role="tabpanel" class="mt-4">
        @include('docs::partials.code', ['code' => $example['source']])
    </div>
</div>
