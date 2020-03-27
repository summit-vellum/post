<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Illuminate\Support\Arr;

class Authors extends Filter
{
    protected function applyFilter(Builder $builder)
    {
    	if (request($this->filterName()) != null) {
    		$authors = collect(json_decode((request($this->filterName()))));
			$authors = Arr::pluck($authors, 'id');

			if ($authors) {
				$builder->with('authors')
		            ->whereHas('authors', function($query) use ($authors){
		                $query->whereIn('posts_authors.author_id', $authors);
		            });
			}
    	}

        return $builder;
    }

    public function key()
    {
        return 'authors';
    }

    public function options()
    {
        return '';
    }

    public function html()
    {
    	$attributes = [
    		'id' => 'authors',
    		'placeholder' => 'Search by Author Name',
    		'tagsinput-config' => json_encode(["apiUrl" => env('UAM_URL')."/author",
	            	"fields" => "id,display_name",
	            	"fieldName" => "display_name",
	            	"name" => "authors",
		           	"isObj" => true])
    	];

    	$value = request($this->filterName());
    	return compressHTML(template('tagsinput',['attributes' => $attributes, 'value' => $value, 'containerClass' => 'dshbrd-filter bg-white'], 'field'));
    }

    public function js()
    {
    	return [
			'vendor/html/js/tagsinput/bootstrap-tagsinput.min.js',
			'vendor/html/js/tagsinput.js',
			'vendor/html/js/tagsinput/typeahead.bundle.js'
    	];
    }

    public function css()
    {
    	return [
    		'vendor/html/css/bootstrap-tagsinput.css'
    	];
    }

    public function label()
    {
    	return 'Author';
    }

}
