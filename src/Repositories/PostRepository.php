<?php

namespace Quill\Post\Repositories;

use Quill\Post\Models\Post;


class PostRepository
{
    
    protected $article;

    public function __construct(Post $article)
    {
        $this->article = $article;
    }

    public function index()
    {
        $data['column_name'] = $this->article->getProperties();
        $data['rows'] = $this->article->getValues();

        return view('catalog', $data);
    }
}
