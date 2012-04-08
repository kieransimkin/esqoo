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
esqoo_picture_index.frame_displaying=1;
esqoo_picture_index.frame_type=null;
esqoo_picture_index.update_viewer = function() { 
	if (esqoo_picture_index.update_in_progress) { 
		return;
	}
	esqoo_picture_index.update_in_progress=true;
	console.log($('#View-0').val());
	console.log('updating viewer');
}
// Get the picture frame that's currently in the foreground
esqoo_picture_index._get_foreground_frame: function() { 
	if (esqoo_picture_index.frame_displaying==1) { 
		return $('#picturelist-1');
	} else { 
		return $('#picturelist-2');
	}
}
// Get the picture frame that's not currently visible
esqoo_picture_index._get_background_frame: function() { 
	if (esqoo_picture_index.frame_displaying==1) { 
		return $('#picturelist-2');
	} else { 
		return $('#picturelist-1');
	}
}
// Switch our record of which frame is visible
esqoo_picture_index._toggle_pframe: function() { 
	if (esqoo_picture_index.frame_displaying==1) { 
		esqoo_picture_index.frame_displaying=2;
	} else { 
		esqoo_picture_index.frame_displaying=1;
	}
}

