<?php

namespace Quill\Post\Actions;

use Vellum\Actions\BaseAction;
use Vellum\Contracts\Actionable;

class OneSignalAction extends BaseAction implements Actionable
{
    public function icon()
    {
        return '
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current h-5 w-5"><path class="heroicon-ui" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
        ';
    }

    public function link($id, $data = [])
    {
        return '#';
    }
  public function styles()
  {
    return collect([
        'normal' => [
            'mx-1',
            'd-inline-block',
            'text-teal-400',
            'hover:text-gray-500'
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
            'items-center mr-2'
        ]
    ]);
  }

    public function attributes()
    {
        return [
          //...
        ];
    }

    public function tooltip()
    {
        return null;
    }

    public function label()
    {
        return 'Edit';
    }

    public function permission()
    {
        return 'update';
    }
}
