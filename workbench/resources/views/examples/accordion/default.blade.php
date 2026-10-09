<x-ui.accordion type="single" collapsible default-value="item-1" class="max-w-md">
    <x-ui.accordion-item value="item-1">
        <x-ui.accordion-trigger>Is it accessible?</x-ui.accordion-trigger>
        <x-ui.accordion-content>Yes. It follows the WAI-ARIA accordion pattern: buttons with <code>aria-expanded</code>, labelled regions and arrow-key navigation.</x-ui.accordion-content>
    </x-ui.accordion-item>
    <x-ui.accordion-item value="item-2">
        <x-ui.accordion-trigger>Is it styled?</x-ui.accordion-trigger>
        <x-ui.accordion-content>Yes. It comes with default styles that match the other components.</x-ui.accordion-content>
    </x-ui.accordion-item>
    <x-ui.accordion-item value="item-3">
        <x-ui.accordion-trigger>Is it animated?</x-ui.accordion-trigger>
        <x-ui.accordion-content>Yes. Height animates with CSS only, no JavaScript measuring.</x-ui.accordion-content>
    </x-ui.accordion-item>
</x-ui.accordion>
