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
				this._load_javascript('/js/tinymce/jquery.tinymce.js',function () {
					console.log('TinyMCE jQuery loaded');
				});
			break;
			case 'CKEditor':
				this._load_javascript('/js/ckeditor/ckeditor.js', function() { 
					console.log('CKEditor jQuery loaded');
				});
			break;
		}
		switch(this.code_editor) { 
			case 'EditArea':
				this._load_javascript('/js/editarea/edit_area.js', function() { 
					console.log('EditArea loaded');
				});
			break;
			case 'Ace':
				this._load_javascript('/js/ace/build/src/ace.js',function() { 
					console.log('Ace loaded');
				});
			break;
			case 'markItUp':
				this._load_javascript('/js/markitup/markitup/jquery.markitup.js',function() { 
					console.log('markItUp');
				});
			break;
		}
	},
	_do_html_setup: function() { 

	},
	_setOption: function (key, value) { 
		switch(key) { 
			case 'disabled':
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
