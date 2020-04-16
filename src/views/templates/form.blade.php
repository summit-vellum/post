<div class="container">
	<input type="hidden" id="post_config" value="{{ json_encode($site) }}">
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
		@yield('yield_url')
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
	<input type="hidden" id="image_source" value="{{ $site['image_domain'] }}">
	<section class="container mb-4">
		<div class="container-fluid hide" data-longform>
			<div class="row">
				<div class="col-md-12">
					<label class="cf-label">Custom Article Intro</label>
				</div>
			</div>
			<div class="row" data-add-intro-cntnr>
				<div class="col-md-6 mb-3">@yield('yield_intro_btn')</div>
			</div>
		</div>
		<div class="container-fluid hide" data-longform-details>
			<div class="row">
				<div class="col-md-12">
					<label class="cf-label">Custom Article Intro</label>
				</div>
			</div>
			<div data-longform-container>
				<div class="mb-5 article-longform-items" data-longform-items>
					<!-- custom intro text + buttons -->
					<div class="row mb-4 intro-details" data-intro-details>
						<div class="col-md-9" data-intro-text>@yield('yield_intro_text')</div>
						<div class="col-md-2">@yield('yield_intro_text_apply_btn')</div>
						<div class="pull-right mr-3">
							<button type="button" class="btn-block intro-up glyphicon glyphicon-triangle-top" data-intro-up></button>
							<button type="button" class="btn-block intro-down glyphicon glyphicon-triangle-bottom" data-intro-down></button>
	                        <button type="button" class="btn-block intro-delete glyphicon glyphicon-trash pt-1" data-intro-delete></button>
						</div>
					</div>
					<!-- switch to desktop/mobile image and text preview -->
					<div class="row text-center mb-2">
						<div class="px-0 intro-format">
							<button type="button" class="btn intro-bg-color glyphicon glyphicon-phone" data-intro-format="mobile"></button>
							<button type="button" class="btn intro-bg-color fa fa-desktop" data-intro-format="desktop"></button>
							<input type="hidden" name="intros_order_id[]" class="intro-count" value="1" data-intro-count>
							<input type="hidden" name="intros_id[]" value="">
							<input type="hidden" id="articleIntroMobileIndex">
							<input type="hidden" id="articleIntroDesktopIndex">
						</div>
					</div>
					<!-- preview on mobile -->
					<div class="intro-container intro-mobile intro-mobile-width" data-preview-mobile>
	                    <div class="row text-center mb-2">
	                        <div class="px-0 intro-preview intro-preview-mobile" data-intro-preview-mobile>
	                            <a href="#" class="intro-upload-mobile" data-toggle="modal" data-target="#toolModal" data-url="">
	                                <div class="glyphicon glyphicon-edit intro-upload">
	                                </div>
	                            </a>
	                            <div class="intro-image">
	                                <img src="{{ $site['image_domain'] }}/images/420x750.png" class="articleIntroMobileDisplay intro-preview-mobile">
	                                <input type="hidden" name="intros_mobile_image[]" value="/images/420x750.png" class="articleIntroMobilePath">
	                            </div>
	                            <div id="intro-mobile-subtext" class="intro-subtext" data-intro-subtext><p class="text-left">Text goes here</p></div>
	                        </div>
	                    </div>
	                    <div class="row px-0 mb-2">
	                        @icon(['icon' => 'info'])
	                        <small class="cf-note">Image should be 420px x 750px.</small>
	                    </div>
	                </div>
	                <!-- preview on desktop -->
	                <div class="intro-container intro-desktop intro-desktop-width hide" data-preview-desktop>
	                    <div class="row text-center mb-2">
	                        <div class="px-0 intro-preview intro-preview-desktop" data-intro-preview-desktop>
	                            <a href="#" class="intro-upload-desktop" data-toggle="modal" data-target="#toolModal" data-url="">
	                                <div class="glyphicon glyphicon-edit intro-upload">
	                                </div>
	                            </a>
	                            <div class="intro-image">
	                                <img src="{{ $site['image_domain'] }}/images/2000x1250.png" class="articleIntroDesktopDisplay intro-preview-desktop">
	                                <input type="hidden" name="intros_desktop_image[]" value="/images/2000x1250.png" class="articleIntroDesktopPath">
	                            </div>
	                            <div id="intro-desktop-subtext" class="intro-subtext" data-intro-subtext><p class="text-left">Text goes here</p></div>
	                        </div>
	                    </div>
	                    <div class="row px-0 mb-2">
	                        @icon(['icon' => 'info'])
	                        <small class="cf-note">Image should be 2000px x 1250px.</small>
	                    </div>
	                </div>
				</div>
			</div>
			<!-- Article Another Intro -->
            <div class="row" data-add-more-intro-cntnr>
                <div class="col-md-6 mb-3">@yield('yield_add_more_intro_btn')</div>
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




