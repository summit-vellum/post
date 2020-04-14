$(document).ready(function(){
	$('.post-submit').on('click', function(e){
		var
          required = '',
          hasTagInput = false;

        if ($(this).hasClass('publish-later') && ($('input[name="date_published"]').val() ==  0 ||
        	$('input[name="date_published"]').val() == '' )) {
                alert("Please select a date for publish later");
                return false;
        }

        $('form input[required]').css('border-color', '');
        $('form input[required]').siblings('.bootstrap-tagsinput').css('border-color', '');

		$('form').find(':input').each(function(){
			var value = $.trim($(this).val()),
				id = $(this).attr('id');

				if (id == 'content') {
					value = tinymce.get('content').getContent();
				}

            if ($(this).prop('required') && (value == '' || value == '[]'))	 {
            	required = id;

            	hasTagInput = $(this).siblings('.bootstrap-tagsinput');

            	if (hasTagInput.length > 0) {
                    var position = $(hasTagInput).offset().top - 100;
                    $(hasTagInput).css('border-color', 'red');
                    $(hasTagInput.siblings('[data-tagsinput]')).tagsinput('focus');;
            	} else {
            		$('#'+required).css('border-color', 'red');

	            	if (required == 'content') {
	            		required = 'content_ifr';
	            		tinymce.get('content').getBody().focus();
	            	} else {
	            		$('#'+required).focus();
	            	}

	            	var position = $('#'+required).offset().top - 100;
            	}

            	$('html, body').animate({
                    scrollTop: position
                }, 2000);

                return false;
            }
		});

		var postStatus = (typeof($(this).data('status')) !== 'undefined') ? $(this).data('status') : $('input[id="status"]').val();

		if (!required) {
			$('input[name="status"]').val(postStatus);

			if ($(this).data('publish') == 'now') {
                $('input[name="date_published"]').val(moment(new Date()).format('YYYY-MM-DD hh:mm A'));
            }

            $("#form-post").submit();
		}
	});
});
