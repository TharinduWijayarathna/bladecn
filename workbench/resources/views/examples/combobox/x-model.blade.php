<div x-data="{ status: 'todo' }" class="flex flex-col items-center gap-3">
    <x-ui.combobox x-model="status" :options="['backlog' => 'Backlog', 'todo' => 'Todo', 'in-progress' => 'In Progress', 'done' => 'Done']" />
    <p class="text-sm text-muted-foreground">Status: <code x-text="status ?? 'null'"></code></p>
</div>
