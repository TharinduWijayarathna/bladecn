<?php

namespace Workbench\App\Docs;

use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Reads a component's props straight from its source so the docs never drift:
 *
 * - class components: the constructor signature of `src/View/Components/...`
 *   (name, declared type, default value);
 * - anonymous components: the `@props([...])` array at the top of the view;
 * - when both exist, `@props` entries not already on the constructor are added.
 */
class PropsExtractor
{
    public function __construct(protected string $packagePath) {}

    /**
     * @return array{tag: string, class: ?string, file: string, props: array<int, array{name: string, type: string, default: ?string, required: bool, source: string}>}
     */
    public function extract(string $tag): array
    {
        [$namespace, $name] = explode('.', $tag, 2);

        $class = 'BladeCN\\BladeCN\\View\\Components\\'.Str::studly($namespace).'\\'.Str::studly($name);
        $file = "resources/views/components/{$namespace}/{$name}.blade.php";

        $props = [];

        if (class_exists($class)) {
            foreach ((new ReflectionClass($class))->getConstructor()?->getParameters() ?? [] as $parameter) {
                $props[$this->attributeName($parameter->getName())] = $this->fromParameter($parameter);
            }
        }

        $path = $this->packagePath.'/'.$file;

        foreach (is_file($path) ? $this->fromBladeProps(file_get_contents($path)) : [] as $key => $prop) {
            $props[$key] ??= $prop;
        }

        return [
            'tag' => $tag,
            'class' => class_exists($class) ? $class : null,
            'file' => $file,
            'props' => array_values($props),
        ];
    }

    /**
     * Blade maps kebab-case attributes back to camelCase props. Acronyms such
     * as `maxSizeMB` would become `max-size-m-b`, so keep those camelCase.
     */
    protected function attributeName(string $name): string
    {
        $kebab = Str::kebab($name);

        return preg_match('/(^|-)[a-z](-|$)/', $kebab) ? $name : $kebab;
    }

    protected function fromParameter(ReflectionParameter $parameter): array
    {
        $type = $parameter->getType();
        $typeName = 'mixed';

        if ($type instanceof ReflectionNamedType) {
            $typeName = ($type->allowsNull() && $type->getName() !== 'mixed' ? '?' : '').$type->getName();
        } elseif ($type !== null) {
            $typeName = (string) $type;
        }

        $hasDefault = $parameter->isDefaultValueAvailable();

        return [
            'name' => $this->attributeName($parameter->getName()),
            'type' => $typeName,
            'default' => $hasDefault ? $this->export($parameter->getDefaultValue()) : null,
            'required' => ! $hasDefault,
            'source' => 'class',
        ];
    }

    /**
     * Parse the first `@props([...])` directive of a Blade file.
     *
     * @return array<string, array<string, mixed>>
     */
    public function fromBladeProps(string $contents): array
    {
        $expression = $this->propsExpression($contents);

        if ($expression === null) {
            return [];
        }

        $factory = new ParserFactory;
        $parser = method_exists($factory, 'createForNewestSupportedVersion')
            ? $factory->createForNewestSupportedVersion()
            : $factory->create(ParserFactory::PREFER_PHP7); // nikic/php-parser 4.x

        $statements = $parser->parse('<?php return '.$expression.';');
        $array = $statements[0]->expr ?? null;

        if (! $array instanceof Expr\Array_) {
            return [];
        }

        $printer = new Standard;
        $props = [];

        foreach ($array->items as $item) {
            if ($item === null) {
                continue;
            }

            if ($item->key === null) {
                // `@props(['title'])` => required prop without a default.
                if (! $item->value instanceof Node\Scalar\String_) {
                    continue;
                }

                $name = $this->attributeName($item->value->value);
                $props[$name] = ['name' => $name, 'type' => 'mixed', 'default' => null, 'required' => true, 'source' => '@props'];

                continue;
            }

            if (! $item->key instanceof Node\Scalar\String_) {
                continue;
            }

            $name = $this->attributeName($item->key->value);
            $props[$name] = [
                'name' => $name,
                'type' => $this->inferType($item->value),
                'default' => $printer->prettyPrintExpr($item->value),
                'required' => false,
                'source' => '@props',
            ];
        }

        return $props;
    }

    protected function propsExpression(string $contents): ?string
    {
        $start = strpos($contents, '@props(');

        if ($start === false) {
            return null;
        }

        $offset = $start + strlen('@props(');
        $depth = 1;
        $quote = null;
        $length = strlen($contents);

        for ($i = $offset; $i < $length; $i++) {
            $char = $contents[$i];

            if ($quote !== null) {
                if ($char === '\\') {
                    $i++;
                } elseif ($char === $quote) {
                    $quote = null;
                }

                continue;
            }

            if ($char === '"' || $char === "'") {
                $quote = $char;
            } elseif ($char === '(' || $char === '[') {
                $depth++;
            } elseif ($char === ')' || $char === ']') {
                $depth--;

                if ($depth === 0) {
                    return substr($contents, $offset, $i - $offset);
                }
            }
        }

        return null;
    }

    protected function inferType(Expr $value): string
    {
        return match (true) {
            $value instanceof Node\Scalar\String_ => 'string',
            $value instanceof Node\Scalar\LNumber, $value instanceof Node\Scalar\Int_ => 'int',
            $value instanceof Node\Scalar\DNumber, $value instanceof Node\Scalar\Float_ => 'float',
            $value instanceof Expr\Array_ => 'array',
            $value instanceof Expr\ConstFetch && in_array(strtolower($value->name->toString()), ['true', 'false']) => 'bool',
            default => 'mixed',
        };
    }

    protected function export(mixed $value): string
    {
        return match (true) {
            $value === null => 'null',
            is_bool($value) => $value ? 'true' : 'false',
            is_array($value) => $value === [] ? '[]' : json_encode($value),
            is_string($value) => "'".$value."'",
            default => (string) $value,
        };
    }
}
