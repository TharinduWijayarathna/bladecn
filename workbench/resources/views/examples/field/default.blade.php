<form class="w-full max-w-md" onsubmit="event.preventDefault()">
    <x-ui.field-set>
        <x-ui.field-legend>Payment Method</x-ui.field-legend>
        <x-ui.field-description>All transactions are secure and encrypted.</x-ui.field-description>
        <x-ui.field-group>
            <x-ui.field>
                <x-ui.field-label for="card-name">Name on Card</x-ui.field-label>
                <x-ui.input id="card-name" placeholder="Evil Rabbit" required />
            </x-ui.field>
            <x-ui.field>
                <x-ui.field-label for="card-number">Card Number</x-ui.field-label>
                <x-ui.input id="card-number" placeholder="1234 5678 9012 3456" />
                <x-ui.field-description>Enter your 16-digit card number.</x-ui.field-description>
            </x-ui.field>
            <x-ui.field-separator>or</x-ui.field-separator>
            <x-ui.field orientation="horizontal">
                <x-ui.checkbox id="same-address" checked />
                <x-ui.field-label for="same-address" class="font-normal">Same as shipping address</x-ui.field-label>
            </x-ui.field>
        </x-ui.field-group>
    </x-ui.field-set>
</form>
