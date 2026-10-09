<x-ui.input-otp name="code" :length="6">
    <x-ui.input-otp-group>
        @foreach (range(0, 5) as $index)
            <x-ui.input-otp-slot :index="$index" />
        @endforeach
    </x-ui.input-otp-group>
</x-ui.input-otp>
