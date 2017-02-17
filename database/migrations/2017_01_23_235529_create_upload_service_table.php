<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->string('bundle');
            $table->integer('user_entity_id')->nullable();
            $table->integer('vk_user_id');
            $table->integer('post_entity_id')->nullable();
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
        Schema::dropIfExists('upload_service');
    }
}
