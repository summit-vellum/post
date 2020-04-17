<?php

namespace Quill\Post\Shortcode;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Quill\Post\Models\Post;

use Vellum\Contracts\Resource;
use Vellum\Contracts\Shortcode;
use Illuminate\Support\Facades\Cookie;

class ArticleRecoShortcode implements Shortcode
{
    protected $name = 'InArticle';
    public $resource;
    public $data = [];
    public $params;
    public $type;
    public $widgetVal;
    public $site;
    public $element;


    public function __construct(Resource $resource)
    {
		$this->resource = $resource;
		$this->site = config('site');
    }

    public function transform($post)
    {
        return $post->id;
    }

    public function dataSource($post)
    {
        return json_encode([
            'id' => $post->id,
            'title' => $post->title,
            'section' => isset($post->category) ? $post->category->name : '',
            'published_at' => \Carbon\Carbon::parse($post->published_at)->format('M d, y h:m A')
        ]);
    }

    public function input($post)
    {
        return '<input
            data-shortcode="articleIds"
            data-shortcode-listen="click"
            data-shortcode-multiple
            data-source=\''.trim(htmlspecialchars($this->dataSource($post), ENT_QUOTES, 'UTF-8')).'\'
            type="'.$this->element.'"
            name="radio"
            value=\''.trim($this->transform($post)).'\'>';
    }

    public function code()
    {
        return "[{$this->name}:".json_encode($this->parameters(), JSON_NUMERIC_CHECK)."]";
    }

    public function parameters()
    {
        $this->getCookieValue();

        return [
            'articleIds' => [],
            'widget' => $this->widgetVal,
            // 'widget' => Cookie::get(),
            // 'image' => ""
        ];
    }

    public function getCookieValue()
    {
        $params = Cookie::get($this->name);

        return json_decode($params);

        $match = preg_match_all('/\[('.$this->name.'):((\d+)|(\[{.+?}\])|({.+?})|(.+?))\]/', $params, $matches);

        if ($match) {
            return json_decode(trim(stripslashes($matches[2][0])));
        }
    }

    public function view($request)
    {
    	$this->type = $request->get('type');
        $rows = $this->resource->getRowsData();
        $this->data['selected'] = $this->getCookieValue() ?? [];
        $this->data['collections'] = $this->resource->getRowsData();
        $this->data['attributes'] = $this->resource->getAttributes();

        $searchables = [];
        if (isset($this->data['attributes']['searchable'])) {
        	foreach ($this->data['attributes']['searchable'] as $key => $searchable) {
        		$searchables[] = $this->data['attributes']['name'][$searchable];
        	}
        }

        $this->data['searchables'] = $searchables;

        $modify = function ($select, $post) {
            return $this->input($post);
        };

        switch ($this->type) {
    		case 'multiple':
    			$this->widgetVal = $this->site['in_article_label']['multiple'];
    			$this->element = 'checkbox';
    			break;

    		default:
    			$this->widgetVal = $this->site['in_article_label']['single'];
    			$this->element = 'radio';
    			break;
    	}

        $this->data['attributes']['collections'] = array_merge(['select' => [
        	"id" => "select",
            "name" => "",
            "element" => "checkbox",
            "relation" => "select",
            "modify" => $modify
        ]], $this->data['attributes']['collections']);

        //modify is called in cell.blade.php; it's a callback
        $this->data['attributes']['relation']['select'] = 'select';
        $this->data['attributes']['modify']['select'] = $modify;
        $this->data['actions'] = ($this->resource->getModalActions()) ? $this->resource->getModalActions() : $this->resource->getActions();
        $this->data['page_title'] = 'In-Article Recommendations';
        $this->data['shortcode'] = $this->code();
        $this->data['type'] = $this->type;
        $this->data['widget_placeholder'] = $this->widgetVal;
        $this->data['shortcode_route'] = route('article-reco.index');

        return view('post::articleReco', $this->data);
    }

    public function registerShortcodeCookie($request)
    {
    	$deleteAllCookie = $request->input('deleteAllCookie', 0);
    	if ($deleteAllCookie) {
    		$cookie = Cookie::forget($this->name);
    		return response()->json([])->withCookie($cookie);
    	}

        $cookie = $this->getCookieValue();
        $data = $cookies = $dataRemoved = [];
        $requestInput = $request->input('input');
        $deleteCookie = $request->input('deleteCookie', 0);
        $isRadio = $request->input('isRadio', 0);

        if (isset($cookie->data) && !$isRadio) {
            foreach ($cookie->data as $key => $item) {
	        	$item = json_decode(json_encode($item), true);
	        	if ($item[0]['id'] != $requestInput[0]['id']) {
	        		$data[] = $item;
	        	}
	        }
        }

        if ($requestInput) {
        	if ($deleteCookie) {
	        	$dataRemoved = reset($requestInput);
	        	$dataRemoved = $dataRemoved['id'];
	        } else {
	        	$data[] = $requestInput;
	        }
        }

        $responseCookie = [
            'data' => $data,
            'shortcode' => request('shortcode')
        ];

        return response()->json([
            'name' => $this->name,
            'data' => $data,
            'shortcode' => $request->input('shortcode'),
            'deleteCookie' => $deleteCookie,
            'removedId' => $dataRemoved
        ], 200, [], JSON_NUMERIC_CHECK )
        ->withCookie(cookie($this->name, json_encode($responseCookie), 60));
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($request, $next)
    {
    	$shortcodes = $next($request);

        $shortcodes[] =  [
            'type' => 'menutime',
            'text' => 'post',
            'label' => 'In-Article Recommendations',
            'url' => route('article-reco.index', ['type' => 'single']),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current"><path class="heroicon-ui" d="M18 21H7a4 4 0 0 1-4-4V5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2h2a2 2 0 0 1 2 2v11a3 3 0 0 1-3 3zm-3-3V5H5v12c0 1.1.9 2 2 2h8.17a3 3 0 0 1-.17-1zm-7-3h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 1 1 0-2zm9 11a1 1 0 0 0 2 0V7h-2v11z"/></svg>'
        ];

        return $shortcodes;
    }
}
