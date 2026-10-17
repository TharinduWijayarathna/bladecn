<div class="w-full max-w-md divide-y rounded-xl border">
    <x-ui.media-item title="Spring campaign visuals" description="Submitted 2 hours ago" href="#"
        :images="[
            'https://picsum.photos/seed/bladecn-1/96',
            'https://picsum.photos/seed/bladecn-2/96',
            'https://picsum.photos/seed/bladecn-3/96',
            'https://picsum.photos/seed/bladecn-5/96',
        ]" />
    <x-ui.media-item title="Landing page hero" description="Submitted yesterday" :images="['https://picsum.photos/seed/bladecn-4/96']">
        <x-slot:actions>
            <x-ui.button variant="outline" size="sm">Reject</x-ui.button>
            <x-ui.button size="sm">Approve</x-ui.button>
        </x-slot:actions>
    </x-ui.media-item>
</div>
