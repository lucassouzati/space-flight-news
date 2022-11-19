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
                        Article::create([
                            'id' => $data['id'],
                            'featured' => $data['featured'],
                            'title' => $data['title'],
                            'url' => $data['url'],
                            'imageUrl' => $data['imageUrl'],
                            'newsSite' => $data['newsSite'],
                            'summary' => $data['summary'],
                            'publishedAt' => $data['publishedAt'],
                        ]);
                    }
                }
                $i += $limitPerQuery;
            } while ($i <= $countArticles);
        }
    }
}
