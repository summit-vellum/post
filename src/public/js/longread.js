$(document).ready(function(){
	var introCount = 0;

	$(document).on('change', '#longform', function(){
		if ($(this).prop('checked') == true) {
			$('[data-longform]').removeClass('hide');
		} else {
			$('[data-longform]').addClass('hide');
			$('[data-longform-details]').addClass('hide');
		}
	});

	$(document).on('click', '[data-add-intro]', function(){
		$('[data-longform]').addClass('hide');
		$('[data-longform-details]').removeClass('hide');
		introCount++;
	});

	$(document).on('click', '[data-add-more-intro]', function(){
		introCount++;
	});

	/* desktop/mobile preview visibility */
	$(document).on('click', '[data-intro-format]', function(){
		var format = $(this).data('introFormat'),
			container = $(this).closest('[data-longform-items]');

		container.find('[data-preview-'+format+']').removeClass('hide');

		if (format == 'mobile') {
			container.find('[data-preview-desktop]').addClass('hide');
		} else if (format == 'desktop') {
			container.find('[data-preview-mobile]').addClass('hide');
		}
	});

	/* apply text to intro's preview, desktop/mobile */
	$(document).on('click', '[data-intro-text-apply-btn]', function(){
		var id = $(this).data('id'),
			container = $(this).closest('[data-longform-items]'),
			textField = tinymce.get('intro_text'+id).getContent(),
			alignment = 'text-align:';

			if (textField.indexOf(alignment) <= -1) {
                container.find('[data-intro-subtext]').html(textField);
                $('[data-intro-subtext] p').attr('style', 'text-align: left');
            }else{
                container.find('[data-intro-subtext]').html(textField);
            }
	});

	/* moves intro downwards */
	$(document).on('click', '[data-intro-down]', function(){
		var nextIndex = parseInt($(this).parents('[data-longform-items]').next().find('input[name="intros_order_id[]"]').val()),
            index = parseInt($(this).closest('[data-longform-items]').find('input[name="intros_order_id[]"]').val()),
            nextId = $(this).parents('[data-longform-items]').next().attr('data-id'),
            prevId = $(this).closest('[data-longform-items]').attr('data-id'),
            nextTinymce = (nextId) ? parseInt(nextId) : '',
            prevTinymce = (prevId) ? parseInt(prevId) : '';

            if (nextIndex > 0){
                tinymce.execCommand('mceRemoveEditor', false, 'intro_text'+nextTinymce);
                tinymce.execCommand('mceRemoveEditor', false, 'intro_text'+prevTinymce);
                $(this).parents('[data-longform-items]').next().find('input[name="intros_order_id[]"]').val(index);
                $(this).parents('[data-longform-items]').insertAfter($(this).parents('[data-longform-items]').next());
                $(this).closest('[data-longform-items]').find('input[name="intros_order_id[]"]').val(nextIndex);
                tinymce.execCommand('mceAddEditor', true, 'intro_text'+nextTinymce);
                tinymce.execCommand('mceAddEditor', true, 'intro_text'+prevTinymce);
            }
	});

	/* moves intro upwards */
	$(document).on('click', '[data-intro-up]', function(){
		var prevIndex = parseInt($(this).parents('[data-longform-items]').prev().find('input[name="intros_order_id[]"]').val()),
            index = parseInt($(this).closest('[data-longform-items]').find('input[name="intros_order_id[]"]').val()),
            prevId = $(this).parents('[data-longform-items]').prev().attr('data-id'),
            nextId = $(this).closest('[data-longform-items]').attr('data-id'),
            prevTinymce = (prevId) ? parseInt(prevId) : '',
            nextTinymce = parseInt($(this).closest('[data-longform-items]').attr('data-id'));

        if (prevIndex > 0) {
            tinymce.execCommand('mceRemoveEditor', false, 'intro_text'+prevTinymce);
            tinymce.execCommand('mceRemoveEditor', false, 'intro_text'+nextTinymce);
            $(this).parents('[data-longform-items]').prev().find('input[name="intros_order_id[]"]').val(index);
            $(this).parents('[data-longform-items]').insertBefore($(this).parents('[data-longform-items]').prev());
            $(this).closest('[data-longform-items]').find('input[name="intros_order_id[]"]').val(prevIndex);
            tinymce.execCommand('mceAddEditor', true, 'intro_text'+prevTinymce);
            tinymce.execCommand('mceAddEditor', true, 'intro_text'+nextTinymce);
        }
	});

	$(document).on('click', '[data-intro-delete]', function(){
		var items = $('[data-longform-items]').length,
					index = introCount + 1;

		if (items > 1) {
            $(this).closest('[data-longform-items]').remove();
        } else {
        	// $('[data-longform], [data-longform-details]').addClass('hide');

            // reset
            $('[data-longform-items] input').val('');
            $('[data-longform-items] img').attr('src', '');
            $('[data-longform-items]').attr('data-id', index);
            $('[data-intro-text-apply-btn]').attr('data-id', index);
            $('[data-intro-text]').remove();
            $('[data-intro-count]').val(index);
            $('[data-intro-subtext]').html('<p style="text-align: left">Text goes here</p>');
            $('[data-intro-details]').prepend('<div class="col-md-9" data-intro-text><textarea id="intro_text'+index+'" rows="10" name="intros_text[]"></textarea></div>');
            $('#longform').bootstrapToggle('off');
            introTinymce('#intro_text'+index+'');

            // set to default mobile preview
            $('[data-preview-desktop]').addClass('hide');
            $('[data-preview-mobile]').removeClass('hide');
        }

	});

	/* add more intros */
	$(document).on('click', '[data-add-more-intro]', function(){
		var template = $('[data-longform-items]:last').clone(),
                index = introCount + 1,
                imageSource = $('#image_source').val();

        // reset
        template.attr('data-id', index);
        template.find('input').val('');
        template.find('[data-intro-preview-mobile] img').attr('src', imageSource+'/images/420x750.png');
        template.find('[data-intro-preview-desktop] img').attr('src', imageSource+'/images/2000x1250.png');
        template.find('input[name="intros_mobile_image[]"]').val('/images/420x750.png');
        template.find('input[name="intros_desktop_image[]"]').val('/images/2000x1250.png');
        template.find('[data-intro-text]').remove();
        template.find('[data-intro-subtext]').html('<p style="text-align: left">Text goes here</p>');
        template.find('[data-intro-text-apply-btn]').attr('data-id', index);
        template.find('[data-intro-details]').prepend('<div class="col-md-9" data-intro-text><textarea id="intro_text'+index+'" rows="10" name="intros_text[]"></textarea></div>');

        // // set to default mobile preview
        template.find('[data-preview-desktop]').addClass('hide');
        template.find('[data-preview-mobile]').removeClass('hide');

        // // count number of intros
        template.find('[data-intro-count]').val(index);
        $('[data-longform-container]').append(template);

        introCount++;
        introTinymce('#intro_text'+index+'');
	});
})

var introTinymce = function(selector) {
	console.log(selector);
	var settings = {
		selector:selector,
        width: '100%',
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tiny.cloud/css/codepen.min.css'
        ],
        toolbar:'undo redo | bold italic | alignleft aligncenter alignright alignjustify',
        setup: function(editor) {
        	editor.on('keyup', function (e) {
        		updateCharCounter(this, getContentLength(tinymce));
            });

            var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
	            editor.on('keydown', function (e) {
	                if (allowedKeys.indexOf(e.keyCode) != -1) return true;
	                if (getContentLength(tinymce) + 1 > this.settings.max_chars) {
	                    e.preventDefault();
	                    e.stopPropagation();
	                    return false;
	                }
	                return true;
	            });
        },
        statusbar: false,
        max_chars: 30,
        init_instance_callback: function () {
			// initializes counter div with max char count
            $('#' + this.id).siblings('.tox-tinymce')
            				.find('.tox-editor-container')
            				.append($('<div class="char_count" style="text-align:right;color:#999;padding:4px;"></div>'));
            updateCharCounter(this, getContentLength(tinymce));
        },
        paste_preprocess: function (plugin, args) {
            var activeEditor = tinymce.get(tinymce.activeEditor.id);
            var len = activeEditor.contentDocument.body.innerText.length;
            var text = $(args.content).text();
            if (len + text.length > activeEditor.settings.max_chars) {
                alert('Pasting this exceeds the maximum allowed number of ' + activeEditor.settings.max_chars + ' characters.');
                args.content = '';
            } else {
                updateCharCounter(activeEditor, len + text.length);
            }
        }
	}

	tinymce.init(settings);
}
