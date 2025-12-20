<x-layout.auth title="Welcome back" description="Sign in to your account to continue" backgroundImage="https://picsum.photos/seed/picsum/1920/1080">
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <x-ui.label for="email">Email</x-ui.label>
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-ui.input-error :message="$errors->first('email')" />
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <x-ui.label for="password">Password</x-ui.label>
                <x-ui.text-link :href="route('password.request')" class="text-xs">
                    Forgot password?
                </x-ui.text-link>
            </div>
            <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-ui.input-error :message="$errors->first('password')" />
        </div>

        <div class="flex items-center space-x-2">
            <x-ui.checkbox id="remember" name="remember" :checked="old('remember')" />
            <x-ui.label for="remember" class="text-sm font-normal">
                Remember me
            </x-ui.label>
        </div>

        <x-ui.button type="submit" class="w-full">
            Sign in
        </x-ui.button>
    </form>
</x-layout.auth>

