<div x-data="{ step: 2 }" class="flex w-full flex-col gap-6">
    <x-ui.stepper x-model="step" clickable="completed" :steps="['Cart', 'Details', 'Payment', 'Done']" />
    <div class="flex items-center justify-between gap-2">
        <x-ui.button variant="outline" size="sm" x-on:click="step = Math.max(0, step - 1)" x-bind:disabled="step === 0">Back</x-ui.button>
        <p class="text-sm text-muted-foreground">Step <span x-text="step + 1"></span> of 4</p>
        <x-ui.button size="sm" x-on:click="step = Math.min(3, step + 1)" x-bind:disabled="step === 3">Next</x-ui.button>
    </div>
</div>
