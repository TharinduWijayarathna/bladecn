<?php

namespace BladeCN\BladeCN\Support;

use Illuminate\View\ComponentAttributeBag;

/**
 * Livewire helpers for Alpine-driven controls, without depending on Livewire.
 *
 * Controls such as switch, combobox, date-picker, slider and input-tags keep
 * their state in Alpine and expose it through `x-modelable`, so `wire:model`
 * (any modifiers) on the tag binds two-way; Livewire 3 turns `wire:model` into
 * an `x-model` that x-modelable entangles with the component's state. When a
 * tag carries `wire:model`, the control additionally:
 *
 * - gets `wire:ignore`, so Livewire's DOM morph does not overwrite markup that
 *   Alpine owns (labels, hidden inputs) — the value still syncs through Alpine;
 * - dispatches `input` and `change` events with the value as `detail` whenever
 *   it changes, for listeners and for older wire:model implementations.
 *
 * Nothing here references Livewire classes, so the package does not require it.
 */
final class Livewire
{
    /**
     * Whether the tag carries `wire:model` (with or without modifiers).
     */
    public static function bound(ComponentAttributeBag $attributes): bool
    {
        foreach (array_keys($attributes->getAttributes()) as $attribute) {
            if (str_starts_with($attribute, 'wire:model')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add `wire:ignore` and an event-dispatching `x-init` for `$property` when
     * the tag is bound with `wire:model`. Returns the bag untouched otherwise.
     */
    /**
     * @param  array<int, string>  $events
     */
    public static function attributes(ComponentAttributeBag $attributes, string $property, array $events = ['input', 'change']): ComponentAttributeBag
    {
        if (! self::bound($attributes)) {
            return $attributes;
        }

        $attributes = new ComponentAttributeBag($attributes->getAttributes());
        $init = self::dispatcher($property, $events);

        if (! $attributes->has('wire:ignore') && ! $attributes->has('wire:ignore.self')) {
            $attributes->offsetSet('wire:ignore', true);
        }

        $existing = $attributes->get('x-init');
        $attributes->offsetSet('x-init', is_string($existing) && $existing !== '' ? $init.'; '.$existing : $init);

        return $attributes;
    }

    /**
     * Alpine expression that re-dispatches `input` / `change` (detail = value) when `$property` changes.
     *
     * @param  array<int, string>  $events
     */
    public static function dispatcher(string $property, array $events = ['input', 'change']): string
    {
        $types = implode(', ', array_map(fn (string $event) => "'{$event}'", $events));

        return "\$watch('{$property}', value => [{$types}].forEach(type => \$el.dispatchEvent(new CustomEvent(type, { detail: value }))))";
    }
}
