<?php

namespace App\Services\Api;

use App\Exceptions\NotFoundNewArticlesApiResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;
use Exception;

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
        }
        catch (NotFoundNewArticlesApiResponseException $e)
        {
            throw $e;
        }
        catch (Exception $e) {
            Notification::route('mail', env('APP_ADMIN_MAIL'))
                ->notify(new ImportNewArticlesFailNotification($e->getMessage()));
            throw $e;
        }
    }
}
