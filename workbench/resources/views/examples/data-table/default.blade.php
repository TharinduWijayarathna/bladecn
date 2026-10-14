@php
    // In a controller this is simply:
    // $users = User::query()
    //     ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
    //     ->orderBy($request->input('sort', 'name'), $request->input('direction', 'asc'))
    //     ->paginate(5);
    $all = collect([
        ['id' => 1, 'name' => 'Olivia Martin', 'email' => 'olivia@example.com', 'role' => 'Admin', 'status' => 'active'],
        ['id' => 2, 'name' => 'Jackson Lee', 'email' => 'jackson@example.com', 'role' => 'Editor', 'status' => 'invited'],
        ['id' => 3, 'name' => 'Isabella Nguyen', 'email' => 'isabella@example.com', 'role' => 'Viewer', 'status' => 'active'],
        ['id' => 4, 'name' => 'William Kim', 'email' => 'will@example.com', 'role' => 'Editor', 'status' => 'suspended'],
        ['id' => 5, 'name' => 'Sofia Davis', 'email' => 'sofia@example.com', 'role' => 'Viewer', 'status' => 'active'],
        ['id' => 6, 'name' => 'Liam Johnson', 'email' => 'liam@example.com', 'role' => 'Admin', 'status' => 'active'],
        ['id' => 7, 'name' => 'Emma Brown', 'email' => 'emma@example.com', 'role' => 'Viewer', 'status' => 'invited'],
    ]);
    $search = request('search');
    $filtered = $all
        ->when($search, fn ($rows) => $rows->filter(fn ($row) => str_contains(strtolower($row['name'].$row['email']), strtolower($search))))
        ->sortBy(request('sort', 'name'), SORT_NATURAL | SORT_FLAG_CASE, request('direction') === 'desc')
        ->values();
    $page = max(1, (int) request('page', 1));
    $users = new \Illuminate\Pagination\LengthAwarePaginator($filtered->forPage($page, 5)->values(), $filtered->count(), 5, $page, ['path' => request()->url()]);

    $columns = [
        ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'hideable' => false, 'class' => 'font-medium'],
        ['key' => 'email', 'label' => 'Email', 'sortable' => true],
        ['key' => 'role', 'label' => 'Role', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'format' => fn ($status) => new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\Blade::render('<x-ui.badge :variant="$v">{{ ucfirst($s) }}</x-ui.badge>', [
                's' => $status, 'v' => $status === 'active' ? 'default' : ($status === 'suspended' ? 'destructive' : 'secondary'),
            ])
        )],
    ];
@endphp

<form id="users-bulk" method="POST" action="#">
    @csrf
</form>

<x-ui.data-table :rows="$users" :columns="$columns" selectable form="users-bulk" search-placeholder="Search users..."
    storage-key="docs-users-columns">
    <x-slot:bulk-actions>
        <x-ui.button type="submit" form="users-bulk" name="action" value="export" variant="outline" size="sm">Export</x-ui.button>
        <x-ui.button type="submit" form="users-bulk" name="action" value="delete" variant="destructive" size="sm">Delete</x-ui.button>
    </x-slot:bulk-actions>
</x-ui.data-table>
