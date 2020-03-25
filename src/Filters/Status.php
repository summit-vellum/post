<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Illuminate\Support\Arr;

class Status extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			$status = request($this->filterName());

			switch ($status) {
				case 0: $builder->draft();
					break;
				case 1: $builder->published(true);
					break;
				case 2: $builder->disabled();
					break;
				case 3: $builder->wherePublishedBetween([time(), strtotime("+1 year")])->published();
					break;
			}
		}

		return $builder;
	}

	protected function foreignKey()
    {
        return 'status';
    }

    public function key()
    {
        return 'status';
    }

    public function options()
    {
        return \Quill\Status\Models\Status::class;
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
    	return 'Status';
    }

}
