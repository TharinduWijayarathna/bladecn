// The package's own runtime: Alpine + @alpinejs/focus (resources/js/app.js).
import '../../../resources/js/app.js';

import hljs from 'highlight.js/lib/core';
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import php from 'highlight.js/lib/languages/php';
import javascript from 'highlight.js/lib/languages/javascript';
import xml from 'highlight.js/lib/languages/xml';

hljs.registerLanguage('xml', xml);
hljs.registerLanguage('css', css);
hljs.registerLanguage('bash', bash);
hljs.registerLanguage('php', php);
hljs.registerLanguage('javascript', javascript);

function highlight() {
    document.querySelectorAll('pre code').forEach((block) => {
        if (!block.dataset.highlighted) {
            hljs.highlightElement(block);
        }
    });
}

// Header dark-mode toggle. Uses the package's convention: `appearance` in
// localStorage (light | dark | system) + a `.dark` class on <html>, the same
// keys <x-ui.appearance-tabs> and the app layout read.
function setupThemeToggle() {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const dark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', dark);
            try {
                localStorage.setItem('appearance', dark ? 'dark' : 'light');
            } catch (e) {}
            document.cookie = `appearance=${dark ? 'dark' : 'light'};path=/;max-age=31536000;SameSite=Lax`;
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        highlight();
        setupThemeToggle();
    });
} else {
    highlight();
    setupThemeToggle();
}
