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

@section('content')

    <div class="">

        <h1 class="text-4xl font-bold mb-5 mt-10">{{ $details['title'] }}</h1>

    	<div data-shortcode="image" data-shortcode-listen="observe" data-shortcode-value="" id="shortcode-observer">
    		<img src="" data-shortcode-prop="src" data-shortcode="image" data-shortcode-listen="observe">
    	</div>

        <form action="">

	        <div class="flex mb-10">

	            <div class="w-3/4">
	                @include('vellum::search')
	            </div>

	            @can('create', $details['model'])

	            <div class="w-1/4 text-right">

            		@button(['action'=>'create', 'icon'=>'plus','color'=>'blue','label'=>'Create new'])

	            </div>

	            @endcan

	        </div>
	        <!-- <input type="text" data-shortcode="widget" data-shortcode-listen="change" name="widget" value="" > -->

	        @include('vellum::modal.selected', ['selected' => $selected])

	        @include('vellum::table', ['collections' => $collections, 'attributes' => $attributes])


        </form>

    </div>

    <input type="text" value="{{ $shortcode }}" name="shortcode" id="shortcode" placeholder="Widget Name..." data-shortcode="widget" data-shortcode-listen="change">

@endsection


@push('scripts')
<script src="{{ asset('vendor/vellum/js/shortcode.js') }}"></script>
<script>
    var shortcode = document.querySelector('#shortcode');

    shortcode.addEventListener('change', function() {
        //...
    });
</script>

@endpush
