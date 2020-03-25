<?php

namespace Quill\Post\Models;

use Quill\Sections\Models\Sections;
use App\User;
use Quill\Seo\Traits\HasSeo;
use Quill\Post\Models\PostTags;
use Quill\Tag\Models\Tag;
use Vellum\Contracts\Fieldable;
use Vellum\Models\BaseModel;
use Quill\History\Models\History;
use Vellum\Models\ResourceLock;
use Vellum\Models\Autosaves;
use Quill\Meta\Models\Meta;
use Quill\Post\Models\PostAuthors;
use Quill\Post\Models\Author;
use Quill\Post\Models\PostSeoScore;
use Illuminate\Support\Arr;

class Post extends BaseModel
{
    use HasSeo;

    protected $table = 'posts';

    protected $appends = ['author', 'tags_list', 'visible_tags_list', 'invisible_tags_list', 'serialized_seo_topic', 'status_icon', 'is_published_later'];

    public function getIsPublishedLaterAttribute()
    {
    	if ($this->attributes['status'] == 1 && $this->attributes['date_published'] > time()) {
            return true;
        }

        return false;
    }

    public function getStatusIconAttribute()
    {
    	$statusConf = config('status');
    	if ($this->attributes['status'] == 1 && $this->attributes['date_published'] > time()) {
            return $statusConf[3]['icon'];
        }

    	return $statusConf[$this->attributes['status']]['icon'];
    }

    public function getSerializedSeoTopicAttribute()
    {
    	$seoTopicArr = [];
    	$seoTopic = $this->attributes['seo_topic'];
    	if ($seoTopic) {
    		$title = str_replace(['wiki/','_'], ['',' '], trim($seoTopic, '/'));
	        $seoTopicArr[] = ['title'=>urldecode($title), 'name'=>urldecode($seoTopic)];
    	}

    	return json_encode($seoTopicArr);
    }

    public function getAuthorAttribute()
    {
    	if ($this->authors) {
    		$authorData = [];
    		$authorsPivot = $this->authors->toArray();

    		$authorsPivot = array_map(function($item){
    			return collect($item)->only('custom_by_line', 'author_id')->toArray();
    		}, $authorsPivot);

    		$authorIds = Arr::pluck($authorsPivot, 'author_id');

    		$authors = Author::whereValid()->whereId($authorIds)->get()->toArray();

    		foreach ($authorsPivot as $authorPivot) {
    			foreach ($authors as $author) {
    				if (isset($authorPivot['author_id']) && $authorPivot['author_id'] == $author['id']) {
                        unset($authorPivot['author_id']);
                        $authorData[] = array_merge($authorPivot, $author);
                    }
    			}
    		}

    		$authorData = array_map(function($item){
    			return collect($item)->only('id', 'display_name')->toArray();
    		}, $authorData);

    		return $authorData;
    	}

    	return [];
    }

    public function getTagsListAttribute()
    {
        $tags = Arr::pluck($this->tags->toArray(), 'name');

        return count($tags) ? implode(',', $tags) : null;
    }

    public function getVisibleTagsListAttribute()
    {
        $tags = Arr::pluck($this->visibleTags->toArray(), 'name');

        return count($tags) ? implode(',', $tags) : null;
    }

    public function getInvisibleTagsListAttribute()
    {
        $tags = Arr::pluck($this->invisibleTags->toArray(), 'name');

        return count($tags) ? implode(',', $tags) : null;
    }

    public function getAuthorNames()
    {
        $authors = $this->getAuthorAttribute();

        return implode(', ', Arr::pluck($authors, 'display_name'));
    }

    public function scopeOrderById($query, $order='DESC')
    {
        return $query->orderBy('id', $order);
    }

    public function scopeOrderByTitle($query, $order='ASC')
    {
        return $query->orderBy('title', $order);
    }

    public function scopeOrderByDatePublished($query, $order='DESC')
    {
        return $query->orderBy('date_published', $order);
    }

    public function scopeWhereValid($query)
    {
        return $query->whereHas('section', function ($query) {
            $query->whereActive();
        });
    }

    public function scopePublished($query, $excludePublishLater = false)
    {
        if ($excludePublishLater) {
            return $query->whereValid()
                         ->where('status', 1)
                         ->where('date_published', '<=', time())
                         ->where('is_published', 1);
        }else{
            return $query->whereValid()->where('status', 1);
        }
    }

    public function scopeDisabled($query)
    {
        return $query->whereValid()->where('status', 2);
    }

    public function scopeDraft($query)
    {
        return $query->whereValid()->where('status', 0);
    }

    public function scopeWherePublishedBetween($query, $dates)
    {
        return $query->whereBetween('date_published', $dates);
    }

    public function authors()
    {
        return $this->hasMany(PostAuthors::class, 'post_id', 'id')->orderBy('id', 'ASC');
    }

    public function meta()
    {
    	return $this->hasOne(Meta::class, 'post_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function section()
    {
        return $this->hasOne(Sections::class,'id','section_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id')->withTimestamps();
        //using(PostTags::class)->withPivot('post_id')
    }

    public function visibleTags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id')->where('is_visible','=', 1);
    }

    public function invisibleTags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id')->where('is_visible','=', 0);
    }

    public function seoScore()
    {
    	return $this->hasOne(PostSeoScore::class,'post_id','id');
    }

    public function history()
    {
        return $this->morphOne(History::class, 'historyable');
    }

    public function resourceLock()
    {
        return $this->morphOne(ResourceLock::class, 'resourceable');
    }

    public function autosaves()
    {
        return $this->morphOne(Autosaves::class, 'autosavable');
    }

}
