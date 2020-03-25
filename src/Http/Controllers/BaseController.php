<?php

namespace Quill\Post\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $activity_code = [];

    public function __construct()
    {
    	$history = config('history');
    	$this->status = config('status');
    	$this->activity_code = $history['activity_code'];
    }


}
