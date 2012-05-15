(function($) {
	// Simple extension to the jQuery's dialog thingie - just adds the 'doq' option, allowing you to bind the dialog to a doq widget
	$.ui.dialog.prototype.options.doq=null;
	var _init = $.ui.dialog.prototype._init;
	$.ui.dialog.prototype._init = function() {
		_init.apply(this, arguments);
		if (this.options.doq !== null) { 
			// Add this dialog to the doq 
			this.options.doq.doq('bind_dialog',this);
		}
	}
})(jQuery);
 
