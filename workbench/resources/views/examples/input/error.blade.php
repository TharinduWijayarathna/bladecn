<div class="grid w-full max-w-sm gap-2">
    <x-ui.label for="input-invalid">Email</x-ui.label>
    <x-ui.input type="email" id="input-invalid" value="not-an-email" aria-invalid="true" />
    <x-ui.input-error message="The email field must be a valid email address." />
</div>
