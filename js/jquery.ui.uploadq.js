(function( $ ) {
$.widget( "esqoo.uploadq", {
	// These options will be used as defaults
	options: {
		url: '/content/upload/api',
		chunksize: 262144  // 512kB in bytes
 	},
	queue: [],
	queue_running: false,
	queue_item_running: null,
	_create: function() { 
		this._do_html_setup();
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
	_enqueue_file_upload: function(file) { 
		var li=$('<li></li>')
			.addClass('esqoo-uploadq-queue-item')
			.html(file.name)
			.appendTo(this.queuecontainer);
		var status_text=$('<span></span>')
			.addClass('esqoo-uploadq-queue-item-status-text')
			.html('Upload Queued')
			.appendTo(li);
		var progress=$('<div></div>')
			.addClass('esqoo-upload-queue-item-progress')
			.addClass('esqoo-progress')
			.progressbar()
			.appendTo(li);
		this.queue.push({file: file,li: li,'status_text':status_text, progress: progress});
		if (!this.queue_running) { 
			this._run_queue();
		}
	},
	_run_queue: function() { 
		if (this.queue.length<1) { 
			this.queue_running=false;
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
	_upload_file_chunk: function(item,i) { 
		// If we use onloadend, we need to check the readyState.
		var me = this;
		reader= new FileReader();
//		reader.addEventListener('loadend',this._reader_chunk_load(item,i),false);
		reader.addEventListener('abort',this._reader_chunk_abort(item,i),false);
		reader.addEventListener('error',this._reader_chunk_error(item,i),false);
		var start=i*this.options.chunksize;
		var stop=i*this.options.chunksize+this.options.chunksize;
		if (typeof(item.file.webkitSlice)!='undefined') {
			var blob = item.file.webkitSlice(start, stop + 1);
		} else if (typeof(item.file.mozSlice)!='undefined') {
			var blob = item.file.mozSlice(start, stop + 1);
		} else if (typeof(item.file.slize)!='undefined') { 
			var blob = item.file.slice(start, stop + 1);
		}
		var formData = new FormData();
		formData.append("Data", blob);
		formData.append("Name", item.file.name);
		formData.append("Size", item.file.size);
		me._update_upload_progress(item,0,item.file.size);
		formData.append("ResponseFormat", 'json');
		formData.append("Chunk", i);
		formData.append("ChunkSize", me.options.chunksize);
		if (item.asset_id===null || typeof(item.asset_id)=='undefined') { 
			formData.append("AssetID",'null');
		} else { 
			formData.append("AssetID",item.asset_id);
		}
		formData.append("MimeType",item.file.type);
		var xhr = new XMLHttpRequest();
		xhr.addEventListener("load", function(evt) { 
			var d=$.parseJSON(xhr.responseText);
			item.asset_id=d.Asset.AssetID;
			item.chunks_uploaded++;
			if (d.RemainingChunkCount>0) {
				me._upload_file_chunk(item,d.RemainingChunks[0]);
			} else { 
				item.li.remove();
				me._run_queue();
			}
		}, false);
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
		item.chunks=Math.ceil(item.file.size/this.options.chunksize);
		item.chunks_uploaded=0;
		item.asset_id=null;
		this._upload_file_chunk(item,0);
	},
	_handleFiles: function(files) { 
		var me = this;
		$(files).each(function(i,o) {
			me._enqueue_file_upload(o);
		});
	},
	_do_html_setup: function() { 
		this.dropzone=$('<div></div>')
				.addClass('esqoo-dropzone')
				.addClass('ui-widget-content')
				.css({position: 'relative'})
				.appendTo(this.element.parent());	
		this.queuecontainer=$('<ul></ul>')
				.addClass('esqoo-uploadq-queue')
				.addClass('ui-widget-content')
				.appendTo(this.element.parent());
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
