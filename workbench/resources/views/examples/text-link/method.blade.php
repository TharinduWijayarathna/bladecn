{{-- In your app: href="{{ route('logout') }}" --}}
<x-ui.text-link href="#logout" method="post">Log out</x-ui.text-link>

<x-ui.text-link href="#posts/1" method="delete" :params="['reason' => 'spam']">
    Delete post
</x-ui.text-link>
