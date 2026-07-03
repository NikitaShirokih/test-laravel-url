<?php

use App\Http\Controllers\ShortCodeRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/{code}', ShortCodeRedirectController::class)
    ->name('short-links.redirect');
