<?php

namespace Quill\Post\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Quill\Post\Models\Post;

class PostCreated
{
    // use Dispatchable, InteractsWithSockets,
    use SerializesModels;

    /**
     * The authenticated post.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $post
     * @return void
     */
    public function __construct(Post $data)
    {
        $this->data = $data;
    }
}
