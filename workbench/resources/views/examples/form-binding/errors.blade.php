@php
    // Demo only: in a real form these messages come from $request->validate().
    $errors->put('demo', new \Illuminate\Support\MessageBag([
        'email' => ['The email has already been taken.'],
        'plan' => ['Please choose a plan.'],
    ]));
    $user = ['email' => 'taken@example.com'];
@endphp

<form method="POST" action="#" class="grid w-full max-w-sm gap-4" onsubmit="event.preventDefault()">
    <x-ui.field>
        <x-ui.field-label for="email-invalid">Email</x-ui.field-label>
        <x-ui.input id="email-invalid" type="email" name="email" :model="$user" error-bag="demo" />
    </x-ui.field>
    <x-ui.field>
        <x-ui.field-label>Plan</x-ui.field-label>
        <x-ui.radio-group name="plan" :options="['free', 'pro', 'team']" :model="$user" error-bag="demo" />
    </x-ui.field>
</form>
