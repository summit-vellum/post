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
    	$data->date_published = strtotime($data->date_published);
    	$data->seo_topic = $this->getSeoTopic($data);
        $this->data = $data;
    }

    public function getSeoTopic($post)
    {
    	$seoTopic = json_decode($post->seo_topic, true);
    	$seoTopic = ($seoTopic) ? reset($seoTopic) : '';

    	return isset($seoTopic['name']) ? $seoTopic['name'] : '';
    }
}
