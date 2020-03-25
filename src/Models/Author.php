<?php

namespace Quill\Post\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $primaryKey = 'id';
	protected $table      = 'user';
	protected $connection = 'uam';

	public function scopeWhereValid($query)
	{
		return $query->where('status', 1);
	}

	public function scopeWhereId($query, $authorId)
	{
		if(is_array($authorId)) {
			return $query->whereIn('id', $authorId);
		} else {
			return $query->where('id', $authorId);
		}
	}
}
