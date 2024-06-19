<?php

use App\Http\Controllers\FeedsController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/article/{id}/read', function ($id) {
    $article = Article::findOrFail($id);
    $article->is_read = true;
    $article->save();

    return redirect($article->link);
})->name('articles.read');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('feeds', FeedsController::class)->only(['index', 'show', 'destroy']);
    Route::get('feeds/{feed}/fetch', [FeedsController::class, 'fetch'])->name('feeds.fetch');
});
