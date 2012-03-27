(function( $ ) {
$.widget( "esqoo.sqtip", {
	options: {

	},
	_create: function() { 
		this._do_setup();
	},
	_do_setup: function() { 
		var me = this;
		$(this.element).qtip({
			content: 'Loading...',
			hide: {
				fixed: true,
				delay: 1000
			},
			events: { 
				render: function(event,api) { 
					$.ajax({url: '/picture/get-full/api', dataType: 'json', type: 'post', data: {ResponseFormat: 'json', PictureID: me.element.attr('data-pictureid')}, success: function(data) { 
						console.log(data);
						api.set('content.text',data.PictureURLs['web-medium']+'foo');
					}});
					console.log(api);
				}
			} 
		});
		console.log(this.element);
		console.log('got here');
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
