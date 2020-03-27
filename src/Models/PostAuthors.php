<?php

namespace Quill\Post\Models;

use Vellum\Models\BaseModel;

class PostAuthors extends BaseModel
{
    protected $table = 'posts_authors';

    protected $fillable = ['post_id', 'author_id', 'custom_by_line', 'status'];
}
