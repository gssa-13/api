<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Article $article)
    {
        return CategoryResource::identifier( $article->category);
    }

    /**
     *  Display the specified resource.
     * @param Article $article
     * @return CategoryResource
     */
    public function show(Article $article)
    {
        return CategoryResource::make($article->category);
    }
}
