<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Illuminate\Support\Str;

class SortBy extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			switch (request($this->filterName())) {
				case '0':
					$builder->orderById();
					break;
				case '1':
					$builder->orderByTitle();
					break;
				case '2':
					$builder->orderByDatePublished('ASC');
					break;
			}
		}

		return $builder;
	}

	public function key()
    {
        return 'sort_by';
    }

    public function options()
    {
        return [0=>'Recent', 1=>'Alphabetical', 2=>'Oldest First'];
    }

    public function js()
    {
    	return [
    		//
    	];
    }

    public function css()
    {
    	return [
    		//
    	];
    }

    public function html()
    {
    	return '';
    }

    public function label()
    {
    	return 'Arrangement';
    }

}
