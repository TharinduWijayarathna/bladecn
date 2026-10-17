<div class="grid w-full max-w-md gap-3">
    <x-ui.payment-method brand="visa" last4="4242" expires="04/28" holder="Jane Doe" default>
        <x-slot:actions>
            <x-ui.button variant="outline" size="sm">Edit</x-ui.button>
        </x-slot:actions>
    </x-ui.payment-method>
    <x-ui.payment-method brand="mastercard" last4="5100" expires="11/27">
        <x-slot:actions>
            <x-ui.button variant="ghost" size="sm">Make default</x-ui.button>
        </x-slot:actions>
    </x-ui.payment-method>
</div>
