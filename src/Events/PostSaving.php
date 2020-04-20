<?php

namespace Quill\Post\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Quill\Post\Models\Post;
use Illuminate\Support\Str;
use Quill\Status\Http\Helpers\StatusHelper as Status;
use Illuminate\Support\Arr;
use Request;

class PostSaving
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
    		if ($data->is_published == null) {
	    		$data->is_published = 0;
	    	}

	    	if ($data->status == Status::PUBLISH && $data->is_published == 0) {
	    		$this->sendPusherNotif($data);
	    		$data->slug = $this->createSlug($data->slug);
	    		$data->is_published = 1;
	    		$data->original_date_published = strtotime($data->date_published);
	    	}

	    	$data->date_published = strtotime($data->date_published);
	    	$data->seo_topic = $this->getSeoTopic($data);
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

    public function getSeoTopic($post)
    {
    	$seoTopic = json_decode($post->seo_topic, true);
    	$seoTopic = ($seoTopic) ? reset($seoTopic) : '';

    	return isset($seoTopic['name']) ? $seoTopic['name'] : '';
    }
}
