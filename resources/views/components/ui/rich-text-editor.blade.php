@props([
    'name' => null,
    'value' => '',
    'placeholder' => null,
    'label' => 'Rich text editor',
    'toolbar' => ['bold', 'italic', 'underline', 'strike', '|', 'heading', 'bulletList', 'orderedList', 'blockquote', 'code', '|', 'undo', 'redo'],
    'cdn' => 'https://esm.sh',
    'version' => '3.31.4',
    'rows' => 6,
    'class' => '',
])

@php
    $svg = fn (string $paths) => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">'.$paths.'</svg>';
    $buttons = [
        'bold' => ['Bold', null],
        'italic' => ['Italic', null],
        'underline' => ['Underline', null],
        'strike' => ['Strikethrough', $svg('<path d="M16 4H9a3 3 0 0 0-2.83 4"/><path d="M14 12a4 4 0 0 1 0 8H6"/><line x1="4" x2="20" y1="12" y2="12"/>')],
        'heading' => ['Heading', $svg('<path d="M4 12h8"/><path d="M4 18V6"/><path d="M12 18V6"/><path d="M21 18h-4c0-4 4-3 4-6 0-1.5-2-2.5-4-1"/>')],
        'bulletList' => ['Bulleted list', $svg('<path d="M3 12h.01"/><path d="M3 18h.01"/><path d="M3 6h.01"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M8 6h13"/>')],
        'orderedList' => ['Numbered list', $svg('<path d="M10 12h11"/><path d="M10 18h11"/><path d="M10 6h11"/><path d="M4 10h2"/><path d="M4 6h1v4"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>')],
        'blockquote' => ['Quote', $svg('<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>')],
        'code' => ['Inline code', $svg('<path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/>')],
        'codeBlock' => ['Code block', $svg('<path d="M10 9.5 8 12l2 2.5"/><path d="m14 9.5 2 2.5-2 2.5"/><rect width="18" height="18" x="3" y="3" rx="2"/>')],
        'undo' => ['Undo', $svg('<path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/>')],
        'redo' => ['Redo', $svg('<path d="m15 14 5-5-5-5"/><path d="M20 9H9.5A5.5 5.5 0 0 0 4 14.5A5.5 5.5 0 0 0 9.5 20H13"/>')],
    ];
    $toolbar = array_values(array_filter((array) $toolbar, fn ($item) => $item === '|' || isset($buttons[$item])));
@endphp

{{--
    An optional Tiptap editor. The <textarea> is the form field: it is visible until the editor loads, so the
    field still works without JavaScript (or if the CDN is unreachable), and is hidden and kept in sync with
    the editor's HTML afterwards. Tiptap is imported from `cdn` at `version`, or from `window.BladeCNTiptap`
    (`{ Editor, StarterKit, Placeholder? }`) when your bundle provides it. Dispatches `change` with `{ value }`.
--}}
<div data-slot="rich-text-editor" x-modelable="html"
    x-data="(() => {
        let editor = null;
        return {
            html: @js((string) $value),
            ready: false,
            failed: false,
            active: {},
            async load() {
                if (window.BladeCNTiptap) return window.BladeCNTiptap;
                const base = @js(rtrim($cdn, '/'));
                const version = @js($version);
                const [core, kit, extensions] = await Promise.all([
                    import(`${base}/@tiptap/core@${version}`),
                    import(`${base}/@tiptap/starter-kit@${version}`),
                    import(`${base}/@tiptap/extensions@${version}`).catch(() => ({})),
                ]);
                return { Editor: core.Editor, StarterKit: kit.StarterKit ?? kit.default, Placeholder: extensions.Placeholder };
            },
            async init() {
                try {
                    // Let x-model entangle first, so an outer value can provide the initial content.
                    await new Promise(resolve => this.$nextTick(resolve));
                    const { Editor, StarterKit, Placeholder } = await this.load();
                    const placeholder = @js($placeholder);
                    const extensions = [StarterKit];
                    if (placeholder && Placeholder) extensions.push(Placeholder.configure({ placeholder }));
                    editor = new Editor({
                        element: this.$refs.editor,
                        extensions,
                        content: this.html || this.$refs.input.value,
                        editorProps: { attributes: { role: 'textbox', 'aria-multiline': 'true', 'aria-label': @js($label), class: 'min-h-32 px-3 py-2 outline-none' } },
                        onUpdate: () => {
                            this.html = editor.isEmpty ? '' : editor.getHTML();
                            this.$refs.input.value = this.html;
                            this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: this.html } }));
                        },
                        onTransaction: () => this.refresh(),
                    });
                    this.$watch('html', value => { if (editor && value !== (editor.isEmpty ? '' : editor.getHTML())) editor.commands.setContent(value || '', { emitUpdate: false }) });
                    this.html = editor.isEmpty ? '' : editor.getHTML();
                    this.$refs.input.value = this.html;
                    this.refresh();
                    this.ready = true;
                } catch (error) {
                    this.failed = true;
                    console.warn('[bladecn] rich-text-editor: Tiptap could not be loaded, falling back to a textarea.', error);
                }
            },
            refresh() {
                if (! editor) return;
                this.active = {
                    bold: editor.isActive('bold'), italic: editor.isActive('italic'), underline: editor.isActive('underline'),
                    strike: editor.isActive('strike'), heading: editor.isActive('heading', { level: 2 }), bulletList: editor.isActive('bulletList'),
                    orderedList: editor.isActive('orderedList'), blockquote: editor.isActive('blockquote'), code: editor.isActive('code'),
                    codeBlock: editor.isActive('codeBlock'), undo: ! editor.can().undo(), redo: ! editor.can().redo(),
                };
            },
            run(command) {
                if (! editor) return;
                const chain = editor.chain().focus();
                ({
                    bold: () => chain.toggleBold(), italic: () => chain.toggleItalic(), underline: () => chain.toggleUnderline(),
                    strike: () => chain.toggleStrike(), heading: () => chain.toggleHeading({ level: 2 }), bulletList: () => chain.toggleBulletList(),
                    orderedList: () => chain.toggleOrderedList(), blockquote: () => chain.toggleBlockquote(), code: () => chain.toggleCode(),
                    codeBlock: () => chain.toggleCodeBlock(), undo: () => chain.undo(), redo: () => chain.redo(),
                })[command]().run();
            },
            toolbarKey(event) {
                const buttons = [...event.currentTarget.querySelectorAll('button:not(:disabled)')];
                const index = buttons.indexOf(document.activeElement);
                const next = { ArrowRight: index + 1, ArrowLeft: index - 1, Home: 0, End: buttons.length - 1 }[event.key];
                if (next === undefined) return;
                event.preventDefault();
                buttons[(next + buttons.length) % buttons.length]?.focus();
            },
            destroy() { editor?.destroy(); editor = null },
        };
    })()"
    {{ $attributes->merge(['class' => cn('w-full overflow-hidden rounded-md border border-input bg-background text-sm shadow-xs focus-within:border-ring focus-within:ring-[3px] focus-within:ring-ring/50', $class)]) }}>
    <div role="toolbar" aria-label="Formatting" data-slot="rich-text-editor-toolbar" x-show="ready" style="display: none;" @keydown="toolbarKey($event)"
        class="flex flex-wrap items-center gap-0.5 border-b bg-muted/40 p-1">
        @foreach ($toolbar as $item)
            @if ($item === '|')
                <span role="separator" aria-orientation="vertical" class="mx-1 h-5 w-px bg-border"></span>
            @else
                @php $toggle = ! in_array($item, ['undo', 'redo'], true); @endphp
                <button type="button" data-command="{{ $item }}" title="{{ $buttons[$item][0] }}" aria-label="{{ $buttons[$item][0] }}"
                    tabindex="{{ $loop->first ? 0 : -1 }}" @mousedown.prevent @click="run(@js($item))"
                    @if ($toggle) :aria-pressed="(!! active[@js($item)]).toString()" @else :disabled="!! active[@js($item)]" @endif
                    class="inline-flex size-8 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-40 aria-pressed:bg-accent aria-pressed:text-foreground">
                    @switch ($item)
                        @case ('bold') <x-icons.bold class="size-4" aria-hidden="true" /> @break
                        @case ('italic') <x-icons.italic class="size-4" aria-hidden="true" /> @break
                        @case ('underline') <x-icons.underline class="size-4" aria-hidden="true" /> @break
                        @default {!! $buttons[$item][1] !!}
                    @endswitch
                </button>
            @endif
        @endforeach
    </div>
    <div x-ref="editor" data-slot="rich-text-editor-content" x-show="ready" style="display: none;" @click="$event.target === $el && $el.querySelector('[contenteditable]')?.focus()"
        class="max-h-[32rem] overflow-y-auto leading-relaxed [&_.ProseMirror]:min-h-32 [&_.ProseMirror>*+*]:mt-3 [&_blockquote]:border-l-2 [&_blockquote]:pl-4 [&_blockquote]:text-muted-foreground [&_code]:rounded [&_code]:bg-muted [&_code]:px-1 [&_code]:py-0.5 [&_code]:font-mono [&_code]:text-[0.85em] [&_h2]:text-lg [&_h2]:font-semibold [&_hr]:border-border [&_ol]:list-decimal [&_ol]:pl-6 [&_pre]:overflow-x-auto [&_pre]:rounded-md [&_pre]:bg-muted [&_pre]:p-3 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:underline [&_a]:underline-offset-4 [&_.is-editor-empty:first-child]:before:pointer-events-none [&_.is-editor-empty:first-child]:before:float-left [&_.is-editor-empty:first-child]:before:h-0 [&_.is-editor-empty:first-child]:before:text-muted-foreground [&_.is-editor-empty:first-child]:before:content-[attr(data-placeholder)]"></div>
    <textarea x-ref="input" data-slot="rich-text-editor-input" @if ($name) name="{{ $name }}" @endif rows="{{ (int) $rows }}" aria-label="{{ $label }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif :hidden="ready" @input="ready || (html = $event.target.value)"
        class="block w-full resize-y border-0 bg-transparent px-3 py-2 outline-none placeholder:text-muted-foreground">{{ $value }}</textarea>
</div>
