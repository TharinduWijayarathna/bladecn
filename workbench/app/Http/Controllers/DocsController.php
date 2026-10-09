<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Workbench\App\Docs\Docs;

class DocsController extends Controller
{
    public function __construct(protected Docs $docs) {}

    public function guide(string $slug = 'introduction')
    {
        $page = $this->docs->find($slug, 'guide') ?? abort(404);

        return view('docs::guide', ['docs' => $this->docs, 'page' => $page]);
    }

    public function component(string $slug)
    {
        $page = $this->docs->find($slug, 'component') ?? abort(404);

        return view('docs::component', [
            'docs' => $this->docs,
            'page' => $page,
            'examples' => $this->docs->examples($page),
            'propTables' => $this->docs->props($page),
        ]);
    }

    /**
     * The README / social-preview hero image source (see `npm run docs:hero`).
     */
    public function hero(string $theme)
    {
        return view('docs::hero', ['docs' => $this->docs, 'theme' => $theme]);
    }

    public function asset(string $file): BinaryFileResponse
    {
        $path = $this->docs->distPath($file);

        abort_unless(is_file($path), 404);

        $types = ['css' => 'text/css', 'js' => 'application/javascript', 'svg' => 'image/svg+xml', 'png' => 'image/png'];

        return response()->file($path, [
            'Content-Type' => $types[pathinfo($path, PATHINFO_EXTENSION)] ?? 'application/octet-stream',
        ]);
    }
}
