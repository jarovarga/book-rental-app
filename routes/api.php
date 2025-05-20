<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;

// Book routes
Route::resource('books', BookController::class)->except(['create', 'edit']);
Route::patch('books/{id}/toggle-borrowed', [BookController::class, 'toggleBorrowed']);

// Author routes
Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
