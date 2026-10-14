@props([
    'name' => 'keywords',
    'class' => '',
    'placeholder' => 'Add keywords...',
    'tags' => [],
])

@php
    // `wire:model` / `x-model` switch to an Alpine-driven variant that binds the tag list (see BladeCN\BladeCN\Support\Livewire).
    $live = \BladeCN\BladeCN\Support\FormField::isModelBound($attributes);
@endphp
@if ($live)
<div data-slot="input-tags" x-modelable="items"
    x-data="{
        items: @js(array_values(array_map('strval', (array) $tags))),
        draft: '',
        add(raw) {
            String(raw).split(/[,\n]/).map(tag => tag.trim()).filter(tag => tag && ! this.items.includes(tag)).forEach(tag => this.items = [...this.items, tag]);
            this.draft = '';
        },
        remove(tag) { this.items = this.items.filter(item => item !== tag) },
    }"
    {{ \BladeCN\BladeCN\Support\Livewire::attributes($attributes, 'items')->merge(['class' => cn('flex w-full flex-col gap-2', $class)]) }}>
    <x-ui.label>{{ $slot ?? 'Keywords' }}</x-ui.label>
    <div class="flex min-h-10 w-full flex-wrap items-center gap-1.5 rounded-md border border-input bg-background px-3 py-2 text-sm focus-within:ring-1 focus-within:ring-ring">
        <template x-for="tag in items" :key="tag">
            <span class="inline-flex max-w-[calc(100%-8px)] items-center gap-1.5 rounded border bg-transparent px-2.5 py-1 text-sm pr-1.5" :data-tag="tag">
                <span class="truncate" x-text="tag"></span>
                <button type="button" class="h-4 w-4 shrink-0 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100" :aria-label="'Remove ' + tag" @click="remove(tag)">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </span>
        </template>
        <input type="text" placeholder="{{ $placeholder }}" x-model="draft"
            @keydown.enter.prevent="draft.trim() && add(draft)" @keydown.comma.prevent="draft.trim() && add(draft)"
            @keydown.backspace="draft === '' && items.length && remove(items[items.length - 1])"
            @paste.prevent="add($event.clipboardData.getData('text'))"
            class="flex-1 bg-transparent outline-hidden placeholder:text-muted-foreground">
    </div>
    <template x-for="tag in items" :key="'input-' + tag">
        <input type="hidden" name="{{ $name }}[]" :value="tag">
    </template>
</div>
@else
<div class="flex w-full flex-col gap-2 {{ $class }}">
    <x-ui.label>{{ $slot ?? 'Keywords' }}</x-ui.label>
    <div id="tagsInputContainer-{{ $name }}" class="flex min-h-10 w-full flex-wrap items-center gap-1.5 rounded-md border border-input bg-background px-3 py-2 text-sm focus-within:ring-1 focus-within:ring-ring">
        @foreach($tags as $tag)
            <span class="inline-flex max-w-[calc(100%-8px)] items-center gap-1.5 rounded border bg-transparent px-2.5 py-1 text-sm pr-1.5" data-tag="{{ $tag }}">
                <span class="truncate">{{ $tag }}</span>
                <button type="button" class="h-4 w-4 shrink-0 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100" data-remove-tag="{{ $tag }}">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </span>
        @endforeach
        <input
            type="text"
            id="tagsInput-{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            class="flex-1 bg-transparent outline-hidden placeholder:text-muted-foreground"
        >
    </div>
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('tagsInputContainer-{{ $name }}');
        const input = document.getElementById('tagsInput-{{ $name }}');
        
        if (!container || !input) return;
        
        // Add keyword on Enter or comma
        input.addEventListener('keydown', function(e) {
            if ((e.key === 'Enter' || e.key === ',') && this.value.trim()) {
                e.preventDefault();
                addKeyword(this.value.trim().replace(/,$/, ''));
                this.value = '';
            }
        });
        
        // Paste handling
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = e.clipboardData.getData('text');
            const keywords = text.split(/[,\n]/).map(k => k.trim()).filter(k => k);
            keywords.forEach(keyword => addKeyword(keyword));
        });
        
        // Remove keyword
        container.addEventListener('click', function(e) {
            const button = e.target.closest('button[data-remove-tag]');
            if (button) {
                button.closest('span[data-tag]').remove();
            }
        });
        
        function addKeyword(keyword) {
            // Check if keyword already exists
            const existing = Array.from(container.querySelectorAll('span[data-tag]'))
                .some(el => el.getAttribute('data-tag') === keyword);
            if (existing) return;
            
            const badge = document.createElement('span');
            badge.className = 'inline-flex max-w-[calc(100%-8px)] items-center gap-1.5 rounded border bg-transparent px-2.5 py-1 text-sm pr-1.5';
            badge.setAttribute('data-tag', keyword);
            badge.innerHTML = `
                <span class="truncate">${keyword}</span>
                <button type="button" class="h-4 w-4 shrink-0 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100" data-remove-tag="${keyword}">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            container.insertBefore(badge, input);
        }
    });
</script>
@endpush

