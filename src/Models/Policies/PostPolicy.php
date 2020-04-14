<?php

namespace Quill\Post\Models\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Quill\Post\Models\Post;
use Illuminate\Auth\Access\Response;

class PostPolicy
{

    use HandlesAuthorization;

    protected $module = 'post';

    /**
     * Determine if the given resource can be viewed by the user.
     *
     * @param  \App\User  $user
     * @param  \Quill\Post\Models\Post  $post
     * @return bool
     */
    public function view(User $user, Post $post)
    {
        return $user->permissions($this->module)->contains('*') ||
                $user->permissions($this->module)->contains('view');
    }

    /**
     * Determine if the given resource can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \Quill\Post\Models\Post  $post
     * @return bool
     */
    public function update(User $user, Post $post)
    {
        return ($user->permissions($this->module)->contains('*') ||
                $user->permissions($this->module)->contains('update'))
                && ($user->id == $post->user_id || $post->user_id == 0)
                ? Response::allow()
                : Response::deny('This content is locked.');
    }

    /**
     * Determine if the user can create a new resource.
     *
     * @param  \App\User  $user
     * @param  \Quill\Post\Models\Post  $post
     * @return bool
     */
    public function create(User $user)
    {
        return $user->permissions($this->module)->contains('*') ||
                $user->permissions($this->module)->contains('create');
    }

    /**
     * Determine if the given resource can be deleted by the user.
     *
     * @param  \App\User                $user
     * @param  \Quill\Post\Models\Post  $post
     * @return bool
     */
    public function delete(User $user, Post $post)
    {
      return ($user->permissions($this->module)->contains('*') ||
        $user->permissions($this->module)->contains('delete'))
        && ($user->id == $post->user_id || $post->user_id == 0)
        ? Response::allow()
        : Response::deny('This content is locked.');
    }

}
