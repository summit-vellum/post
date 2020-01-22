<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;

class Author extends Filter
{
    protected function applyFilter(Builder $builder)
    {
        return $builder->with('user')
            ->whereHas('user', function($query){
                $query->where('id',request($this->filterName()));
            });
    }

    public function key()
    {
        return 'author';
    }

    public function options()
    {
        return \App\User::class;
    }

}
