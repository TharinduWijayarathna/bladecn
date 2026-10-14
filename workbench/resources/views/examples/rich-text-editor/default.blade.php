@php
$notes = <<<'HTML'
<h2>Release notes</h2>
<p>This release adds a <strong>rich text editor</strong>, <em>timelines</em> and <u>kanban boards</u>.</p>
<ul><li>Toolbar with keyboard support</li><li>Syncs HTML to the form</li></ul>
HTML;
@endphp

<form class="w-full max-w-xl" x-data="{ body: '' }" x-on:submit.prevent>
    <x-ui.rich-text-editor name="body" :value="$notes" x-model="body" placeholder="Write something…" />
    <p class="mt-2 text-xs text-muted-foreground">Submitted as <code>body</code>: <span class="line-clamp-2 font-mono" x-text="body"></span></p>
</form>
