@props([
    'protocol' => 'https',
    'domain' => '',
    'route' => '#',
])

<div x-data="{
    editing: false,
    domain: @js($domain),
    protocol: @js($protocol),
    saving: false,
    success: false,
    toggleEdit() {
        this.editing = !this.editing;
        if (this.editing) {
            this.$nextTick(() => {
                if (this.$refs.input) {
                    this.$refs.input.focus();
                    this.$refs.input.select();
                }
            });
        }
    },
    async save() {
        this.saving = true;
        this.success = false;

        try {
            const response = await fetch(@js($route), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    protocol: this.protocol,
                    domain: this.domain
                }),
            });

            if (!response.ok) throw new Error('Save failed');

            this.success = true;
            this.editing = false;

            setTimeout(() => this.success = false, 1500);
        } catch (error) {
            alert('Something went wrong while saving.');
            console.error(error);
        } finally {
            this.saving = false;
        }
    }
}" class="flex flex-col gap-6">
    <x-ui.input-group>
        {{-- Editable input --}}
        <x-ui.input-group-input x-ref="input" type="text" name="domain" placeholder="example.com" x-model="domain"
            x-bind:disabled="!editing" class="!pl-1" />

        {{-- Protocol --}}
        <x-ui.input-group-addon align="inline-start" class="pl-0">
            <x-ui.native-select x-model="protocol" x-bind:disabled="!editing"
                class="rounded-r-none border-t-0 border-b-0 border-l-0 shadow-none ring-0 focus-visible:ring-0">
                <option value="http">http://</option>
                <option value="https">https://</option>
            </x-ui.native-select>
        </x-ui.input-group-addon>

        {{-- Action buttons --}}
        <x-ui.input-group-addon align="inline-end">
            <template x-if="!editing && !success">
                <x-ui.input-group-button type="button" aria-label="Edit" title="Edit" size="icon-xs"
                    @click="toggleEdit">
                    <x-icons.pencil />
                </x-ui.input-group-button>
            </template>

            <template x-if="editing && !saving">
                <x-ui.input-group-button type="button" aria-label="Save" title="Save" size="icon-xs" @click="save">
                    <x-icons.save />
                </x-ui.input-group-button>
            </template>

            <template x-if="saving">
                <div class="flex items-center justify-center size-5">
                    <x-ui.spinner size="sm" />
                </div>
            </template>

            <template x-if="success">
                <x-icons.check class="size-4 text-emerald-500" />
            </template>
        </x-ui.input-group-addon>
    </x-ui.input-group>
</div>
