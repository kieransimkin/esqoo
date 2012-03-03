(function( $ ) {
$.widget( "esqoo.uploadq", {
	// These options will be used as defaults
	options: {
		chunksize: 16384, // 16kB in bytes
		file_hash_threshold: 10485760 // 10mB in bytes
 	},
	queue: [],
	queue_running: false,
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
		console.log(this.queue);
	},
	_run_queue: function() { 
		if (this.queue.length<1) { 
			this.queue_running=false;
		}
		this.queue_running=true;
		this._process_queue_item(this.queue.shift());
	},
	_process_queue_item: function(item) { 
		item.status_text.html('Handshaking');
		item.reader= new FileReader();
		// If we use onloadend, we need to check the readyState.
		item.reader.onloadend = function(evt) {
			if (evt.target.readyState == FileReader.DONE) { // DONE == 2
				/*
				document.getElementById('byte_content').textContent = evt.target.result;
				document.getElementById('byte_range').textContent = 
				    ['Read bytes: ', start + 1, ' - ', stop + 1,
				     ' of ', file.size, ' byte file'].join('');
				*/
				console.log(Sha256.hash(evt.target.result));
			}
		};
		var start=0;
		var end=1000;
		if (typeof(item.file.webkitSlice)!='undefined') {
			var blob = item.file.webkitSlice(start, stop + 1);
		} else if (typeof(item.file.mozSlice)!='undefined') {
			var blob = item.file.mozSlice(start, stop + 1);
		} else if (typeof(item.file.slize)!='undefined') { 
			var blob = item.file.slice(start, stop + 1);
		}
		item.reader.readAsBinaryString(blob);
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
