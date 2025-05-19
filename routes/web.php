<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

Route::get('/', function () {
    return view('welcome');
});

Route::patch('/books/{book}/toggle-borrowed', [BookController::class, 'toggleBorrowed'])->name('books.toggleBorrowed');
Route::post('books/{book}/toggle', [BookController::class, 'toggleBorrowed'])->name('books.toggle');

Route::resource('books', BookController::class);
Route::resource('authors', AuthorController::class);
