<?php

return [

    'name' => 'Article',

    'limit' => 10,

    'uneditable_fields' => ['slug', 'section_id'],

    'resource_lock' => [
    	'title' => 'title',
    	'pre_author_txt' => 'by:',
    	'author' => 'author_names',
    ]
];
