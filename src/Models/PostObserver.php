<?php

namespace Quill\Post\Models;

use Illuminate\Support\Str;
use Quill\Post\Events\PostCreated;
use Quill\Post\Models\Post;

class PostObserver
{

    public function creating(Post $post)
    {
        $post->slug = Str::slug(request('title'));
    }

    public function created(Post $post)
    {
        event(new PostCreated($post));
    }

    public function saving(Post $post)
    {
        // saving logic...
    }

    public function saved(Post $post)
    {
        // saved logic...
    }

    public function updating(Post $post)
    {
        // updating logic...
    }

    public function updated(Post $post)
    {
        // updated logic...
    }

}