<?php

namespace Quill\Post\Models;

use Quill\Post\Events\PostDeleted;
use Quill\Post\Events\PostSaved;
use Quill\Post\Events\PostSaving;
use Quill\Post\Events\PostUpdating;
use Quill\Post\Events\PostCreated;
use Quill\Post\Events\PostUpdated;
use Quill\Post\Models\Post;
use Vellum\Contracts\Resource;
use Illuminate\Support\Facades\Schema;
use Quill\Post\Http\Controllers\BaseController;
use Request;
use Quill\Status\Http\Helpers\StatusHelper as Status;

class PostObserver extends BaseController
{
    public function creating(Post $post)
    {

    }

    public function created(Post $post)
    {
    	$post->activity_code = $this->activity_code['created'];

        event(new PostCreated($post));
    }

    public function saving(Post $post)
    {
    	event(new PostSaving($post));
    }

    public function saved(Post $post)
    {
        if ($post->isDirty('status')) {
            if ($post->status == Status::PUBLISH) {
                if ($post->date_published > time()) {
                    $post->activity_code = $this->activity_code['set_as_publish_later'];
                } else {
                	$post->activity_code = $this->activity_code['published'];
                }
            } else if($post->status == Status::DRAFT) {
            	if (request()->get('id') == null) {
            		$post->activity_code = $this->activity_code['edited'];
            	} else {
            		$post->activity_code = $this->activity_code['set_back_as_draft'];
            	}
            } if ($post->status == Status::DISABLE) {
                $post->activity_code = $this->activity_code['disabled'];
            }
     	} else {
     		$post->activity_code = $this->activity_code['edited'];
     	}

        event(new PostSaved($post));
    }

    public function updating(Post $post)
    {
        event(new PostUpdating($post));
    }

    public function updated(Post $post)
    {
        event(new PostUpdated($post));
    }

    public function deleting(Post $post)
    {
    	$post->update([
    		'status' => Status::DISABLE
    	]);
    }


}
