@props([
    'name' => 'files',
    'id' => null,
    'multiple' => true,
    'accept' => null,
    'maxFiles' => null,
    'maxSize' => null,
    'disabled' => false,
    'required' => false,
    'label' => null,
    'description' => null,
    'progress' => false,
    'class' => '',
])

@php
    $id ??= 'dropzone-'.\Illuminate\Support\Str::random(6);
    $inputName = $multiple && ! str_ends_with($name, '[]') ? $name.'[]' : $name;
    $maxFiles = $multiple ? ($maxFiles !== null ? (int) $maxFiles : null) : 1;

    if ($description === null) {
        $parts = array_filter([
            $accept ? 'Accepted: '.str_replace(',', ', ', $accept) : null,
            $maxSize ? 'Max '.$maxSize.' MB per file' : null,
            $maxFiles && $multiple ? 'Up to '.$maxFiles.' files' : null,
        ]);
        $description = implode(' · ', $parts);
    }
@endphp

<div data-slot="file-dropzone" data-disabled="{{ $disabled ? 'true' : 'false' }}"
    x-data="{
        files: [],
        errors: [],
        dragging: false,
        uploading: false,
        percent: 0,
        multiple: @js((bool) $multiple),
        accept: @js($accept),
        maxFiles: @js($maxFiles),
        maxSize: @js($maxSize !== null ? (float) $maxSize : null),
        disabled: @js((bool) $disabled),
        progress: @js((bool) $progress),
        init() {
            const form = this.$refs.input.form;
            if (this.progress && form) {
                form.addEventListener('submit', (event) => this.upload(event, form));
            }
            if (form) {
                form.addEventListener('reset', () => { this.files.forEach(f => f.url && URL.revokeObjectURL(f.url)); this.files = []; this.errors = []; });
            }
        },
        matches(file) {
            if (! this.accept) return true;
            const name = file.name.toLowerCase();
            const type = (file.type || '').toLowerCase();
            return this.accept.split(',').map(a => a.trim().toLowerCase()).filter(Boolean).some(rule => {
                if (rule.startsWith('.')) return name.endsWith(rule);
                if (rule.endsWith('/*')) return type.startsWith(rule.slice(0, -1));
                return type === rule;
            });
        },
        add(list) {
            if (this.disabled) return;
            this.errors = [];
            for (const file of Array.from(list)) {
                if (! this.matches(file)) { this.errors.push(`${file.name}: file type not accepted.`); continue; }
                if (this.maxSize && file.size > this.maxSize * 1024 * 1024) { this.errors.push(`${file.name}: larger than ${this.maxSize} MB.`); continue; }
                if (this.files.some(f => f.file.name === file.name && f.file.size === file.size && f.file.lastModified === file.lastModified)) continue;
                if (! this.multiple) { this.files.forEach(f => f.url && URL.revokeObjectURL(f.url)); this.files = []; }
                if (this.maxFiles && this.files.length >= this.maxFiles) { this.errors.push(`${file.name}: no more than ${this.maxFiles} file${this.maxFiles === 1 ? '' : 's'} allowed.`); continue; }
                this.files.push({ file, url: file.type.startsWith('image/') ? URL.createObjectURL(file) : null });
            }
            this.sync();
        },
        remove(index) {
            const [removed] = this.files.splice(index, 1);
            if (removed?.url) URL.revokeObjectURL(removed.url);
            this.sync();
            this.$nextTick(() => this.$refs.zone.focus());
        },
        sync() {
            // The real <input type=file> carries the files, so a normal multipart form submit just works.
            const transfer = new DataTransfer();
            this.files.forEach(f => transfer.items.add(f.file));
            this.$refs.input.files = transfer.files;
            this.$dispatch('dropzone-change', { files: this.files.map(f => f.file) });
        },
        open() { if (! this.disabled) this.$refs.input.click(); },
        size(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1024 / 1024).toFixed(1) + ' MB';
        },
        upload(event, form) {
            if (event.defaultPrevented || ! window.FormData || ! window.XMLHttpRequest) return;
            event.preventDefault();
            const xhr = new XMLHttpRequest();
            this.uploading = true;
            this.percent = 0;
            xhr.upload.addEventListener('progress', (e) => { if (e.lengthComputable) this.percent = Math.round(e.loaded / e.total * 100); });
            xhr.addEventListener('load', () => {
                this.percent = 100;
                this.$dispatch('dropzone-uploaded', { status: xhr.status, response: xhr.responseText });
                const type = xhr.getResponseHeader('Content-Type') || '';
                if (type.includes('text/html')) {
                    // Show the page the server answered with (e.g. the redirect target with its flash message).
                    if (xhr.responseURL) history.replaceState(null, '', xhr.responseURL);
                    document.open(); document.write(xhr.responseText); document.close();
                } else {
                    this.uploading = false;
                }
            });
            xhr.addEventListener('error', () => { this.uploading = false; this.errors = ['Upload failed. Please try again.']; });
            xhr.open((form.getAttribute('method') || 'POST').toUpperCase(), form.action);
            xhr.setRequestHeader('Accept', 'text/html, application/json');
            xhr.send(new FormData(form));
        },
    }"
    {{ $attributes->merge(['class' => cn('grid w-full gap-3', $class)]) }}>

    @if ($label)
        <label for="{{ $id }}" class="text-sm font-medium leading-none">{{ $label }}</label>
    @endif

    <div x-ref="zone" role="button" tabindex="{{ $disabled ? '-1' : '0' }}" data-slot="file-dropzone-area"
        aria-label="{{ $label ? $label.': ' : '' }}Upload files. Drag and drop or press Enter to browse."
        aria-describedby="{{ $id }}-description" aria-disabled="{{ $disabled ? 'true' : 'false' }}"
        x-on:click="open()" x-on:keydown.enter.prevent="open()" x-on:keydown.space.prevent="open()"
        x-on:dragenter.prevent="if (! disabled) dragging = true" x-on:dragover.prevent="if (! disabled) dragging = true"
        x-on:dragleave.prevent="if (! $el.contains($event.relatedTarget)) dragging = false"
        x-on:drop.prevent="dragging = false; add($event.dataTransfer.files)"
        x-bind:data-dragging="dragging.toString()" data-dragging="false"
        class="flex min-h-36 cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-input p-6 text-center transition-colors outline-none hover:bg-accent/50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 data-[dragging=true]:border-primary data-[dragging=true]:bg-accent dark:bg-input/10 aria-disabled:pointer-events-none aria-disabled:opacity-50">
        <div class="flex size-11 shrink-0 items-center justify-center rounded-full border bg-background" aria-hidden="true">
            <x-icons.cloud-upload class="size-5 opacity-60" />
        </div>
        <p class="text-sm"><span class="font-medium">Click to upload</span> or drag and drop</p>
        <p id="{{ $id }}-description" class="text-xs text-muted-foreground">{{ $description }}</p>
        <input x-ref="input" type="file" id="{{ $id }}" name="{{ $inputName }}" class="sr-only" tabindex="-1"
            aria-hidden="true" @if ($multiple) multiple @endif @if ($accept) accept="{{ $accept }}" @endif
            @disabled($disabled) @required($required)
            x-on:click.stop x-on:change="const picked = [...$event.target.files]; add(picked)">
    </div>

    <ul x-show="errors.length" x-cloak style="display: none;" role="alert" data-slot="file-dropzone-errors"
        class="grid gap-1 text-sm text-destructive">
        <template x-for="(error, index) in errors" :key="index">
            <li x-text="error"></li>
        </template>
    </ul>

    <ul x-show="files.length" x-cloak style="display: none;" data-slot="file-dropzone-list" aria-label="Selected files"
        class="grid gap-2">
        <template x-for="(item, index) in files" :key="item.file.name + item.file.size + item.file.lastModified">
            <li class="flex items-center gap-3 rounded-lg border bg-background p-2 pr-3">
                <div class="flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-md border bg-muted" aria-hidden="true">
                    <template x-if="item.url">
                        <img :src="item.url" alt="" class="size-full object-cover">
                    </template>
                    <template x-if="! item.url">
                        <x-icons.file-check class="size-4 opacity-60" />
                    </template>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium" x-text="item.file.name"></p>
                    <p class="text-xs text-muted-foreground" x-text="size(item.file.size)"></p>
                </div>
                <button type="button" x-on:click="remove(index)" x-bind:aria-label="`Remove ${item.file.name}`" x-bind:disabled="uploading"
                    class="inline-flex size-8 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:opacity-50">
                    <x-icons.x class="size-4" aria-hidden="true" />
                </button>
            </li>
        </template>
    </ul>

    @if ($progress)
        <div x-show="uploading" x-cloak style="display: none;" data-slot="file-dropzone-progress" class="grid gap-1.5">
            <div class="flex justify-between text-xs text-muted-foreground">
                <span>Uploading…</span>
                <span x-text="percent + '%'"></span>
            </div>
            <div role="progressbar" aria-label="Upload progress" aria-valuemin="0" aria-valuemax="100"
                x-bind:aria-valuenow="percent" class="h-2 w-full overflow-hidden rounded-full bg-primary/20">
                <div class="h-full bg-primary transition-[width] duration-200" x-bind:style="`width: ${percent}%`"></div>
            </div>
        </div>
    @endif
</div>
