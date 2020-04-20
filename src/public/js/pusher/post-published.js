// Subscribe to the channel we specified in our Laravel Event
var postPublished = pusher.subscribe('post-published');

// Bind a function to a Event (the full Laravel class)
postPublished.bind('Quill\\Post\\Events\\PostPublished', function(data) {
	var notifContainer = $('[data-notif-container]'),
		notifClone = $('[data-notif]').eq(0).clone(),
		notifPostData = JSON.parse(data.message);

	notifClone.find('[data-notif-title]').text(notifPostData.title)
										 .attr('href', notifPostData.url);
	notifClone.find('[data-notif-author]').text(notifPostData.author);
	notifClone.show();
	notifContainer.prepend(notifClone);

	setTimeout(function(){
        notifClone.hide();
    }, pusherTimeout);
});

postPublished.bind('pusher:subscription_succeeded', function(members) {

});
