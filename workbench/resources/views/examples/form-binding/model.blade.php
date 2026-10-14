@php
    // In a real app: $user = auth()->user(); here a stand-in model.
    $user = new \Illuminate\Support\Fluent([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'bio' => 'Analyst, metaphysician and founder of scientific computing.',
        'address' => ['city' => 'London'],
    ]);
@endphp

<form method="POST" action="#" class="grid w-full max-w-sm gap-4" onsubmit="event.preventDefault()">
    <x-ui.field>
        <x-ui.field-label for="name">Name</x-ui.field-label>
        <x-ui.input id="name" name="name" :model="$user" required />
    </x-ui.field>
    <x-ui.field>
        <x-ui.field-label for="email">Email</x-ui.field-label>
        <x-ui.input id="email" type="email" name="email" :model="$user" required />
    </x-ui.field>
    <x-ui.field>
        <x-ui.field-label for="city">City</x-ui.field-label>
        {{-- Dot names are read with data_get() and submitted as address[city]. --}}
        <x-ui.input id="city" name="address.city" :model="$user" />
    </x-ui.field>
    <x-ui.field>
        <x-ui.field-label for="bio">Bio</x-ui.field-label>
        <x-ui.textarea id="bio" name="bio" :model="$user" rows="3" />
    </x-ui.field>
</form>
