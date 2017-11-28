<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHasTagsTables extends Migration
{
    public function up()
    {
        Schema::create('has_tags_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable()->index();
            $table->string('slug')->index();
            $table->string('name');
            $table->json('metadata')->nullable();
            $table->integer('count');
            $table->timestamps();

            $table->unique(['type','slug']);
        });

        Schema::create('has_tags_taggables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned();
            $table->morphs('taggable');

            $table->foreign('tag_id')->references('id')->on('has_tags_tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('has_tags_taggables');
        Schema::drop('has_tags_tags');
    }
}
