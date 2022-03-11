<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

use App\Http\Requests\V1\SaveArticleRequest;

use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{

    public function index(): JsonResource
    {
        $articles = Article::query()
            ->allowedIncludes(['category', 'author'])
            ->allowedFilters(['title', 'content', 'month', 'year', 'categories'])
            ->allowedSorts(['title', 'content'])
            ->sparseFieldset()
            ->jsonPaginate();

        return ArticleResource::collection( $articles );
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
    public function show($article): JsonResource
    {
        $article = Article::where('slug', $article)
            ->allowedIncludes(['category', 'author'])
            ->sparseFieldset()
            ->firstOrFail();

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
