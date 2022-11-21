<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider listArticlesFields
     */
    public function test_if_can_list_articles($data, $results)
    {
        $response = $this->getJson(
            route('articles.index', $data),

        );
        $response->assertStatus($results);
    }


    public function listArticlesFields()
    {
        return
            [
                'withoutPaginate' =>
                [
                    [''],
                    200
                ],
                'withPaginate' =>
                [
                    ['paginate' => 300],
                    200
                ]
            ];
    }

    public function test_if_can_store_article()
    {
        $response = $this->postJson('articles', Article::factory()->make()->toArray());

        $response->assertCreated();
    }

    public function test_if_can_update_article()
    {
        $article = Article::factory()->create();
        $response = $this->putJson('articles/' . $article->id, Article::factory()->make()->toArray());

        $response->assertStatus(200);
    }

    public function test_if_can_show_article()
    {
        $article = Article::factory()->create();
        $response = $this->getJson('articles/' . $article->id);
        $response->assertStatus(200);
    }

    public function test_if_can_delete_article()
    {
        $article = Article::factory()->create();
        $response = $this->deleteJson('articles/' . $article->id);
        $response->assertStatus(204);
    }

    public function test_if_see_message_root_route()
    {
        $response = $this->get('/');
        $response->assertJson(['message' => 'Back-end Challenge 2021 🏅 - Space Flight News']);
    }
}
