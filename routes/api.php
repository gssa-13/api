<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\AuthorController;

Route::apiResource('articles', ArticleController::class);

Route::apiResource('categories', CategoryController::class)
    ->only('index', 'show');

Route::apiResource('authors', AuthorController::class)
    ->only('index', 'show');

Route::get('articles/{article}/relationships/category', fn() => 'FOO')
    ->name('articles.relationships.category');

Route::get('articles/{article}/category', fn() => 'FOO')
    ->name('articles.category');

