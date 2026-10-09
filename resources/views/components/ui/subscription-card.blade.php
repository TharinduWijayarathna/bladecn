@props([
    'plan',
    'subscription',
    'description' => null,
    'price',
    'currency',
    'expireDate',
    'progress' => 0,
    'upgradeRoute' => null,
])

<x-ui.card>
    <x-ui.card-header class="flex-row items-center justify-between">
        <div>
            <x-ui.card-title class="flex items-center gap-2 text-xl">
                {{ $plan }}
                <x-ui.badge variant="secondary" class="rounded-md capitalize">{{ $subscription }}</x-ui.badge>
            </x-ui.card-title>
            @if($description)
                <x-ui.card-description>{{ $description }}</x-ui.card-description>
            @endif
        </div>
        <div class="text-2xl font-semibold whitespace-nowrap tabular-nums">
            {{ $currency }} {{ $price }}
        </div>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="grid gap-2">
            <span class="text-sm text-muted-foreground">
                Due {{ \Carbon\Carbon::parse($expireDate)->format('F j, Y') }}
            </span>
            <x-ui.progress :value="$progress" />
        </div>
    </x-ui.card-content>
    @if($upgradeRoute)
        <x-ui.card-footer class="flex-col items-end border-t py-2 text-sm">
            <x-ui.button variant="link" tag="a" :href="$upgradeRoute" class="cursor-default">
                Upgrade plan
                <x-icons.arrow-up-right />
            </x-ui.button>
        </x-ui.card-footer>
    @endif
</x-ui.card>

