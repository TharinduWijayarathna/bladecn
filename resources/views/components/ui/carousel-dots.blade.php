@props([
    'class' => '',
])

{{-- One dot per slide, rendered by Alpine once the slides are counted. --}}
<div data-slot="carousel-dots" role="group" aria-label="Choose slide"
    {{ $attributes->merge(['class' => cn('flex items-center justify-center gap-2 pt-4', $class)]) }}>
    <template x-for="i in count" :key="i">
        <button type="button" data-slot="carousel-dot" @click="scrollTo(i - 1)"
            :aria-label="'Go to slide ' + i" :aria-current="index === i - 1 ? 'true' : 'false'"
            :data-state="index === i - 1 ? 'active' : 'inactive'"
            class="size-2 rounded-full bg-primary/20 transition-all outline-none hover:bg-primary/40 focus-visible:ring-[3px] focus-visible:ring-ring/50 data-[state=active]:w-5 data-[state=active]:bg-primary"></button>
    </template>
</div>
