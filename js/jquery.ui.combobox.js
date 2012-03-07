(function( $ ) {
	$.widget( "ui.combobox", {
		options: {
			source: null
		},
		_create: function() {
			var me = this;
			$(this.element).autocomplete({
			    source: this.options.source,
			    minLength: 0
			}).addClass("ui-widget ui-widget-content ui-corner-left");
			$(this.element).removeClass('ui-corner-all');
			$(this.element).addClass('ui-corner-left');
			this.button = $( "<button type='button'>&nbsp;</button>" )
				.attr( "tabIndex", -1 )
				.attr( "title", "Show All Items" )
				.insertBefore( this.element )
				.button({
					icons: {
						primary: "ui-icon-triangle-1-s"
					},
					text: false
				})
				.removeClass( "ui-corner-all" )
				.addClass( "ui-corner-right ui-button-icon esqoo-combobox-button" )
				.click(function() {
					// close if already visible
					if ( me.element.autocomplete( "widget" ).is( ":visible" ) ) {
						me.element.autocomplete( "close" );
						return;
					}

					// work around a bug (likely same cause as #5265)
					$( this ).blur();

					// pass empty string as value to search for, displaying all results
					me.element.autocomplete( "search", "" );
					me.element.focus();
				});
		},

		destroy: function() {
			this.button.remove();
			$.Widget.prototype.destroy.call( this );
		}
	});
})( jQuery );
