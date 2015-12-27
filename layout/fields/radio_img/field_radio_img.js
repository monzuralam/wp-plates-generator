/*
 *
 * ATT_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function att_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.att-radio-img-'+labelclass).removeClass('att-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('att-radio-img-selected');
}//function