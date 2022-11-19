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
                Article::updateOrCreate(['id' => $data['id']], $data);
            }
        }
    }
}
