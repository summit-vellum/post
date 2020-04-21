@extends('vellum::modal')

@push('css')

@endpush

@section('head')
    @include('post::modal.preview-header', ['rightBtnClass' => 'hide', 'attributes' => arrayToHtmlAttributes(['id' => 'insert-shortcode', 'disabled' => 'disabled'])])
@endsection

@section('content')
<div class="preview">
	<div class="preview-platform preview-mobile" id="platform-mobile">
		<iframe src="{{ $link }}?preview=1&device=mobile" height="100%" frameborder="0" width="100%" class="full-height"></iframe>
	</div>
	<div class="preview-platform preview-desktop" id="platform-desktop">
        <iframe src="{{ $link }}?preview=1&device=desktop" height="100%" frameborder="0" width="100%" class="full-height"></iframe>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
	$('#toolModal', window.parent.document).click(function(){
		removeToolModalStyle();
	});

	$('[close-modal]').click(function(){
		removeToolModalStyle();
	});

	var removeToolModalStyle = function(){
		$('#toolModal', window.parent.document).find('.modal-dialog').removeAttr('style');
	}

	$('.btn-preview-platform').on('click', function(event) {
        $('.btn-preview-platform').removeClass('selected');
        $(this).addClass('selected');
    });
</script>
@endpush

