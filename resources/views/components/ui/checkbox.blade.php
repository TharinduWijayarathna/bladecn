<input type="checkbox" {{ $checked ? 'checked' : '' }}
    {{ $attributes->merge(['class' => 'size-4 shrink-0 rounded-[4px] border border-input accent-primary shadow-xs outline-none transition-shadow focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:ring-destructive/20']) }} />

