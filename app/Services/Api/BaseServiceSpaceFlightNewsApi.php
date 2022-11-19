<?php

namespace App\Services\Api;

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
                Notification::route('mail', env('APP_ADMIN_MAIL'))
                    ->notify(new ImportNewArticlesFailNotification('Erro do servidor - ' . $response->body()));
            }
        } catch (Exception $e) {
            Notification::route('mail', env('APP_ADMIN_MAIL'))
                ->notify(new ImportNewArticlesFailNotification($e->getMessage()));
        }

        return null;
    }
}
