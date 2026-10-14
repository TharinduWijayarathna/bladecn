<form x-data="{ board: {} }" class="flex w-full flex-col gap-3" x-on:submit.prevent>
    <x-ui.kanban name="board" x-model="board" label="Sprint board">
        <x-ui.kanban-column value="todo" title="To do" class="min-w-44! basis-44!">
            <x-ui.kanban-card value="12">Design the empty state</x-ui.kanban-card>
            <x-ui.kanban-card value="15">
                <p class="font-medium">Write release notes</p>
                <p class="mt-1 text-xs text-muted-foreground">Due Friday</p>
            </x-ui.kanban-card>
        </x-ui.kanban-column>
        <x-ui.kanban-column value="doing" title="In progress" class="min-w-44! basis-44!">
            <x-ui.kanban-card value="9">Date range picker</x-ui.kanban-card>
        </x-ui.kanban-column>
        <x-ui.kanban-column value="done" title="Done" class="min-w-44! basis-44!">
            <x-ui.kanban-card value="3">Set up CI</x-ui.kanban-card>
            <x-ui.kanban-card value="4">Dark mode</x-ui.kanban-card>
        </x-ui.kanban-column>
    </x-ui.kanban>
    <pre class="overflow-x-auto rounded-md bg-muted p-3 text-xs" x-text="JSON.stringify(board)"></pre>
</form>
