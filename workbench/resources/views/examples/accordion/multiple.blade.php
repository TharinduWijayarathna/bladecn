<x-ui.accordion type="multiple" :default-value="['shipping', 'returns']" class="max-w-md">
    <x-ui.accordion-item value="shipping">
        <x-ui.accordion-trigger>Shipping</x-ui.accordion-trigger>
        <x-ui.accordion-content>Orders ship within 2 business days.</x-ui.accordion-content>
    </x-ui.accordion-item>
    <x-ui.accordion-item value="returns">
        <x-ui.accordion-trigger>Returns</x-ui.accordion-trigger>
        <x-ui.accordion-content>Return anything within 30 days for a full refund.</x-ui.accordion-content>
    </x-ui.accordion-item>
    <x-ui.accordion-item value="gift" disabled>
        <x-ui.accordion-trigger>Gift wrapping (unavailable)</x-ui.accordion-trigger>
        <x-ui.accordion-content>Coming soon.</x-ui.accordion-content>
    </x-ui.accordion-item>
</x-ui.accordion>
