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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('api_id')->nullable();//ID from api
            $table->boolean('featured')->defalt(0);
            $table->string('title');
            $table->string('url');
            $table->string('imageUrl');
            $table->string('newsSite')->nullable();
            $table->longText('summary')->nullable();
            $table->string('publishedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
