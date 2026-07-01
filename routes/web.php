<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectShortLinkController;
use App\Http\Controllers\ShortLinkController;
use App\Models\ShortLink;
use App\Security\Enums\ShortLinkAbility;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('/links', [ShortLinkController::class, 'index'])
        ->middleware(ShortLinkAbility::ViewAny->middleware(ShortLink::class))
        ->name('links.index');

    Route::get('/links/create', [ShortLinkController::class, 'create'])
        ->middleware(ShortLinkAbility::Create->middleware(ShortLink::class))
        ->name('links.create');

    Route::post('/links', [ShortLinkController::class, 'store'])
        ->middleware(ShortLinkAbility::Create->middleware(ShortLink::class))
        ->name('links.store');

    Route::get('/links/{link}', [ShortLinkController::class, 'show'])
        ->middleware(ShortLinkAbility::View->middleware('link'))
        ->name('links.show');

    Route::delete('/links/{link}', [ShortLinkController::class, 'destroy'])
        ->middleware(ShortLinkAbility::Delete->middleware('link'))
        ->name('links.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{code}', RedirectShortLinkController::class)
    ->name('short-links.redirect');
