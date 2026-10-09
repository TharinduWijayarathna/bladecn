@props([
    'title',
    'description',
    'cardType' => 'visa',
    'cardHolder',
    'cardNumber',
    'expiryDate',
])

<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title class="text-xl">{{ $title }}</x-ui.card-title>
        <x-ui.card-description>{{ $description }}</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="flex items-start justify-between rounded-lg border border-accent p-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-16 items-center justify-center rounded-md border">
                    @if($cardType === 'visa' || $cardType === 'master')
                        <x-icons.visa-logo />
                    @endif
                </div>
                <div class="space-y-0.5">
                    <p class="font-medium text-foreground">**** **** **** {{ substr($cardNumber, -4) }}</p>
                    <p class="text-sm text-muted-foreground">
                        Expires {{ \Carbon\Carbon::parse($expiryDate)->format('m/y') }}
                    </p>
                    <span class="text-sm text-muted-foreground">{{ $cardHolder }}</span>
                </div>
            </div>
            <x-ui.button variant="outline" size="sm" tag="a" href="#" class="cursor-default">
                Edit
            </x-ui.button>
        </div>
    </x-ui.card-content>
</x-ui.card>

