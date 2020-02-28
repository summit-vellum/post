<?php

namespace Quill\Post\Models;

use App\Http\Models\Section;
use App\User;
use Quill\Seo\Traits\HasSeo;
use Quill\Tag\Models\PostTag;
use Quill\Tag\Models\Tag;
use Vellum\Contracts\Fieldable;
use Vellum\Models\BaseModel;

class Post extends BaseModel
{
    use HasSeo;

    protected $table = 'posts';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function category()
    {
        return $this->hasOne(Section::class,'id','section_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->using(PostTag::class);
    }

    public function history()
    {
        return $this->morphOne('Quill\History\Models\History', 'historyable');
    }

    public function resourceLock()
    {
        return $this->morphOne('Vellum\Models\ResourceLock', 'resourceable');
    }

    public function autosaves()
    {
        return $this->morphOne('Vellum\Models\Autosaves', 'autosavable');
    }

}
