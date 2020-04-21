<?php

namespace Quill\Post\Actions;

use Illuminate\Support\Facades\Route;
use Vellum\Actions\BaseAction;
use Vellum\Contracts\Actionable;

class PreviewAction extends BaseAction implements Actionable
{
    public function icon()
    {
        return view('vellum::icons.icon')->with(['icon' => 'monitor'])->render();
    }

    public function link($id, $data = [])
    {
        $module = explode('.', Route::current()->getName())[0];
        return route($module . '.show', $id);
    }

    public function styles()
    {
        return collect([
            'normal' => [
                'mx-1',
                'd-inline-block',
                'text-teal-400',
                'hover:text-gray-500',
            ],
            'button' => [
                'bg-blue-500',
                'rounded',
                'px-4',
                'py-2',
                'text-white',
                'hover:bg-blue-700',
                'font-semibold',
                'shadow',
                'inline-flex',
                'items-center mr-2',
            ],
        ]);
    }

    public function attributes($data = [])
    {
    	$attributes = [];
    	$attributes = [
            'data-toggle' => 'modal',
            'data-target' => '#toolModal',
            'data-url' => ''
        ];

        return $attributes;
    }

    public function tooltip()
    {
        return null;
    }

    public function label()
    {
        return 'Preview';
    }

    public function permission()
    {
        return 'view';
    }

    public function withDialog()
    {
    	return false;
    }

}
