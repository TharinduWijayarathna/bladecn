<div class="mx-auto flex h-[460px] max-w-xl flex-col rounded-xl border">
    <x-ai.conversation>
        <x-ai.conversation-content>
            <x-ai.message from="user">
                <x-ai.message-content>How do I add a dark mode toggle?</x-ai.message-content>
            </x-ai.message>
            <x-ai.message from="assistant">
                <x-ai.message-content>
                    <x-ai.response>
                        <p>Drop <code>&lt;x-ui.appearance-tabs /&gt;</code> into your settings page. It toggles the <code>.dark</code> class and remembers the choice.</p>
                    </x-ai.response>
                </x-ai.message-content>
            </x-ai.message>
        </x-ai.conversation-content>
    </x-ai.conversation>

    <div class="p-4 pt-0">
        <x-ai.prompt-input action="#" onsubmit="event.preventDefault()">
            <x-ai.prompt-textarea placeholder="Ask anything..." />
            <x-ai.prompt-toolbar>
                <x-ai.prompt-tools>
                    <x-ui.button type="button" variant="ghost" size="icon-sm" aria-label="Magic">
                        <x-icons.magic-wand />
                    </x-ui.button>
                    <x-ui.button type="button" variant="ghost" size="icon-sm" aria-label="Search the web">
                        <x-icons.earth />
                    </x-ui.button>
                </x-ai.prompt-tools>
                <x-ui.button type="submit" size="icon-sm" aria-label="Send">
                    <x-icons.arrow-right />
                </x-ui.button>
            </x-ai.prompt-toolbar>
        </x-ai.prompt-input>
    </div>
</div>
