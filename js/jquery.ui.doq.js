(function( $ ) {
$.widget( "esqoo.doq", {
	options: {
		sides_bigger:true
	},
	leftbar_docked_items: [],
	rightbar_docked_items: [],
	topbar_docked_items: [],
	bottombar_docked_items: [],
	_create: function() { 
		this._do_html_setup();
	},
	bind_dialog: function(d) { 
		d.uiDialog.bind('dialogdragstart.ui-dialog',this._dialog_dragstart(d));
		d.uiDialog.bind('dialogdragstop.ui-dialog',this._dialog_dragstop(d));
		d.uiDialog.bind('dialogdrag.ui-dialog',this._dialog_drag(d));
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
			if (ui.position.top<me.container.offset().top+me._get_topbar_height()) { 
				// Dialog is within the topbar dropzone
				me._mouse_in_dropzone(d,me.topbar);
			}
			if (ui.position.left<me.container.offset().left+me._get_leftbar_width()) { 
				// Dialog is within the leftbar dropzone
				me._mouse_in_dropzone(d,me.leftbar);
			}
			if (ui.position.left+d.element.parent().outerWidth()>(me.container.offset().left+me.container.width())-me._get_rightbar_width()) { 
				// Dialog is within the rightbar dropzone
				me._mouse_in_dropzone(d,me.rightbar);
			}
			if (ui.position.top+d.element.parent().outerHeight()>(me.container.offset().top+me.container.height())-me._get_bottombar_height()) { 
				// Dialog is within the bottombar dropzone
				me._mouse_in_dropzone(d,me.bottombar);
			}
		}
	},
	_mouse_in_dropzone: function(dialog,dropzone) { 
		console.log(dropzone);
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
