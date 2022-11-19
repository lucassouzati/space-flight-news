<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\Api\ImportAllArticles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $import_all_articles = new ImportAllArticles();
        $import_all_articles->execute();
    }
}
