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
    	$visibleTags = $this->upsertTags($data, 'visible_tags');
    	$invisibleTags = $this->upsertTags($data, 'invisible_tags', 0);
    	$data->tags()->detach();
    	$data->tags()->sync(array_merge($visibleTags, $invisibleTags));

    	$this->upsertSeoScoreBreakdown($data);
    	$this->createAuthors($data);
    	$this->createMeta($data);

    	$data->is_published = 0;
    	if ($data->status == Status::PUBLISH && $data->is_published == 0) {
    		$this->sendPusherNotif($data);
    		$data->slug = $this->createSlug($data->slug);
    		$data->is_published = 1;
    		$data->original_date_published = strtotime($data->date_published);
    	}

        $this->data = $data;
    }

    public function sendPusherNotif($data)
    {
		event(new PostPublished([
    		'title'=> $data->title,
    		'author'=> auth()->user()->display_name,
    		'url' => config('site.protocol').config('site.domain').'/'.$data->section->url.$data->slug
    	]));
    }

    public function createSlug($slug)
    {
    	$slugArr = explode('-', $slug);
    	$syndicatedFrom = false;
    	$siteSuffix = '';

    	foreach ($slugArr as $key => $value) {
            if (preg_match('/^(sa|a)([[:digit:]]{5})/', $value)) {
                unset($slugArr[$key]);
            } elseif (preg_match('/^[[:digit:]]{8}$/', $value)) { //removes all author ids from slug ex(a00123, sa00123)
                unset($slugArr[$key]);
            }
        }

        $slug = implode('-', $slugArr);

        $customAuthors = Request::input('custom_byline_author');
        if (!empty($customAuthors)) {
            foreach ($customAuthors as $key => $customAuthor) {
                if (!empty($customAuthor)) {
                    $customAuthor         = json_decode($customAuthor, true);
                    $authorIdSlugSuffix[] = 'a'.Arr::get(head($customAuthor), 'id');
                }
            }
        }

        $authors = Request::input('authors');
        $authors = json_decode($authors);
        if ($authors) {
            foreach ($authors as $author) {
                $authorIdSlugSuffix[] = ($syndicatedFrom ? 'sa' : 'a').$author->id;
            }
        }

        $authorSuffix = '-'.implode('-', $authorIdSlugSuffix);

        $datepublished = date('Ymd', strtotime(Request::input('date_published')));
        $newSlug       = $slug.$authorSuffix.'-'.$datepublished.$siteSuffix;

        $wordCount     = str_word_count(strip_tags(Request::input('content')));

        //clean existing long form suffix
        $arrSearch     = array('-lfrm10','-lfrm'.floor($wordCount / 1000),'-lfrm');
        $newSlug       = str_replace($arrSearch, '',  $newSlug);

        //add lform1-10
        if ($wordCount >= 10000) {
            $newSlug .= '-lfrm10';
        } elseif ($wordCount >= 2000) {
            $newSlug .= '-lfrm'.floor($wordCount / 1000);
        } elseif ($wordCount >= 1000) {
            $newSlug .= '-lfrm';
        }

        //final slug
        return $newSlug;

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
