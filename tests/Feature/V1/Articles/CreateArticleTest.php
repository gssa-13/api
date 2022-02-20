<?php

namespace Tests\Feature\V1\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {
        $response = $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Article content'
        ])->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show' , $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    'content' => 'Article content'
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }

    /** @test */
    public function title_is_required()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'slug' => 'new-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New',
            'slug' => 'new-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article'
        ])->assertJsonApiValidationErrors('content');
    }
}
