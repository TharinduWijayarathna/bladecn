<x-ui.plan-card class="w-full max-w-md" name="Pro" badge="Monthly" price="$29" interval="month"
    description="Unlimited projects and priority support." renews="2026-11-01" :usage="68" usage-label="Storage used">
    <x-slot:footer>
        <x-ui.button variant="ghost" size="sm">Cancel plan</x-ui.button>
        <x-ui.button size="sm">Upgrade</x-ui.button>
    </x-slot:footer>
</x-ui.plan-card>
