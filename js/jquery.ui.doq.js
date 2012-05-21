(function( $ ) {
$.widget( "esqoo.doq", {
	options: {
		sides_bigger:true,
		default_leftbar_width: '200',
		default_rightbar_width: '200',
		default_topbar_height: '200',
		default_bottombar_height: '200'
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
			if (ui.position.left+d.element.parent().outerWidth()>me.container.offset().left+me.container.width()) { 
				// Make the dialog not exceed the right of the doq
				d.element.parent().css({'left':(me.container.offset().left+me.container.width())-d.element.parent.outerWidth()});
				me._mouse_in_dropzone(d,me.rightbar);
				return false;
			}
			if (ui.position.top+d.element.parent().outerHeight()>me.container.offset().top+me.container.height()) { 
				// Make the dialog not exceed the bottom of the doq
				d.element.parent().css({'top':(me.container.offset().top+me.container.height())-d.element.parent().outerHeight()});
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
			if (ui.position.left+d.element.parent().outerWidth()>(me.container.offset().left+me.container.width())-me._get_rightbar_width()) { 
				// Dialog is within the rightbar dropzone
				me._mouse_in_dropzone(d,me.rightbar);
				indropzone=true;
			}
			if (ui.position.top+d.element.parent().outerHeight()>(me.container.offset().top+me.container.height())-me._get_bottombar_height()) { 
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
	_mouse_in_dropzone: function(dialog,dropzone) { 
		if (this.hover_dialog===null) { 
			this._mouse_enter_dropzone(dialog,dropzone);
			this.hover_dialog=dropzone;
		} else if (this.hover_dialog==dropzone) { 
			this._mouse_move_dropzone(dialog,dropzone);
		} else { 
			this._mouse_change_dropzone(dialog,dropzone);
			this.hover_dialog=dropzone;
		}
	},
	_mouse_enter_dropzone: function(dialog,dropzone) { 
		if (this._get_docked_items(dropzone)<1) { 
			this._expand_bar(dropzone);
		} else { 

		}
	},
	_mouse_move_dropzone: function(dialog,dropzone) { 

	},
	_mouse_change_dropzone: function(dialog,dropzone) { 
		// XXX some work still to do on this
		return;
		this._mouse_leave_dropzone(dialog);
		this._mouse_enter_dropzone(dialog,dropzone);
	},
	_mouse_leave_dropzone: function(dialog) { 
		if (this._get_docked_items(this.hover_dialog)<1) { 
			this._collapse_bar(this.hover_dialog);
		} else { 

		}
	},
	_expand_bar: function(bar) { 
		var size=this._get_bar_size(bar);
		this._resize_slide_bar(bar,size);
	},
	_resize_slide_bar: function(bar,size) { 
		switch (bar) { 
			case this.topbar:
			case this.bottombar:
				bar.animate({height: size},'fast');
				break;
			case this.leftbar:
			case this.rightbar:
				bar.animate({width: size},'fast');
				break;
		}
	},
	_collapse_bar: function(bar) { 
		this._resize_slide_bar(bar,'1em');
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
					return (this.leftbar_width=this.options.default_leftbar_width);
				}
			case this.rightbar:
				if (this.rightbar_width!==null) { 
					return this.rightbar_width;
				} else { 
					return (this.rightbar_width=this.options.default_rightbar_width);
				}
			case this.topbar:
				if (this.topbar_height!==null) { 
					return this.topbar_height;
				} else { 
					return (this.topbar_height=this.options.default_topbar_height);
				}
			case this.bottombar:
				if (this.bottombar_height!==null) { 
					return this.bottombar_height;
				} else { 
					return (this.bottombar_height=this.options.default_bottombar_height);
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
