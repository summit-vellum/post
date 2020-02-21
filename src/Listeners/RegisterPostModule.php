<?php

namespace Quill\Post\Listeners;


class RegisterPostModule
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        return [
            'name' => 'post',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current"><path class="heroicon-ui" d="M18 21H7a4 4 0 0 1-4-4V5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2h2a2 2 0 0 1 2 2v11a3 3 0 0 1-3 3zm-3-3V5H5v12c0 1.1.9 2 2 2h8.17a3 3 0 0 1-.17-1zm-7-3h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 1 1 0-2zm9 11a1 1 0 0 0 2 0V7h-2v11z"/></svg>',
            'title' => 'Post Article',
            'class' => 'font-xl text-blue-500',
            'model' => \Quill\Post\Models\Post::class,
            'module' => 'Post',
        ];
    }


}
