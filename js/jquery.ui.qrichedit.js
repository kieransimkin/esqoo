(function ( $ ) {
$.widget('esqoo.qrichedit', {
	options: { 
		
	},
	visual_editor: 'TinyMCE',
	code_editor: 'Ace',
	_create: function() {
		this._do_javascript_loads();
		this._do_html_setup();
	},
	_load_javascript: function(s,callback) { 
		var me = this;
		var head= document.getElementsByTagName('head')[0];
		var script= document.createElement('script');
		script.type= 'text/javascript';
		script.onreadystatechange= function () {
			if (this.readyState == 'complete') me._script_load_complete(s,callback)();
		}
		script.onload= me._script_load_complete(s,callback);
		script.src= s;
		head.appendChild(script);
	},
	_script_load_complete: function(script,callback) { 
		return function(e) { 
			callback(script);
		}
	},
	_do_javascript_loads: function() { 
		switch(this.visual_editor) { 
			case 'TinyMCE':
			
			break;
			case 'CKEditor':

			break;
		}
		switch(this.code_editor) { 
			case 'EditArea':

			break;
			case 'Ace':

			break;
			case 'markItUp':

			break;
		}
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
