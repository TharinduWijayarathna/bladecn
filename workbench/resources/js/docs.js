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

const slugify = (text) =>
    text
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

// Content headings (not the ones inside live previews or cards).
function contentHeadings() {
    const article = document.querySelector('[data-docs-article]');
    if (!article) return [];
    return [...article.querySelectorAll('h2, h3')].filter(
        (h) => !h.closest('[data-example-preview], [data-slot], [data-docs-hero], [data-docs-showcase], .docs-example, footer, nav'),
    );
}

// Give headings ids + hover anchors, and build the TOC on guide pages.
function setupHeadings() {
    const headings = contentHeadings();
    const used = new Set();

    headings.forEach((heading) => {
        const section = heading.closest('section[id]');
        if (!heading.id && !section) {
            let id = slugify(heading.textContent) || 'section';
            while (used.has(id) || document.getElementById(id)) id += '-1';
            heading.id = id;
        }
        used.add(heading.id);
        const target = heading.id || section.id;
        if (!heading.querySelector('.docs-anchor')) {
            const a = document.createElement('a');
            a.href = `#${target}`;
            a.className = 'docs-anchor';
            a.setAttribute('aria-label', `Link to ${heading.textContent.trim()}`);
            a.textContent = '#';
            heading.appendChild(a);
        }
    });

    const toc = document.querySelector('[data-toc-auto] [data-toc-list]');
    if (toc && !toc.children.length) {
        const hasH2 = headings.some((h) => h.tagName === 'H2');
        headings.forEach((heading) => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.className = 'docs-toc-link';
            a.href = `#${heading.id}`;
            a.dataset.depth = heading.tagName === 'H3' && hasH2 ? '3' : '2';
            a.textContent = heading.firstChild?.textContent?.trim() || heading.textContent.replace(/#$/, '').trim();
            li.appendChild(a);
            toc.appendChild(li);
        });
    }

    const container = document.querySelector('[data-toc]');
    if (container && !container.querySelector('[data-toc-list] a')) {
        container.closest('aside')?.classList.add('xl:hidden');
    }
}

// Highlight the TOC entry for the section currently in view.
function setupScrollSpy() {
    const links = [...document.querySelectorAll('[data-toc-list] a[href^="#"]')];
    if (!links.length) return;

    const targets = links
        .map((link) => document.getElementById(decodeURIComponent(link.getAttribute('href').slice(1))))
        .filter(Boolean);

    const update = () => {
        const offset = 110;
        let current = targets[0];
        for (const target of targets) {
            if (target.getBoundingClientRect().top - offset <= 0) current = target;
        }
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4) {
            current = targets[targets.length - 1];
        }
        links.forEach((link) => link.toggleAttribute('data-active', link.getAttribute('href') === `#${current?.id}`));
    };

    let ticking = false;
    window.addEventListener(
        'scroll',
        () => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                update();
                ticking = false;
            });
        },
        { passive: true },
    );
    update();
}

// Keep the active sidebar link visible in a long navigation list.
function revealActiveNav() {
    const active = document.querySelector('[data-docs-sidebar] [data-active]');
    const sidebar = document.querySelector('[data-docs-sidebar]');
    if (!active || !sidebar) return;
    const top = active.offsetTop - sidebar.clientHeight / 3;
    if (top > 0) sidebar.scrollTop = top;
}

// A preview only scrolls sideways when its component is genuinely wider than the
// canvas (e.g. a calendar on a phone). Otherwise it stays overflow-visible so
// dropdowns, popovers and tooltips are never clipped by the preview frame.
function setupWidePreviews() {
    const previews = [...document.querySelectorAll('[data-example-preview]')];
    if (!previews.length) return;

    const measure = () => {
        previews.forEach((preview) => {
            if (preview.offsetParent === null) return;
            preview.classList.remove('docs-preview-scroll');
            const content = preview.firstElementChild;
            const wide = preview.scrollWidth > preview.clientWidth + 1 || (content && content.scrollWidth > content.clientWidth + 1);
            preview.classList.toggle('docs-preview-scroll', wide);
        });
    };

    let frame = 0;
    const schedule = () => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(measure);
    };

    if ('ResizeObserver' in window) {
        const observer = new ResizeObserver(schedule);
        previews.forEach((preview) => observer.observe(preview));
    }
    window.addEventListener('resize', schedule, { passive: true });
    window.addEventListener('load', schedule);
    document.addEventListener('click', (event) => {
        if (event.target.closest?.('.docs-example [role="tab"]')) setTimeout(schedule, 0);
    });
    schedule();
}

function boot() {
    highlight();
    setupThemeToggle();
    setupHeadings();
    setupScrollSpy();
    revealActiveNav();
    setupWidePreviews();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
