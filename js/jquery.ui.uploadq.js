(function( $ ) {
$.widget( "esqoo.uploadq", {
	// These options will be used as defaults
	options: {
		url: '/content/upload/api',
		chunksize: 262144,  // 256kB in bytes
		maxchunksize: 1048576,
		chunksizestepup: 10485760,
		chunkfailurelimit: 10
 	},
	queue: [],
	complete: [],
	failed: [],
	albumlist: null,
	queue_running: false,
	queue_item_running: null,
	_create: function() { 
		var me = this;
		this._do_html_setup();
		$(document).ready(function() { 
			me._update_album_list();
		});
		$('#album-0').click(function() { 
			if ($('#album-0').val()==$('#new_album_name-0').val()) { 
				$('#album-0').focus();
				$('#album-0').select();
			}
		});
	},
	_dragenter: function() { 
		var me = this;
		return function (e) { 
			e.stopPropagation();
			e.preventDefault();
			me.dropzone.addClass('ui-state-active');
			return false;
		}
	},
	_dragleave: function() { 
		var me = this;
		return function (e) { 
			e.stopPropagation();
			e.preventDefault();
			me.dropzone.removeClass('ui-state-active');
			return false;	
		}
	},
	_dragover: function() { 
		return function(e) { 
			e.stopPropagation();
			e.preventDefault();
			return false;	
		}
	},
	_drop: function() { 
		var me = this;
		return function(e) { 
			e.stopPropagation();
			e.preventDefault();	
			me.dropzone.removeClass('ui-state-active');
			var dt=e.dataTransfer;
			var files=dt.files;
			me._handleFiles(files);
		}
	},
	_handle_file_change: function() { 
		var me = this;
		return function() { 
			me._handleFiles(this.files);
		}
	},
	_get_album_name_from_id: function (albumid) { 
		var ret=$('#album-0').val();
		$(this.albumlist).each(function(i,o) { 
			if (o.value==albumid) { 
				ret=o.label;
				return false;
			}
		});
		return ret;
	},
	_enqueue_file_upload: function(file,albumid) { 
		var li=$('<li></li>')
			.addClass('esqoo-uploadq-queue-item')
			.appendTo(this.queuecontainer);
		var status_text=$('<span></span>')
			.addClass('esqoo-uploadq-queue-item-status-text')
			.html('Queued')
			.appendTo(li);
		var namelabel=$('<span></span>').appendTo(li).html('Name:').addClass('esqoo-upload-queue-item-name-label');
		var namecontainer=$('<input type="text"></input>').appendTo(li).val(file.name).addClass('esqoo-upload-queue-item-name-container').attr('readonly','readonly');
		$('<br/>').appendTo(li);
		var albumlabel=$('<span></span>').appendTo(li).html('Album:').addClass('esqoo-upload-queue-item-album-label');
		var albumcontainer=$('<input type="text"></input>').appendTo(li).val(this._get_album_name_from_id(albumid)).addClass('esqoo-upload-queue-item-album-container').attr('readonly','readonly');
		var progress=$('<div></div>')
			.addClass('esqoo-upload-queue-item-progress')
			.addClass('esqoo-progress')
			.progressbar()
			.appendTo(li);
		if (!this.queue_visible) { 
			this.queue_visible=true;
			this.queuediv.fadeIn('slow');
		}
		this.queue.push({file: file,li: li,'status_text':status_text, progress: progress,albumid: albumid,namecontainer: namecontainer});
		if (!this.queue_running) { 
			this._run_queue();
		}
	},
	_run_queue: function() { 
		if (this.queue.length<1) { 
			this.queue_running=false;
			return;
		}
		this.queue_running=true;
		this._process_queue_item(this.queue.shift());
	},
	_reader_chunk_abort: function(item,i) { 
		return function (e) { 
			console.log('abort');
			console.log(e);
		}
	},
	_reader_chunk_error: function(item,i) { 
		return function (e) { 
			console.log('error');
			console.log(e);
		}
	},
	_xhr_failure: function(item,i) { 
		var me = this;
		return function(e) { 
			item.chunk_failures++;
			me._upload_file_chunk(item,i);
		}
	},
	_upload_complete: function (item) { 
		var me = this;
		item.li.slideUp('slow',function() { 
			item.li.detach();
			item.namecontainer.removeAttr('readonly');
			item.progress.hide();
			$('<br/>').appendTo(item.li);
			item.descriptionlabel=$('<span></span>').html('Description').appendTo(item.li);
			$('<br/>').appendTo(item.li);
			item.descriptioncontainer=$('<textarea></textarea>').appendTo(item.li);
			if (me.queue.length<1 && me.queue_visible) { 
				me.queue_visible=false;
				me.queuediv.fadeOut('slow');
			}
			item.status_text.html('Complete');
			item.li.appendTo(me.completecontainer);
			item.li.slideDown('slow');
			if (!me.complete_visible) { 
				me.complete_visible=true;
				me.completediv.fadeIn('slow');
			}
		});
		this.complete.push(item);
	},
	_upload_failed: function(item) { 
		var me = this;
		item.li.slideUp('slow',function() { 
			item.li.detach();
			if (me.queue.length<1 && me.queue_visible) { 
				me.queue_visible=false;
				me.queuediv.fadeOut('slow');
			}
			item.status_text.html('Failed');
			item.li.appendTo(me.failedcontainer);
			item.li.slideDown('slow');
			if (!me.failed_visible) { 
				me.failed_visible=true;
				me.faileddiv.fadeIn('slow');
			}
		});
		this.failed.push(item);
	},
	_get_current_album_id: function() { 
		var name = $('#album-0').val();
		if (this.albumlist===null) { 
			this.albumlist=$.parseJSON($('#albumlist-0').val());
		}
		var album_id=null;
		$(this.albumlist).each(function (i,o) {
			if (o.label==name) { 
				album_id=o.value;
				return false;
			}
		});
		if (album_id!==null) { 
			return album_id;
		} else { 
			if ($('#album-0').val()!=$('#new_album_name-0').val()) { 
				this._update_album_name($('#new_album_id-0').val(),$('#album-0').val());
			} else { 
				this._album_stub_complete($('#new_album_id-0').val());
			}
			return $('#new_album_id-0').val();	
		}
	},
	_album_stub_complete: function(id) { 
		var me = this;
		$.post('/album/stub-complete/api',{AlbumID: id, ResponseFormat: 'json'},function(d) {
			me._update_album_list();
		});
	},
	_update_album_name: function(id,name) { 
		var me = this;
		$.post('/album/update/api',{AlbumID: id, Name: name,ResponseFormat: 'json'},function(d) {
			me._update_album_list();
		});
	},
	_update_album_list: function() { 
		var me = this;
		$('#albumlist').flexReload();
		$.post('/album/list/api',{ResponseFormat: 'json'},function (d) { 
			me.albumlist=[];
			$(d.Rows).each(function(i,o) { 
				me.albumlist.push({label: o.Name, value: o.AlbumID});
			});
			$('#album-0').autocomplete('option','source',me.albumlist);
		},'json');
		$.post('/album/get-stub/api',{ResponseFormat: 'json'},function(d) { 
			$('#new_album_name-0').val(d.Name);
			$('#new_album_id-0').val(d.AlbumID);
		},'json');
	},
	_upload_file_chunk: function(item,i) { 
		// If we use onloadend, we need to check the readyState.
		var me = this;
		if (item.chunk_failures>me.options.chunkfailurelimit) { 
			me._upload_failed(item);
			esqoo_ui.create_message('Upload failed: '+item.file.name,'Notice');
			me._run_queue();
			return;
		}
		reader= new FileReader();
//		reader.addEventListener('loadend',this._reader_chunk_load(item,i),false);
		reader.addEventListener('abort',this._reader_chunk_abort(item,i),false);
		reader.addEventListener('error',this._reader_chunk_error(item,i),false);
		var start=i*item.chunksize;
		var stop=i*item.chunksize+item.chunksize;
		if (typeof(item.file.webkitSlice)!='undefined') {
			var blob = item.file.webkitSlice(start, stop);
		} else if (typeof(item.file.mozSlice)!='undefined') {
			var blob = item.file.mozSlice(start, stop);
		} else if (typeof(item.file.slize)!='undefined') { 
			var blob = item.file.slice(start, stop);
		}
		var formData = new FormData();
		formData.append("Data", blob);
		formData.append("Name", item.file.name);
		formData.append("Size", item.file.size);
		formData.append("AlbumID",item.albumid);
		me._update_upload_progress(item,0,item.file.size);
		formData.append("ResponseFormat", 'json');
		formData.append("Chunk", i);
		formData.append("ChunkSize", item.chunksize);
		if (item.asset_id===null || typeof(item.asset_id)=='undefined') { 
			formData.append("AssetID",'null');
		} else { 
			formData.append("AssetID",item.asset_id);
		}
		formData.append("MimeType",item.file.type);
		var xhr = new XMLHttpRequest();
		xhr.addEventListener("load", function(evt) { 
			try {
				var d=$.parseJSON(xhr.responseText);
			} catch (e) { 
				console.log('exception');
				console.log(e);
				item.chunk_failures++;
				me._upload_file_chunk(item,i);
				return;
			}
			if (d.ErrorCount>0) { 
				item.chunk_failures++;
				me._upload_file_chunk(item,i);
				return;
			}
			item.asset_id=d.Asset.AssetID;
			item.chunks_uploaded++;
			if (d.RemainingChunkCount>0) {
				item.chunk_failures=0;
				me._upload_file_chunk(item,d.RemainingChunks[0]);
			} else { 
				item.assettype=d.AssetType;
				if (d.AssetType=='Video') { 
					esqoo_ui.create_message('Video upload successful: '+item.file.name,'Notice');
					item.videoid=d.Video.VideoID;
				} else if (d.AssetType=='Audio') { 
					esqoo_ui.create_message('Audio upload successful: '+item.file.name,'Notice');
					item.audioid=d.Audio.AudioID;
				} else if (d.AssetType=='Picture') {
					esqoo_ui.create_message('Picture upload successful: '+item.file.name,'Notice');
					item.pictureid=d.Picture.PictureID;
				} else if (d.AssetType=='File') {
					esqoo_ui.create_message('File upload successful: '+item.file.name,'Notice');
					item.fileid=d.File.FileID;
				}
				me._upload_complete(item);
				me._run_queue();
			}
		}, false);
		xhr.addEventListener('error',me._xhr_failure(item,i),false);
		xhr.addEventListener('abort',me._xhr_failure(item,i),false);
		xhr.addEventListener('timeout',me._xhr_failure(item,i),false);
		xhr.addEventListener('progress',function(e) { 
			if (e.lengthComputable) { 
				me._update_upload_progress(item,e.loaded,e.total);
			}
		}, false);
		xhr.open("POST", me.options.url);
		xhr.send(formData);
	},
	_update_upload_progress: function(item,thischunkloaded,thischunktotal) { 
		var chunksize=100/item.chunks;
		var val=chunksize*item.chunks_uploaded;
		val=val+((chunksize/thischunktotal)*thischunkloaded);
		$(item.progress).progressbar("option","value",val);
	},
	_process_queue_item: function(item) { 
		this.queue_item_running=item;
		item.status_text.html('Uploading');
		item.chunksize=this.options.chunksize;
		if (item.file.size>this.options.chunksizestepup) { 
			item.chunksize=item.chunksize+item.chunksize*Math.floor(item.file.size/this.options.chunksizestepup);
			if (item.chunksize>this.options.maxchunksize) {
				item.chunksize=this.options.maxchunksize;
			}
		}
		item.chunks=Math.ceil(item.file.size/item.chunksize);
		item.chunks_uploaded=0;
		item.chunk_failures=0;
		item.asset_id=null;
		this._upload_file_chunk(item,0);
	},
	_handleFiles: function(files) { 
		var me = this;
		var albumid= this._get_current_album_id();
		$(files).each(function(i,o) {
			me._enqueue_file_upload(o,albumid);
		});
	},
	_do_html_setup: function() { 
		this.dropzone=$('<div></div>')
				.addClass('esqoo-dropzone')
				.addClass('ui-widget-content')
				.css({position: 'relative'})
				.appendTo(this.element.parent());
		this.queuediv=$('<div></div>')
				.addClass('esqoo-uploadq-queue-div')
				.addClass('ui-widget-content')
				.addClass('ui-corner-all')
				.hide()
				.appendTo(this.element.parent());
		this.queuedivheader=$('<div></div>')
				.addClass('ui-widget-header')
				.addClass('ui-corner-all')
				.addClass('esqoo-uploadq-queue-div-header')
				.html('Upload Queue')
				.appendTo(this.queuediv);
		this.queue_visible=false;
		this.queuecontainer=$('<ul></ul>')
				.addClass('esqoo-uploadq-queue')
				.appendTo(this.queuediv);
		this.faileddiv=$('<div></div>')
				.addClass('esqoo-uploadq-failed-div')
				.addClass('ui-widget-content')
				.addClass('ui-corner-all')
				.hide()
				.appendTo(this.element.parent());
		this.faileddivheader=$('<div></div>')
				.addClass('ui-widget-header')
				.addClass('ui-corner-all')
				.addClass('esqoo-uploadq-failed-div-header')
				.html('Failed')
				.appendTo(this.faileddiv);
		this.failed_visible=false;
		this.failedcontainer=$('<ul></ul>')
				.addClass('esqoo-uploadq-failed')
				.appendTo(this.faileddiv);
		this.completediv=$('<div></div>')
				.addClass('esqoo-uploadq-complete-div')
				.addClass('ui-widget-content')
				.addClass('ui-corner-all')
				.hide()
				.appendTo(this.element.parent());
		this.completedivheader=$('<div></div>')
				.addClass('ui-widget-header')
				.addClass('ui-corner-all')
				.addClass('esqoo-uploadq-complete-div-header')
				.html('Complete')
				.appendTo(this.completediv);
		this.complete_visible=false;
		this.completecontainer=$('<ul></ul>')
				.addClass('esqoo-uploadq-complete')
				.appendTo(this.completediv);
		this.dropzonehotspot=$('<div></div>')
				.addClass('esqoo-dropzone-hotspot')
				.css({position: 'absolute', width: '100%', height: '100%', top: '0px', left: '0px'})
				.appendTo(this.dropzone);
		this.dropzonelabel=$('<span></span>')
				.addClass('esqoo-dropzone-label')
				.html('Drag and drop files here')
				.appendTo(this.dropzone);
		this.dropzonehotspot.get(0).addEventListener('drop',this._drop(),false);

		this.dropzonehotspot.get(0).addEventListener('dragenter',this._dragenter(),false);
		this.dropzonehotspot.get(0).addEventListener('dragleave',this._dragleave(),false);
		this.dropzonehotspot.get(0).addEventListener('dragover',this._dragover(),false);

		this.element.change(this._handle_file_change());
		var fileElem = this.element;
		this.uploadbutton=$('<div>'+this.element.parent().parent().find('label:eq(0)').html()+'</div>')
				.prependTo(this.element.parent())
				.button({icons: {primary: 'ui-icon-folder-open', secondary: null}})
				.click(function() { 
					if (fileElem) { 
						fileElem.click();
					}
				});
		this.element.parent().parent().find('label:eq(0)').hide();
		this.element.hide();
	},
	// Use the _setOption method to respond to changes to options
	_setOption: function( key, value ) {
		switch( key ) {

			case "disabled":
			// handle changes to disabled option

			break;

		}

		$.Widget.prototype._setOption.apply( this, arguments );
	},
	destroy: function() {
		$.Widget.prototype.destroy.call( this );

	}
	
});
}(jQuery));
