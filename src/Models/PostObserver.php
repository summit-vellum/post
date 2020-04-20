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
        event(new PostSaved($post));
    }

    public function updating(Post $post)
    {
        event(new PostUpdating($post));
    }

    public function updated(Post $post)
    {
    	$post->activity_code = $this->activity_code['edited'];
        event(new PostUpdated($post));
    }

    public function deleting(Post $post)
    {
    	$post->update([
    		'status' => Status::DISABLE
    	]);
    }


}
