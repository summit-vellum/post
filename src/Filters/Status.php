<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Illuminate\Support\Arr;
use Quill\Status\Models\Status as StatusModel;
use Quill\Status\Http\Helpers\StatusHelper;

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
    	$status = StatusModel::where('id', '!=', 4)->pluck('name', 'id')->toArray();
        return $status;
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
