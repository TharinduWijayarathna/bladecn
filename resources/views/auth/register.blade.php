<x-layout.auth title="Create an account" description="Enter your information to get started" backgroundImage="https://picsum.photos/seed/picsum/1920/1080">
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <x-ui.label for="name">Name</x-ui.label>
            <x-ui.input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-ui.input-error :message="$errors->first('name')" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="email">Email</x-ui.label>
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-ui.input-error :message="$errors->first('email')" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="password">Password</x-ui.label>
            <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-ui.input-error :message="$errors->first('password')" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="password_confirmation">Confirm Password</x-ui.label>
            <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-start space-x-2">
            <x-ui.checkbox id="terms" name="terms" required class="mt-0.5" />
            <x-ui.label for="terms" class="text-sm font-normal">
                I agree to the Terms of Service and Privacy Policy
            </x-ui.label>
        </div>

        <x-ui.button type="submit" class="w-full">
            Create account
        </x-ui.button>
    </form>
</x-layout.auth>

