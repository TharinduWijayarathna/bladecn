<?php

if (! function_exists('cn')) {
    /**
     * Merge class names together, filtering out empty values.
     * Similar to clsx utility in JavaScript.
     *
     * @param  mixed  ...$classes  strings, nested arrays; anything else (null, false) is ignored
     */
    function cn(...$classes): string
    {
        $classes = array_filter(
            array_map(function ($class) {
                if (is_array($class)) {
                    return cn(...$class);
                }

                return is_string($class) ? trim($class) : '';
            }, $classes),
            fn ($class) => ! empty($class)
        );

        return implode(' ', $classes);
    }
}

if (! function_exists('getInitials')) {
    /**
     * Get initials from a name (first letter of first two words).
     */
    function getInitials(string $name): string
    {
        if (empty(trim($name))) {
            return '';
        }

        $words = preg_split('/\s+/', trim($name));

        if (count($words) === 0) {
            return '';
        }

        // Get first letter of first word
        $first = mb_substr($words[0], 0, 1, 'UTF-8');

        // Get first letter of second word if it exists, otherwise use first word's second letter
        if (count($words) >= 2) {
            $second = mb_substr($words[1], 0, 1, 'UTF-8');
        } else {
            // If only one word, use first two letters
            $second = mb_strlen($words[0]) > 1 ? mb_substr($words[0], 1, 1, 'UTF-8') : '';
        }

        return strtoupper($first.$second);
    }
}
