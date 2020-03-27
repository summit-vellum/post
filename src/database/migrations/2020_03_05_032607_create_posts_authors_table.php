<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_authors', function(Blueprint $table){
    		$table->bigIncrements('id');
    		$table->integer('post_id');
    		$table->integer('author_id');
    		$table->integer('old_author_id')->default(0);
    		$table->string('custom_by_line', 500)->nullable();
    		$table->tinyInteger('status')->default(1);
    		$table->timestamps();
            $table->softDeletes();

            $table->index(['id']);
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_authors');
    }
}
