@extends('vellum::modal')

@section('title', $page_title)

@push('css')
<style>
    #filter:not(checked) ~ .filter-menu {
        display: none;
    }
    #filter:checked ~ .filter-menu {
        display: inline-block;
    }

</style>
@endpush

@section('head')
    @include('vellum::modal.header-buttons', ['attributes' => arrayToHtmlAttributes(['id' => 'insert-shortcode', 'disabled' => 'disabled'])])
@endsection

@section('content')
<ul class="nav-reco nav nav-tabs">
	<li class="{{ ($type == 'single') ? 'active' : '' }}" >
		<a href="{{ route('article-reco.index', ['type' => 'single']) }}" article-reco-type>Single Article</a>
	</li>
	<li class="{{ ($type == 'multiple') ? 'active' : '' }}">
		<a href="{{ route('article-reco.index', ['type' => 'multiple']) }}" article-reco-type>Multiple Articles</a>
	</li>
</ul>
<div class="navtab-content">
	<div id="single" class="tab-pane fade in active">
		<div class="px-3">
			<div class="row">
				<h4>Widget Label</h4>
				<div class="col-md-13 pl-0 mb-3">
					<input type="text" class="cf-input single-widget" data-shortcode="widget" data-shortcode-listen="change" name="widget" value="" placeholder="{{ $widget_placeholder }}" data-validation-bypass = true data-default-value="{{ $widget_placeholder }}">
				</div>
				<div class="mt-2">
					@icon(['icon' => 'info', 'classes'=>'pull-left'])
					<small class="cf-note">If you wish to change the label for this widget please replace the text inside the space provided.</small>
				</div>
			</div>
			<div class="row">
				<h4>Recommended Article</h4>
				@include('vellum::modal.search', ['placeholder' => 'Search for article id or title...', 'type' => $type])
				<div class="mb-3">
					@if ($type == 'multiple')
						@icon(['icon' => 'info', 'classes'=>'pull-left'])
						<small class="cf-note">The minimum and maximum article inserts are 4.</small>
					@endif
				</div>
			</div>
			<div class="row">
				@include('vellum::modal.selected', ['selected' => $selected])
		        @include('vellum::modal.selected-items')
			</div>
			<div class="row">
		        @include('vellum::modal.table', ['collections' => $collections, 'attributes' => $attributes])
			</div>
		</div>
	</div>
</div>

<!-- <div data-shortcode="image" data-shortcode-listen="observe" data-shortcode-value="" id="shortcode-observer">
	<img src="" data-shortcode-prop="src" data-shortcode="image" data-shortcode-listen="observe">
</div> -->

<input type="text" value="{{ $shortcode }}" name="shortcode" id="shortcode" placeholder="Widget Name..." data-shortcode="widget" data-shortcode-listen="change" data-checkbox-validation="Sorry, you can only add up 4 articles per widget" data-checkbox-min=4 data-checkbox-max=4 data-shortcode-trigger="#insert-shortcode" data-shortcode-url="{{ $shortcode_route }}" class="hide">

@endsection


@push('scripts')
<script src="{{ asset('vendor/vellum/js/shortcode.js') }}"></script>
<script>
	var shortcodeRoute = '{{ $shortcode_route}}';

    document.querySelector('#insert-shortcode').addEventListener('click', function(event){
		var iframe = document.querySelector('[name="tools"]'),
			shortcode = document.querySelector('#shortcode').value;
		parent.tinymce.get('content').execCommand('mceInsertContent', false, shortcode);

		updateCookieInLaravel(shortcodeRoute, {deleteAllCookie:1}, true);

		$('[close-modal]').click();

    	event.preventDefault();
    });

    $('[close-modal], [article-reco-type]').click(function(){
    	updateCookieInLaravel(shortcodeRoute, {deleteAllCookie:1}, true);
    });
</script>

@endpush
