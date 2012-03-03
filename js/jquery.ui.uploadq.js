(function( $ ) {
$.widget( "esqoo.uploadq", {
	// These options will be used as defaults
	options: {
		chunksize: 16384 // 16kB in bytes
 	},
	_create: function() { 
		this._do_html_setup();
	},
	_dragenter: function() { 
		return function (e) { 
			e.stopPropagation();
			e.preventDefault();	
		}
	},
	_dragover: function() { 
		return function(e) { 
			e.stopPropagation();
			e.preventDefault();	
		}
	},
	_drop: function() { 
		var me = this;
		return function(e) { 
			e.stopPropagation();
			e.preventDefault();	
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
	_handleFiles: function(files) { 
		console.log(files);
	},
	_do_html_setup: function() { 
		this.dropzone=$('<div>Drag and drop files here</div>')
				.appendTo(this.element.parent());
		this.dropzone.get(0).addEventListener('drop',this._drop(),false);

		// Stupid microshit
		this.dropzone.get(0).addEventListener('dragenter',this._dragenter(),false);
		this.dropzone.get(0).addEventListener('dragover',this._dragover(),false);

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
