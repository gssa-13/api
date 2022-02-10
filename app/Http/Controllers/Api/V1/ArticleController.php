<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ArticleCollection;
use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index(): ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }
}
