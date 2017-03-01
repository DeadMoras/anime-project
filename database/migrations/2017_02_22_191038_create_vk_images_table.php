<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vk_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('height');
            $table->integer('width');
            $table->string('album_link');
            $table->integer('author_vk_id');
            $table->string('src');
            $table->string('src_big');
            $table->string('bundle');
            $table->string('src_small');
            $table->string('src_xbig');
            $table->string('src_xxbig');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vk_images');
    }
}
