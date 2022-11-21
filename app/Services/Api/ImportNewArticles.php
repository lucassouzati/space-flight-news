<?php

namespace App\Services\Api;


use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;

class ImportNewArticles extends BaseServiceSpaceFlightNewsApi
{
    public function __invoke()
    {
        $this->execute();
    }

    public function execute()
    {
        $result = $this->getJsonResult('articles');
        if ($result != null) {
            foreach ($result as $data) {
                $article = Article::updateOrCreate(['id' => $data['id']], $data);
                if (!empty($data['launches']))
                    $article->launches()->upsert($data['launches'], ['id'], ['provider']);
                if (!empty($data['events']))
                    $article->events()->upsert($data['events'], ['id'], ['provider']);
            }
            return true;
        }

        return null;
    }
}
