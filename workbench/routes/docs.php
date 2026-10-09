<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\DocsController;

Route::prefix(config('docs.prefix'))->name('docs.')->group(function () {
    Route::get('/', [DocsController::class, 'guide'])->name('home');
    Route::get('assets/{file}', [DocsController::class, 'asset'])->where('file', '[A-Za-z0-9._-]+')->name('asset');
    Route::get('components/{slug}', [DocsController::class, 'component'])->where('slug', '[a-z0-9-]+')->name('component');
    Route::get('{slug}', [DocsController::class, 'guide'])->where('slug', '[a-z0-9-]+')->name('guide');
});
