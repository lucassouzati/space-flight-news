<?php

namespace App\Services\Api;

use Exception;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;

class ImportNewArticles
{

    public function __invoke()
    {
        try {
            $response = Http::get('https://api.spaceflightnewsapi.net/v3/article');

            if ($response->successful()) {
                foreach ($response->json() as $data) {
                    Article::updateOrCreate(['id' => $data['id']], $data);
                }
            } else {
                Notification::route('mail', env('APP_ADMIN_MAIL'))
                    ->notify(new ImportNewArticlesFailNotification('Erro do servidor - '. $response->body()));
            }
        } catch (Exception $e) {
            Notification::route('mail', env('APP_ADMIN_MAIL'))
                    ->notify(new ImportNewArticlesFailNotification($e->getMessage()));
        }
    }
}
