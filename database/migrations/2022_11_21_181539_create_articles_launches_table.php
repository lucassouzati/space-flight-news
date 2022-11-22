<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_launches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('article_id');
            $table->foreign('article_id')->references('id')->on('articles');

            $table->foreignUuid('launch_id');
            $table->foreign('launch_id')->references('id')->on('launches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_launches');
    }
};
