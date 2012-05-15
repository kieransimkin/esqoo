(function($) {

	$.ui.dialog.prototype.options.dock=null;
	var _init = $.ui.dialog.prototype._init;
	$.ui.dialog.prototype._init = function() {
		_init.apply(this, arguments);
		if (this.options.dock !== null) { 
			// Add this dialog to the dock
			this.options.dock.bind_dialog(this);
		}
	}
})(jQuery);
 
