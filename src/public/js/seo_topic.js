var getSeoTopic = function(item) {
	var obj = [];
    $.each(item[1], function(index){
        el = {}
        el['title'] = item[1][index];
        el['name'] = item[3][index].replace('https://en.wikipedia.org','');
        obj.push(el);
    });

   	return obj;
}
