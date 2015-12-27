<?php
class ATT_Options_upload extends ATT_Options{

	/**
	 * Field Constructor.
	*/
	function __construct( $field = array(), $value ='', $parent = NULL ){
		if( is_object($parent) ) parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;		
	}

	
	/**
	 * Field Render Function.
	*/
	function render( $meta = false ){
		
		$class = ( isset($this->field['class']) ) ? $this->field['class'] : 'regular-text';
		$name = ( ! $meta ) ? ( $this->args['opt_name'].'['.$this->field['id'].']' ) : $this->field['id'];
		
		echo '<input type="hidden" name="'. $name .'" value="'.$this->value.'" class="'.$class.'" />';
		echo '<img class="att-opts-screenshot" src="'.$this->value.'" />';

		if($this->value == ''){$remove = ' style="display:none;"';$upload = '';}else{$remove = '';$upload = ' style="display:none;"';}
		echo ' <a href="javascript:void(0);" data-choose="Choose a File" data-update="Select File" class="att-opts-upload"'.$upload.' ><span></span>'.__('Browse', 'att-opts').'</a>';
		echo ' <a href="javascript:void(0);" class="att-opts-upload-remove"'.$remove.'>'.__('Remove Upload', 'att-opts').'</a>';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.$this->field['desc'].'</span>':'';
	}

    /**
     * Enqueue Function.
    */
    function enqueue() {
        $wp_version = floatval( get_bloginfo( 'version' ) );
//         print_r($wp_version);

        if ( $wp_version < "3.5" ) {
            wp_enqueue_script(
                'att-opts-field-upload-js', 
                ATT_Options_URI . 'fields/upload/field_upload_3_4.js', 
                array('jquery', 'thickbox', 'media-upload'),
                time(),
                true
            );
            wp_enqueue_style('thickbox');
        } else {
            wp_enqueue_script(
                'att-opts-field-upload-js', 
                ATT_Options_URI . 'fields/upload/field_upload.js', 
                array('jquery'),
                time(),
                true
            );
            wp_enqueue_media();
        }
        wp_localize_script('att-opts-field-upload-js', 'att_upload', array('url' => $this->url.'fields/upload/blank.png'));
    }
}
