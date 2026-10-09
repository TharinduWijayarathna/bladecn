@php
    $people = [
        ['name' => 'shadcn', 'email' => 'shadcn@vercel.com', 'avatar' => 'https://github.com/shadcn.png'],
        ['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com', 'avatar' => 'https://github.com/taylorotwell.png'],
    ];
@endphp

<x-ui.item-group class="w-full max-w-md rounded-md border">
    @foreach ($people as $person)
        <x-ui.item>
            <x-ui.item-media variant="image">
                <img src="{{ $person['avatar'] }}" alt="{{ $person['name'] }}">
            </x-ui.item-media>
            <x-ui.item-content>
                <x-ui.item-title>{{ $person['name'] }}</x-ui.item-title>
                <x-ui.item-description>{{ $person['email'] }}</x-ui.item-description>
            </x-ui.item-content>
            <x-ui.item-actions>
                <x-ui.button variant="ghost" size="icon-sm" aria-label="Invite">
                    <x-icons.mail />
                </x-ui.button>
            </x-ui.item-actions>
        </x-ui.item>
        @unless ($loop->last)
            <x-ui.item-separator />
        @endunless
    @endforeach
</x-ui.item-group>
