(function ( $ ) {
$.ui.staticQRichEdit={'unique_id':0};
$.widget('esqoo.qrichedit', {
	options: { 
		defaulttab: 'visual'
	},
	unique_id:null,
	current_tab:null,
	_create: function() {
		this.current_tab=this.options.defaulttab;
		this._do_html_setup();
		this._do_javascript_loads();
		this._attach_form_submit_event_handler();
	},
	_attach_form_submit_event_handler: function() { 
		var me = this;
		this.element.closest("form").bind('submit',function(e,o) { 
			me._read_value();
			if (me.element.is('input')) { 
				me.element.val(me.current_value);
			} else if (me.element.is('textarea')) { 
				me.element.html(me.current_value);
			}
		});
	},
	get_value: function() { 
		this._read_value();
		return this.current_value;
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
	_load_stylesheet: function(s) { 
		var me = this;
		var head = document.getElementsByTagName('head')[0];
		var link = document.createElement('link');
		link.rel='stylesheet';
		link.href=s;
		head.appendChild(link);
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
					$(me.visualtextbox).tinymce({script_url : '/js/tinymce/tiny_mce.js'});
				});
			break;
			case 'CKEditor':
				this._load_javascript('/js/ckeditor/ckeditor.js', function() { 
					me._load_javascript('/js/ckeditor/adapters/jquery.js',function() { 
						$(me.visualtextbox).ckeditor();
					});
				});
			break;
		}
		switch(esqoo_ui.code_editor) { 
			case 'CodeMirror':
				this._load_javascript('/js/codemirror/lib/codemirror.js',function() { 
						me._load_stylesheet('/js/codemirror/lib/codemirror.css');
						me._load_javascript('/js/codemirror/mode/xml/xml.js',function() { 
							me._load_javascript('/js/codemirror/mode/javascript/javascript.js',function() { 
								me._load_javascript('/js/codemirror/mode/css/css.js', function() { 
									me._load_javascript('/js/codemirror/mode/clike/clike.js', function() { 
										me._load_javascript('/js/codemirror/mode/php/php.js', function() { 
											var washidden=false;
											if (me.codetabcontainer.hasClass('ui-tabs-hide')) { 
												washidden=true;
												me.codetabcontainer.removeClass('ui-tabs-hide');
											}
											me.codemirror_editor=CodeMirror.fromTextArea(document.getElementById(me.codetextbox.attr('id')),{
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
      });
											if (washidden) { 
												me.codetabcontainer.addClass('ui-tabs-hide');
											}
										});
									});
								});
							});
						});
				});
			break;
			case 'EditArea':
				this._load_javascript('/js/editarea/edit_area_full.js', function() { 
				});
			break;
			case 'Ace':
				this._load_javascript('/js/acewidget/contrib/jquery.acewidget/jquery.acewidget.js',function() { 
					$(me.codetextbox).acewidget();
				});
			break;
			case 'markItUp':
				this._load_javascript('/js/markitup/markitup/jquery.markitup.js',function() { 
						me._load_stylesheet('/js/markitup/markitup/skins/markitup/style.css');
						me._load_stylesheet('/js/markitup/markitup/sets/default/style.css');
						$(me.codetextbox).markItUp({
	onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
	onTab:    		{keepDefault:false, replaceWith:'    '},
	markupSet:  [ 	
		{name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
		{name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
		{name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>'},
		{name:'Numeric List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>'},
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
		{name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
		{separator:'---------------' },
		{name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },		
		{name:'Preview', className:'preview',  call:'preview'}
	]
});

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
					.css({width: '100%'})
					.attr('id','esqoo-ui-visual-text-box-'+this._get_unique_id())
					.appendTo(this.visualtabcontainer);
		this.codetabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-code-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-code-rich-editor-tab-container')
					.prependTo(this.container);
		this.codetextbox=$('<textarea></textarea>')
					.html(this.element.html())
					.css({width: '100%'})
					.attr('id','esqoo-ui-code-text-box-'+this._get_unique_id())
					.appendTo(this.codetabcontainer);
		this.rawtabcontainer=$('<div></div>')
					.attr('id','esqoo-ui-raw-rich-editor-'+this._get_unique_id())
					.addClass('esqoo-ui-raw-rich-editor-tab-container')
					.prependTo(this.container);
		this.rawtextbox=$('<textarea></textarea>')
					.html(this.element.html())
					.css({width: '100%'})
					.attr('id','esqoo-ui-raw-text-box-'+this._get_unique_id())
					.appendTo(this.rawtabcontainer);
		this.tabbar=$('<ul></ul>')
					.addClass('esqoo-ui-tabbar')
					.wrap('<nav />')
					.prependTo(this.container);
		$('<li><a href="#esqoo-ui-visual-rich-editor-'+this._get_unique_id()+'">Visual</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-code-rich-editor-'+this._get_unique_id()+'">Code</a></li>').appendTo(this.tabbar);
		$('<li><a href="#esqoo-ui-raw-rich-editor-'+this._get_unique_id()+'">Raw</a></li>').appendTo(this.tabbar);
		this.container.tabs({select: this._handle_tab_change()});
		this.element.hide();
	},
	_read_value_from_visual: function() { 
		switch (esqoo_ui.visual_editor) { 
			case 'TinyMCE':
				this._read_value_from_tinymce();
			break;
			case 'CKEditor':
				this._read_value_from_ckeditor();	
			break;
		}
	},
	_read_value_from_tinymce: function() { 
		this.current_value=this.visualtextbox.html();
	},
	_read_value_from_ckeditor: function() { 
		this.current_value=this.visualtextbox.val();
	},
	_read_value_from_code: function() { 
		switch(esqoo_ui.code_editor) { 
			case 'EditArea':
			this._read_value_from_editarea();	
			break;
			case 'Ace':
			this._read_value_from_ace();	
			break;
			case 'markItUp':
			this._read_value_from_markitup();
			break;
			case 'CodeMirror':
			this._read_value_from_codemirror();
			break;
		}
	},
	_read_value_from_codemirror: function() { 
		this.current_value=this.codemirror_editor.getValue();
	},
	_read_value_from_editarea: function() { 
		this.current_value=editAreaLoader.getValue($(this.codetextbox).attr('id'));
	},
	_read_value_from_ace: function() { 

	},
	_read_value_from_markitup: function() { 
		this.current_value=this.codetextbox.val();
	},
	_read_value_from_raw: function() { 
		this.current_value=this.rawtextbox.val();
	},
	_write_value_to_visual: function() { 
		switch (esqoo_ui.visual_editor) { 
			case 'TinyMCE':
				this._write_value_to_tinymce();
			break;
			case 'CKEditor':
				this._write_value_to_ckeditor();	
			break;
		}
	},
	_write_value_to_tinymce: function() { 
		this.visualtextbox.tinymce().execCommand('mceReplaceContent',false,this.current_value);
	},
	_write_value_to_ckeditor: function() { 
		this.visualtextbox.val(this.current_value);
	},
	_write_value_to_code: function() { 
		switch(esqoo_ui.code_editor) { 
			case 'EditArea':
			this._write_value_to_editarea();	
			break;
			case 'Ace':
			this._write_value_to_ace();	
			break;
			case 'markItUp':
			this._write_value_to_markitup();
			break;
			case 'CodeMirror':
			this._write_value_to_codemirror();
			break;
		}

	},
	_write_value_to_codemirror: function() { 
		var washidden=false;
		if (this.codetabcontainer.hasClass('ui-tabs-hide')) { 
			washidden=true;
			this.codetabcontainer.removeClass('ui-tabs-hide');
		}
		this.codemirror_editor.setValue(this.current_value);
		if (washidden) { 
			this.codetabcontainer.addClass('ui-tabs-hide');
		}
	},
	_write_value_to_editarea: function() { 
		editAreaLoader.setValue($(this.codetextbox).attr('id'),this.current_value);
	},
	_write_value_to_ace: function() { 

	},
	_write_value_to_markitup: function() { 
		this.codetextbox.val(this.current_value);
	},
	_write_value_to_raw: function() { 
		this.rawtextbox.val(this.current_value);
	},
	_read_value: function() { 
		switch(this.current_tab) { 
			case 'visual':
			this._read_value_from_visual();
			break;
			case 'code':
			this._read_value_from_code();
			break;
			case 'raw':
			this._read_value_from_raw();
			break;
		}
	},
	_handle_tab_change: function() { 
		var me = this;
		return function(e,ui) { 
			var tab=null;
			me._read_value();
			switch (ui.index) { 
				case 0:
				// Visual tab
				me._write_value_to_visual();
				tab='visual';
				break;
				case 1:
				// Code tab
				me._write_value_to_code();
				tab='code';
				break;
				case 2:
				// Raw tab
				me._write_value_to_raw();
				tab='raw';
				break;
			}
			if (me.current_tab=='code' && tab !='code' && esqoo_ui.code_editor=='EditArea') { 
				me._hide_editarea();
			}
			if (me.current_tab!='code' && tab=='code' && esqoo_ui.code_editor=='EditArea') { 
				me._show_editarea();
			}
			me.current_tab=tab;
		}
	},
	_hide_editarea: function() { 
		editAreaLoader.delete_instance($(this.codetextbox).attr('id'));
	},
	_show_editarea: function() { 
		editAreaLoader.init({id:$(this.codetextbox).attr('id'),allow_toggle:false, syntax: "html", start_highlight: true });
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
