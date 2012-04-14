(function( $ ) {
$.widget( "esqoo.thumbnailbrowse", {
	options: {
		picturesizes: {},
		initialsize: 150,
		minsize: 100,
		maxsize: null,
		esqoo_xml_data: null,
		esqoo_xml_ajax: null,
		atom_xml_data: null,
		atom_xml_ajax: null,
		"json_data": null,
		"json_ajax": null,
		"flickr_public_photos_data": null,
		"flickr_favorites_data": null,
		"flickr_groups_data": null,
	},
	thumbnail_list: [],
	_create: function() { 
		if (this.options.maxsize==null) { 
			this.options.maxsize=this.element.width();
		}
		this._do_html_setup();
		this._init_data();
	},
	_resize: function() { 
		var me = this;
		return function() { 
			console.log(me.element.height());
			console.log(me.element.width());
			me._position_content_body();
		}
	},
	_do_html_setup: function() { 
		$(window).resize(this._resize());
		this.container=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-container')
				.css({position: 'relative', height: '100%', width: '100%'})
				.appendTo(this.element);
		this.footer_controls=$('<footer></footer>')
				.addClass('esqoo-ui-thumbnailbrowse-footer-controls')
				.addClass('ui-widget-content')
				.addClass('ui-corner-bl')
				.addClass('ui-corner-br')
				.addClass('ui-corner-tr')
				.css({position: 'absolute', bottom: '0px', width: '100%'})
				.appendTo(this.container);
		this.element.addClass('ui-widget');
		this.footer_controls_content=$('<div></div>')
				.css({margin: '0.2em'})
				.appendTo(this.footer_controls);
		this.header_controls=$('<header></header>')
				.addClass('esqoo-ui-thumbnailbrowse-header-controls')
				.addClass('ui-widget-content')
				.addClass('ui-corner-tr')
				.addClass('ui-corner-tl')
				.addClass('ui-corner-br')
				.css({position: 'absolute', top: '0px', width: '100%'})
				.appendTo(this.container);
		this.header_controls_content=$('<div></div>')
				.css({margin: '0.2em'})
				.appendTo(this.header_controls);
		this.content_body=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-content-body')
				.css({position: 'absolute', top: '0px', width: '100%'})
				.prependTo(this.container);
		this.content_left_bar=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-content-left-bar')
				.css({width: '25%', 'min-width':'250px', height: '100%', float: 'left'})
				.prependTo(this.content_body);
		this.content_left_bar_body=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-content-left-bar-body')
				.addClass('ui-widget-content')
				.css({width:'100%', height: '100%'})
				.prependTo(this.content_left_bar);
		this.content_left_bar_body_content=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-content-left-bar-body-content')
				.css({margin: '0.2em'})
				.appendTo(this.content_left_bar_body);
		this.thumb_container=$('<div></div>')
				.addClass('esqoo-ui-thumbnailbrowse-thumb-container')
				.css({width: '75%', height: '100%', float: 'left', 'overflow-y': 'auto'})
				.appendTo(this.content_body);
		this.thumbnail_container_list=$('<ul></ul>')
				.addClass('esqoo-ui-thumbnailbrowse-thumb-container-list')
				.appendTo(this.thumb_container);
		this._setup_header_controls_html();
		this._setup_footer_controls_html();
		this._setup_left_bar_html();
		this._position_content_body();
	},
	_generate_thumbnail_list: function() { 
		this.thumb_container.html('Thumb container');
		this.d.each(function() { 
			console.log(this);
		});
	},
	_setup_left_bar_html: function() { 
		this.content_left_bar_body_minimize_button=$('<button data-icon-primary="ui-icon-close"></button>')
						.css({float: 'right'})
						.appendTo(this.content_left_bar_body_content)
						.click(this._left_bar_minimize_button_click());
		this.content_left_bar_body_maximize_button=$('<button data-icon-primary="ui-icon-maximize"></button>')
						.appendTo(this.content_left_bar_body_content)
						.css({display: 'none'})
						.click(this._left_bar_maximize_button_click());
		this.content_left_bar_body_text=$('<span></span>').appendTo(this.content_left_bar_body_content);
		this.content_left_bar_body_text.html('Left Bar');
	},
	_left_bar_minimize_button_click: function() { 
		var me = this;
		return function() { 
			me.content_left_bar_body_minimize_button.attr('disabled',true);
			me.content_left_bar_body_minimize_button.fadeOut('fast');
			me.content_left_bar_body_text.fadeOut('fast',function() { 
				me.content_left_bar_body_maximize_button.fadeIn('fast');
				me.content_left_bar_body_maximize_button.removeAttr('disabled');
				me.content_left_bar_body_maximize_button.css({height: me.content_left_bar_body.height(), top: '-1em', left: '-0.3em', width: '1em'});
				me.content_left_bar.css({'min-width':'0px'});
				me.content_left_bar.animate({width:'0.7em'});
			});
		}
	},
	_left_bar_maximize_button_click: function() { 
		var me = this;
		return function() { 
			me.content_left_bar_body_maximize_button.attr('disabled',true);
			me.content_left_bar_body_maximize_button.fadeOut('fast');
			me.content_left_bar.animate({width: '25%','min-width':'250px'},function() {
				me.content_left_bar_body_minimize_button.removeAttr('disabled');
				me.content_left_bar_body_minimize_button.fadeIn('fast');
				me.content_left_bar_body_text.fadeIn('fast');
			});
		}
	},
	_setup_header_controls_html: function() { 
		this.header_controls_content.html('Header');	
		this.header_controls_size_slider=$('<div></div>')
					.addClass('esqoo-ui-thumbnailbrowse-header-controls-size-slider')
					.css({width: '25%', float: 'right', 'min-width':'200px'})
					.appendTo(this.header_controls_content)
					.slider({min: this.options.minsize, max: this.options.maxsize, slide: this._size_slider_slide()});
	},
	_size_slider_slide: function() { 
		var me = this;
		return function (event,ui) { 
			me._update_thumbnail_size(ui.value);
		}
	},
	_update_thumbnail_size: function(size) { 
		console.log('got size'+size);
	},
	_setup_footer_controls_html: function() { 
		this.footer_controls_status=$('<span></span>')
					.addClass('esqoo-ui-thumbnailbrowse-footer-status')
					.appendTo(this.footer_controls_content);
	},
	_position_content_body: function() { 
		this.content_body.css({top: this.header_controls.height(), height: this.element.height()-(this.header_controls.height()+this.footer_controls.outerHeight())});
	},
	// Use the _setOption method to respond to changes to options
	_setOption: function( key, value ) {
		$.Widget.prototype._setOption.apply( this, arguments );
		switch( key ) {
			case "atom_xml_data":
			case "atom_xml_ajax":
			case "esqoo_xml_data":
			case "esqoo_xml_ajax":
			case "json_data":
			case "json_ajax":
			case "flickr_public_photos_data": 
			case "flickr_favorites_data":
			case "flickr_groups_data":
				this._init_data();
				break;
			case "disabled":
			// handle changes to disabled option

			break;

		}

	},
	// Load and parse the data from its data source
	_init_data: function() { 
		o=this;
		if (this.options.atom_xml_data !== null) { 
			this.dataType='atom';
			if (typeof(this.options.atom_xml_data)=='string') { 
				this.data=$.parseXML(this.options.atom_xml_data);
			} else { 
				this.data=this.options.atom_xml_data;
			}
			this._init_display();
		} else if (this.options.atom_xml_ajax !== null) { 
			if (typeof(this.options.atom_xml_ajax)!='string') { 
				$.ajax(this.options.atom_xml_ajax.url,{data: this.options.atom_xml_ajax.options, success: function(data) { 
					o.data=$(data);
					o.dataType='atom';
					o._init_display();
				}, error: function(j,t,e) { 
					throw new Error('ThumbnailBrowse: '+t);
				}});
			} else { 
				$.ajax(this.options.atom_xml_ajax,{success: function(data) { 
					o.data=$(data);
					o.dataType='atom';
					o._init_display();
				}, error: function(j,t,e) { 
					throw new Error('ThumbnailBrowse: '+t);
				}});
			}
		} else if (this.options.esqoo_xml_data !== null) { 
			this.dataType='esqoo';
			if (typeof(this.options.esqoo_xml_data)=='string') { 
				this.data=$.parseXML(this.options.esqoo_xml_data);
			} else { 
				this.data=this.options.esqoo_xml_data;
			}
			this._init_display();
		} else if (this.options.esqoo_xml_ajax !== null) { 
			if (typeof(this.options.esqoo_xml_ajax)!='string') { 
				$.ajax(this.options.esqoo_xml_ajax.url,{data: this.options.esqoo_xml_ajax.options, success: function(data) { 
					o.data=$(data);
					o.dataType='esqoo';
					o._init_display();	
				}, error: function(j,t,e) { 
					throw new Error('ThumbnailBrowse: '+t);
				}});
			} else { 
				$.ajax(this.options.esqoo_xml_ajax,{success: function(data) { 
					o.data=$(data);
					o.dataType='esqoo';
					o._init_display();
				}, error: function(j,t,e) { 
					throw new Error('ThumbnailBrowse: '+t);
				}});
			}
		} else if (this.options.json_data!== null) { 
			this.dataType='json';
			if (typeof(this.options.json_data)=='string') { 
				this.data=$.parseJSON(this.options.json_data);
			} else { 
				this.data=this.options.json_data;
			}
			this._init_display();
		} else if (this.options.json_ajax !== null) { 
			if (typeof(this.options.json_ajax)!='string') { 
				$.getJSON(this.options.json_ajax.url,this.options.json_ajax.options, function(data) { 
					o.data=$(data);
					o.dataType='json';
					o._init_display();
				});
			} else { 
				$.getJSON(this.options.json_ajax,{}, function(data) { 
					o.data=$(data);
					o.dataType='json';
					o._init_display();
				});
			}
		} else if (this.options.flickr_public_photos_data !== null) { 
			if (typeof(this.options.flickr_public_photos_data)!='string') { 
				this.flickr_smallthumbs=this.options.flickr_public_photos_data.smallthumbs;
				this.flickr_largenormals=this.options.flickr_public_photos_data.largenormals;
				this.flickr_smallnormals=this.options.flickr_public_photos_data.smallnormals;
				$.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?",{id: this.options.flickr_public_photos_data.id, ids: this.options.flickr_public_photos_data.ids, tags: this.options.flickr_public_photos_data.tags, tagmode: this.options.flickr_public_photos_data.tagmode, format: 'json'}, function(data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();	
				});
			} else { 
				$.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?",{id: this.options.flickr_public_photos_data, format: 'json'}, function(data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();	
				});
			}
		} else if (this.options.flickr_favorites_data !== null) { 
			if (typeof(this.options.flickr_favorites_data)!='string') { 
				this.flickr_smallthumbs=this.options.flickr_favorites_data.smallthumbs;
				this.flickr_largenormals=this.options.flickr_favorites_data.largenormals;
				this.flickr_smallnormals=this.options.flickr_favorites_data.smallnormals;
				$.getJSON("http://api.flickr.com/services/feeds/photos_faves.gne?jsoncallback=?",{id: this.options.flickr_favorites_data.id,'format': 'json'}, function (data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();
				});
			} else { 
				$.getJSON("http://api.flickr.com/services/feeds/photos_faves.gne?jsoncallback=?",{id: this.options.flickr_favorites_data, format: 'json'}, function (data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();
				});
			}
		} else if (this.options.flickr_groups_data !== null) {
			if (typeof(this.options.flickr_groups_data)!='string') { 
				this.flickr_smallthumbs=this.options.flickr_groups_data.smallthumbs;
				this.flickr_largenormals=this.options.flickr_groups_data.largenormals;
				this.flickr_smallnormals=this.options.flickr_groups_data.smallnormals;
				$.getJSON("http://api.flickr.com/services/feeds/groups_pool.gne?jsoncallback=?",{id: this.options.flickr_groups_data.id, format: 'json'}, function (data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();
				});	
			} else { 
				$.getJSON("http://api.flickr.com/services/feeds/groups_pool.gne?jsoncallback=?",{id: this.options.flickr_groups_data, format: 'json'}, function (data) { 
					o.data=data;
					o.dataType='flickr';
					o._init_display();
				});
			}
		} else {
			throw new Error('ThumbnailBrowse: No data specified.');
		}
	},
	// Setup display
	_init_display: function() { 
		this._parse_data();
		this.setup=true;
		this._trigger('ready');
	},
	// Doesn't actually parse the data - that's already done, what it does is take the parsed data and translate it into a normalized format
	_parse_data: function() { 
		var d=new Array();
		var me = this;
		if (this.dataType=='atom') { 
			this.d=d;
		} else if (this.dataType=='esqoo') { 
			this.data.find('Item').each(function(i,ob) { 
				d.push({title: $(ob).find('Name').text(),
					link: '',
					id: $(ob).find('PictureID').text(),
					updated: $(ob).find('ModifyDate').text(),
					'web-fullsize': $(ob).find('PictureURLs').find('web-fullsize').text(),
					'web-small': $(ob).find('PictureURLs').find('web-small').text(),
					'web-medium': $(ob).find('PictureURLs').find('web-medium').text(),
					'web-large': $(ob).find('PictureURLs').find('web-large').text(),
					'thumbnail-large': $(ob).find('PictureURLs').find('thumbnail-large').text(),
					'thumbnail-small': $(ob).find('PictureURLs').find('thumbnail-small').text(),
					'square': $(ob).find('PictureURLs').find('square').text()
				});
				
			});
			this.d=d;
		} else if (this.dataType=='json') { 
			this.d=d;
		} else if (this.dataType=='flickr') { 
			this.d=d;
		} else {
			throw new Error('ThumbnailBrowse: unknown data type: '+this.dataType);
		}
		this._do_thumbnail_html_setup();
		this._do_no_selection_toolbar_set();
	},
	_do_thumbnail_html_setup: function() { 
		var me = this;
		me.thumbnail_container_list.html('');
		$(this.d).each(function() { 
			me.thumbnail_list[this.id]=$('<li></li>')
						.html(this.title)
						.appendTo(me.thumbnail_container_list);
		});
		this.header_controls_size_slider.slider('value',this.options.initialsize);
		this._update_thumbnail_size(this.options.initialsize);
	},
	_do_no_selection_toolbar_set: function() { 
		this.footer_controls_status.html(this.d.length+' Pictures');
	},
	destroy: function() {
		$.Widget.prototype.destroy.call( this );

	}
});
}(jQuery));
