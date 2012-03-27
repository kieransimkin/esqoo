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
			position: {
				my: 'right middle',
				at: 'left middle',
				target: $(me.element)
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-shadow',
				widget: true
			},
			events: { 
				render: function(event,api) { 
					$.ajax({url: '/picture/get-full/api', dataType: 'json', type: 'post', data: {ResponseFormat: 'json', PictureID: me.element.attr('data-pictureid')}, success: function(data) { 
						var i=new Image();
						i.src=data.PictureURLs['web-small'];
						i.onload=function() { 
							api.set('content.text','<img src="'+data.PictureURLs['web-small']+'"><br /><div class="sqtip-exif-container"><div class="esqoo-qtip sqtip-isospeedratings" data-qtip-content="ISO Speed" data-qtip-position-my="top right" data-qtip-position-at="bottom left">'+data.EXIF.ISOSpeedRatings+'</div><div class="esqoo-qtip sqtip-exposuretime" data-qtip-content="Exposure Time" data-qtip-position-my="top middle" data-qtip-position-at="bottom middle">'+data.EXIF.ExposureTime+'sec</div><div class="esqoo-qtip sqtip-aperturefnumber" data-qtip-content="Aperture" data-qtip-position-my="top left" data-qtip-position-at="bottom right">'+data.EXIF.ApertureFNumber+'</div></div>');
						}
					}});
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
