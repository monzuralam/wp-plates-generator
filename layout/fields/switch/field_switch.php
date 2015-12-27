<?php
class ATT_Options_switch extends ATT_Options{	
	
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
		
		$class = ( isset( $this->field['class']) ) ? 'class="'.$this->field['class'].'" ' : '';	
		$name = ( ! $meta ) ? ( $this->args['opt_name'].'['.$this->field['id'].']' ) : $this->field['id'];
		if( ! $this->value ) $this->value = 0; // fix for value "off = 0"
		
		echo '<fieldset class="buttonset">';	
			foreach($this->field['options'] as $k => $v){
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'. $name .'" '.$class.' value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo '<label for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">'.$v.'</label>';
			}
		echo '</fieldset>';
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'&nbsp;&nbsp;<span class="description btn-desc">'.$this->field['desc'].'</span>':'';
			
	}
	
	/**
	 * Enqueue Function.
	*/
	function enqueue(){		
		wp_enqueue_style('att-opts-jquery-ui-css');
		wp_enqueue_script(
			'att-opts-field-switch-js', 
			ATT_Options_URI.'fields/switch/field_switch.js', 
			array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'),
			time(),
			true
		);
	}
	
}
?>