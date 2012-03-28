$(document).ready(function() { 
	$('#album-0').change(esqoo_picture_index.album_select_change);
	$('#tag-0').change(esqoo_picture_index.tag_select_change);
});
var esqoo_picture_index={};
esqoo_picture_index.album_select_change = function(e) { 
	console.log(this);
	console.log('a changed');
}
esqoo_picture_index.tag_select_change = function(e) { 
	console.log(this);
	console.log('e changed');
}
