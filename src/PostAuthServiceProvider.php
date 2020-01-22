<?php

namespace Quill\Post;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Quill\Post\Models\Policies\PostPolicy;
use Quill\Post\Models\Post;

class PostAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the afpplication.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
