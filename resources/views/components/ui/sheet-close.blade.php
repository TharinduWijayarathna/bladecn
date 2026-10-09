<button data-action="close-sheet" {{ $attributes->merge(['type' => 'button', 'class' => $sheetCloseClasses()]) }}>
    {{ $slot }}
</button>
