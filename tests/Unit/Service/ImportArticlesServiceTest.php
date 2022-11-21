<?php

namespace Tests\Feature\Service;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use App\Services\Api\ImportAllArticles;
use App\Services\Api\ImportNewArticles;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\NotFoundNewArticlesApiResponseException;
use GuzzleHttp\Psr7\Request;

class ImportArticlesServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_import_new_articles_with_mock()
    {
        $article_mock = Article::factory()->make();
        $article_mock->id = 99999;

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response([$article_mock->toArray()], 200)
        ]);

        (new ImportNewArticles)->execute();

        $this->assertModelExists($article_mock);
    }

    public function test_if_return_null_when_dont_receive_sucessully_response_from_api()
    {

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response(['Error' => 'error'], 404)
        ]);

        $this->assertNull((new ImportNewArticles)->execute());
    }

    // public function test_if_return_null_when_receive_a_exception_from_import_service()
    // {
    //     $url = 'http://non-existing-third-party.com/endpoint';
    //     $guzzleRequest = new Request('get', $url);

    //     Http::fake([
    //        $url =>  fn (Request $request) => new RejectedPromise(new ConnectException('Connection error', $guzzleRequest)),
    //        env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response([], 200)
    //     ]);

    //     $this->assertNull((new ImportNewArticles)->execute());
    // }

    public function test_if_import_all_articles_with_mock()
    {
        $mock_ids = range(1, 100);

        $articles_mock = Article::factory()
            ->count(100)
            ->make();


        //add random ids to mocks articles
        for ($i = 0; $i < 100; $i++) {
            $index = array_rand($mock_ids);
            $articles_mock[$i]->id = $mock_ids[$index];
            unset($mock_ids[$index]);
        }

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles/count' => Http::response(100, 200)
        ]);

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles?_sort=id&_start=0&_limit=1000' => Http::response($articles_mock->toArray(), 200)
        ]);

        (new ImportAllArticles)->execute();

        $this->assertDatabaseCount('articles', 100);
    }
}
