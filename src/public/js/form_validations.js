$(document).ready(function(){
	/* fields validation */
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

	var isPublished = $('#is_published').val(),
		uneditableFields = $('#uneditable_fields').val();

	uneditableFields = (uneditableFields) ? JSON.parse(uneditableFields) : '';

	/* disables slug and section when an article have been published */
	if (isPublished == 1) {
		$.each(uneditableFields, function(index, field) {
			$('#'+field).attr('readOnly', true);
			$('#'+field).parents().find('.bootstrap-select').addClass('disabled');
		});
	}

	/* enables search volume if seo keyword has value */
	var seoKeyword = $('#seo_keyword'),
		searchVol = $('#search_volume');

	seoKeyword.on('keyup', function(event) {
        if(seoKeyword.val() != '' && searchVol.val() == ''){
            searchVol.css('border-color', 'red')
            		 .attr('required', true)
            		 .attr('disabled', false);
        }else if(seoKeyword.val() == '' && searchVol.val() != ''){
            seoKeyword.css('border-color', 'red')
            		  .attr('required', true);
        }else{
            searchVol.removeAttr("style")
            		 .attr('required', false);
            seoKeyword.removeAttr("style")
            		  .attr('required', false);
        }
    });

    searchVol.on('blur', function(event) {
        if (seoKeyword.val() != '' && searchVol.val() == '') {
            searchVol.css('border-color', 'red')
            		 .attr('required', true);
        } else if (seoKeyword.val() == '' && searchVol.val() != '') {
            seoKeyword.css('border-color', 'red')
            		  .attr('required', true);
        } else {
            searchVol.removeAttr("style")
            		 .attr('required', false);
            seoKeyword.removeAttr("style")
            		  .attr('required', false);
        }
    });

});
