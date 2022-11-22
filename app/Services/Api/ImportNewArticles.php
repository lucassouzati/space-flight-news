<?php

namespace App\Services\Api;


use App\Models\Event;
use App\Models\Launch;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Services\Api\BaseServiceSpaceFlightNewsApi;
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
                if ($this->validate($data)) {
                    $article = Article::updateOrCreate(['api_id' => $data['id']], [
                        'api_id' => $data['id'],
                        'featured' => $data['featured'],
                        'title' => $data['title'],
                        'url' => $data['url'],
                        'imageUrl' => $data['imageUrl'],
                        'newsSite' => $data['newsSite'],
                        'summary' => $data['summary'],
                        'publishedAt' => $data['publishedAt'],
                    ]);

                    if (!empty($data['launches'])) {
                        foreach ($data['launches'] as $launch_data) {
                            $article->launches()->attach(Launch::updateOrCreate(['id' => $launch_data['id']], $launch_data)->id);
                        }
                    }
                    if (!empty($data['events'])) {
                        foreach ($data['events'] as $event_data) {
                            $article->events()->attach(Event::updateOrCreate(['id' => $event_data['id']], $event_data)->id);
                        }
                    }
                }
            }
            return true;
        }

        return null;
    }
}
