<?php

namespace App\Services\Api;

use App\Models\Article;
use Illuminate\Support\Facades\Http;

class ImportAllArticles
{

    public function execute()
    {
        $response = Http::get('https://api.spaceflightnewsapi.net/v3/articles/count');

        if ($response->successful()) {
            $count_articles = $response->json();

            // $loops = ceil($count_articles / 1000);

            $i = 0;
            do {
                $response_articles = Http::get('https://api.spaceflightnewsapi.net/v3/articles?_sort=id&_start=' . $i . '&_limit=1000');
                if ($response_articles->successful()) {
                    foreach ($response_articles->json() as $data) {
                        // dd($data['id']);
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
                $i += 1000;
            } while ( $i <= $count_articles);
        }
    }
}
