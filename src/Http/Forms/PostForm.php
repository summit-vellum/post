<?php

namespace Quill\Post\Http\Forms;

use Vellum\Contracts\Form\Fields\Datetime;
use Vellum\Contracts\Form\Fields\Image;
use Vellum\Contracts\Form\Fields\Select;
use Vellum\Contracts\Form\Fields\Text;
use Vellum\Contracts\Form\Fields\Textarea;
use Vellum\Contracts\Form\Fields\Tinymce;
use Vellum\Contracts\Formable;
use Illuminate\Support\Facades\DB;
use Quill\Post\Models\Post;

class PostForm extends Post implements Formable
{
    public function formFields()
    {
        return [
            (new Text)->make('Title')
                ->rules('required|unique:posts|max:255')
                ->info('The ideal length of an article title is between 55-65 characters. This will appear in all widgets within the site.'),

            (new Textarea)->make('Blurb')
                ->info('Use a blurb to convey a short statement to accompany your title. This is displayed in the article page, as well as on Facebook and Twitter.'),

            (new Select)->make('Section','section_id')
                ->rules('required')
                ->options( DB::table('sections')->pluck('name','id')->toArray() )
                ->info('Section is part of the article\'s URL structure, thus, it can no longer be changed once published.'),

            (new Select)->make('Author', 'author_id')
                ->rules('required')
                ->options( DB::table('users')->pluck('name','id')->toArray() ),

            (new Tinymce)->make('Content')
                ->rules('required'),

            (new Datetime)->make('Published At'),

            (new Image)->make('Image')
                ->rules('required|image|mimes:jpeg,png,jpg,gif|max:2048')
                ->info('Preferred dimension is 1200px x 675px'),

            (new Image)->make('Thumbnail')
                ->rules('required|image|mimes:jpeg,png,jpg,gif|max:2048')
                ->info('Preferred dimension is 225px x 128px'),

            (new Select)->make('Status')
                ->options(['Draft', 'Publish', 'Deleted']),

             (new Select)->make('Type')
                ->options([
                    'article' => 'Article',
                    'page' => 'Page'
                    ]),
        ];
    }
}