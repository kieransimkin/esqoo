$(document).ready(function() { 
	$('#AlbumID-0').change(esqoo_picture_index.album_select_change);
	$('#TagID-0').change(esqoo_picture_index.tag_select_change);
	$('#View-0').change(esqoo_picture_index.update_viewer);
	if ($('#AlbumID-0').val()!=='') { 
		esqoo_picture_index.album_select_change();
	}
	if ($('#TagID-0').val()!=='') { 
		esqoo_picture_index.tag_select_change();
	}
});
var esqoo_picture_index={};
esqoo_picture_index.selectedstate='';
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
	var active_frame=esqoo_picture_index.get_foreground_frame();
	var inactive_frame=esqoo_picture_index.get_background_frame($('#View-0').val());
	$(active_frame).css({'z-index': 1});
	$(inactive_frame).css({'z-index': 2});
	esqoo_picture_index.prepare_new_viewer($('#View-0').val(),inactive_frame,function() { 
		esqoo_picture_index.current_view=$('#View-0').val();
		if ($(active_frame).attr('id')!=$(inactive_frame).attr('id')) { 
			$(inactive_frame).fadeIn(1000,function() { 
				$(active_frame).hide();
				esqoo_picture_index.update_in_progress=false;
			});
		} else { 
			esqoo_picture_index.update_in_progress=false;
		}
	});	
}
esqoo_picture_index.prepare_new_viewer = function(view,frame,loadcallback) { 
	switch (view) { 
		case 'flexigrid':
			if (esqoo_picture_index.flexigrid_loaded!==true) { 
				esqoo_picture_index.load_flexigrid(frame,loadcallback);
				esqoo_picture_index.flexigrid_loaded=true;
			} else { 
				esqoo_picture_index.update_flexigrid(frame,loadcallback);
			}
			break;
		case 'thumbnailbrowse':
			if (esqoo_picture_index.thumbnailbrowse_loaded!==true) { 
				esqoo_picture_index.load_thumbnailbrowse(frame,loadcallback);
				esqoo_picture_index.thumbnailbrowse_loaded=true;
			} else { 
				esqoo_picture_index.update_thumbnailbrowse(frame,loadcallback);
			}	
			break;		
		case 'mediaslide':
			if (esqoo_picture_index.mediaslide_loaded!==true) { 
				esqoo_picture_index.load_mediaslide(frame,loadcallback);
				esqoo_picture_index.mediaslide_loaded=true;
			} else { 
				esqoo_picture_index.update_mediaslide(frame,loadcallback);
			}
			break;
	}
}
esqoo_picture_index.get_list_params = function() { 
	var params=[{name: 'AlbumID', value: $('#AlbumID-0').val()}];
	if (esqoo_picture_index.selectedstate=='tag') { 
		params=[{name: 'TagID', value: $('#TagID-0').val()}];
	}
	return params;
}
esqoo_picture_index.get_list_url = function () { 
	var url='/album/list-pictures/api/';
	if (esqoo_picture_index.selectedstate=='tag') { 
		url='/tag/list-pictures/api/';
	}
	return url;
}
esqoo_picture_index.load_flexigrid = function(frame,loadcallback) { 
	$(frame).show();
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	esqoo_picture_index.flexigrid=$(frame).find('div').flexigrid({
		height: $(frame).height()-100, 
		width: $(frame).width(), 
		usepager: true, 
		page: 0, 
		useRp: true, 
		rp: 10, 
		url: url, 
		dataType: 'json', 
		colModel: [
			{display: 'ID', name: 'PictureID', width: '10%', sortable: true, align: 'left'},
			{display: 'Title', name: 'Name', width: '50%', sortable: true, align: 'left'}	
		],
		params: params,
		idfield: 'PictureID'
	});
	$(frame).hide();
	loadcallback();
}
esqoo_picture_index.update_flexigrid = function(frame,loadcallback) { 
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	$(frame).find('div').flexReload(url,params);
	loadcallback();
}
esqoo_picture_index.load_thumbnailbrowse = function(frame,loadcallback) { 
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	$(frame).show();
	$(frame).thumbnailbrowse({esqoo_xml_ajax: {url: url, options: params}, ready: loadcallback});
	$(frame).hide();
	loadcallback(); // TODO - disable this once thumbnailbrowse triggers ready
}
esqoo_picture_index.update_thumbnailbrowse = function(frame,loadcallback) { 
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	$(frame).thumbnailbrowse('option','esqoo_xml_ajax',{url: url, options: params});
	loadcallback();
}
esqoo_picture_index.load_mediaslide = function(frame,loadcallback) { 
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	$(frame).show();
	$(frame).mediaslide({esqoo_xml_ajax: {url: url, options: params}, ready:loadcallback });
	$(frame).hide();
}
esqoo_picture_index.update_mediaslide = function(frame,loadcallback) { 
	var params=esqoo_picture_index.get_list_params();
	var url=esqoo_picture_index.get_list_url();
	$(frame).mediaslide('option','esqoo_xml_ajax',{url: url, options: params});
	loadcallback();
}
// Get the frame that's currently in the foreground
esqoo_picture_index.get_foreground_frame = function() { 
	if (esqoo_picture_index.current_view=='flexigrid') { 
		return $('#picturelist-flexigrid');
	} else if (esqoo_picture_index.current_view=='mediaslide') { 
		return $('#picturelist-mediaslide');
	} else if (esqoo_picture_index.current_view=='thumbnailbrowse') { 
		return $('#picturelist-thumbnailbrowse');
	} else { 
		return $('<div></div>');
	}
}
// Get the picture frame that's not currently visible
esqoo_picture_index.get_background_frame = function(view) { 
	switch (view) { 
		case 'flexigrid':
			return $('#picturelist-flexigrid');
		case 'thumbnailbrowse':
			return $('#picturelist-thumbnailbrowse');
		case 'mediaslide':
			return $('#picturelist-mediaslide');
	}
}
