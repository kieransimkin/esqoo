(function( $ ) {
$.widget( "esqoo.thumbnailbrowse", {
	options: {
		picturesizes: {},
		initialsize: 150,
		minsize: 100,
		maxsize: null,
		selecttype: 'single', // also supports 'multi'
		selectmode: 'add', // this option only applies if selecttype is 'multi' - 'add' adds to the selection when you click on a thumb, 'normal' only adds to the selection if you hold down ctrl
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
	thumbnail_size: null,
	thumbnails_loading: {},
	thumbnails_loaded: {},
	scroll_timeout: null,
	size_slider_scroll_fraction: null,
	selected_thumbs: [],
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
			me._position_content_body();
			me._size_thumb_container();
			me._size_controls();
			me._trigger_thumbnail_loads();
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
				.css({position: 'absolute', bottom: '0px'})
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
				.css({position: 'absolute', top: '0px'})
				.appendTo(this.container);
		this.header_controls_content=$('<div></div>')
				.css({margin: '0.2em'})
				.appendTo(this.header_controls);
		this._size_controls();
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
				.css({ height: '100%', float: 'left', 'overflow-y': 'auto'}) // TODO fix this width
				.scroll(this._scroll_thumb_container())
				.appendTo(this.content_body);
		this.thumbnail_container_list=$('<ul></ul>')
				.addClass('esqoo-ui-thumbnailbrowse-thumb-container-list')
				.css({padding: '0px','margin-top':'0px'})
				.appendTo(this.thumb_container);
		this._setup_header_controls_html();
		this._setup_footer_controls_html();
		this._setup_left_bar_html();
		this._position_content_body();
		this._size_thumb_container();
	},
	_size_controls: function() { 
		this.header_controls.css({'width':this.element.width()-2});
		this.footer_controls.css({'width':this.element.width()-2});
	},
	_size_thumb_container: function() { 
		this.thumb_container.css({'width': this.element.width()-(this.content_left_bar.outerWidth()+1)});
	},
	_scroll_thumb_container: function() { 
		var me = this;
		return function() { 
			// stupid hack to run this a separate thread:
			function run() { 
				me._trigger_thumbnail_loads();
				me.scroll_timeout=null;
			}
			if (me.scroll_timeout!==null) { 
				clearTimeout(me.scroll_timeout);
			}
			me.scroll_timeout=setTimeout(run,300);

		}
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
				me.content_left_bar.animate({width:'0.7em'},function() { 
					me._size_thumb_container();
				});
			});
		}
	},
	_left_bar_maximize_button_click: function() { 
		var me = this;
		return function() { 
			me.content_left_bar_body_maximize_button.attr('disabled',true);
			me.content_left_bar_body_maximize_button.fadeOut('fast');
			if (me.element.width()*0.25<250) { 
				var ratio=250/me.element.width();
				me.thumb_container.css({width: (me.element.width()*(1-ratio))-1});
			} else { 
				me.thumb_container.css({width: (me.element.width()*0.75)-1});
			}
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
					.css({width: '25%', float: 'right', 'min-width':'200px', top:'0.1em'})
					.appendTo(this.header_controls_content)
					.slider({animate: true,min: this.options.minsize, max: this.options.maxsize, slide: this._size_slider_slide(), change: this._size_slider_change(), start: this._size_slider_start()});
	},
	_size_slider_start: function() { 
		var me = this;
		return function(event, ui) { 
			me.size_slider_scroll_fraction=me._get_scroll_fraction();
		}
	},
	_get_scroll_fraction: function() { 
		return this.thumb_container.scrollTop()/this.thumb_container[0].scrollHeight;
		
	},
	_set_scroll_fraction: function(frac) { 
		if (frac===null) { 
			return;
		}
		this.thumb_container.scrollTop(this.thumb_container[0].scrollHeight*frac);
	},
	_size_slider_slide: function() { 
		var me = this;
		return function (event,ui) { 
			me._update_thumbnail_size(ui.value,false);
			me._set_scroll_fraction(me.size_slider_scroll_fraction);
		}
	},
	_size_slider_change: function() { 
		var me = this;
		return function (event,ui) { 
			me._update_thumbnail_size(ui.value);
			me._set_scroll_fraction(me.size_slider_scroll_fraction);
		}
	},
	_get_current_image_size: function() { 
		if (this.thumbnail_size<=this.options.picturesizes['thumbnail-small']) { 
			return 'thumbnail-small';
		} else if (this.thumbnail_size<=this.options.picturesizes['thumbnail-large']) { 
			return 'thumbnail-large';
		} else if (this.thumbnail_size<=this.options.picturesizes['web-small']) { 
			return 'web-small';
		} else if (this.thumbnail_size<=this.options.picturesizes['web-medium']) { 
			return 'web-medium';
		} else { 
			return 'web-large';
		}
		return this.thumbnail_size;
	},
	_get_best_thumbnail_quality: function(thumb) { 
		if (typeof(this.thumbnails_loaded['web-large:'+thumb.object.id])!='undefined') { 
			return 'web-large';
		} else if (typeof(this.thumbnails_loaded['web-medium:'+thumb.object.id])!='undefined') { 
			return 'web-medium';
		} else if (typeof(this.thumbnails_loaded['web-small:'+thumb.object.id])!='undefined') { 
			return 'web-small';
		} else if (typeof(this.thumbnails_loaded['thumbnail-large:'+thumb.object.id])!='undefined') { 
			return 'thumbnail-large';
		} else if (typeof(this.thumbnails_loaded['thumbnail-small:'+thumb.object.id])!='undefined') { 
			return 'thumbnail-small';
		} else { 
			return null;
		}
	},
	_update_thumbnail_size: function(size,trigger) { 
		this.thumbnail_size=size;
		if (typeof(trigger)=='undefined') { 
			trigger=true;
		}
		$.each(this.thumbnail_list, function(i,o) { 
			$(o.li).find('div').css({width:size});
			$(o.li).find('div').css({height:size});
			$(o.li).find('span').css({width:size});
		});
		if (trigger) { 
			this._trigger_thumbnail_loads();
		}
	},
	_update_thumbnail_best_quality: function(thumb) { 
		var quality=this._get_best_thumbnail_quality(thumb);
		thumb.li.find('img').attr('src',thumb.object[quality]);
		if (this.thumbnails_loaded[quality+':'+thumb.object.id].width < this.options.picturesizes[quality]) { 
			var nwidth=(this.thumbnails_loaded[quality+':'+thumb.object.id].width/this.options.picturesizes[quality])*100;
			thumb.li.find('img').css({width:nwidth+'%'});
		} else { 
			thumb.li.find('img').css({width:'100%'});
		}
	},
	_do_thumbnail_load: function (thumb,size) { 
		if (typeof(this.thumbnails_loading[size+':'+thumb.object.id])!='undefined') { 
			return;
		}
		this.thumbnails_loading[size+':'+thumb.object.id]='true';
		var img=new Image();
		var me=this;
		img.onload=function() { 
			me.thumbnails_loaded[size+':'+thumb.object.id]=this;	
			me._update_thumbnail_best_quality(thumb);
		}
		img.src=thumb.object[size];
	},
	_trigger_thumbnail_loads: function() { 
		var parentoffsettop=this.thumbnail_container_list.offset()['top'];
		var scrolltop=this.thumb_container.scrollTop();
		var scrollbottom=scrolltop+this.thumb_container.height();
		var me = this;
		var current_image_size=me._get_current_image_size();
		$.each(this.thumbnail_list,function(i,o) { 
			if (typeof(o)=='undefined') { 
				return;
			}
			var offsettop=$(o.li).find(':eq(0)').offset()['top']-parentoffsettop;
			if (offsettop>scrollbottom) { 
				return;
			}
			if (offsettop+$(o.li).height()<scrolltop) { 
				return;
			}
			me._do_thumbnail_load(o,current_image_size);
		});
	},
	_setup_footer_controls_html: function() { 
		this.footer_controls_status=$('<span></span>')
					.html('Loading...')
					.addClass('esqoo-ui-thumbnailbrowse-footer-status')
					.appendTo(this.footer_controls_content);
	},
	_position_content_body: function() { 
		this.content_body.css({top: this.header_controls.height(), height: this.element.height()-(this.header_controls.height()+this.footer_controls.outerHeight())});
	},
	_do_thumbnail_html_setup: function() { 
		var me = this;
		me.thumbnail_container_list.html('');
		me.thumbnail_list={};
		$(this.d).each(function() { 
			me.thumbnail_list[this.id]={object: this, li: $('<li></li>')
						.css({display: 'block',float: 'left', margin:'0.5em',padding: '0.5em','border':'1px solid transparent','cursor':'pointer'})
						.addClass('ui-corner-all')
						.hover(me._thumb_mouseover(this.id), me._thumb_mouseout(this.id))
						.mousedown(me._thumb_mousedown(this.id))
						.mouseup(me._thumb_mouseup(this.id))
						.appendTo(me.thumbnail_container_list)};
			$('<span></span>').html(this.title).css({'font-weight':'normal',height:'2em','word-wrap':'break-word', display: 'block',width: '100px','text-align':'center'}).appendTo(me.thumbnail_list[this.id].li);
			var imagecontainer=$('<div></div>').css({'width':'100px', margin: 'auto','text-align':'center'}).prependTo(me.thumbnail_list[this.id].li);
			var table=$('<table></table>').css({height: '100%',width:'100%'}).appendTo(imagecontainer);
			var tr=$('<tr></tr>').appendTo(table);
			var td=$('<td></td>').css({'vertical-align':'middle'}).appendTo(tr);
			$('<img />').css({'width': '100%','max-height': '100%'}).appendTo(td);
		});
		this.header_controls_size_slider.slider('value',this.options.initialsize);
	},
	_clear_selection: function() { 
		$.each(this.selected_thumbs,function(i,o) { 
			o.li.removeClass('ui-widget-content').css({'border':'1px solid transparent'});
		});
		this.selected_thumbs=[];
	},
	_thumb_mouseover: function(id) { 
		return function() { 
			$(this).addClass('ui-widget-content ui-state-hover').css({'border':''});
		}
	},
	_thumb_mouseout: function(id) { 
		var me = this;
		return function() { 
			console.log(me.selected_thumbs);
			var found=false;
			var t=this;
			$.each(me.selected_thumbs,function(i,o) { 
					console.log([o.li.get()[0],t]);
				if (o.li.get()[0]==t) { 
					found=true;
					return false;
				}
			});
			if (!found) { 
				$(this).removeClass('ui-widget-content').css({'border':'1px solid transparent'}); 
			}
			$(this).removeClass('ui-state-hover ui-state-active');
		}
	},
	_thumb_mousedown: function(id) { 
		var me = this;
		return function() { 
			var thumb = me.thumbnail_list[id];
			thumb.li.removeClass('ui-state-hover').addClass('ui-state-active');
		}
	},
	_thumb_mouseup: function(id) { 
		var me = this;
		return function() { 
			var thumb = me.thumbnail_list[id];
			thumb.li.removeClass('ui-state-active').addClass('ui-state-hover');
			if (me.options.selecttype=='single') { 
				me._clear_selection();
				me.selected_thumbs=[thumb];
			} else { 
				if (me.options.selectmode=='add') { 
					me.selected_thumbs.push(thumb);	
				} else { 
					//TODO - support holding down CTRL to select multiple
					me._clear_selection();
					me.selected_thumbs=[thumb];
				}
			}
		}

	},
	_do_no_selection_toolbar_set: function() { 
		this.footer_controls_status.html(this.d.length+' Pictures');
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
		this.thumbnails_loading={};
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
				var options=this.options.esqoo_xml_ajax.options;
				options.push({'name':'ResultsPerPage','value':'1000'});
				$.ajax(this.options.esqoo_xml_ajax.url,{data: options, success: function(data) { 
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
	destroy: function() {
		$.Widget.prototype.destroy.call( this );

	}
});
}(jQuery));
