<div x-data="{ checked: [] }" class="flex w-72 flex-col gap-3">
    <x-ui.tree-view checkboxes name="permissions" label="Permissions" :expanded="true" :checked="['posts.view', 'posts.create']"
        x-on:change="checked = $event.detail.checked" :items="[
        ['id' => 'posts', 'label' => 'Posts', 'children' => [
            ['id' => 'posts.view', 'label' => 'View'],
            ['id' => 'posts.create', 'label' => 'Create'],
            ['id' => 'posts.delete', 'label' => 'Delete'],
        ]],
        ['id' => 'users', 'label' => 'Users', 'children' => [
            ['id' => 'users.view', 'label' => 'View'],
            ['id' => 'users.invite', 'label' => 'Invite'],
            ['id' => 'users.ban', 'label' => 'Ban', 'disabled' => true],
        ]],
    ]" />
    <p class="text-xs text-muted-foreground">Checked: <code x-text="checked.join(', ') || '…'"></code></p>
</div>
