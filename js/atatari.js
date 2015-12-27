jQuery.fn.keepInView = function( spacing ) {
  if ( spacing == null ) spacing = 30;

  this.each( function() {
    var $element = jQuery( this ),
        initial_offset = $element.offset().top;

    jQuery( window ).scroll( function() {
      var offset = Math.max( jQuery( window ).scrollTop() + spacing - initial_offset, 0 );
      $element.stop().animate( { marginTop: offset + 'px' }, 'fast' );
    } );
  } );

  return this;
}

	// reset
	jQuery('.att-popup-reset').click(function(){
		if( confirm( "Are you sure? Resetting will loose all custom values!" ) ){
			return true;
	    } else {
	    	return false;
	    }
	});

