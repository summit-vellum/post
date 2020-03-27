<div class="container">
	<div class="container px-0 container-max-width">
		@yield('yield_longform')
		<div class="row mt-7">
			<div class="col-md-6">
				@yield('yield_seo_keyword')
			</div>
			<div class="col-md-2">
				@yield('yield_search_volume')
			</div>
			<div class="col-md-12">
				@yield('yield_seo_keyword_help')
			</div>
		</div>
		@yield('yield_title')
		@yield('yield_headline')
		@yield('yield_meta_title')
		@yield('yield_section')
		@yield('yield_slug')
		@yield('yield_blurb')
		@yield('yield_meta_description')
		@yield('yield_meta_canonical')
	</div>
	<section class="gradient pt-6 pb-3">
		<div class="container px-0 " style="max-width: 740px;">
			@yield('yield_content')
		</div>
	</section>
	<hr>
	<section class="container mb-4">
		<div class="container-fluid">
			@yield('yield_summary')
			@yield('yield_visible_tags')
			@yield('yield_invisible_tags')
			@yield('yield_seo_topic')
		</div>
	</section>
	<hr>
	<section class="container mb-4">
		<div class="container-fluid">
			<div class="row">
				<div <div class="col-md-12">
					@yield('yield_image_label')
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">@yield('yield_image')</div>
				<div class="col-md-2 display-image">@yield('yield_show_image')</div>
				<div class="col-md-4">@yield('yield_thumb_image')</div>
				<div class="col-md-12">@yield('yield_image_help')</div>
			</div>
		</div>
	</section>
	<hr>
	<section class="container mb-4">
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-md-6">@yield('yield_authors_label')</div>
    		</div>
    		<div class="row">
    			<div class="col-md-6">@yield('yield_authors')</div>
    			<div class="col-md-2">@yield('yield_show_author')</div>
    			<div class="col-md-12">@yield('yield_authors_help')</div>
    		</div>
    		<div class="row mt-5">
    			<div class="col-md-6">@yield('yield_custom_byline_label')</div>
    		</div>
    		<!-- custom byline start -->
    		<div class="row">
    			@yield('yield_custom_byline_author_copy')
    			<div data-custom-byline-container>
    				<div data-custom-byline>
		    			<div class="col-md-6">@yield('yield_custom_byline')</div>
		    			<div class="col-md-6">@yield('yield_custom_byline_author')</div>
		    		</div>
    			</div>
    			<div class="col-md-6 mt-5">@yield('yield_custom_byline_btn')</div>
    		</div>
    		<!-- custom byline end-->
    		<div class="row">
    			<div class="col-md-6">@yield('yield_editor')</div>
    			<div class="col-md-6">@yield('yield_contributor_fee')</div>
    		</div>
    	</div>
    </section>
    <hr>
    <section class="container mb-4">
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-md-12">
    				<label class="cf-label">Other Settings</label>
    			</div>
    		</div>
    		<div class="row">
				<div class="col-md-6 mb-5">@yield('yield_is_instant')</div>
				<div class="col-md-6 mb-5">@yield('yield_allow_comments')</div>
			</div>
			<div class="row">
				<div class="col-md-6 mb-5">@yield('yield_is_news')</div>
				<div class="col-md-6 mb-5">@yield('yield_is_nsfw')</div>
			</div>
			<div class="row">
				<div class="col-md-6 mb-5">@yield('yield_is_pushed_notif')</div>
				<div class="col-md-6 mb-5">@yield('yield_is_no_index')</div>
			</div>
			<div class="row">
				<div class="col-md-6 mb-5">@yield('yield_enable_syndicate')</div>
			</div>
    	</div>
    </section>
</div>




