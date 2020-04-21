$(document).ready(function(){

	$('[data-post-preview]').on('click', function(e){
		$('#toolModal').find('.modal-dialog').css('width', '1280px');
	});

	$(document).on('click', function() {
		($('[flash-message]').length) ? $('[btn-post-save]').removeClass('hide') : '';
		$('[flash-message]').remove();
	});

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

		var postStatus = (typeof($(this).data('status')) !== 'undefined') ? $(this).data('status') : $('input[id="status"]').val(),
			onEditForm = (typeof($(this).data('status')) == 'undefined') ? true : false;

		if (onEditForm) {
			$('[btn-post-save]').addClass('hide');
			$('[data-post-loader]').removeClass('hide');
		}

		if (!required && postStatus != 2) {
			$('input[name="status"]').val(postStatus);

			if ($(this).data('publish') == 'now') {
                $('input[name="date_published"]').val(moment(new Date()).format('YYYY-MM-DD hh:mm A'));
            }

            $("#form-post").submit();
		} else {
			$('[post-satus-btn]').click();
		}
	});

	var isPublished = $('#is_published').val(),
		uneditableFields = $('#uneditable_fields').val();

	uneditableFields = (uneditableFields) ? JSON.parse(uneditableFields) : '';

	var articleUrl = '#url';

	/* generates article url */
	var generateSectionUrl = function(id) {
		ajaxPartialUpdate('/sections/search-section', 'POST', {id:id}).then(function(response){
			if (response.success) {
				url(response.section.url, $('#slug').val(), articleUrl);
			}
		});
	}

	url('', $('#slug').val(), articleUrl);
	generateSectionUrl($('#section_id').val());

	/* disables slug and section when an article have been published */
	if (isPublished == 1) {
		$.each(uneditableFields, function(index, field) {
			$('#'+field).attr('readOnly', true);
			$('#'+field).parents().find('.bootstrap-select').addClass('disabled');
		});

	} else {
		/* udapte url value based on slug and selected section */
		$(document).on('change', '#slug' , function() {
			url('', $(this).val(), articleUrl);
			generateSectionUrl($('#section_id').val());
		});

		$(document).on('change', '#section_id', function(){
			generateSectionUrl($(this).val());
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

    searchVol.val($('#search_volume').attr('value').trim());
    if (searchVol.val() != '') {
    	searchVol.attr('disabled', false);
    } else {
    	searchVol.attr('disabled', true);
    }
});
