(function( $ ) {
$.widget( "esqoo.doq", {
	options: {
		sides_bigger:true
	},
	_create: function() { 
		this._do_html_setup();
	},
	_do_html_setup: function() { 
		$(window).resize(this._resize());
		this.container = $('<div></div>')
				.css({'position':'relative','top':'0px','left':'0px','width':'100%','height':'100%'})
				.addClass('esqoo-ui-doq-container ui-widget')
				.insertBefore(this.element);
		this.leftbar=$('<div></div>')
				.css({'position':'absolute','left':'0px','width':'0.8em','height':'100%','top':'0px'})
				.addClass('esqoo-ui-doq-leftbar ui-widget-content ui-state-active')
				.appendTo(this.container);
		this.rightbar=$('<div></div>')
				.css({'position':'absolute','right':'0px','width':'0.8em','height':'100%'})
				.addClass('esqoo-ui-doq-rightbar ui-widget-content ui-state-active')
				.appendTo(this.container);
		this.topbar=$('<div></div>')
				.css({'position':'absolute','top':'0px', 'width':'100%', 'height':'0.8em'})
				.addClass('esqoo-ui-doq-topbar ui-widget-content ui-state-active')
				.appendTo(this.container);
		this.bottombar=$('<div></div>')
				.css({'position':'absolute','bottom':'0px', 'width':'100%','height':'0.8em'})
				.addClass('esqoo-ui-doq-bottombar ui-widget-content ui-state-active')
				.appendTo(this.container);
		this._setup_droppables();
		this._size_bars();
	},
	_resize: function() { 
		var me = this;
		return function() { 
			me._size_bars();
		}
	},
	_setup_droppables: function() { 
		this.leftbar.droppable({drop: this._do_drop(this.leftbar)});
		this.rightbar.droppable({drop: this._do_drop(this.rightbar)});
		this.topbar.droppable({drop: this._do_drop(this.topbar)});
		this.bottombar.droppable({drop: this._do_drop(this.bottombar)});
	},
	_do_drop: function(bar) { 
		return function (event,ui) { 
			console.log('got drop');
			console.log(bar);
			console.log(event);
			console.log(ui);
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
			this.topbar.css({'width':this.element.width()-2});
			this.bottombar.css({'width':this.element.width()-2});
			this.leftbar.css({'top':this._get_topbar_height()+1,'height':this.element.height()-(this._get_topbar_height()+this._get_bottombar_height()+7)});
			this.rightbar.css({'top':this._get_topbar_height()+1,'height':this.element.height()-(this._get_topbar_height()+this._get_bottombar_height()+7)});
		} else { 
			this.topbar.css({'left':this._get_leftbar_width()+1,'width':this.element.width()-(this._get_leftbar_width()+this._get_rightbar_width()+4)});
			this.bottombar.css({'left':this._get_leftbar_width()+1,'width':this.element.width()-(this._get_leftbar_width()+this._get_rightbar_width()+4)});
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