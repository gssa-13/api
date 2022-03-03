<?php

namespace Tests\Feature\V1\Articles;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        Article::factory()->create([
            'title' => 'Learn Laravel from scratch'
        ]);

        Article::factory()->create([
            'title' => 'Other Article'
        ]);

        // articles?filter[title]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'title' => 'Laravel'
            ]
        ]);

//        dd( urldecode( $url ) );
        $this->getJson($url)->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from scratch')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        Article::factory()->create([
            'content' => 'Learn Laravel from scratch'
        ]);

        Article::factory()->create([
            'content' => 'Other Article'
        ]);

        // articles?filter[content]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'content' => 'Laravel'
            ]
        ]);

//        dd( urldecode( $url ) );
        $this->getJson($url)->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from scratch')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021)
        ]);

        Article::factory()->create([
            'title' => 'Article from 2022',
            'created_at' => now()->year(2022)
        ]);

        // articles?filter[year]=2021

        $url = route('api.v1.articles.index', [
            'filter' => [
                'year' => '2021'
            ]
        ]);

        $this->getJson($url)->assertJsonCount(1, 'data')
            ->assertSee('Article from 2021')
            ->assertDontSee('Article from 2022');
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        Article::factory()->create([
            'title' => 'Article from 3',
            'created_at' => now()->month(3)
        ]);

        Article::factory()->create([
            'title' => 'Another Article from 3',
            'created_at' => now()->month(3)
        ]);

        Article::factory()->create([
            'title' => 'Article from 1',
            'created_at' => now()->month(1)
        ]);

        // articles?filter[month]=3

        $url = route('api.v1.articles.index', [
            'filter' => [
                'month' => '3'
            ]
        ]);

        $this->getJson($url)->assertJsonCount(2, 'data')
            ->assertSee([
                'Article from 3',
                'Another Article from 3'
            ])
            ->assertDontSee('Article from 1');
    }

    /** @test */
    public function can_filter_articles_by_category()
    {
        Article::factory()->count(3)->create();
        $cat1 = Category::factory()->hasArticles(3)->create([ 'slug' => 'cat-1' ]);
        $cat2 = Category::factory()->hasArticles()->create([ 'slug' => 'cat-2' ]);

        // articles?filter[categories]=cat-1

        $url = route('api.v1.articles.index', [
            'filter' => [
                'categories' => 'cat-1,cat-2'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(4, 'data')
            ->assertSee($cat1->articles[0]->title)
            ->assertSee($cat1->articles[1]->title)
            ->assertSee($cat1->articles[2]->title)
            ->assertSee($cat2->articles[0]->title);
    }

    /** @test */
    public function can_filter_articles_by_unknown_filters()
    {
        Article::factory()->count(3)->create();

        // articles?filter[unknown]=filter

        $url = route('api.v1.articles.index', [
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);

        $this->getJson($url)->assertStatus(400);
    }

}
