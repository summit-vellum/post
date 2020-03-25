$(document).ready(function(){
	$('.post-submit').on('click', function(){
		if (typeof($(this).data('status')) !== 'undefined') {
			$('input[name="status"]').val($(this).data('status'));

			if ($(this).data('publish') == 'now') {
                $('input[name="date_published"]').val(moment(new Date()).format('YYYY-MM-DD hh:mm A'));
            }

            $('button[name="submit"]').trigger('click');
		}
	});
});
