// backend.js
// do not distribute without platesgenerator.php
/*
	This file is part of PlatesGenerator.

    PlatesGenerator is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    PlatesGenerator is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PlatesGenerator.  If not, see <http://www.gnu.org/licenses/>.
*/
	// reset
	jQuery('.att-popup-reset').click(function(){
		if( confirm( "Are you sure? Resetting will loose all custom values!" ) ){
			return true;
	    } else {
	    	return false;
	    }
	});
	
	
//START: PLATESIZES	
jQuery(function(){
	//add a group
	jQuery( '#addgroup').bind( 'click' , function(){
			apprise('Please enter the group title', {'input':true,'animate':true},function( callback ){
				if( callback ){
					jQuery( '#form_action' ).val( 'add_group' );
					jQuery( '#form_groupname' ).val( callback );
					jQuery( '#form_formdata' ).submit();
				}
			});
	});
	//add a plate size

	//delete a group
	jQuery( '.delete_group_button').bind( 'click' , function( e ){
		elem = this;
		apprise('Are you sure to delete this group?', {'verify':true},function(callback){
			if( !callback )
				return false;
			jQuery( '#form_action' ).val( 'delete_group' );
			jQuery( '#form_groupid' ).val( jQuery( elem ).attr('data-groupid') );
			jQuery( '#form_formdata' ).submit();
		});
		
	});
	
	jQuery( '.add_new_plate').bind( 'click' , function(){
			jQuery( '#add_new_plate' + jQuery( this ).attr('data-groupid') ).append( pg_append_template.replace('{groupid}', jQuery( this ).attr('data-groupid')) );
	});
	
	jQuery( '.delete_plate_button').bind( 'click' , function( e ){
		elem = this;
		apprise('Are you sure to delete this size?', {'verify':true},function(callback){
			if( !callback )
				return false;
			jQuery( '#form_action' ).val( 'delete_plate' );
			jQuery( '#form_plateid' ).val( jQuery( elem ).attr('plateid') );
			jQuery( '#form_formdata' ).submit();
		});
		
	});

});

	function append_to_the_group(id) {
		jQuery('#pg_append_action'+id).val('append');		
		jQuery('#add_new_plate'+id).submit();
	}

//END: PLATESIZES

//START: TEXTSTYLES
jQuery(function(){
	//add a group
	jQuery( '#upload_button').bind( 'click' , function(){
		values = {
			text_name : jQuery( '#form_text_name' ).val(),
			form_text_ttf : jQuery( '#form_text_ttf' ).val(),
			form_maxtext : jQuery( '#form_maxtext' ).val(),
			form_multiplier : jQuery( '#form_multiplier' ).val(),
			form_xdist : jQuery( '#form_xdist' ).val(),
			form_font_vertical_dest : jQuery( '#form_font_vertical_dest' ).val(),
		}
		
		for( key in values )
			if( values[key] == '' ){
				apprise( 'Please fill out all the fields' );
				return false;
			}

		jQuery( '#form_action' ).val( 'add' );
		jQuery( '#form_formdata' ).submit();
	});
	jQuery( '.delete_style_button').bind( 'click' , function(){
		if( jQuery( this ).attr('data-id') != ''){
			jQuery( '#form_action' ).val( 'delete' );
			jQuery( '#form_text_id' ).val( jQuery( this ).attr('data-id') );
			jQuery( '#form_formdata' ).submit();
		}
	});	
	
	
});
//END: TEXTSTYLES	

//START: BADGES
jQuery(function(){
	//add a group

	//delete a group
	jQuery( '.delete_badge_button').bind( 'click' , function( e ){
		elem = this;
		apprise('Are you sure to delete the badge #' + jQuery( elem ).attr('badge_id') + '?', {'verify':true},function(callback){
			if( !callback )
				return false;
			jQuery( '#badges_action' ).val( 'delete' );
			jQuery( '#badges_id' ).val( jQuery( elem ).attr('badge_id') );
			jQuery( '#form_badges' ).submit();
		});
		
	});
});
//END: BADGES