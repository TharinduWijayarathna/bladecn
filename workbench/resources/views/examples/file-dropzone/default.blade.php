<form method="POST" action="#" enctype="multipart/form-data" class="grid w-full max-w-md gap-4">
    @csrf
    <x-ui.file-dropzone name="attachments" label="Attachments" accept="image/*,.pdf" :max-files="5" :max-size="10" />
    <div class="flex justify-end gap-2">
        <x-ui.button type="reset" variant="outline">Clear</x-ui.button>
        <x-ui.button type="submit">Upload</x-ui.button>
    </div>
</form>
