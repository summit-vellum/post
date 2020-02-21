<?php

namespace Quill\Post\Resource;

use App\Http\Models\Section;
use Vellum\Models\Type;
use App\User;
use Quill\Html\Fields\Datetime;
use Quill\Html\Fields\ID;
use Quill\Html\Fields\Image;
use Quill\Html\Fields\Select;
use Quill\Html\Fields\Text;
use Quill\Html\Fields\Textarea;
use Quill\Html\Fields\Tinymce;
use Quill\Post\Models\Post;
use Quill\Status\Models\Status;
use Vellum\Contracts\Formable;

class PostResource extends Post implements Formable
{
    public function fields()
    {
    	// dd(ID::make('id'));
        return [
            ID::make()->sortable()->searchable(),
            Text::make('Title')
                ->rules('required','unique:posts','max:255')
                ->help('The ideal length of an article title is between 55-65 characters. This will appear in all widgets within the site.')
                ->searchable()
                ->sortable()
                ->fieldSelected(),

            Textarea::make('Blurb')
                ->help('Use a blurb to convey a short statement to accompany your title. This is displayed in the article page, as well as on Facebook and Twitter.')
                ->hideFromIndex()
                ->searchable(),

            Select::make('Section','section_id')
                ->rules('required')
                ->relation('category.name')
                ->options( Section::class )
                ->help('Section is part of the article\'s URL structure, thus, it can no longer be changed once published.'),

            Select::make('Author', 'author_id')
                ->rules('required')
                ->relation('user.name')
                ->options( User::class ),

            Tinymce::make('Content')
                ->rules('required')
                ->hideFromIndex(),

            Datetime::make('Published At')
                ->relation('published_at', 'published_at')
                ->modify(function($published_at, $post){
                    return \Carbon\Carbon::parse($published_at)->format('M d, y h:m A');
                })->sortable(),

            Image::make('Image')
                // ->rules('image','mimes:jpeg,png,jpg,gif','max:2048')
                ->help('Preferred dimension is 1200px x 675px')
                ->hideFromIndex(),

            Image::make('Thumbnail')
                // ->rules('image','mimes:jpeg,png,jpg,gif','max:2048')
                ->help('Preferred dimension is 225px x 128px')
                ->hideFromIndex(),

            Select::make('Status', 'status_id')
                ->relation('status.name')
                ->options(Status::class),

             Select::make('Type')
                ->options(Type::all())
                ->hideFromIndex(),
        ];
    }

    public function filters()
    {
        return [
            \Quill\Post\Filters\Section::class,
            \Quill\Post\Filters\Author::class,
        ];
    }

    public function actions()
    {
        return [
            new \Vellum\Actions\EditAction,
            new \Vellum\Actions\ViewAction,
            new \Vellum\Actions\DeleteAction,
            new \Quill\Post\Actions\PreviewAction,
        ];
    }

    public function modalActions()
    {
        return [
            new \Vellum\Actions\ViewAction
        ];
    }

}
