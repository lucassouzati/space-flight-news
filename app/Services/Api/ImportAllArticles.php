<?php

namespace App\Services\Api;

use App\Models\Event;
use App\Models\Launch;
use App\Models\Article;
use App\Services\Api\BaseServiceSpaceFlightNewsApi;

class ImportAllArticles extends BaseServiceSpaceFlightNewsApi
{

    public function execute()
    {
        $result = $this->getJsonResult('articles/count');

        if ($result != null) {
            $countArticles = $result;
            $limitPerQuery = 1000;

            $i = 0;
            do {
                $result_articles = $this->getJsonResult('articles?_sort=id&_start=' . $i . '&_limit=' . $limitPerQuery);
                if ($result_articles != null) {
                    foreach ($result_articles as $data) {
                        if ($this->validate($data)) {
                            $article = Article::create([
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
                }
                $i += $limitPerQuery;
            } while ($i <= $countArticles);
        }
    }
}
