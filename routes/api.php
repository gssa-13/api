<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleCategoryController;
use App\Http\Controllers\Api\V1\ArticleAuthorController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthorController;

Route::apiResource('articles', ArticleController::class);

Route::apiResource('categories', CategoryController::class)
    ->only('index', 'show');

Route::apiResource('authors', AuthorController::class)
    ->only('index', 'show');

Route::get('articles/{article}/relationships/category', [
    ArticleCategoryController::class, 'index'
])->name('articles.relationships.category');

Route::get('articles/{article}/category', [
    ArticleCategoryController::class, 'show'
])->name('articles.category');

Route::get('articles/{article}/relationships/author', [
    ArticleAuthorController::class, 'index'
])->name('articles.relationships.author');

Route::get('articles/{article}/author', [
    ArticleAuthorController::class, 'show'
])->name('articles.author');
