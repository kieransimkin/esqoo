var esqoo_album_index={};
esqoo_album_index.actions= function (id,data) { 
	var ret = $('<div></div>');
	$('<button></button>').attr('data-icon-primary','ui-icon-pencil').html('<?=_('Manage')?>').attr('onclick','esqoo_album_index.manage('+id+'); return false;').appendTo(ret);
	//$('<button></button>').attr('data-icon-primary','ui-icon-trash').html('<?=_('Delete')?>').attr('onclick','esqoo_album_index.delete('+id+'); return false;').appendTo(ret);
	return ret;
}
esqoo_album_index.manage = function(id) { 
	esqoo_ui.make_dialog({singleton: true, title:'Manage Album'},'/album/manage/'+id);
}
esqoo_album_index.delete = function(id) { 
	console.log('delete '+id);	
}
 
