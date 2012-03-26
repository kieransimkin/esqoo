(function( $ ) {
$.widget( "esqoo.thumbnailbrowse", {
	options: {

	},
	_create: function() { 
		this._do_html_setup();
	},
	_do_html_setup: function() { 

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
