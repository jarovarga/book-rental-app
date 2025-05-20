<?php

use Illuminate\Support\Facades\Route;

// All routes are handled by the Vue.js app
Route::get('/{any?}', function () {
    return view('vue');
})->where('any', '.*');
