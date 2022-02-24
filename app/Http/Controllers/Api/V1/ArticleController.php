<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests\V1\SaveArticleRequest;

use App\Http\Resources\V1\ArticleCollection;
use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{

    public function index(): ArticleCollection
    {
        $articles = Article::allowedSorts(array('title', 'content'));

        return ArticleCollection::make(
            $articles->paginate(
                $perPage = request('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends( request()->only('sort','page.size') )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param SaveArticleRequest $request
     * @return ArticleResource
     */
    public function store(SaveArticleRequest $request): ArticleResource
    {
        $article = Article::create($request->validated());
        return ArticleResource::make($article);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveArticleRequest $request
     * @param Article $article
     * @return ArticleResource
     */
    public function update(SaveArticleRequest $request, Article $article): ArticleResource
    {
        $article->update($request->validated());

        return ArticleResource::make($article);
    }

    /**
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article): Response
    {
        $article->delete();
        return response()->noContent();
    }
}
