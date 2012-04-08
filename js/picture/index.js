$(document).ready(function() { 
	$('#AlbumID-0').change(esqoo_picture_index.album_select_change);
	$('#TagID-0').change(esqoo_picture_index.tag_select_change);
	$('#View-0').change(esqoo_picture_index.update_viewer);
	esqoo_picture_index.album_select_change();
	esqoo_picture_index.tag_select_change();
});
var esqoo_picture_index={};
esqoo_picture_index.album_select_change = function(e) { 
	var val = $('#AlbumID-0').val();
	if (val!=='') { 
		if (esqoo_picture_index.selectedstate==='tag') { 
			$('#TagID-0').val('');
			$('#TagID-0').selectmenu('value','');
			$('#tagcontainer-0').removeClass('ui-widget-header');
		}
		$('#albumcontainer-0').addClass('ui-widget-header');
		esqoo_picture_index.selectedstate='album';
	}
	esqoo_picture_index.update_viewer();
}
esqoo_picture_index.tag_select_change = function(e) { 
	var val=$('#TagID-0').val();
	if (val!=='') { 
		if (esqoo_picture_index.selectedstate==='album') { 
			$('#AlbumID-0').selectmenu('value','');
			$('#AlbumID-0').val('');
			$('#albumcontainer-0').removeClass('ui-widget-header');
		}
		$('#tagcontainer-0').addClass('ui-widget-header');
		esqoo_picture_index.selectedstate='tag';
	}
	esqoo_picture_index.update_viewer();
}
esqoo_picture_index.update_in_progress = false;
esqoo_picture_index.update_viewer = function() { 
	if (esqoo_picture_index.update_in_progress) { 
		return;
	}
	esqoo_picture_index.update_in_progress=true;
	console.log($('#View-0').val());
	console.log('updating viewer');
}
