<?php

namespace Quill\Post\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Quill\Post\Models\Post;
use Illuminate\Support\Arr;
use Quill\Tag\Models\Tag;
use Illuminate\Support\Str;
use Request;
use Quill\Status\Http\Helpers\StatusHelper as Status;

class PostSaved
{
    // use Dispatchable, InteractsWithSockets,
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param  \Quill\Post\Models\Post  $data
     * @return void
     */
    public function __construct(Post $data)
    {
    	if ($data->status != Status::DISABLE) {
    		$visibleTags = $this->upsertTags($data, 'visible_tags');
	    	$invisibleTags = $this->upsertTags($data, 'invisible_tags', 0);
	    	$data->tags()->detach();
	    	$data->tags()->sync(array_merge($visibleTags, $invisibleTags));

	    	$this->upsertSeoScoreBreakdown($data);
	    	$this->createAuthors($data);
	    	$this->createMeta($data);
    	}

        $this->data = $data;
    }

    public function upsertSeoScoreBreakdown($post)
    {
    	$seoScoreBreakdown = Request::input('seo_score_breakdown');

    	$post->seoScore()->updateOrCreate(
            ['post_id' => $post->id], [
            	'post_id' => $post->id,
                'score_breakdown' => serialize(json_decode($seoScoreBreakdown, true)),
            ]
        );
    }

    public static function createAuthors($post)
    {
    	$authors = Request::input('authors');
    	$authors = json_decode($authors, true);

    	$customBylineAuthors = Request::input('custom_byline_author');
    	$customBylines = Request::input('custom_byline');

    	if (!empty($customBylineAuthors)) {
    		foreach ($customBylineAuthors as $key => $customBylineAuthor) {
    			if ($customBylineAuthor != '') {
                    $customBylineAuthor = json_decode($customBylineAuthor, true);
                    $customBylineAuthors[$key] = [
                        'custom_by_line' => $customBylines[$key],
                        'author_id' => Arr::get(head($customBylineAuthor), 'id')
                    ];
                }
    		}
    		$customBylineAuthors = array_filter($customBylineAuthors);
            $customBylineAuthors = array_map(function($item){ return array_filter($item); }, $customBylineAuthors);
            $customBylineAuthors = array_filter($customBylineAuthors);
    	}

		$post->authors()->forceDelete();

		if (!empty($authors)) {
			$authorIds = Arr::pluck($authors, 'id');
			foreach ($authorIds as $key => $authorId) {
				$author = [
					'post_id' => $post->id,
	                'author_id' => $authorId,
	                'status' => 1
				];
				$post->authors()->create($author);
			}
		}

		if (!empty($customBylineAuthors)) {
            foreach ($customBylineAuthors as $author) {
            		$cbAuthor = [
	                    'article_id'     => $post->id,
	                    'author_id'      => $author['author_id'],
	                    'custom_by_line' => $author['custom_by_line'],
	                    'status'         => 1
	                ];
					$post->authors()->create($cbAuthor);
            }
        }
    }

    public static function createMeta($post)
    {
    	$post->meta()->forceDelete();
    	$metaRequest = Request::only('meta_title', 'meta_description', 'meta_canonical');

    	$meta = [
    		'title' => $metaRequest['meta_title'],
    		'slug' => Str::slug($metaRequest['meta_title']),
    		'description' => $metaRequest['meta_description'],
    		'canonical' => $metaRequest['meta_canonical'],
    		'post_id' => $post->id,
    		'post_type' => 'Article',
    	];

    	$post->meta()->create($meta);
    }

    public static function upsertTags($post, $tagName, $visibility = 1)
    {
    	$tags = Request::only($tagName);
    	$tags = explode(',', $tags[$tagName]);
    	$tagsList = [];

    	foreach ($tags as $tag) {
    		$tag = trim($tag);

            if (!empty($tag) && !is_array($tag)) {

            	$existingTag = Tag::whereName($tag)->whereIsVisible($visibility)->first();

            	if ($existingTag) {
            		$tagId = $existingTag->id;
            	} else {
            		$slug = seoUrl($tag, '-');

                    if (!$visibility) {
                        $slug = 'inv-'.$slug;
                    }

            		$newTag = new Tag([
        				'name' => $tag,
        				'slug' => $slug,
        				'is_visible' => $visibility
            		]);
            		$newTag->save();

            		$tagId = $newTag->id;
            	}

            	$tagsList[] = $tagId;

            }
    	}

    	return $tagsList;
    }
}
