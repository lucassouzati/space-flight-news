<?php

namespace App\Services\Api;

use App\Models\Article;
use Illuminate\Support\Facades\Http;

class ImportNewArticles {

    public function __invoke()
    {
        $response = Http::get('https://api.spaceflightnewsapi.net/v3/articles');

        if($response->successful())
        {
            foreach ($response->json() as $data){
                Article::updateOrCreate(['id' => $data['id']], $data);
            }
        }
    }

}
