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
});

var appendCustomByline = function(index, value) {
	var customBylineClone = customByLine.eq(0).clone();

	customBylineClone.find('.yield_custom_byline #custom_byline').val('')
		   														 .attr('id', 'custom_byline_'+index)

	customBylineClone.find('.yield_custom_byline_author #custom_byline_authorList').val('[]')
																				   .attr('id', 'custom_byline_author_'+index+'List');

	customBylineClone.find('.yield_custom_byline_author .bootstrap-tagsinput').remove();

	customBylineAuthorConfig = customBylineClone.find('.yield_custom_byline_author #custom_byline_author').data('tagsinputConfig');
	customBylineAuthorConfig.name = 'custom_byline_author_'+index;
	customBylineClone.find('.yield_custom_byline_author #custom_byline_author').removeAttr('data-tagsinput')
																			   .attr('data-tagsinput-config', JSON.stringify(customBylineAuthorConfig));

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
}
