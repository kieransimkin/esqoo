(function( $ ) {
$.widget( "esqoo.thumbnailbrowse", {
	options: {
		url: null
	},
	_create: function() { 
		this._do_html_setup();
	},
	_do_html_setup: function() { 
		this.element.html('hello');
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
