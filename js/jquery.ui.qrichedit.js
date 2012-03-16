(function ( $ ) {
$.widget('esqoo.qrichedit', {
	options: { 
		
	},
	visual_editor: 'TinyMCE',
	code_editor: 'Ace',
	unique_id: null,
	_create: function() {
		this._do_html_setup();
		this._do_javascript_loads();
	},
	_get_unique_id: function() { 
		if (this.unique_id!==null) { 
			return this.unique_id;
		}
		var me = this;
		return (function() { 
			var id=0;
			return (me.unique_id=function() { return id++; });
		})();
	},
	_load_javascript: function(s,callback) { 
		var me = this;
		var head= document.getElementsByTagName('head')[0];
		var script= document.createElement('script');
		script.type= 'text/javascript';
		script.onreadystatechange= function () {
			if (this.readyState == 'complete') me._script_load_complete(s,callback)();
		}
		script.onload= this._script_load_complete(s,callback);
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
		this.tabbar=$('<ul></ul>')
					.addClass('esqoo-ui-tabbar')
					.insertAfter(this.element);
		$('<li><a href="#esqoo-ui-visual-rich-editor-'+this._get_unique_id()+'">Visual</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-code-rich-editor-'+this._get_unique_id()+'">Code</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-raw-rich-editor-'+this._get_unique_id()+'">Raw</a></li>').appendTo(this.tabbar);
		this.visualtabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-visual-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-visual-rich-editor-tab-container')
					.insertAfter(this.element);
		this.codetabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-code-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-code-rich-editor-tab-container')
					.insertAfter(this.element);
		this.rawtabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-raw-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-raw-rich-editor-tab-container')
					.insertAfter(this.element);
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
