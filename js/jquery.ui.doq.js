(function( $ ) {
$.widget( "esqoo.doq", {
	options: {

	},
	_create: function() { 
		this._do_html_setup();
	},
	_do_html_setup: function() { 

	},
	_setOption: function( key, value ) {
		$.Widget.prototype._setOption.apply( this, arguments );
		switch( key ) {
			case "disabled":
			// handle changes to disabled option
			break;
		}
	},
	destroy: function() {
		$.Widget.prototype.destroy.call( this );
	}
});
}(jQuery)); 
