<x-ui.field invalid class="max-w-sm">
    <x-ui.field-label for="email-invalid">Email</x-ui.field-label>
    <x-ui.input id="email-invalid" type="email" value="not-an-email" aria-invalid="true" />
    <x-ui.field-error :messages="['Enter a valid email address.']" />
</x-ui.field>
