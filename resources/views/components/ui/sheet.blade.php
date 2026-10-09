<div {{ $attributes->merge(['class' => 'sheet-component']) }} data-sheet data-translate-in="{{ $translateIn() }}"
    data-translate-out="{{ $translateOut() }}">
    <!-- Trigger -->
    <div class="sheet-trigger">
        {{ $trigger ?? '' }}
    </div>

    <!-- Overlay -->
    <div class="sheet-overlay fixed inset-0 z-50 hidden bg-black/50 opacity-0 transition-opacity duration-300"></div>

    <!-- Content -->
    <div class="sheet-content hidden {{ $contentClasses() }}" role="dialog" aria-modal="true" tabindex="-1">
        {{ $slot }}
        <button type="button" data-action="close-sheet"
            class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <x-icons.x class="h-4 w-4" />
            <span class="sr-only">Close</span>
        </button>
    </div>
</div>

@pushOnce('scripts')
    <script>
        (function() {
            function init(sheet) {
                if (sheet.dataset.sheetReady) return;
                sheet.dataset.sheetReady = '1';

                const trigger = sheet.querySelector(':scope > .sheet-trigger > *');
                const overlay = sheet.querySelector(':scope > .sheet-overlay');
                const content = sheet.querySelector(':scope > .sheet-content');
                const translateIn = sheet.dataset.translateIn;
                const translateOut = sheet.dataset.translateOut;
                let lastFocus = null;

                const title = content.querySelector('[data-slot="sheet-title"]');
                const description = content.querySelector('[data-slot="sheet-description"]');
                const uid = 'sheet-' + Math.random().toString(36).slice(2, 9);
                if (title) { title.id ||= uid + '-title'; content.setAttribute('aria-labelledby', title.id); }
                if (description) { description.id ||= uid + '-desc'; content.setAttribute('aria-describedby', description.id); }

                const isOpen = () => !overlay.classList.contains('hidden');

                function open() {
                    lastFocus = document.activeElement;
                    overlay.classList.remove('hidden');
                    content.classList.remove('hidden');
                    trigger?.setAttribute('aria-expanded', 'true');

                    requestAnimationFrame(() => requestAnimationFrame(() => {
                        overlay.classList.replace('opacity-0', 'opacity-100');
                        content.classList.replace(translateOut, translateIn);
                        (content.querySelector('[autofocus], input, select, textarea, button:not([data-action="close-sheet"])') || content).focus();
                    }));
                }

                function close() {
                    overlay.classList.replace('opacity-100', 'opacity-0');
                    content.classList.replace(translateIn, translateOut);
                    trigger?.setAttribute('aria-expanded', 'false');

                    setTimeout(() => {
                        overlay.classList.add('hidden');
                        content.classList.add('hidden');
                        lastFocus?.focus?.();
                    }, 300);
                }

                trigger?.setAttribute('aria-haspopup', 'dialog');
                trigger?.setAttribute('aria-expanded', 'false');
                trigger?.addEventListener('click', open);
                overlay.addEventListener('click', close);
                content.querySelectorAll('[data-action="close-sheet"]').forEach(button => button.addEventListener('click', close));

                document.addEventListener('keydown', (event) => {
                    if (!isOpen()) return;

                    if (event.key === 'Escape') {
                        close();
                    } else if (event.key === 'Tab') {
                        // Keep focus inside the open sheet.
                        const focusable = [...content.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])')]
                            .filter(el => el.offsetParent !== null);
                        if (!focusable.length) return;
                        const first = focusable[0], last = focusable[focusable.length - 1];
                        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
                    }
                });
            }

            function boot() { document.querySelectorAll('[data-sheet]').forEach(init); }
            document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();
        })();
    </script>
@endPushOnce
