<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Illuminate\Support\Arr;

class DatePublished extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			$dateRange = urldecode(request($this->filterName()));
			$dateRange = explode('-', $dateRange);

			if ($dateRange[0] == $dateRange[1]) {
                $dateRange[0] = $dateRange[0] . ' 00:01';
                $dateRange[1] = $dateRange[1] . ' 23:59';
            }

            $dateRange[0] = strtotime($dateRange[0]);
			$dateRange[1] = strtotime($dateRange[1]);

			$builder->wherePublishedBetween($dateRange);
		}

		return $builder;
	}

	protected function foreignKey()
    {
        return 'date_published';
    }

    public function key()
    {
        return 'date_published';
    }

    public function options()
    {
        return '';
    }

    public function html()
    {
    	$value = request($this->filterName());
    	$dateRange = explode('-', $value);

    	$startDate = ($value) ? $dateRange[0] : date('m/d/Y');
        $endDate = ($value) ? $dateRange[1] : date('m/d/Y');

    	$attributes = [
    		'id' => 'date_published',
    		'classes' => 'form-control',
    		'data-config' => json_encode([
				    			'single' => false,
				    			'dateFormat' => 'MM/DD/YYYY',
				    			'localeFormat' => 'MM/DD/YYYY',
				    			'startDate' => $startDate,
				    			'endDate' => $endDate
				    		])
    	];

    	return compressHTML(template('datetime', ['attributes' => $attributes, 'value' => $value],'field'));;
    }

    public function js()
    {
    	return [
    		'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js',
            'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
            'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
            'vendor/html/js/datetime.js'
    	];
    }

    public function css()
    {
    	return [
    		'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
            'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css'
    	];
    }

    public function label()
    {
    	return 'Date Range';
    }

}
