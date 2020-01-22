function()
{
	$('#modal').removeClass('hidden');
	$('#modal iframe').attr('src', '{{ $shortcode['url'] }}');

	$('#modal #modal-title').html('{!! $shortcode['icon'].' '.$shortcode['label'] !!}');

}
