<?php

namespace Workbench\App\Docs;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Registry for the docs site: navigation, pages, examples and props.
 */
class Docs
{
    /** @var array<string, array<string, mixed>> */
    protected array $pages;

    /**
     * @param  array{guides: array<string, array<string, mixed>>, components: array<string, array<string, mixed>>}  $pages
     */
    public function __construct(array $pages, protected string $packagePath, protected string $workbenchPath)
    {
        $guides = collect($pages['guides'])->map(fn ($page, $slug) => $page + [
            'slug' => $slug,
            'type' => 'guide',
            'group' => 'Getting Started',
            'components' => [],
            'props' => [],
        ]);

        $components = collect($pages['components'])
            ->map(fn ($page, $slug) => $page + [
                'slug' => $slug,
                'type' => 'component',
                'group' => $page['group'] ?? 'Components',
                'components' => [],
                'examples' => [],
                'props' => [],
            ])
            ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE);

        $this->pages = $guides->merge($components)->all();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function pages(): array
    {
        return $this->pages;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function componentPages(): array
    {
        return array_filter($this->pages, fn ($page) => $page['type'] === 'component');
    }

    /**
     * Sidebar groups, in display order.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function navigation(): array
    {
        $groups = ['Getting Started' => [], 'Components' => [], 'Extras' => []];

        foreach ($this->pages as $page) {
            $groups[$page['group']][] = $page;
        }

        return array_filter($groups);
    }

    public function find(string $slug, ?string $type = null): ?array
    {
        $page = $this->pages[$slug] ?? null;

        if ($page && $type && $page['type'] !== $type) {
            return null;
        }

        return $page;
    }

    /**
     * Public path for a page, relative to the docs root (no leading slash).
     */
    public function path(array $page): string
    {
        if ($page['slug'] === 'introduction') {
            return '';
        }

        return $page['type'] === 'component' ? 'components/'.$page['slug'] : $page['slug'];
    }

    public function url(string $path = ''): string
    {
        $base = config('docs.base_url') ?? '/'.config('docs.prefix');
        $base = rtrim($base, '/');
        $path = ltrim($path, '/');

        return $path === '' ? ($base === '' ? '/' : $base.'/') : $base.'/'.$path;
    }

    public function pageUrl(array $page): string
    {
        return $this->url($this->path($page));
    }

    public function asset(string $file): string
    {
        $path = $this->distPath($file);
        $version = is_file($path) ? substr(md5_file($path), 0, 8) : 'missing';

        return $this->url('assets/'.$file).'?v='.$version;
    }

    public function distPath(string $file = ''): string
    {
        return $this->workbenchPath.'/dist'.($file ? '/'.$file : '');
    }

    public function assetsBuilt(): bool
    {
        return is_file($this->distPath('docs.css')) && is_file($this->distPath('docs.js'));
    }

    /**
     * Examples for a component page. Each example is one Blade file under
     * `workbench/resources/views/examples/{slug}/{key}.blade.php`; the same
     * file is rendered for the Preview tab and printed verbatim in the Code tab.
     *
     * @return array<int, array{key: string, title: string, description: ?string, view: string, source: string}>
     */
    public function examples(array $page): array
    {
        return collect($page['examples'])->map(function ($example, $key) use ($page) {
            $example = is_string($example) ? ['title' => $example] : $example;
            $file = $this->exampleFile($page['slug'], $key);

            return [
                'key' => $key,
                'title' => $example['title'],
                'description' => $example['description'] ?? null,
                'preview' => $example['preview'] ?? 'center',
                'view' => 'docs::examples.'.$page['slug'].'.'.$key,
                'source' => is_file($file) ? rtrim(file_get_contents($file))."\n" : '',
            ];
        })->values()->all();
    }

    public function exampleFile(string $slug, string $key): string
    {
        return $this->workbenchPath."/resources/views/examples/{$slug}/{$key}.blade.php";
    }

    /**
     * Props tables for every component on the page.
     *
     * @return array<int, array{tag: string, class: ?string, file: string, props: array<int, array<string, mixed>>}>
     */
    public function props(array $page): array
    {
        $extractor = new PropsExtractor($this->packagePath);

        return collect($page['components'])->map(function (string $tag) use ($extractor, $page) {
            $info = $extractor->extract($tag);
            $descriptions = Arr::get($page['props'], $tag, []);

            $info['props'] = array_map(fn ($prop) => $prop + [
                'description' => $descriptions[$prop['name']] ?? null,
            ], $info['props']);

            return $info;
        })->all();
    }

    /**
     * Which components `php artisan bladecn:install` copies into an app, read
     * from the installer's own lists so the docs stay in sync with it.
     *
     * @return array{views: array<int, string>, classes: array<int, string>}
     */
    public function publishedByInstaller(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $source = (string) @file_get_contents($this->packagePath.'/src/Commands/InstallBladeCNCommand.php');
        $views = [];
        $classes = [];

        foreach (['/\'ui\' => \[(.*?)\]/s', '/\$settingsComponents = \[(.*?)\]/s'] as $pattern) {
            if (preg_match($pattern, $source, $match)) {
                preg_match_all("/'([a-z0-9-]+)'/", $match[1], $names);
                $views = array_merge($views, $names[1]);
            }
        }

        if (preg_match('/\$essentialUiComponents = \[(.*?)\]/s', $source, $match)) {
            preg_match_all("/'([A-Za-z0-9]+)'/", $match[1], $names);
            $classes = $names[1];
        }

        return $cache = ['views' => array_values(array_unique($views)), 'classes' => $classes];
    }

    /**
     * Component tags on a page that the installer does not publish.
     *
     * @return array<int, string>
     */
    public function unpublished(array $page): array
    {
        $published = $this->publishedByInstaller();

        return array_values(array_filter($page['components'], function (string $tag) use ($published) {
            [$namespace, $name] = explode('.', $tag, 2);

            if ($namespace === 'icons') {
                return false;
            }

            if ($namespace !== 'ui' || ! in_array($name, $published['views'], true)) {
                return true;
            }

            $class = 'BladeCN\\BladeCN\\View\\Components\\Ui\\'.Str::studly($name);

            return class_exists($class) && ! in_array(Str::studly($name), $published['classes'], true);
        }));
    }

    /**
     * @return array<int, string>
     */
    public function icons(): array
    {
        $icons = array_map(
            fn ($file) => basename($file, '.blade.php'),
            glob($this->packagePath.'/resources/views/components/icons/*.blade.php') ?: []
        );
        sort($icons);

        return $icons;
    }

    public function markdown(?string $text): string
    {
        return $text ? (string) Str::markdown($text, ['html_input' => 'allow']) : '';
    }

    public function packagePath(string $path = ''): string
    {
        return $this->packagePath.($path ? '/'.ltrim($path, '/') : '');
    }
}
