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
            $table->string('title', 255);
            $table->text('headline')->nullable();
            $table->string('slug', 255);
            $table->longText('content');
            $table->text('blurb');
            $table->text('summary')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('image_id')->nullable()->default(0)->comment('main image id; id is from image library');
            $table->integer('section_id');
            // 'section_id'
            // 'section'

            //multiple relationship; pivot table can be created
            // 'author_id'
            // 'author'
            // tags
            // Ask if serialized yun section id and section per post row
            // Prob: what if multiple authors
            // section: parent with child
            $table->integer('video_id')->nullable()->default(0);
            $table->text('video_caption')->nullable();
            $table->integer('gallery_id')->nullable()->default(0);
            $table->tinyInteger('show_image');
            $table->string('thumb_image', 255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Draft, 1=Publish, 2=Disable');
            $table->string('media_type', 10)->default('Article')->comment('Article, Gallery, Poll, Video');
            $table->integer('media_id')->default(0);
            $table->float('contributor_fee')->nullable();
            $table->string('editor', 50)->nullable();
            $table->integer('show_author')->default(1);
            $table->integer('allow_comments')->default(1);
            $table->integer('is_published')->default(0)->comment('1=published, 0=never published');
            $table->integer('is_instant')->default(0);
            $table->integer('is_news')->default(0);
            $table->integer('newsroom_id')->default(0);
            $table->mediumText('syndicated_from');
            //$table->integer('autosave_id');
            $table->integer('locked_by')->default(0)->comment('uam user id');
			$table->integer('lock_timestamp')->default(0);

			$table->tinyInteger('longform')->nullable();
			$table->tinyInteger('is_nsfw')->nullable();
			$table->tinyInteger('is_archived')->nullable();
			$table->integer('fixed')->default(0);
			$table->integer('is_pushed_notif');
			$table->integer('is_no_index');
			$table->string('seo_topic', 255)->nullable();
			$table->string('seo_keyword', 255)->nullable();
			$table->integer('total_seo_score')->default(0);
			$table->mediumInteger('search_volume')->nullable();
			$table->tinyInteger('enable_syndicate')->default(0);
            $table->integer('syndicate_original_date_published')->nullable();
            $table->integer('original_date_published')->default(0);
            $table->integer('date_published')->default(0);
            $table->integer('date_modified')->default(0);
            $table->integer('date_created')->default(0);

            //$table->uuid('uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //update this index
            $table->index(['slug', 'status', 'date_published', 'is_published']);
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
