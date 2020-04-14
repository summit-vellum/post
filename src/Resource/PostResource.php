<?php

namespace Quill\Post\Resource;

use Quill\Sections\Models\Sections;
use Vellum\Models\Type;
use App\User;
use Quill\Html\Fields\Datetime;
use Quill\Html\Fields\ID;
use Quill\Html\Fields\Image;
use Quill\Html\Fields\Select;
use Quill\Html\Fields\Text;
use Quill\Html\Fields\Textarea;
use Quill\Html\Fields\Tinymce;
use Quill\Html\Fields\Toggle;
use Quill\Post\Models\Post;
use Quill\Status\Models\Status;
use Vellum\Contracts\Formable;
use Quill\Html\Fields\Tagsinput;
use Quill\Html\Fields\Label;
use Quill\Html\Fields\Button;
use Request;

class PostResource extends Post implements Formable
{
    public function fields()
    {
        return [
            ID::make()->searchable()
            	->setStyle([
            		'https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'
            	])
            	->setJs([
            		'vendor/post/js/form_validations.js',
            		'vendor/post/js/longread.js'
            	])
            	->template(view('post::templates.form', ['site' => config('site')])),

            Toggle::make('Longform?', 'longform')
            	->hideFromIndex()
            	->container([
	            	'sectionName' => 'longform',
	            	'view' => view('vellum::containers.div', ['yieldName'=>'longform', 'classes'=>'bg-text pull-right longform-toogle'])
	            ])
	            ->labelClasses('hide')
	            ->yieldAt('yield_longform')
	            ->inputClass('form-group mb-5'),

            Text::make('Keyword', 'seo_keyword')
            	->help('These tags describes your page\'s content. They don\'t appear on the article page itself but only in the html code. These tags help tell search engines what the page is about.')
            	->classes('cf-input')
	            ->hideFromIndex()
	            ->yieldInfoTextSectionAt('yield_seo_keyword_help')
	            ->yieldAt('yield_seo_keyword'),

	        Text::make('Search Volume', 'search_volume')
	        	->classes('cf-input')
	            ->hideFromIndex()
	            ->yieldAt('yield_search_volume'),

            Textarea::make('Article Title', 'title')
            	->relation('title')
            	->modify(function($title, $post){
            		if (count(Request::segments()) > 1) {
            			return $title;
            		} else {
	            		return '<span class="pull-left status '.$post->status_icon.'"></span>
	            					<div class="ml-4 middle">'.$title.'</div>';
            		}
                })
                // ->rules('required','unique:posts','max:255')
                ->rules('required', 'max:255')
                ->help('This headline will appear on the article landing page. The ideal length of an article title is between 54-65 characters.')
                ->searchable()
                ->fieldSelected()
                ->classes('cf-input-lg countChar scrollable-input expanded-txtbox')
                ->characterCount(55, 65, 'Oops! The title you added is quite long. This might look odd on certain parts of the site. Please consider making it shorter.')
                ->autoSlug()
                ->thWidthAttribute('50%')
                ->uniqueChecker([
                	'unique' => 'Nice! You have a unique title!',
                	'hasDuplicate' => 'Sorry, That title is already taken. Please provide another one.'
                ])
                ->thWidthAttribute('30%')
                ->displayAsEdit()
                ->yieldAt('yield_title')
                ->inputClass('form-group mt-5 mb-5'),

            Textarea::make('Custom Headline', 'headline')
            	->help('This will appear on: all widgets, feeds within the site, Facebook, Twitter.')
            	->classes('cf-input countChar scrollable-input')
            	->hideFromIndex()
            	->yieldAt('yield_headline')
            	->inputClass('form-group mb-5'),

            Textarea::make('Seo Title', 'meta_title')
            	->rules('required')
            	->relation('meta.title')
            	->help('The ideal length of an article meta title is between 55-65 characters. Meta titles are shown in results page of search engines like Google; but are truncated if it is too long.')
            	->classes('cf-input scrollable-input')
            	->characterCount(55, 65, '')
            	->hideFromIndex()
            	->yieldAt('yield_meta_title')
            	->inputClass('form-group mb-5'),

            Textarea::make('Article Slug', 'slug')
            	->help('Make sure that you review your article slug before publishing. You may no longer change it once published.')
            	->classes('cf-input scrollable-input')
            	->autoSlugSource('title')
            	->hideFromIndex()
            	->yieldAt('yield_slug')
            	->inputClass('form-group mb-5'),

            Textarea::make('Blurb')
            	->rules('required')
                ->help('Use a blurb to convey a short statement to accompany your title. This is displayed in the article page, as well as on Facebook and Twitter.')
                ->hideFromIndex()
                ->classes('cf-input scrollable-input')
                ->characterCount(70, 80, '')
                ->hideFromIndex()
                ->yieldAt('yield_blurb')
            	->inputClass('form-group mb-5'),

            Textarea::make('SEO Meta Description', 'meta_description')
            	->rules('required')
            	->relation('meta.description')
                ->help('Provide a short summary of what visitors should expect to read in your article. This is displayed on search engine results pages.')
                ->classes('cf-input scrollable-input')
                ->characterCount(290, 300, '')
                ->hideFromIndex()
                ->yieldAt('yield_meta_description')
            	->inputClass('form-group mb-5'),

            Textarea::make('Meta Canonical URL', 'meta_canonical')
            	->relation('meta.canonical')
                ->help('WARNING: Only touch this field IF this page is accessible via multiple links. Enter the latest/correct URL of the page here.')
                ->classes('cf-input scrollable-input')
                ->hideFromIndex()
                ->characterCount(150, 160, '')
                ->yieldAt('yield_meta_canonical')
            	->inputClass('form-group mb-5'),

            Select::make('Section', 'section_id')
            	->relation('sections.id')
       	    	->modify(function($id, $post){
	            	return $post->section['name'];
       	    	})
            	->options(Sections::whereActive()->pluck('name', 'id')->toArray())
                ->rules('required')
                ->help('Section is part of the article\'s URL structure, thus, it can no longer be changed once published.')
                ->container([
	            	'sectionName' => 'section',
	            	'view' => view('vellum::containers.row-col', ['yieldName'=>'section', 'colClass'=>'cf-select-container'])
	            ])
	            ->displayDashboardNotif()
	            ->dashboardContainerClass('text-center middle')
	            ->yieldAt('yield_section')
            	->inputClass('form-group mb-5'),

            Tinymce::make('Body Content', 'content')
                ->rules('required')
	            ->help('Width is optimized to actual desktop screen content area of 720px.')
	            ->tinymceRows(28)
	            ->hideFromIndex()
	            ->yieldAt('yield_content')
            	->inputClass('form-group'),

	        Tinymce::make('Article Summary', 'summary')
	        	->help('You may add an article summary to this article.')
	        	->tinymceRows(10)
	        	->hideFromIndex()
	        	->yieldAt('yield_summary')
            	->inputClass('form-group mb-5')
            	->tinyMceAttributes([
            		'tools' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | customBullist customNumlist | link code | fullscreen'
            	]),

	       	TagsInput::make('Seo Topic', 'seo_topic')
	       		->relation('seo_topic')
	       		->modify(function($seo_topic, $post){
	       			return $post->serialized_seo_topic;
	       		})
	       		->tagsInput([
	       			'apiUrl' => 'https://en.wikipedia.org/w/api.php?action=opensearch&ction=centralauthtoken&srlimit=10&list=search&format=json&origin=*',
	            	'fieldName' => 'title',
	            	'urlQueryKeyword' => '&search',
	            	'name' => 'seo_topic',
		           	'isObj' => true,
		           	'isMultiple' => false
	            ])
	       		->help('This field refers to the primary Wikipedia topic associated with this article. Some examples: for an article about "the main antagonist in Star Wars",<br>the SEO topic is: "Darth Vader" from https://en.wikipedia.org/wiki/Darth_Vader; for an article about "pregnancy", the SEO topic is: "Pregnancy"<br>from https://en.wikipedia.org/wiki/Pregnancy; for an article about "difficulties /complications during pregnancy", the SEO topic is: "Complications of Pregnancy"<br>from https://en.wikipedia.org/wiki/Complications_of_pregnancy.')
	       		->classes('cf-input')
	       		->placeholder('Add closest Wikipedia topic URL')
	       		->setJs(['vendor/post/js/seo_topic.js'])
	       		->hideFromIndex()
	       		->yieldAt('yield_seo_topic')
            	->inputClass('form-group'),

       	    Tagsinput::make('Visible Tags', 'visible_tags')
       	    	->relation('id')
       	    	->modify(function($id, $post){
	            	return $post->visible_tags_list;
       	    	})
	            ->tagsInput([
	            	'apiUrl' => '/tag/api',
	            	'fields' => 'id,name,count',
	            	'fieldName' => 'name',
	            	'name' => 'visible_tags',
		           	'isObj' => false,
		           	'fieldCountName' => 'count',
		           	'visibility' => 1
	            ])
	            ->classes('cf-input')
	            ->placeholder('Add a visible tag to this article')
	            ->help('Use tags to specify a topic, person, brand, institution, or an event included in your article. Avoid duplicating or restating words as it doesn\'t help in organizing content.')
	            ->hideFromIndex()
	            ->yieldAt('yield_visible_tags')
            	->inputClass('form-group mb-5'),

            Tagsinput::make('Invisible Tags', 'invisible_tags')
            	->relation('id')
       	    	->modify(function($id, $post){
	            	return $post->invisible_tags_list;
       	    	})
	            ->tagsInput([
	            	'apiUrl' => '/tag/api',
	            	'fields' => 'id,name,count',
	            	'fieldName' => 'name',
	            	'name' => 'invisible_tags',
		           	'isObj' => false,
		           	'fieldCountName' => 'count',
		           	'visibility' => 0
	            ])
	            ->classes('cf-input')
	            ->placeholder('Add an invisible tag to this article')
	            ->help('Use invisible tags for tags that should not be visible to your audience.')
	            ->hideFromIndex()
	            ->yieldAt('yield_invisible_tags')
            	->inputClass('form-group mb-5'),


            Image::make('Article Image', 'image')
                // ->rules('image','mimes:jpeg,png,jpg,gif','max:2048')
                ->help('A main image (1200px x 675px) serves as a visual representation of your article. It appears on social media when your article is shared.<br>An alternate image (225px x 128px) can be used to show a different photo on the website widgets')
                ->hideFromIndex()
                ->label('+ Add Main Image')
                ->hideFromIndex()
                ->yieldAt('yield_image')
            	->yieldInfoTextSectionAt('yield_image_help')
            	->yieldLabelSectionAt('yield_image_label'),

            Toggle::make('Display?', 'show_image')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_show_image'),

       	   Label::make('', 'thumb_image')
	        	->setStaticValue('+ Add Alternate thumbnail')
	        	->setLabelElement('label')
	        	->classes('cf-label px-3 py-3')
	       		->hideFromIndex()
	       		->container([
	            	'sectionName' => 'thumb_image',
	            	'view' => view('vellum::containers.label', ['yieldName'=>'thumb_image', 'classes'=>'cf-label'])
	            ])
	            ->yieldAt('yield_thumb_image'),

            Tagsinput::make('Author', 'authors')
            	->rules('required')
	            ->relation('id')
	            ->modify(function($id, $post){
	            	$data = '';
	            	if (count(Request::segments()) > 1) {
	            		$data = json_encode(
	            					array_filter($post->author, function($q) {
				            			return empty($q['custom_by_line']);
				            		})
				            	);
	            	} else {
	            		$data = $post->getAuthorNames();
	            	}

	            	return $data;
	            })
	            ->tagsInput([
	            	'apiUrl' => env('UAM_URL').'/author',
	            	'fields' => 'id,display_name',
	            	'fieldName' => 'display_name',
	            	'name' => 'authors',
	            	'isObj' => true
	            ])
	            ->help('You can add multiple authors by selecting their names from the field above.')
	            ->classes('cf-input')
	            ->placeholder('Search by Author Name')
	            ->displayDashboardNotif()
	            ->thWidthAttribute('10%')
	            ->dashboardContainerClass('text-center middle')
	            ->yieldAt('yield_authors')
            	->yieldLabelSectionAt('yield_authors_label')
            	->yieldInfoTextSectionAt('yield_authors_help'),

            Toggle::make('Display?', 'show_author')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_show_author'),

	        Datetime::make('Published', 'date_published')
                ->relation('date_published')
                ->dateConfig(['single' => true, 'dateFormat' => 'ddd, MMM DD, YYYY, hh:mm A'])
                ->modify(function($date_published, $post){
                	if (count(Request::segments()) > 1) {
                		return ($date_published != 0) ? date('D, M d, Y, h:m A', $date_published) : 0;
                		//Y-m-d H:i:s
                	} else {
                		return ($date_published != 0) ? date('M d, Y H:i:s', $date_published) : '';
                	}
                })
                ->classes('form-control')
                ->displayDashboardNotif()
                ->dashboardContainerClass('text-center middle')
                ->labelClasses('hide')
                ->hideOnForms(),

            Datetime::make('Published Copy', 'date_published_copy')
            	->relation('date_published')
                ->dateConfig(['single' => true, 'dateFormat' => 'ddd, MMM DD, YYYY, hh:mm A', 'copyOn' => '#date_published'])
                ->modify(function($date_published, $post){
                	if (count(Request::segments()) > 1) {
                		return ($date_published != 0) ? date('D, M d, Y, h:m A', $date_published) : 0;
                	} else {
                		return ($date_published != 0) ? date('M d, Y H:i:s', $date_published) : '';
                	}
                })
                ->classes('form-control')
                ->labelClasses('hide')
                ->hideFromIndex()
                ->yieldAt('publishDate'),

            Text::make('Custom Byline', 'custom_byline')
            	->relation('custom_byline_author')
	            ->modify(function($customBylineAuthor, $post){
	            	$customAuthorLists = array_filter($post->author, function($q) {
					            			return !empty($q['custom_by_line']);
					            		});

	            	return ($customAuthorLists) ? $customAuthorLists[1]['custom_by_line'] : '';
	            })
	            ->placeholder('Enter custom byline')
	            ->help('If filled, this will always appear on the live site even if Main Author display is turned off.')
	            ->classes('cf-input')
	            ->hideFromIndex()
	            ->yieldAt('yield_custom_byline')
            	->yieldLabelSectionAt('yield_custom_byline_label')
            	->anArrayField()
            	->setJs(['vendor/post/js/custom_byline.js']),

            Text::make('Custom Byline Author Copy', 'custom_byline_author_copy')
            	->labelClasses('hide')
            	->relation('custom_byline_author')
            	->modify(function($customBylineAuthor, $post){
            		$customAuthorLists = array_filter($post->author, function($q) {
					            			return !empty($q['custom_by_line']);
					            		});

            		if (count($customAuthorLists) > 1) {
            			unset($customAuthorLists[1]);
            			return json_encode($customAuthorLists);
            		}
	            })
	            ->yieldAt('yield_custom_byline_author_copy')
	            ->hideFromIndex()
	            ->classes('hide'),

	        Tagsinput::make('Custom Byline Author', 'custom_byline_author')
	        	->relation('custom_byline_author')
	            ->modify(function($customBylineAuthor, $post){
	            	$customAuthorLists = array_filter($post->author, function($q) {
					            			return !empty($q['custom_by_line']);
					            		});
	            	return ($customAuthorLists) ? json_encode([$customAuthorLists[1]]) : '';

	            })
	        	->tagsInput([
	            	'apiUrl' => env('UAM_URL').'/author',
	            	'fields' => 'id,display_name',
	            	'fieldName' => 'display_name',
	            	'name' => 'custom_byline_author',
	            	'isObj' => true,
	            	'isMultiple' => false
	            ])
	            ->placeholder('Search by Author Name')
	            ->help('Please assign a name for this custom byline.')
	            ->labelClasses('hide')
	            ->classes('cf-input hide')
	            ->hideFromIndex()
	            ->yieldAt('yield_custom_byline_author')
	            ->anArrayField(),

	        Label::make('', 'custom_byline_btn')
	        	->setStaticValue('+ Add Another Custom Byline')
	        	->setLabelElement('a')
	       		->hideFromIndex()
	       		->container([
	            	'sectionName' => 'customBylineAuthor',
	            	'view' => view('vellum::containers.label', ['yieldName'=>'customBylineAuthor', 'classes'=>'cf-label'])
	            ])
	            ->yieldAt('yield_custom_byline_btn')
	            ->inputClass('form-group mb-5'),

	        TagsInput::make('Editor', 'editor')
	        	->relation('editor')
	            ->modify(function($editor, $post){
	            	return $post->editor;
	            })
	        	->tagsInput([
	            	'apiUrl' => env('UAM_URL').'/website/staff/3',
	            	'fields' => 'id,display_name',
	            	'fieldName' => 'display_name',
	            	'name' => 'editor',
	            	'isObj' => true,
	            	'isMultiple' => false
	            ])
	            ->placeholder('Search by Name')
	            ->help('Please assign an editor to this article.')
	            ->classes('cf-input')
	            ->hideFromIndex()
	            ->yieldAt('yield_editor'),

	        Text::make('Total Content Cost', 'contributor_fee')
	        	->placeholder('0.00')
	            ->help('This is the total cost of producing the article.')
	            ->classes('cf-input text-right')
	            ->hideFromIndex()
	            ->customLabel('₱')
	            ->customLabelClasses('input-group-addon px-4')
	            ->yieldAt('yield_contributor_fee'),

            Toggle::make('Enable Facebook Instant Article?', 'is_instant')
            	->help('Please specify if you would want to turn on Facebook Instant Articles on mobile.')
            	->hideFromIndex()
            	->labelClasses('hide')
            	->yieldAt('yield_is_instant'),

            Toggle::make('Hide Comments?', 'allow_comments')
            	->help('Please specify if you would want to turn off commenting for this article.')
            	->hideFromIndex()
            	->labelClasses('hide')
            	->yieldAt('yield_allow_comments'),

            Toggle::make('Is This News Content?', 'is_news')
            	->help('Please specify so we may add it to the RSS for Google News Search Results.')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_is_news'),

            Toggle::make('Is This NSFW?', 'is_nsfw')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_is_nsfw'),

            Toggle::make('Push Notification?', 'is_pushed_notif')
            	->help('Please specify if you would want to push notification on OneSignal for this article.')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_is_pushed_notif'),

            Toggle::make('Exclude this article from Google search?', 'is_no_index')
            	->labelClasses('hide')
            	->help('Please specify to disallow search engines to index this page.')
            	->hideFromIndex()
            	->yieldAt('yield_is_no_index'),

            Toggle::make('Enable For Syndication?', 'enable_syndicate')
            	->help('Please specify if you want this article to be seen in the syndication tool by other Summit sites,<br> regardless of the author.')
            	->labelClasses('hide')
            	->hideFromIndex()
            	->yieldAt('yield_enable_syndicate'),

            Text::make('Status', 'status')
            	->hideOnForms()
            	->hideFromIndex(),

            Text::make('Seo Score', 'total_seo_score')
            	->hideOnForms()
            	->setJs(['vendor/seoscore/js/seo_score.js', 'vendor/seoscore/js/seo_score_form.js'])
            	->dashboardContainerClass('text-center middle'),

           	Text::make('Seo Score Breakdown', 'seo_score_breakdown')
           		->relation('seoScore')
	            ->modify(function($seoScore, $post){
	            	return ($post->seoScore) ? json_encode(unserialize($post->seoScore->score_breakdown)) : '';
	            })
           		->hideOnForms()
           		->hideFromIndex(),

           	Text::make('Deleted at')
            ->hideFromIndex()
            ->hideOnForms(),

            /* Longform */
            Button::make('Custom Article Intro', 'intro_btn')
            	->labelClasses('hide')
            	->setStaticValue('+ Add Intro')
            	->classes('btn btn-success btn-block cf-button')
            	->help('You may add a custom article intro before your article starts.')
	       		->hideFromIndex()
	       		->yieldAt('yield_intro_btn')
	       		->setDataAttributes([
            		'data-add-intro'=>''
            	]),

	       	Tinymce::make('', 'intro_text')
	       		->labelClasses('hide')
	        	->tinymceRows(10)
	        	->hideFromIndex()
            	->yieldAt('yield_intro_text')
            	->tinyMceAttributes([
            		'tools' => 'undo redo | bold italic | alignleft aligncenter alignright alignjustify',
            		'hideStatusBar' => true,
            		'charCount' => true,
            		'maxChars' => 80
            	])
            	->anArrayField(),

            Button::make('', 'intro_text_apply_btn')
            	->labelClasses('hide')
            	->setStaticValue('Apply')
            	->hideFromIndex()
            	->classes('btn btn-primary cf-button')
            	->yieldAt('yield_intro_text_apply_btn')
            	->setDataAttributes([
					'data-id'=>'',
					'data-intro-text-apply-btn'=>''
				]),

            Button::make('', 'add_more_intro_btn')
            	->labelClasses('hide')
            	->setStaticValue('+ Add Another Intro')
            	->hideFromIndex()
            	->classes('btn btn-success btn-block cf-button')
            	->yieldAt('yield_add_more_intro_btn')
            	->setDataAttributes([
            		'data-add-more-intro'=>''
            	])

        ];
    }

    public function filters()
    {
        return [
        	\Quill\Post\Filters\DatePublished::class,
        	\Quill\Post\Filters\SortBy::class,
        	\Quill\Post\Filters\Tags::class,
            \Quill\Post\Filters\Section::class,
            \Quill\Post\Filters\Authors::class,
            \Quill\Post\Filters\Status::class,
        ];
    }

    public function actions()
    {
    	$deleteDialogNotif = [
    		'header' => 'Are you sure you want to disable this article? You will no longer be able to publish this after it has been disabled.',
    		'valueDisplayedIn' => [
    			'title' => 'title',
    			'subText' => 'author'
    		],
    		'dismiss' => 'Cancel',
    		'continue' => 'Yes, Delete this article'
    	];

        return [
            // new \Vellum\Actions\ViewAction,
            new \Vellum\Actions\DeleteAction($deleteDialogNotif, true),
            // new \Quill\Post\Actions\PreviewAction,
        ];
    }

    public function modalActions()
    {
        return [
            new \Vellum\Actions\ViewAction
        ];
    }

    public function excludedFields()
    {
    	return ['meta_title', 'meta_description', 'meta_canonical', 'custom_byline', 'authors', 'pushed_notif', 'visible_tags', 'invisible_tags', 'custom_byline', 'custom_byline_author', 'custom_byline_author_copy', 'custom_byline_btn', 'seo_score_breakdown', 'intro_btn', 'intro_text_1', 'intro_text_apply_btn', 'add_more_intro_btn', 'intro_text', 'intros_order_id', 'intros_id', 'intros_mobile_image', 'intros_desktop_image'];
    }

}
