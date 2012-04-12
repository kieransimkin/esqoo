(function( $ ) {
$.widget( "esqoo.thumbnailbrowse", {
	options: {
		url: null
	},
	_create: function() { 
		this._do_html_setup();
	},
	_do_html_setup: function() { 
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
				.css({width: '75%', height: '100%', float: 'left'})
				.appendTo(this.content_body);
		this._setup_header_controls_html();
		this._setup_footer_controls_html();
		this._setup_left_bar_html();
		this._position_content_body();
		this._generate_thumbnail_list();
	},
	_generate_thumbnail_list: function() { 
		this.thumb_container.html('Thumb container');
	},
	_setup_left_bar_html: function() { 
		this.content_left_bar_body_content.html('Left Bar');
	},
	_setup_header_controls_html: function() { 
		this.header_controls_content.html('Header');	
	},
	_setup_footer_controls_html: function() { 
		this.footer_controls_content.html('Footer');
	},
	_position_content_body: function() { 
		this.content_body.css({top: this.header_controls.height(), height: this.element.height()-(this.header_controls.height()+this.footer_controls.outerHeight())});
	},
	// Use the _setOption method to respond to changes to options
	_setOption: function( key, value ) {
		switch( key ) {

			case "disabled":
			// handle changes to disabled option

			break;

		}

		$.Widget.prototype._setOption.apply( this, arguments );
	},
	destroy: function() {
		$.Widget.prototype.destroy.call( this );

	}
});
}(jQuery));
