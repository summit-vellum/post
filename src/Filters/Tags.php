<?php

namespace Quill\Post\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vellum\Filters\Filter;
use Quill\Sections\Models\Sections;
use Illuminate\Support\Arr;

class Tags extends Filter
{
	protected function applyFilter(Builder $builder)
	{
		if (request($this->filterName()) != null) {
			$tags = collect(json_decode((request($this->filterName()))));
			$tags = Arr::pluck($tags, 'id');

			if ($tags) {
				$builder->with('tags')
	            ->whereHas('tags', function($query) use ($tags) {
	                $query->whereIn('tags.id', $tags);
	            });
			}
		}

		return $builder;
	}

    public function key()
    {
        return 'tags';
    }

    public function options()
    {
        return '';
    }

    public function html()
    {
    	$attributes = [
    		'id' => 'tags',
    		'placeholder' => 'Search by Article Tag',
    		'tagsinput-config' => json_encode(["apiUrl" => "https://local.quill.cosmo.summitmedia-digital.com/tag/api",
	            	"fields" => "id,name",
	            	"fieldName" => "name",
	            	"name" => "tags",
		           	"isObj" => true])
    	];

    	$value = request($this->filterName());
    	return compressHTML(template('tagsinput',['attributes' => $attributes, 'value' => $value],'field'));
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
    	return 'Article Tag';
    }

}
