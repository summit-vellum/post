<?php

return [

    'name' => 'Article',

    'limit' => 10,

    'uneditable_fields' => ['slug', 'section_id'],

    'resource_lock' => [
    	'title' => 'title',
    	'pre_author_txt' => 'by:',
    	'author' => 'author_names',
    ],

    'delete_dialog_notif' => [
    	'header' => 'Are you sure you want to disable this article? You will no longer be able to publish this after it has been disabled.',
		'valueDisplayedIn' => [
			'title' => 'title',
			'preSubText' => 'by:',
			'subText' => 'author_names'
		],
		'dismiss' => 'Cancel and go back to dashboard',
		'continue' => 'Continue and disable this article'
    ]
];
