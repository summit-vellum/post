<?php

namespace Quill\Post\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vellum\Contracts\Resource;

class PostController extends Controller
{
	public function __construct(Resource $resource)
    {
		$this->resource = $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['column_name'] = $this->article->getProperties();
        $data['rows'] = $this->article->getValues();

        return view('post::index');
    }

    public function preview(Request $request)
    {
    	$id = $request->get('id');
    	$post = $this->resource->findById($id);
    	$link = isset($post->url) ? $post->url : '';
    	return view('post::preview', ['link' => $link]);
    }
}
