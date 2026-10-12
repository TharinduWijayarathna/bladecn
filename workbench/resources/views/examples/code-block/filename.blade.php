@php
$code = <<<'PHP'
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
PHP;
@endphp

<x-ui.code-block :code="$code" language="php" filename="routes/web.php" line-numbers class="max-w-xl" />
