var esqoo_tag_index = {};
esqoo_tag_index.actions=function(id,data) { 
	var ret = $('<div></div>');
	$('<button></button>').attr('data-icon-primary','ui-icon-pencil').html('Manage').attr('onclick','esqoo_tag_index.manage('+id+'); return false;').appendTo(ret);
	return ret;
}
esqoo_tag_index.manage=function(id) { 
	esqoo_ui.make_dialog({singleton: true, title:'Manage Tag'},'/tag/manage/'+id);
}

