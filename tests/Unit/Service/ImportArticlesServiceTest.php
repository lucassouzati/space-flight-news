<?php

namespace Tests\Feature\Service;

use App\Exceptions\InvalidArticleDataException;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Launch;
use App\Models\Article;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use App\Services\Api\ImportAllArticles;
use App\Services\Api\ImportNewArticles;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\NotFoundNewArticlesApiResponseException;
use Exception;

class ImportArticlesServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_import_new_articles_with_mock()
    {
        $article_mock = Article::factory()
            ->make();
        $article_mock->id = 99999;

        $array_data = $article_mock->toArray();
        $array_data['events'] = Event::factory()->count(1)->make()->toArray();
        $array_data['launches'] = Launch::factory()->count(1)->make()->toArray();

        // dd($array_data);

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response([$array_data], 200)
        ]);

        (new ImportNewArticles)->execute();

        $this->assertDatabaseHas('articles', [
            'api_id' => $article_mock->id
        ]);
    }

    public function test_if_return_null_when_dont_receive_sucessully_response_from_api()
    {
        $this->expectException(NotFoundNewArticlesApiResponseException::class);
        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response(['Error' => 'error'], 404)
        ]);

        // $this->assertNull((new ImportNewArticles)->execute());
        (new ImportNewArticles)->execute();
    }

    // public function test_if_return_null_when_receive_a_exception_from_import_service()
    // {
    //     $this->expectException(Exception::class);

    //     $url = 'http://non-existing-third-party.com/endpoint';
    //     $guzzleRequest = new Request('get', $url);

    //     // Http::fake([
    //     //    $url =>  fn (Request $request) => new RejectedPromise(new ConnectException('Connection error', $guzzleRequest)),
    //     //    env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response([], 200)
    //     // ]);

    //     (new ImportNewArticles)->execute();
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

    public function test_if_throw_a_exception_if_imports_a_articles_with_invalid_data()
    {
        $this->expectException(InvalidArticleDataException::class);

        $array_data = [['invalid_data' => 'invalid_data']];

        Http::fake([
            env('SPACE_FLIGHT_NEWS_API_BASE_URL') . 'articles' => Http::response([$array_data], 200)
        ]);

        (new ImportNewArticles)->execute();

    }
}
