<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Quill\Sections\Models\Sections;

class Section extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			$builder->with('section')
            ->whereHas('section', function($query) {
                $query->where($this->foreignKey(), request($this->filterName()));
            });
		}

		return $builder;
	}

    public function apply(Builder $builder)
    {
        return $builder->with('section')
            ->whereHas('section', function($query){
                $query->where($this->foreignKey(), request($this->filterName()));
            });
    }

    public function key()
    {
        return 'section';
    }

    protected function foreignKey()
    {
        return 'section_id';
    }

    public function options()
    {
        return Sections::whereActive()->pluck('name', 'id')->toArray();
    }

    public function html()
    {
    	return '';
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

    public function label()
    {
    	return 'Section';
    }

}
