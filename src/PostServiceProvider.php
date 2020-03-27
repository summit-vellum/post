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
use Quill\Post\Resource\PostResource;
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
        // PostCreated::class => [
        //    SavePostToArchive::class,
        // ],
    ];


    public function boot()
    {
        parent::boot();

        $this->registerObserver();

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'post');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/post.php', 'post');

        $this->publishes([
        	__DIR__ . '/database/factories/PostFactory.php' => database_path('factories/PostFactory.php'),
            __DIR__ . '/database/seeds/PostTableSeeder.php' => database_path('seeds/PostTableSeeder.php'),
        ], 'post.migration');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/post'),
        ], 'post.views');

        $this->publishes([
        	__DIR__ . '/public/js/seo_topic.js' => public_path('vendor/post/js/seo_topic.js')
        ], 'post.seotopic.js');

        $this->publishes([
        	__DIR__ . '/public/js/custom_byline.js' => public_path('vendor/post/js/custom_byline.js')
        ], 'post.custom_byline.js');

        $this->publishes([
        	__DIR__ . '/public/js/form_validation.js' => public_path('vendor/post/js/form_validation.js')
        ], 'post.form_validation.js');
    }

    public function register()
    {
        Event::listen(Quill::MODULE, RegisterPostModule::class);
    }

    public function registerObserver()
    {
        PostResource::observe(PostObserver::class);
    }
}
