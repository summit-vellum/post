<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;

class Section extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			$builder->with('category')
            ->whereHas('category', function($query) {
                $query->where($this->foreignKey(), request($this->filterName()));
            });
		}

		return $builder;
	}

    public function apply(Builder $builder)
    {
        return $builder->with('category')
            ->whereHas('category', function($query){
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
        return \App\Http\Models\Section::class;
    }

}
