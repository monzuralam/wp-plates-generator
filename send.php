<?php
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
	include_once '../../../wp-config.php';
	include_once '../../../wp-load.php';
	include_once '../../../wp-includes/wp-db.php'; 
	global $wpdb; 

	$options = array(
		'email' => 'no_mail'
	);
	
	$complete_message = __('Thank you for your order!', 'platesgenerator');
	
	if ($results = $wpdb->get_results("SELECT option_name, option_value FROM ".$wpdb->prefix."options WHERE option_name='your_mail' or option_name='platesgenerator_success_message'"))
		foreach ($results as $result) {	
			if( $result->option_name=='your_mail')
				$options['email'] = $result->option_value;
			else
				$complete_message = $result->option_value;
		}
		
	if ($options['email'] == 'no_mail' && $results = $wpdb->get_results("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='admin_email' LIMIT 0,1"))
		foreach ($results as $result) 
			$options['email'] = $result->option_value;
		
	$summary = 0;
	foreach( $_POST['cost'] as $key=>$value )
		if( is_numeric($value) )
			$summary += $value;
	unset(  $key, $value );
	
	if( $_POST['cost']['one_plate'] == 'half price')
		$summary /= 2;
	
	
	$message = __('Sending a message TO', 'platesgenerator').": {$options['email']}<br/>\n";
	$message .= __('CUSTOMER', 'platesgenerator').": {$_POST['customer']}( {$_POST['customer_mail']} )<br/>\n";
	
	if( $_POST['customer_address'] != '' )
		$message .= __('ADDRESS', 'platesgenerator').": {$_POST['customer_address']}<br/>\n";

	if( $_POST['customer_phone'] != '' )
		$message .= __('PHONE', 'platesgenerator').": {$_POST['customer_phone']}<br/>\n";
	
	$message .= __('Amount due', 'platesgenerator').": {$summary}<br/>\n";
	$message .= __('Requested features', 'platesgenerator').":<br/>\n";
	foreach( $_POST['cost'] as $key=> $value )
		$message .= "{$key} : {$value}<br/>\n";
	$message .= "<a href=\"{$_POST['img_url']}\">".__('Resulting image', 'platesgenerator')."</a>.";	
	
	
	$EOL = "\r\n"; // delimeter of strings
	$boundary     = "--".md5(uniqid(time()));  // any not defined string  
	$headers    = "MIME-Version: 1.0;$EOL";   
	$headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";  
	$headers   .= "From: ".get_settings('blogname');  
	  
	$multipart  = "--$boundary$EOL";   
	$multipart .= "Content-Type: text/html; charset=utf-8$EOL";   
	$multipart .= "Content-Transfer-Encoding: base64$EOL";   
	$multipart .= $EOL; // delimiter between the headers and the html body 
	$multipart .= chunk_split(base64_encode($message));
	$multipart .= "$EOL--$boundary--$EOL";
	if( !mail($options['email'], __('New plate order', 'platesgenerator'), $multipart, $headers) )
		echo __('Error!', 'platesgenerator');
	else
		echo $complete_message;
?>