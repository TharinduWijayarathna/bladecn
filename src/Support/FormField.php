<?php

namespace BladeCN\BladeCN\Support;

use BackedEnum;
use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Stringable;
use UnitEnum;

/**
 * Laravel form binding for BladeCN fields.
 *
 * Fills a field from `old()` and a bound model, maps dot names to array names
 * (`user.email` <=> `user[email]`) and reads validation errors from the shared
 * `$errors` bag. Every helper is a no-op for a field without a `name`, so markup
 * that does not opt in renders exactly as before.
 */
final class FormField
{
    /**
     * Dot-notation key for a field name: `user[email]` => `user.email`, `tags[]` => `tags`.
     */
    public static function key(?string $name): ?string
    {
        if ($name === null || $name === '') {
            return null;
        }

        $key = str_replace(['[]', '[', ']'], ['', '.', ''], $name);

        return trim(preg_replace('/\.+/', '.', $key) ?? $key, '.');
    }

    /**
     * HTML name for a field: `user.email` => `user[email]`. Names that already
     * use brackets (or have no dots) are returned untouched.
     */
    public static function htmlName(?string $name): ?string
    {
        if ($name === null || ! str_contains($name, '.') || str_contains($name, '[')) {
            return $name;
        }

        $segments = explode('.', $name);

        return array_shift($segments).implode('', array_map(fn ($segment) => '['.$segment.']', $segments));
    }

    /**
     * Whether the previous request flashed any old input (i.e. a form was just submitted).
     */
    public static function hasOldInput(): bool
    {
        $request = app('request');

        return $request->hasSession() && $request->session()->hasOldInput();
    }

    /**
     * Old input for a key, without touching the session when there is none.
     */
    public static function old(?string $key, mixed $default = null): mixed
    {
        if ($key === null) {
            return $default;
        }

        $request = app('request');

        return $request->hasSession() ? $request->old($key, $default) : $default;
    }

    /**
     * The value a field should show: old input, then the model's attribute, then the default.
     */
    public static function value(?string $name, mixed $model = null, mixed $default = null): mixed
    {
        $key = self::key($name);

        if ($key === null) {
            return $default;
        }

        if ($model !== null) {
            $default = data_get($model, $key, $default);
        }

        return self::old($key, $default);
    }

    /**
     * Whether a checkbox-like control should be checked.
     *
     * Old input wins when present. After a submit, a bound control whose key is
     * missing from the old input was unchecked. Otherwise the model decides, and
     * without a model the component's own `checked` default is kept.
     */
    public static function checked(?string $name, mixed $model = null, ?string $value = null, bool $default = false, bool $bound = false): bool
    {
        $key = self::key($name);

        if ($key === null) {
            return $default;
        }

        $old = self::old($key);

        if ($old !== null) {
            return self::matches($old, $value);
        }

        if (($model !== null || $bound) && self::hasOldInput()) {
            return false;
        }

        if ($model !== null) {
            return self::matches(data_get($model, $key), $value);
        }

        return $default;
    }

    /**
     * Whether a submitted / stored value checks a control with the given `value`.
     */
    protected static function matches(mixed $current, ?string $value): bool
    {
        if (is_iterable($current)) {
            foreach ($current as $item) {
                if (self::toString($item) === $value) {
                    return true;
                }
            }

            return false;
        }

        if ($value === null || in_array($value, ['on', '1', 'true'], true)) {
            return filter_var(self::toString($current), FILTER_VALIDATE_BOOLEAN);
        }

        return self::toString($current) === $value;
    }

    /**
     * Validation messages for a field, from the shared `$errors` bag.
     *
     * @return array<int, string>
     */
    public static function errors(?string $name, string $bag = 'default'): array
    {
        $key = self::key($name);
        $errors = app('view')->shared('errors');

        if ($key === null || ! $errors instanceof ViewErrorBag) {
            return [];
        }

        $messages = $errors->getBag($bag);
        $direct = $messages->get($key);

        // Array fields (`tags[]`) are validated as `tags.*`; fall back to those messages.
        $nested = $direct === [] ? array_merge([], ...array_values($messages->get($key.'.*'))) : [];

        return array_values(array_unique([...$direct, ...$nested]));
    }

    /**
     * Whether the tag carries `wire:model*` or `x-model*`: the value then comes
     * from Livewire / Alpine and must not be pre-filled on the server.
     */
    public static function isModelBound(ComponentAttributeBag $attributes): bool
    {
        foreach (array_keys($attributes->getAttributes()) as $attribute) {
            if (str_starts_with($attribute, 'wire:model') || str_starts_with($attribute, 'x-model')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Bind a field's attributes: map a dot name to an array name, fill `value`
     * (when `$fillValue`), and, for bound fields with errors, set `aria-invalid`
     * and `aria-describedby`.
     *
     * A field is bound when a `model` is passed or `bind` is true. Only bound
     * fields render their error message, so forms that already print
     * `<x-ui.input-error>` next to the field do not show it twice.
     *
     * @return array{0: ComponentAttributeBag, 1: ?string, 2: ?string} attributes, first error, error element id
     */
    public static function bind(ComponentAttributeBag $attributes, mixed $model = null, ?bool $bind = null, string $errorBag = 'default', bool $fillValue = true, ?string $type = null): array
    {
        $name = $attributes->get('name');

        if (! is_string($name) || $name === '') {
            return [$attributes, null, null];
        }

        $attributes = new ComponentAttributeBag($attributes->getAttributes());
        $key = (string) self::key($name);
        $htmlName = self::htmlName($name);

        if ($htmlName !== $name) {
            $attributes->offsetSet('name', $htmlName);
        }

        $type = strtolower((string) ($type ?? $attributes->get('type', 'text')));

        if ($fillValue && ! in_array($type, ['password', 'file'], true) && ! self::isModelBound($attributes)) {
            $current = $attributes->get('value');
            $resolved = self::value($name, $model, $current);

            if ($resolved !== $current) {
                $attributes->offsetSet('value', self::toString($resolved, $type));
            }
        }

        return self::withError($attributes, $key, $model !== null || $bind === true, $errorBag, $bind);
    }

    /**
     * Add `aria-invalid` / `aria-describedby` and return the first error for a bound field.
     *
     * @return array{0: ComponentAttributeBag, 1: ?string, 2: ?string}
     */
    public static function withError(ComponentAttributeBag $attributes, string $key, bool $bound, string $errorBag = 'default', ?bool $bind = null): array
    {
        if (! $bound || $bind === false) {
            return [$attributes, null, null];
        }

        $error = self::errors($key, $errorBag)[0] ?? null;

        if ($error === null) {
            return [$attributes, null, null];
        }

        $errorId = self::errorId($key, $attributes->get('id'));
        $describedBy = trim(((string) $attributes->get('aria-describedby', '')).' '.$errorId);

        $attributes->offsetSet('aria-invalid', 'true');
        $attributes->offsetSet('aria-describedby', $describedBy);

        return [$attributes, $error, $errorId];
    }

    /**
     * Id for a field's error element: `{id}-error`, or one derived from the name.
     */
    public static function errorId(string $key, mixed $id = null): string
    {
        $base = is_string($id) && $id !== '' ? $id : 'field-'.Str::slug(str_replace('.', '-', $key));

        return $base.'-error';
    }

    /**
     * Mark `<option>`s in rendered select options as selected for a value
     * (or a list of values for a multiple select).
     */
    public static function selectOptions(string $html, mixed $value): string
    {
        $values = array_map(fn ($item) => self::toString($item), is_iterable($value) ? [...$value] : [$value]);

        return (string) preg_replace_callback('/<option\b([^>]*)>/i', function (array $match) use ($values) {
            $attributes = (string) preg_replace('/\sselected(=("[^"]*"|\'[^\']*\'|[^\s>]*))?(?=[\s>\/]|$)/i', '', $match[1]);

            if (! preg_match('/\svalue=("([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $attributes, $valueMatch)) {
                return '<option'.$attributes.'>';
            }

            $raw = $valueMatch[2] !== '' ? $valueMatch[2] : ($valueMatch[3] ?? '');
            $optionValue = html_entity_decode($raw !== '' ? $raw : ($valueMatch[4] ?? ''), ENT_QUOTES);

            return '<option'.$attributes.(in_array($optionValue, $values, true) ? ' selected' : '').'>';
        }, $html);
    }

    /**
     * Cast a model / old-input value to the string an input shows.
     */
    public static function toString(mixed $value, ?string $type = null): ?string
    {
        return match (true) {
            $value === null => null,
            $value instanceof BackedEnum => (string) $value->value,
            $value instanceof UnitEnum => $value->name,
            $value instanceof DateTimeInterface => $value->format(match ($type) {
                'datetime-local' => 'Y-m-d\\TH:i',
                'time' => 'H:i',
                'month' => 'Y-m',
                default => 'Y-m-d',
            }),
            is_bool($value) => $value ? '1' : '0',
            is_scalar($value), $value instanceof Stringable => (string) $value,
            default => null,
        };
    }
}
