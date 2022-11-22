<?php

namespace App\Services\Api;

use App\Exceptions\InvalidArticleDataException;
use App\Exceptions\NotFoundNewArticlesApiResponseException;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;
use Exception;
use Illuminate\Support\Facades\Validator;

abstract class BaseServiceSpaceFlightNewsApi
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('SPACE_FLIGHT_NEWS_API_BASE_URL');
    }

    public function getJsonResult(string $endpoint): mixed
    {
        try {
            $response = Http::get($this->base_url . $endpoint);
            if ($response->successful())
                return $response->json();
            else {
                throw new NotFoundNewArticlesApiResponseException($response->body());
            }
        } catch (NotFoundNewArticlesApiResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            Notification::route('mail', env('APP_ADMIN_MAIL'))
                ->notify(new ImportNewArticlesFailNotification($e->getMessage()));
            throw $e;
        }
    }

    public function validate(array $values)
    {
        $validator = Validator::make($values, Article::$rules);

        try {
            if ($validator->fails()) {
                throw new InvalidArticleDataException($values, $validator->errors()->all());
            }
            return true;
        } catch (InvalidArticleDataException $e) {
            throw $e;
        }
    }
}
