(function ( $ ) {
$.ui.staticQRichEdit={'unique_id':0};
$.widget('esqoo.qrichedit', {
	options: { 
		
	},
	unique_id:null,
	_create: function() {
		this._do_html_setup();
		this._do_javascript_loads();
	},
	_get_unique_id: function(initial) { 
		if (this.unique_id===null) { 
			this.unique_id=$.ui.staticQRichEdit.unique_id;
			$.ui.staticQRichEdit.unique_id=$.ui.staticQRichEdit.unique_id+1;
		}
		return this.unique_id;
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
		var me = this;
		switch(esqoo_ui.visual_editor) { 
			case 'TinyMCE':
				this._load_javascript('/js/tinymce/jquery.tinymce.js',function () {
					console.log('TinyMCE jQuery loaded');
					$(me.visualtextbox).tinymce({script_url : '/js/tinymce/tiny_mce.js'});
				});
			break;
			case 'CKEditor':
				this._load_javascript('/js/ckeditor/ckeditor.js', function() { 
					me._load_javascript('/js/ckeditor/adapters/jquery.js',function() { 
						$(me.visualtextbox).ckeditor();
						console.log('CKEditor jQuery loaded');
					});
				});
			break;
		}
		console.log(esqoo_ui.code_editor);
		switch(esqoo_ui.code_editor) { 
			case 'EditArea':
				this._load_javascript('/js/editarea/edit_area_full.js', function() { 
					editAreaLoader.init({
						id: $(me.codetextbox).attr('id')
					});
					console.log('EditArea loaded');
				});
			break;
			case 'Ace':
				this._load_javascript('/js/acewidget/contrib/jquery.acewidget/jquery.acewidget.js',function() { 
					console.log('Ace loaded');
					$(me.codetextbox).acewidget();
				});
			break;
			case 'markItUp':
				this._load_javascript('/js/markitup/markitup/jquery.markitup.js',function() { 
					$(me.codetextbox).markItUp({
    nameSpace:       "html", // Useful to prevent multi-instances CSS conflict
    onShiftEnter:    {keepDefault:false, replaceWith:'<br />\n'},
    onCtrlEnter:     {keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
    onTab:           {keepDefault:false, openWith:'     '},
    markupSet:  [
        {name:'Heading 1', key:'1', openWith:'<h1(!( class="[![Class]!]")!)>', closeWith:'</h1>', placeHolder:'Your title here...' },
        {name:'Heading 2', key:'2', openWith:'<h2(!( class="[![Class]!]")!)>', closeWith:'</h2>', placeHolder:'Your title here...' },
        {name:'Heading 3', key:'3', openWith:'<h3(!( class="[![Class]!]")!)>', closeWith:'</h3>', placeHolder:'Your title here...' },
        {name:'Heading 4', key:'4', openWith:'<h4(!( class="[![Class]!]")!)>', closeWith:'</h4>', placeHolder:'Your title here...' },
        {name:'Heading 5', key:'5', openWith:'<h5(!( class="[![Class]!]")!)>', closeWith:'</h5>', placeHolder:'Your title here...' },
        {name:'Heading 6', key:'6', openWith:'<h6(!( class="[![Class]!]")!)>', closeWith:'</h6>', placeHolder:'Your title here...' },
        {name:'Paragraph', openWith:'<p(!( class="[![Class]!]")!)>', closeWith:'</p>'  },
        {separator:'---------------' },
        {name:'Bold', key:'B', openWith:'<strong>', closeWith:'</strong>' },
        {name:'Italic', key:'I', openWith:'<em>', closeWith:'</em>'  },
        {name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
        {separator:'---------------' },
        {name:'Ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
        {name:'Ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
        {name:'Li', openWith:'<li>', closeWith:'</li>' },
        {separator:'---------------' },
        {name:'Picture', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
        {name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
        {separator:'---------------' },
        {name:'Clean', replaceWith:function(h) { return h.selection.replace(/<(.*?)>/g, "") } },
        {name:'Preview', call:'preview', className:'preview' }
    ]
});
					console.log('markItUp');
				});
			break;
		}
	},
	_do_html_setup: function() { 
		this.container= $('<div></div>')
					.addClass('esqoo-ui-rich-editor-container')
					.insertAfter(this.element);
		this.visualtabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-visual-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-visual-rich-editor-tab-container')
					.appendTo(this.container);
		this.visualtextbox=$('<textarea></textarea>')
					.html(this.element.html())
					.attr('id','esqoo-ui-visual-text-box-'+this._get_unique_id())
					.appendTo(this.visualtabcontainer);
		this.codetabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-code-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-code-rich-editor-tab-container')
					.prependTo(this.container);
		this.codetextbox=$('<textarea></textarea>')
					.html(this.element.html())
					.attr('id','esqoo-ui-code-text-box-'+this._get_unique_id())
					.appendTo(this.codetabcontainer);
		this.rawtabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-raw-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-raw-rich-editor-tab-container')
					.prependTo(this.container);
		this.rawtextbox=$('<textarea></textarea>')
					.html(this.element.html())
					.attr('id','esqoo-ui-raw-text-box-'+this._get_unique_id())
					.appendTo(this.rawtabcontainer);
		this.tabbar=$('<ul></ul>')
					.addClass('esqoo-ui-tabbar')
					.wrap('<nav />')
					.prependTo(this.container);
		$('<li><a href="#esqoo-ui-visual-rich-editor-'+this._get_unique_id()+'">Visual</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-code-rich-editor-'+this._get_unique_id()+'">Code</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-raw-rich-editor-'+this._get_unique_id()+'">Raw</a></li>').appendTo(this.tabbar);
		this.container.tabs();
		this.element.hide();
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
