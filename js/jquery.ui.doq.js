(function( $ ) {
$.widget( "esqoo.doq", {
	options: {
		sides_bigger:true,
		default_leftbar_width: '20%',
		default_rightbar_width: '20%',
		default_topbar_height: '20%',
		default_bottombar_height: '20%'
	},
	leftbar_docked_items: [],
	rightbar_docked_items: [],
	topbar_docked_items: [],
	bottombar_docked_items: [],
	leftbar_width: null,
	rightbar_width: null,
	topbar_height: null,
	bottombar_height: null,
	hover_dialog: null,
	hover_suspended: false,
	_create: function() { 
		this._do_html_setup();
	},
	bind_dialog: function(d) { 
		d.uiDialog.bind('dialogdragstart.ui-dialog',this._dialog_dragstart(d));
		d.uiDialog.bind('dialogdragstop.ui-dialog',this._dialog_dragstop(d));
		d.uiDialog.bind('dialogdrag.ui-dialog',this._dialog_drag(d));
	},
	_dialog_drag: function(d) { 
		var me = this;
		return function(event,ui) { 
			if (ui.position.top<me.container.offset().top) { 
				// Make the dialog not exceed the top of the doq
				d.element.parent().css({'top':me.container.offset().top});
				me._mouse_in_dropzone(d,me.topbar);
				return false;
			}
			if (ui.position.left<me.container.offset().left) { 
				// Make the dialog not exceed the left of the doq
				d.element.parent().css({'left':me.container.offset().left});
				me._mouse_in_dropzone(d,me.leftbar);
				return false;
			}
			if (ui.position.left+me._get_dialog_width(d)>me.container.offset().left+me.container.width()) { 
				// Make the dialog not exceed the right of the doq
				d.element.parent().css({'left':(me.container.offset().left+me.container.width())-me._get_dialog_width(d)});
				me._mouse_in_dropzone(d,me.rightbar);
				return false;
			}
			if (ui.position.top+me._get_dialog_height(d)>me.container.offset().top+me.container.height()) { 
				// Make the dialog not exceed the bottom of the doq
				d.element.parent().css({'top':(me.container.offset().top+me.container.height())-me._get_dialog_height(d)});
				me._mouse_in_dropzone(d,me.bottombar);
				return false;
			}
			var indropzone=false;
			// TODO - only one of these should be executed, they need to be prioritized correctly depending on where the mouse is
			if (ui.position.top<me.container.offset().top+me._get_topbar_height()) { 
				// Dialog is within the topbar dropzone
				me._mouse_in_dropzone(d,me.topbar);
				indropzone=true;
			}
			if (ui.position.left<me.container.offset().left+me._get_leftbar_width()) { 
				// Dialog is within the leftbar dropzone
				me._mouse_in_dropzone(d,me.leftbar);
				indropzone=true;
			}
			if (ui.position.left+me._get_dialog_width(d)>(me.container.offset().left+me.container.width())-me._get_rightbar_width()) { 
				// Dialog is within the rightbar dropzone
				me._mouse_in_dropzone(d,me.rightbar);
				indropzone=true;
			}
			if (ui.position.top+me._get_dialog_height(d)>(me.container.offset().top+me.container.height())-me._get_bottombar_height()) { 
				// Dialog is within the bottombar dropzone
				me._mouse_in_dropzone(d,me.bottombar);
				indropzone=true;
			}
			if (!indropzone && me.hover_dialog!==null) { 
				me._mouse_leave_dropzone(d);	
				me.hover_dialog=null;
			}
		}
	},
	_dialog_drag_inner: function(d) { 
		var me = this;
		return function(event,ui) { 

		}
	},
	_mouse_in_dropzone: function(dialog,dropzone) { 
		if (!this.hover_suspended) { 
			if (this.hover_dialog===null) { 
				this._mouse_enter_dropzone(dialog,dropzone);
				this.hover_dialog=dropzone;
			} else if (this.hover_dialog==dropzone) { 
				this._mouse_move_dropzone(dialog,dropzone);
			} else { 
				this._mouse_change_dropzone(dialog,dropzone);
				this.hover_dialog=dropzone;
			}
		}
	},
	_mouse_enter_dropzone: function(dialog,dropzone,callback) { 
		if (this._get_docked_items(dropzone)<1) { 
			this._expand_bar(dropzone,callback);
		} else { 

		}
		this._size_dialog_to_bar(dialog,dropzone);
	},
	_mouse_move_dropzone: function(dialog,dropzone) { 

	},
	_mouse_change_dropzone: function(dialog,dropzone) { 
		var me = this;
		this.hover_suspended=true;
		this._mouse_leave_dropzone(dialog,function() { 
			me._mouse_enter_dropzone(dialog,dropzone,function() { 
				me.hover_suspended=false;
			});
		});
	},
	_mouse_leave_dropzone: function(dialog,callback) { 
		if (this._get_docked_items(this.hover_dialog)<1) { 
			this._collapse_bar(this.hover_dialog,callback);
		} else { 

		}
		this._restore_dialog_size(dialog);
	},
	_expand_bar: function(bar,callback) { 
		var size=this._get_bar_size(bar);
		this._resize_slide_bar(bar,size,callback);
	},
	_resize_slide_bar: function(bar,size,callback) { 
		switch (bar) { 
			case this.topbar:
			case this.bottombar:
				bar.animate({height: size},'fast','swing',callback);
				break;
			case this.leftbar:
			case this.rightbar:
				bar.animate({width: size},'fast','swing',callback);
				break;
		}
	},
	_collapse_bar: function(bar,callback) { 
		this._resize_slide_bar(bar,'1em',callback);
	},
	_dialog_dragstart: function(d) { 
		var me = this;
		return function(event,ui) { 
			console.log('dragstart');
		}
	},
	_dialog_dragstop: function(d) { 
		var me = this;
		return function(event,ui) { 
			console.log('dragstop');
		}
	},
	_get_bar_size: function(bar) { 
		switch (bar) { 
			case this.leftbar:
				if (this.leftbar_width!==null) { 
					return this.leftbar_width;
				} else { 
					// XXX External esqoo_ui call
					return (this.leftbar_width=esqoo_ui.convert_percentages_to_viewport_width_pixels(this.options.default_leftbar_width));
				}
			case this.rightbar:
				if (this.rightbar_width!==null) { 
					return this.rightbar_width;
				} else { 
					// XXX External esqoo_ui call
					return (this.rightbar_width=esqoo_ui.convert_percentages_to_viewport_width_pixels(this.options.default_rightbar_width));
				}
			case this.topbar:
				if (this.topbar_height!==null) { 
					return this.topbar_height;
				} else { 
					// XXX External esqoo_ui call
					return (this.topbar_height=esqoo_ui.convert_percentages_to_viewport_height_pixels(this.options.default_topbar_height));
				}
			case this.bottombar:
				if (this.bottombar_height!==null) { 
					return this.bottombar_height;
				} else { 
					// XXX External esqoo_ui call
					return (this.bottombar_height=esqoo_ui.convert_percentages_to_viewport_height_pixels(this.options.default_bottombar_height));
				}
		}
	},
	_get_docked_items: function(bar) { 
		switch (bar) { 
			case this.leftbar:
				return this.leftbar_docked_items;
			case this.rightbar:
				return this.rightbar_docked_items;
			case this.topbar:
				return this.topbar_docked_items;
			case this.bottombar:
				return this.bottombar_docked_items;
		}
	},
	_do_html_setup: function() { 
		$(window).resize(this._resize());
		this.container = $('<div></div>')
				.css({'position':'relative','top':'0px','left':'0px','width':'100%','height':'100%'})
				.addClass('esqoo-ui-doq-container ui-widget')
				.insertBefore(this.element);
		this.leftbar=$('<div></div>')
				.css({'position':'absolute','left':'0px','width':'0.8em','height':'100%','top':'0px'})
				.addClass('esqoo-ui-doq-leftbar ui-widget-content ui-state-active esqoo-ui-dockbar')
				.appendTo(this.container);
		this.rightbar=$('<div></div>')
				.css({'position':'absolute','right':'0px','width':'0.8em','height':'100%'})
				.addClass('esqoo-ui-doq-rightbar ui-widget-content ui-state-active esqoo-ui-dockbar')
				.appendTo(this.container);
		this.topbar=$('<div></div>')
				.css({'position':'absolute','top':'0px', 'width':'100%', 'height':'0.8em'})
				.addClass('esqoo-ui-doq-topbar ui-widget-content ui-state-active esqoo-ui-dockbar')
				.appendTo(this.container);
		this.bottombar=$('<div></div>')
				.css({'position':'absolute','bottom':'0px', 'width':'100%','height':'0.8em'})
				.addClass('esqoo-ui-doq-bottombar ui-widget-content ui-state-active esqoo-ui-dockbar')
				.appendTo(this.container);
		this._size_bars();
	},
	_resize: function() { 
		var me = this;
		return function() { 
			me._size_bars();
		}
	},
	_get_topbar_height: function() { 
		return this.topbar.height();
	},
	_get_bottombar_height: function() { 
		return this.bottombar.height();
	},
	_get_leftbar_width: function() { 
		return this.leftbar.width();
	},
	_get_rightbar_width: function() { 
		return this.rightbar.width();
	},
	_get_sidebar_height: function() { 
		return this.leftbar.height();
	},
	_get_endbar_width: function() { 
		return this.topbar.width();
	},
	_get_dialog_width: function(dialog) { 
		if (typeof(dialog.temp_docked)!='undefined' && dialog.temp_docked===true) { 
			return dialog.oldwidth;
		} else { 
			return dialog.element.parent().outerWidth();
		}
	},
	_get_dialog_height: function(dialog) { 
		if (typeof(dialog.temp_docked)!='undefined' && dialog.temp_docked===true) { 
			return dialog.oldheight;
		} else { 
			return dialog.element.parent().outerHeight();
		}
	},
	_size_dialog_to_bar: function(dialog,bar) { 
		var width=null;
		var height=null;
		dialog.uiDialog.unbind('dialogdrag.ui-dialog');
		dialog.uiDialog.bind('dialogdrag.ui-dialog',this._dialog_drag_inner(dialog));
		switch (bar) { 
			case this.leftbar:
				width=this._get_bar_size(bar)-20;
				if (this.leftbar_docked_items.length<1) { 
					height=this._get_sidebar_height()-20;
				} else { 
					height=this._get_sidebar_height()/this.leftbar_docked_items.length;
				}
				break;
			case this.rightbar:
				width=this._get_bar_size(bar)-20;
				if (this.rightbar_docked_items.length<1) { 
					height=this._get_sidebar_height()-20;
 				} else { 
					height=this._get_sidebar_height()/this.rightbar_docked_items.length;
				}
				break;	
			case this.topbar:
				height=this._get_bar_size(bar)-20;	
				if (this.topbar_docked_items.length<1) { 
					width=this._get_endbar_width()-20;
				} else { 
					width=this._get_endbar_width()/this.topbar_docked_items.length;
				}
				break;	
			case this.bottombar:
				height=this._get_bar_size(bar)-20;
				if (this.bottombar_docked_items.length<1) { 
					width=this._get_endbar_width()-20;	
				} else { 
					width=this._get_endbar_width()/this.bottombar_docked_items.length;
				}
				break;
		}
		console.log(width);
		console.log(height);
		dialog.oldwidth=this._get_dialog_width(dialog);
		dialog.oldheight=this._get_dialog_height(dialog);
		dialog.temp_docked=true;
		dialog.stored_minwidth=dialog.option('minWidth');
		dialog.stored_minheight=dialog.option('minHeight');
		dialog.stored_maxwidth=dialog.option('maxWidth');
		dialog.stored_maxheight=dialog.option('maxHeight');
		dialog.stored_width=dialog.option('width');
		dialog.stored_height=dialog.option('height');
		dialog.option('maxWidth',width);
		dialog.option('maxHeight',height);
		dialog.option('minWidth',width);
		dialog.option('minHeight',height);
		dialog.option('width',width);
		dialog.option('height',height);
	},
	_restore_dialog_size: function(dialog) { 
		dialog.uiDialog.unbind('dialogdrag.ui-dialog');
		dialog.uiDialog.bind('dialogdrag.ui-dialog',this._dialog_drag(dialog));
		dialog.temp_docked=false;
		dialog.option('maxWidth',dialog.stored_maxwidth);
		dialog.option('maxHeight',dialog.stored_maxheight);	
		dialog.option('minWidth',dialog.stored_minwidth);
		dialog.option('minHeight',dialog.stored_minheight);
		dialog.option('width',dialog.stored_width);
		dialog.option('height',dialog.stored_height);
	},
	_size_bars: function() { 
		if (!this.options.sides_bigger) { 
			this.topbar.css({'width':this.element.width()-2,'z-index':'2'});
			this.bottombar.css({'width':this.element.width()-2,'z-index':'2'});
			this.leftbar.css({'top':this._get_topbar_height()+1,'height':this.element.height()-(this._get_topbar_height()+this._get_bottombar_height()+7)});
			this.rightbar.css({'top':this._get_topbar_height()+1,'height':this.element.height()-(this._get_topbar_height()+this._get_bottombar_height()+7)});
		} else { 
			this.topbar.css({'left':this._get_leftbar_width()+1,'width':this.element.width()-(this._get_leftbar_width()+this._get_rightbar_width()+4)});
			this.bottombar.css({'left':this._get_leftbar_width()+1,'width':this.element.width()-(this._get_leftbar_width()+this._get_rightbar_width()+4)});
			this.rightbar.css({'z-index':'2'});
			this.leftbar.css({'z-index':'2'});
		}
	},
	_setOption: function( key, value ) {
		$.Widget.prototype._setOption.apply( this, arguments );
		switch( key ) {
			case "disabled":
			// handle changes to disabled option
			break;
		}
	},
	destroy: function() {
		$.Widget.prototype.destroy.call( this );
	}
});
}(jQuery)); 
