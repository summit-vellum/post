<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('author_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('type', 20)->nullable();
            $table->integer('status_id')->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('image')->nullable();
            $table->text('blurb')->nullable();
            $table->text('thumbnail')->nullable();
            $table->uuid('uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['section_id', 'published_at', 'type', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
