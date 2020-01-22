<?php

namespace Quill\Post;

use App\Providers\EventServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Quill\Archive\Listeners\SavePostToArchive;
use Quill\Post\Events\PostCreated;
use Quill\Post\Listeners\RegisterPostModule;
use Quill\Post\Models\Post;
use Quill\Post\Models\PostObserver;
use Vellum\Module\Quill;

class PostServiceProvider extends ServiceProvider 
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PostCreated::class => [
           SavePostToArchive::class,
        ],
    ];


    public function boot()
    {
        parent::boot();

        $this->registerObserver();

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'post');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/post.php', 'post');
    }

    public function register()
    {
        Event::listen(Quill::MODULE, RegisterPostModule::class);
    }

    public function registerObserver()
    {
        Post::observe(PostObserver::class);
    }
}
