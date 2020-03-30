var customByLine = $('[data-custom-byline]'),
	customByLineContainer = $('[data-custom-byline-container]'),
	customBylineAuthorConfig = '',
	customByLineIndex = 0,
	customByLineCopy = $('#custom_byline_author_copy').val();

$(document).ready(function(){
	if (customByLineCopy != '') {
		$.each(JSON.parse(customByLineCopy), function(index, value){
			appendCustomByline(customByLineIndex, value);
			customByLineIndex++
		});
	}

	$('#custom_byline_btn').on('click', function(){
		appendCustomByline(customByLineIndex, '');
		customByLineIndex++;
	});

	$('*[data-custom-byline]').find('[data-tagsinput]').on('itemAdded', makeCustomeBylineRequired);
	$('*[data-custom-byline]').find('[data-tagsinput]').on('itemRemoved', makeCustomeBylineRequired);

	$(document).on('blur', '[name*="custom_byline"]', function(){
		$(this).removeAttr('style');
        var makeCbAuthorRequired = $(this).parents('[data-custom-byline]').find('[name="custom_byline_author[]"]');

		if ($(this).val() == '') {
			makeCbAuthorRequired.attr('required', false);
		} else {
			makeCbAuthorRequired.attr('required', true);
		}
    });

});

var makeCustomeBylineRequired = function(event) {
	$(event.target).siblings('.bootstrap-tagsinput').removeAttr('style');
	var makeCbRequired = $(this).parents('[data-custom-byline]').find('[name="custom_byline[]"]');

	if ($(this).val() == '') {
		makeCbRequired.attr('required', false);
	} else {
		makeCbRequired.attr('required', true);
	}
}

var appendCustomByline = function(index, value) {
	var customBylineClone = customByLine.eq(0).clone();

	customBylineClone.find('.yield_custom_byline #custom_byline').val('')
		   														 .attr('id', 'custom_byline_'+index)

	customBylineClone.find('.yield_custom_byline_author #custom_byline_authorList').val('[]')
																				   .attr('id', 'custom_byline_author_'+index+'List');

	customBylineClone.find('.yield_custom_byline_author .bootstrap-tagsinput').remove();

	customBylineAuthorConfig = customBylineClone.find('.yield_custom_byline_author #custom_byline_author').data('tagsinputConfig');
	customBylineAuthorConfig.name = 'custom_byline_author_'+index;
	customBylineClone.find('.yield_custom_byline_author #custom_byline_author').attr('data-tagsinput-config', JSON.stringify(customBylineAuthorConfig));

	customBylineClone.find('.yield_custom_byline_author #custom_byline_author').attr('id', 'custom_byline_author_'+index);

	customByLineContainer.append(customBylineClone);

	var clonedBylineAuthor = $(customBylineClone.find('.yield_custom_byline_author #custom_byline_author_'+index)),
		clonedByline = customBylineClone.find('.yield_custom_byline #custom_byline_'+index);

	initTagsInput(clonedBylineAuthor, 0);

	clonedBylineAuthor.closest('div').addClass('mt-5');
	clonedByline.closest('div').addClass('mt-5');

	if (value != '') {
		clonedBylineAuthor.tagsinput('add', value);
		clonedByline.val(value.custom_by_line);
	}

	clonedBylineAuthor.on('itemAdded', makeCustomeBylineRequired);
	clonedBylineAuthor.on('itemRemoved', makeCustomeBylineRequired);

}
