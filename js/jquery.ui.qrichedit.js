(function ( $ ) {
$.widget('esqoo.qrichedit', {
	options: { 
		
	},
	visual_editor: 'TinyMCE',
	code_editor: 'Ace',
	_create: function() {
		this._do_html_setup();
	},
	_do_html_setup: function() { 

	},
	_setOption: function (key, value) { 
		switch(key) { 
			case 'disabled';
			// handle changes to disabled option
			break;
		}
		$.Widget.prototype._setOption.apply(this, arguments);
	},
	destroy: function() { 
		$.Widget.prototype.destroy.call(this);
	}
});
})(jQuery);
