@php
    $settings = [
        'country' => 'lk',
        'newsletter' => true,
        'channels' => ['email', 'push'],
        'public' => true,
    ];
@endphp

<form method="POST" action="#" class="grid w-full max-w-sm gap-4" onsubmit="event.preventDefault()">
    <x-ui.field>
        <x-ui.field-label for="country">Country</x-ui.field-label>
        <x-ui.native-select id="country" name="country" :model="$settings">
            <option value="gb">United Kingdom</option>
            <option value="lk">Sri Lanka</option>
            <option value="us">United States</option>
        </x-ui.native-select>
    </x-ui.field>
    <div class="flex items-center gap-2">
        <x-ui.checkbox id="newsletter" name="newsletter" value="1" :model="$settings" />
        <x-ui.label for="newsletter">Send me the newsletter</x-ui.label>
    </div>
    <div class="flex flex-col gap-2">
        @foreach (['email' => 'Email', 'sms' => 'SMS', 'push' => 'Push'] as $value => $label)
            <div class="flex items-center gap-2">
                <x-ui.checkbox :id="'channel-'.$value" name="channels[]" :value="$value" :model="$settings" />
                <x-ui.label :for="'channel-'.$value">{{ $label }}</x-ui.label>
            </div>
        @endforeach
    </div>
    <div class="flex items-center gap-2">
        <x-ui.switch id="public" name="public" :model="$settings" />
        <x-ui.label for="public">Public profile</x-ui.label>
    </div>
</form>
