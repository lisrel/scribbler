<?php

use App\Http\Controllers\NotesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('notes', NotesController::class);
    Route::get('/data/notes', [NotesController::class, 'getData'])->name('notes.data');
});


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
