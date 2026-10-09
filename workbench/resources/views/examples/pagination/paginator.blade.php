@php
    // In a controller this is simply: $users = User::paginate(10);
    $users = new \Illuminate\Pagination\LengthAwarePaginator(range(1, 10), total: 200, perPage: 10, currentPage: 7, options: ['path' => '/users']);
@endphp

<x-ui.pagination :paginator="$users" />
