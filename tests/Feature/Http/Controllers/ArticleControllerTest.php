<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_store_article()
    {
        $response = $this->postJson('/api/articles', Article::factory()->make()->toArray());

        $response->assertCreated();
    }

    public function test_update_article()
    {
        $article = Article::factory()->create();
        $response = $this->putJson('/api/articles/'.$article->id, Article::factory()->make()->toArray());

        $response->assertStatus(200);
    }

    public function test_show_article()
    {
        $article = Article::factory()->create();
        $response = $this->getJson('api/articles/'.$article->id);
        $response->assertStatus(200);
    }

    public function test_delete_article()
    {
        $article = Article::factory()->create();
        $response = $this->deleteJson('api/articles/'.$article->id);
        $response->assertStatus(204);
    }
}
