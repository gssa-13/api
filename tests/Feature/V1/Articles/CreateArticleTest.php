<?php

namespace Tests\Feature\V1\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Article;
use App\Models\Category;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {
        $category = Category::factory()->create();

        $response = $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Article content',
            '_relationships' => [
                'category' => $category
            ]
        ])->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show' , $article)
        );

        $response->assertJsonApiResource($article, [
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Article content',
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
    public function slug_must_be_unique()
    {
        $article = Article::factory()->create();

        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => $article->slug,
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => '$%^&',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'with_underscore',
            'content' => 'Article content'
        ])->assertSee( __('validation.no_underscore') )
            ->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_start_with_dashes()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => '-start-with-dash',
            'content' => 'Article content'
        ])->assertSee( __('validation.no_starting_dashes') )
            ->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_end_with_dashes()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'end-with-dash-',
            'content' => 'Article content'
        ])->assertSee( __('validation.no_ends_with_dash') )
            ->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article'
        ])->assertJsonApiValidationErrors('content');
    }

    /** @test */
    public function category_relationship_is_required()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Article Content'
        ])->assertJsonApiValidationErrors('relationships.category');
    }

    /** @test */
    public function category_must_exist_in_database()
    {
        $this->postJson( route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Article Content',
            '_relationships' => [
                'category' => Category::factory()->make()
            ]
        ])->assertJsonApiValidationErrors('relationships.category');
    }
}
