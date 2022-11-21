<?php

namespace App\Services\Api;

use App\Models\Article;

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
                        $article = Article::create([
                            'id' => $data['id'],
                            'featured' => $data['featured'],
                            'title' => $data['title'],
                            'url' => $data['url'],
                            'imageUrl' => $data['imageUrl'],
                            'newsSite' => $data['newsSite'],
                            'summary' => $data['summary'],
                            'publishedAt' => $data['publishedAt'],
                        ]);
                        if (!empty($data['launches']))
                            $article->launches()->upsert($data['launches'], ['id'], ['provider']);
                        if (!empty($data['events']))
                            $article->events()->upsert($data['events'], ['id'], ['provider']);
                    }
                }
                $i += $limitPerQuery;
            } while ($i <= $countArticles);
        }
    }
}
