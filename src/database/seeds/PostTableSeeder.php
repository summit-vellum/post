<?php

use Illuminate\Database\Seeder;
use Quill\Post\Models\Post;

class PostTableSeeder extends Seeder
{
   	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$old_db = DB::connection('olddb');

    	$itemsPerBatch = 500;

    	$articles = $old_db->table('tbl_articles');

    	$this->command->getOutput()->progressStart($articles->count());

    	$vellumArticles = $articles->orderBy('article_id')->chunk($itemsPerBatch, function($articles){

    		foreach ($articles as $article) {

    			$migratedPost = new Post;
    			$migratedPost->create([
    				'id' => $article->article_id,
    				'title' => $article->article_title,
    				'headline'=> $article->article_headline,
    				'slug'=> $article->article_slug,
    				'content'=> $article->article_content,
    				'blurb'=> $article->article_blurb,
    				'summary'=> $article->article_summary,
    				'image'=> $article->article_image,
    				'image_id'=> $article->article_image_id,
    				'video_id'=> $article->article_video_id,
    				'video_caption'=> $article->article_video_caption,
    				'gallery_id'=> $article->article_gallery_id,
    				'show_image'=> $article->article_show_image,
    				'thumb_image'=> $article->article_thumb_image,
    				'status'=> $article->article_status,
    				'media_type'=> $article->media_type,
    				'media_id'=> $article->media_id,
    				'contributor_fee'=> $article->article_contributor_fee,
    				'editor'=> $article->article_editor,
    				'show_author'=> $article->article_show_author,
    				'allow_comments'=> $article->article_allow_comments,
    				'is_published'=> $article->article_is_published,
    				'is_instant'=> $article->article_is_instant,
    				'is_news'=> $article->article_is_news,
    				'newsroom_id'=> $article->article_newsroom_id,
    				'syndicated_from'=> $article->article_syndicated_from,
    				'autosave_id'=> $article->article_autosave_id,
    				'locked_by'=> $article->article_locked_by,
    				'lock_timestamp'=> $article->article_lock_timestamp,
    				'longform'=> $article->article_longform,
    				'is_nsfw'=> $article->is_nsfw,
    				'is_archived'=> $article->is_archived,
    				'fixed'=> $article->fixed,
    				'is_pushed_notif'=> $article->article_pushed_notif,
    				'is_no_index'=> $article->article_is_no_index,
    				'seo_topic'=> $article->seo_topic,
    				'seo_keyword'=> $article->seo_keyword,
    				'total_seo_score'=> $article->total_seo_score,
    				'search_volume'=> $article->search_volume,
    				'enable_syndicate'=> $article->enable_syndicate,
    				'syndicate_original_date_published'=> $article->original_publish_date,
    				'original_date_published'=> $article->article_original_date_published,
    				'date_published'=> $article->article_date_published,
    				'date_modified'=> $article->article_date_modified,
    				'date_created'=> $article->article_date_created,
    				'created_at'=> date('Y-m-d H:m:i', $article->article_original_date_published),
    				'updated_at'=> date('Y-m-d H:m:i', $article->article_date_modified)
    			]);

    			$this->command->getOutput()->progressAdvance();

    		}

    	});

    	$this->command->getOutput()->progressFinish();
    }

}
