<?php

namespace Quill\Post\Models;

use Vellum\Uploader\UploadTrait;
use Illuminate\Support\Str;
use Quill\Post\Models\Post;
use Illuminate\Queue\SerializesModels;


class PostCreating
{
    use SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle(Post $post)
    {
        $post->slug = Str::slug(request('title'));
        dd($post);
    }

}