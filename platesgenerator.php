<?php
/*
Plugin Name: Plates Generator for WordPress
Plugin URI: http://www.platesgenerator.com
Description: Auto plates generator with borders, badges and styles. Usage: {platesgenerator:content}
Version: 0.9.0
License: GPLv2 or later
Author: Anton Nikiforov
Author URI: http://www.atatari.com/
*/ 

define( 'PG_OPTIONS_KEY', 'platesgenerator_options' );
define( 'PG_ADMIN_URL', admin_url( 'admin.php?page=' . PG_OPTIONS_KEY ) );
define( 'PG_PLUGIN_FOLDER', substr( plugin_basename( __FILE__ ), 0, strpos( plugin_basename( __FILE__ ), '/' ) ));
define( 'PG_PLUGIN_BASE', ABSPATH.'wp-content/plugins/'.PG_PLUGIN_FOLDER.'/' );
define( 'PG_PLUGIN_URL', WP_PLUGIN_URL . '/' . PG_PLUGIN_FOLDER.'/' );
define( 'PG_DB_VERSION', '1.0.0' );  


function pg_action_init() {
	global $pg_options;
	include 'js/atatari.php'; 
	load_plugin_textdomain( 'platesgenerator', false, dirname(plugin_basename(__FILE__)).'/languages/' );
	
	$pg_options = array(
		'your_reg_caption' => __('your_reg_caption', 'platesgenerator'),
		'your_reg_description' => __('your_reg_description', 'platesgenerator'),
		'plate_size_caption' => __('plate_size_caption', 'platesgenerator'),
		'text_style_caption' => __('text_style_caption', 'platesgenerator'),
		'badge_caption' => __('badge_caption', 'platesgenerator'),
		'border_caption' => __('border_caption', 'platesgenerator'),
		'slogan_caption' => __('slogan_caption', 'platesgenerator'),
		'your_mail' => get_settings('admin_email')
	);		
}

function pg_sidebar( $save_button = false ) { 
	$output = '';
    ob_start();
?>
<div class="inner-sidebar">
<?php
    $output .= ob_get_contents();
    ob_end_clean();

$output .= '
  <div class="stuffbox">
    <h3>Help</h3>
    <div class="submitbox">
      <div class="inside">
        <p>After you finish configuring the plugin, add PlatesGenerator to your posts/pages with <em>{platesgenerator:content}</em> shortcode.</p>
        <p><b>Questions? Support? Custom work?</b><a href="mailto:support@atatari.com">support@atatari.com</a></p>
      </div>
    </div>
  </div>

</div>';
return $output;
 }
 

function pg_array_empty($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $value) {
            if (!pg_array_empty($value)) {
                return false;
            }
        }
    }
    elseif (!empty($mixed)) {
        return false;
    }
    return true;
}  

add_action('init', 'pg_action_init');

class UPlatesGenerator{
	private $wpdb,$content_key;
	public function __construct(){
		global $wpdb;
		$this->wpdb = &$wpdb;
		
		$this->content_key = '{platesgenerator:content}';
		
		add_filter( 'the_content', 'platesgenerator_the_content_filter', 20 );
		add_filter( 'wp_head' , 'UPlatesGenerator::add_platesgenerator_header' );
		add_action('admin_menu', 'UPlatesGenerator::add_admin_config');
		
	}
	
	public function install(){
		global $wpdb;
		$sql_query = array(
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plgen_badges` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  UNIQUE KEY `id` (`id`)
			);",
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plgen_platesizes` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `group` int(11) NOT NULL,
			  `name` varchar(200) NOT NULL,
			  `width` int(11) NOT NULL,
			  `height` int(11) NOT NULL,
			  UNIQUE KEY `id` (`id`)
			);",
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plgen_platesizes_groups` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(200) NOT NULL,
			  UNIQUE KEY `id` (`id`)
			);",
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plgen_prices` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `type` varchar(20) NOT NULL,
			  `elementid` int(11) NOT NULL,
			  `price` float NOT NULL,
			  UNIQUE KEY `id` (`id`)
			);",
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plgen_textstyles` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(200) NOT NULL,
			  `ttf` varchar(200) NOT NULL,
			  `xdist` float NOT NULL,
			  `maxtext` int(11) NOT NULL,
			  `multiplier` float NOT NULL,
			  `font_vertical_dest` float NOT NULL,
			  UNIQUE KEY `id` (`id`)
			);",
			"INSERT INTO `{$wpdb->prefix}plgen_platesizes_groups` (`id`, `name`) VALUES
				(1, 'Standard');",
			"INSERT INTO `{$wpdb->prefix}plgen_platesizes` (`id`, `group`, `name`, `width`, `height`) VALUES
				(1, 1, 'Standard plate(522x111)',522,111);",
			"INSERT INTO `{$wpdb->prefix}plgen_textstyles` (`id`, `name`, `ttf`, `xdist`, `maxtext`, `multiplier`, `font_vertical_dest`) VALUES
				(4, 'Standard', 'Standard.ttf', 0.8, 70, 0.77, 0.86),
				(2, 'Army', 'Army.ttf', 1, 40, 0.8, 1.1),
				(3, 'Carbon', 'Carbon.ttf', 1, 60, 0.75, 0.86);",
			"INSERT INTO `{$wpdb->prefix}plgen_badges` (`id`) VALUES
					(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),
					(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),
					(21),(22),(23),(24),(25),(26),(27),(28),(29),(30),
					(31),(32),(33),(34),(35),(36),(37),(38),(39),(40),
					(41),(42),(43),(44),(45),(46),(47),(48),(49),(50),
					(51),(52),(53),(54),(55),(56);"
		);
				
		foreach( $sql_query as $query )
			$wpdb->query( $query );
		unset( $query );
	}
	
	public function uninstall(){
		//this function works without instance creating
		global $wpdb;
		$sql_query = array(
			"DROP TABLE {$wpdb->prefix}plgen_badges",
			"DROP TABLE {$wpdb->prefix}plgen_platesizes",
			"DROP TABLE {$wpdb->prefix}plgen_platesizes_groups",
			"DROP TABLE {$wpdb->prefix}plgen_prices",
			"DROP TABLE {$wpdb->prefix}plgen_textstyles"
		);
		foreach( $sql_query as $query )
			$wpdb->query( $query );
		unset( $query );
	}
	
	public function add_admin_config() {
		add_menu_page('Plates Generator', 'Plates Generator', 8, __FILE__, 'show_config');
		add_submenu_page(__FILE__, 'Plate Sizes', 'Sizes', 8, 'pg_platesizes', 'UPlatesGenerator::platesizes_config');
		add_submenu_page(__FILE__, 'Prices', 'Prices', 8, 'pg_prices', 'UPlatesGenerator::prices_config');
		add_submenu_page(__FILE__, 'Text Styles', 'Text Styles', 8, 'pg_textstyles', 'UPlatesGenerator::textstyles_config');
		add_submenu_page(__FILE__, 'Badges', 'Badges', 8, 'pg_badges', 'UPlatesGenerator::badges_config');
		add_submenu_page(__FILE__, 'Payment Settings', 'Payment Settings', 8, 'pg_buy_options', 'UPlatesGenerator::pg_payments_page');
		
		wp_enqueue_style( 'pg-options', PG_PLUGIN_URL.'layout/css/options.css');
		wp_enqueue_style( 'pg-aristo', PG_PLUGIN_URL.'layout/css/jquery-ui-aristo/aristo.css');
		wp_enqueue_style( 'pg-apprise', PG_PLUGIN_URL.'config_tmpls/css/apprise.min.css');

		wp_enqueue_script( 'pg-opts-js', PG_PLUGIN_URL.'/js/backend.js', array('jquery'));
		wp_enqueue_script( 'pg-apprise-js', PG_PLUGIN_URL.'/js/apprise-1.5.min.js', array( 'jquery' ) );
	    wp_enqueue_script( 'pg-switch-js', PG_PLUGIN_URL.'layout/fields/switch/field_switch.js', array( 'jquery' ) );
	    wp_enqueue_script('admin-widgets');
	    wp_enqueue_script('jquery-ui-button');

		
	}
	
	public function textstyles_config(){
		global $wpdb;
		switch ( $_POST['action'] ) {
			case 'add':
				if( $_POST['text_name'] != '' && $_FILES['text_ttf']['tmp_name'] != '' && $_FILES['text_ttf']['type'] == 'application/octet-stream' ){
					$uploaddir = dirname( __FILE__ ) . '/textstyles/';
					$filename = sha1( basename($_FILES['text_ttf']['name']) . rand() . time() ) . '.ttf';
					$uploadfile = $uploaddir . $filename;
					if( move_uploaded_file($_FILES['text_ttf']['tmp_name'], $uploadfile)){
						$wpdb->insert( "{$wpdb->prefix}plgen_textstyles", 
							array( 
								'name' => sanitize_text_field( $_POST['text_name'] ),
								'ttf' => $filename,
								'xdist' => sanitize_text_field( $_POST['xdist'] ),
								'maxtext' => sanitize_text_field( $_POST['maxtext'] ),
								'multiplier' => sanitize_text_field( $_POST['multiplier'] ),
								'font_vertical_dest' => sanitize_text_field( $_POST['font_vertical_dest'] )
							), 
							array( 
								'%s',
								'%s'
							) 
						);
					}					
				}
			break;
			case 'delete':
				if( intval( $_POST['text_id'] ) >0 ){
					$ttf_name = $wpdb->get_row("SELECT ttf FROM {$wpdb->prefix}plgen_textstyles WHERE id='". intval( $_POST['text_id'] ) .'\'', ARRAY_A);
					$ttf_name = $ttf_name['ttf'];
					if( unlink( dirname( __FILE__ ) . '/textstyles/' . $ttf_name ) )
						$wpdb->delete( "{$wpdb->prefix}plgen_textstyles", array( 'id' => intval( $_POST['text_id'] ) ) );
				}
			break;
		}
		
		$result = '<hr><div style="display:block; margin: 3px 3px 3px 3px; min-height:150px"><ul>';
		
		$textstyles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_textstyles", ARRAY_A);
		foreach( $textstyles as $textstyle ){
			$result .= "<li><a href='javascript:void(0);' style_id={$textstyle['id']} class='delete_style_button'><img src='%PATH%/platesgenerator/config_tmpls/img/delete.png'/></a> {$textstyle['name']}</li>";
			
		}
		$result .= '<ul></div><hr>';
		unset( $textstyle );
		
		/*$content_template = file_get_contents( plugins_url() . '/platesgenerator/config_tmpls/textstyles.php' );
		$content_template = str_replace( '%STYLES%', $result , $content_template );
		$content_template = str_replace( '%PATH%', plugins_url() , $content_template );*/
		
		echo UPlatesGenerator::textstyles_config_show();
		
	}
	
	public function textstyles_config_show() {
		global $wpdb;
		$output = '';
		
$title = 'Text Styles';

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
	$output .= '<form action="" method="post" enctype="multipart/form-data" id="form_formdata">';	

							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
			
						// sections -------------------------------------------------
						$output .= '<div class="att-sections">';
						
						$output .= '<div id="general-att-section" class="att-section" style="display: block;">
<h3>'.$title.'</h3>
<input type="hidden" id="form_text_id" name="text_id" />
<table class="form-table" style="background:#ccc;">
<tbody>';
						
						$output .= '<tr style="border:0px;">
	<th scope="row" colspan="2" style="font-size:1.2em;padding-left:10px;">
		Add New Text Style
	</th>
</tr>
<tr style="border:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Font File<span class="description">Here you can select a font file to upload.</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input type="file" name="text_ttf" accept="application/x-font-ttf" id="form_text_ttf" />
		<span class="description small-text">Only *.ttf files are supported</span>
	</td>
</tr>	
<tr style="border:0px;">
	<th style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Font Name<span class="description">Your frontend users will know the font being uploaded by this name</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input name="text_name" type="text" id="form_text_name" value="" class="small-text"/>
	</td>
</tr>	
<tr style="border:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Max Font Size <span class="description"><em>e.g. 10 - 100</em></span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input name="maxtext" type="text" id="form_maxtext" value="" class="small-text"/>
	</td>
</tr>	
<tr style="border:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Font Multiplier <span class="description"><em>e.g. 0.01 - 1</em> Use this setting to scale the fontsize</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input name="multiplier" type="text" id="form_multiplier" value="" class="small-text"/>
	</td>
</tr>
<tr style="border:0px;margin-bottom:-20px;margin-top:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Left offset <span class="description"><em>e.g. 0.01 - 2</em> Left offset of the print measured in the distance to the center of the plate</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input name="xdist" type="text" id="form_xdist" value="" class="small-text"/>
	</td>
</tr>
<tr style="border:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Line spacing <span class="description"><em>e.g. 0.01 - 2</em> Vertical spacing between lines of text measured in font size</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input name="font_vertical_dest" type="text" id="form_font_vertical_dest" value="" class="small-text"/>
	</td>
</tr>
<tr>
	<th>
	</th>
	<td>
		<input type="button" name="upload_button" id="upload_button" class="button-primary" value="Add Font Style" />
	</td>
</tr>
</tbody>
</table>
';
						
						$output .= '<table class="form-table">
<tbody>
<tr>
	<th scope="row">
		Available Text Styles
	</th>
	<td>';				
						
		$rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_textstyles", ARRAY_A);
		
if (count($rows) > 0) {			
$output .= '
<div class="widget-holder">
			<div class="sidebar-description">
				<p class="description">Groups and sizes listed here are available on Plate Sizes tab in the frontend.</p>
			</div>
			<div id="widget-list">';
$i = 1;
foreach( $rows as $row ){
	$output .= '
				<div id=\'widget-'.$i.'_archives-__i__\' class=\'widget\'>

<div class="widget-top">
	<div class="widget-title-action">
		<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
		<a class="widget-control-edit hide-if-js" href=""></a>
	</div>
	<div class="widget-title"><h4>'.$row['name'].'<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">

	<div class="widget-content">
	<ul style="margin-left:10px;">
	<li><strong>Font File:</strong> &nbsp; '.$row['ttf'].'</li>
	<li><strong>Font Name:</strong> &nbsp; '.$row['name'].'</li>
	<li><strong>Max Font Size:</strong> &nbsp; '.$row['maxtext'].'</li>
	<li><strong>Font Multiplier:</strong> &nbsp; '.$row['multiplier'].'</li>
	<li><strong>X distance:</strong> &nbsp; '.$row['xdist'].'</li>
	<li><strong>Vertical Font Destination:</strong> &nbsp; '.$row['font_vertical_dest'].'</li>
	';
	$output .= '</ul>
	</div>

	<div class="widget-control-actions">
		<div class="alignleft" style="margin-left:10px;margin-bottom:5px;">
		<a class="delete_style_button" href="#remove" data-id="'. $row['id'] .'">Delete Style</a> |
		<a class="widget-control-close" href="#close">Cancel</a>
		</div>
		<div class="alignright widget-control-noform">
		
			<span class="spinner"></span>
		</div>
		<br class="clear" />
	</div>

	</div>
	
	
</div>';
	$i++;
}

$output .= '</div></div>';
}
		
$output .= '	</td>
</tr>';			
		
$output .= '</tbody>
</table>';
						$output .= '</div>';
					$output .= '</div>';	
$output .= '</div>';

$output .= '
  <input type="hidden" id="form_action" name="action">
  <input type="hidden" id="form_groupname" name="groupname">
  <input type="hidden" id="form_groupid" name="groupid">
  <input type="hidden" id="form_plateid" name="plateid">
';
$output .= '</form>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

$output .= '<form id="form_formdata" action="" method="post">
  <input type="hidden" id="form_action" name="action">
  <input type="hidden" id="form_groupname" name="groupname">
  <input type="hidden" id="form_groupid" name="groupid">
  <input type="hidden" id="form_plateid" name="plateid">
</form>';
return $output;		
	}
	
	public function platesizes_config(){
		global $wpdb;
				
		switch ( $_POST['action'] ) {
			case 'add_group':
				if( $_POST['groupname'] != '' ){
					$wpdb->insert( "{$wpdb->prefix}plgen_platesizes_groups", 
						array( 
							'name' => sanitize_text_field( $_POST['groupname'] )
						), 
						array( 
							'%s'
						) 
					);
				}
			break;
			case 'delete_group':
				if( $_POST['groupid'] != '' ){
					$wpdb->delete( "{$wpdb->prefix}plgen_platesizes_groups", array( 'id' => sanitize_text_field( $_POST['groupid'] ) ) );
					$wpdb->query( "DELETE FROM {$wpdb->prefix}plgen_platesizes WHERE `group` = '".sanitize_text_field( $_POST['groupid'] )."'" );
				};
			break;
			case 'append':
				if( $_POST['add_plate_name'] != '' && intval($_POST['add_plate_width']) > 0 && intval( $_POST['add_plate_height'] ) > 0&& intval( $_POST['add_plate_group'] ) > 0 ){
					$wpdb->insert( "{$wpdb->prefix}plgen_platesizes", 
						array( 
							'group' => intval( $_POST['add_plate_group'] ),
							'name' => sanitize_text_field( $_POST['add_plate_name'] ),
							'width' => intval( $_POST['add_plate_width'] ),
							'height' => intval( $_POST['add_plate_height'] )
						), 
						array( 
							'%d',
							'%s',
							'%d',
							'%d'
						) 
					);
				}
			break;
			case 'delete_plate':
				if( $_POST['plateid'] != '' ){
					$wpdb->delete( "{$wpdb->prefix}plgen_platesizes", array( 'id' => sanitize_text_field( $_POST['plateid'] ) ) );
				}
			break;
		}

		echo UPlatesGenerator::platesizes_config_show();
	}
	
	public function platesizes_config_show(){
		global $wpdb;
				
	
		$output = '';
		
$title = 'Plate Sizes';
$output .= '
<script>
var pg_append_template = \'<table><tr><td>Size title:</td><td><input type="text" name="add_plate_name" size="3" class="small-text1"/></td></tr>\';
pg_append_template += \'<tr><td>Width, <em>px</em>: </td><td><input type="text" name="add_plate_width" size="3" class="small-text1"/></td></tr>\';
pg_append_template += \'<tr><td>Height, <em>px</em>: </td><td><input type="text" name="add_plate_height" size="3" class="small-text1"/></td></tr>\';
pg_append_template += \'<tr><td colspan="2"><a class="button-secondary" href="javascript:void(0);" onClick="append_to_the_group({groupid})">Append to the group</a>\';
pg_append_template += \'</td></tr></table>\';
</script>';

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
	//$output .= '<form method="post" action="" enctype="multipart/form-data" id="att-form-wrapper">';	
					//$output .= '<div id="att-main">';
						//$output .= '<div class="att-header">';
							//$output .= '<div class="att-buttons">';
							//$output .= '</div>';
							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
								//$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
						//$output .= '</div>';
			

			
						// sections -------------------------------------------------
						$output .= '<div class="att-sections">';
						
						$output .= '<div id="general-att-section" class="att-section" style="display: block;">
<h3>'.$title.'</h3>
<table class="form-table">
<tbody>';
						
						$output .= '<tr>
	<th scope="row">
		Size Groups<span class="description">You can organize plate sizes in groups.</span>
	</th>
	<td>
		<input type="button" name="addgroup" id="addgroup" class="button-primary" value="Add New Group" />
		<span class="description small-text">E.g. "Standard plate", "Motorcycle plate", etc.</span>
	</td>
</tr>';
						
						$output .= '<tr>
	<th scope="row">
		Available Groups
	</th>
	<td>';

		$plate_groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_platesizes_groups", ARRAY_A);
		
if (count($plate_groups) > 0) {			
$output .= '
<div class="widget-holder">
			<div class="sidebar-description">
				<p class="description">Groups and sizes listed here are available on Plate Sizes tab in the frontend.</p>
			</div>
			<div id="widget-list">';
$i = 1;
foreach( $plate_groups as $row ){
	$output .= '
				<div id=\'widget-'.$i.'_archives-__i__\' class=\'widget\'>

<div class="widget-top">
	<div class="widget-title-action">
		<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
		<a class="widget-control-edit hide-if-js" href=""></a>
	</div>
	<div class="widget-title"><h4>'.$row['name'].'<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">

	<div class="widget-content">
	<ul>';
	$platesizes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_platesizes WHERE `group`='{$row['id']}'", ARRAY_A);
			foreach( $platesizes as $platesize ) {
				$output .= '<li style="margin:10px 10px 10px 10px;">
				'."{$platesize['name']} : {$platesize['width']}x{$platesize['height']}".
				'<a href="javascript:void(0);" plateid="'. $platesize['id'] .'" class="delete_plate_button"><img src="'.PG_PLUGIN_URL.'/config_tmpls/img/delete.png"/></a>
				</li>';
			}
	$output .= '</ul>
	<form method="post" class="add_new_plate" id="add_new_plate'. $row['id'] .'">
	<input type="hidden" id="pg_append_action'. $row['id'] .'" name="action" value=""/>
	<input type="hidden" name="add_plate_group" value="'. $row['id'] .'"/>
	</form>
	</div>

	<div class="widget-control-actions">
		<div class="alignleft" style="margin-left:5px;margin-bottom:5px;">
		<a class="add_new_plate" href="#remove" name="addnew" data-groupid="'. $row['id'] .'">Add New Size</a> |
		<a class="delete_group_button" href="#remove" data-groupid="'. $row['id'] .'">Delete Group</a> |
		<a class="widget-control-close" href="#close">Cancel</a>
		</div>
		<div class="alignright widget-control-noform">
		
			<span class="spinner"></span>
		</div>
		<br class="clear" />
	</div>

	</div>
	
	
</div>';
	$i++;
}

$output .= '</div></div>';
}
		
$output .= '	</td>
</tr>';			
		
$output .= '</tbody>
</table>';
						$output .= '</div>';
					$output .= '</div>';	
$output .= '</div>';



$output .= '<form id="form_formdata" action="" method="post">
  <input type="hidden" id="form_action" name="action">
  <input type="hidden" id="form_groupname" name="groupname">
  <input type="hidden" id="form_groupid" name="groupid">
  <input type="hidden" id="form_plateid" name="plateid">
</form>';

$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
		
		return $output;
		
		
	}

	
	public function prices_config(){
		global $wpdb;
		$price = array(
			'badge' => '1',
			'border' => '1',
			'slogan' => '1',
			'plates' => array(),
			'txstyles' => array()
		);
	
		if( $_POST['price_save_action'] === 'save'){
?>
    <div id="setting-error-settings_updated" class="updated settings-error">
      <p><strong>Settings saved.</strong></p>
    </div>
<?php 
			foreach( $_POST['items'] as $key ){
				if( is_numeric( $key['price'] ) ){
					if( $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\''.intval($key['sid']).'\' AND type=\''.sanitize_text_field($key['type']).'\''  ) == NULL)
						$wpdb->insert(
							$wpdb->prefix .'plgen_prices',
							array(
								'elementid' => intval( $key['sid'] ),
								'type' => sanitize_text_field( $key['type'] ),
								'price' => floatval( $key['price'] )
							),
							array(
								'%d',
								'%s',
								'%f'
							)
						);
					else
						$wpdb->update( 
							$wpdb->prefix .'plgen_prices', 
							array(
								'price' => floatval( $key['price'] )
							), 
							array(
								'elementid' => intval( $key['sid'] ),
								'type' => sanitize_text_field( $key['type'] )
							), 
							array(
								'%f'
							), 
							array( '%d', '%s' ) 
						);
				}
			}
		}
		
		$price['badge'] = $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\'0\' AND type=\'badge\'');
		$price['border'] = $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\'0\' AND type=\'border\'');
		$price['slogan'] = $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\'0\' AND type=\'slogan\'');
		
		$price['badge'] = ($price['badge']==NULL)?'1':$price['badge'];
		$price['border'] = ($price['border']==NULL)?'1':$price['border'];
		$price['slogan'] = ($price['slogan']==NULL)?'1':$price['slogan'];
		
		$temp_arr = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}plgen_platesizes", ARRAY_N );
		foreach( $temp_arr as $key )
			$price['plates'][] = array( 'name'=>$key[1], 'id'=>$key[0], 'price'=>0 ) ;
			
		$temp_arr = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}plgen_textstyles", ARRAY_N );
		foreach( $temp_arr as $key )
			$price['txstyles'][] = array( 'name'=>$key[1], 'id'=>$key[0], 'price'=>0 ) ;
		unset( $key );
		
		foreach( $price['txstyles'] as &$key ){
			$var_price = $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\''.$key['id'].'\' AND type=\'textstyle\''  );
			$key['price'] = ($var_price!=NULL)?$var_price:0;
			
		}
		unset( $key );
		
		foreach( $price['plates'] as &$key ){
			$var_price = $wpdb->get_var( 'SELECT price FROM '. $wpdb->prefix .'plgen_prices WHERE elementid=\''.$key['id'].'\' AND type=\'plate\''  );
			$key['price'] = ($var_price!=NULL)?$var_price:0;
			
		}
		unset( $key,$var_price );
		
		$counter = 3;
		$pg_money_format = str_replace('[CURRENCY_SYMBOL]', get_option('platesgenerator_currency_symbol'), get_option('platesgenerator_money_format'));
		$moneyFormatted = !(strpos($pg_money_format, '[VALUE]') === false);
		

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
	$output .= '<form action="" method="post" enctype="multipart/form-data" id="form_formdata">';
							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
								$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
echo $output;			
						// sections -------------------------------------------------
						echo '<div class="att-sections">';		
		
?>						
<div id="general-att-section" class="att-section" style="display: block;">
<h3>Prices</h3>
<table class="form-table">
<tbody>
<?php if ($moneyFormatted) {?>
<tr>
	<th scope="row" colspan="2">
		<span class="description">All prices listed on this page are denominated in <?php echo get_option('platesgenerator_currency_symbol');?></span>
	</th>
</tr>
<?php } ?>
<tr>
	<th scope="row">
		Badge
	</th>
	<td>
		<?php echo '<input type="text" name="items[0][price]" value="'.$price['badge'].'" class="small-text" />';?>
		<?php //echo ($moneyFormatted) ? str_replace('[VALUE]', $input, $pg_money_format) : $input;?>
		<span class="description small-text">Price for printing a badge</span>
		<input type="hidden" name="items[0][sid]" value="0" />
		<input type="hidden" name="items[0][type]" value="badge" />
	</td>
</tr>
<tr>
	<th scope="row">
		Border
	</th>
	<td>
		<input type="text" name="items[1][price]" value="<?php echo $price['border'];?>" class="small-text" />
		<span class="description small-text">Price for border engraving</span>
		<input type="hidden" name="items[1][sid]" value="0" />
		<input type="hidden" name="items[1][type]" value="border" />
	</td>
</tr>
<tr>
	<th scope="row">
		Slogan
	</th>
	<td>
		<input type="text" name="items[2][price]" value="<?php echo $price['slogan'];?>" class="small-text" />
		<span class="description small-text">Price for printing a slogan</span>
		<input type="hidden" name="items[2][sid]" value="0" />
		<input type="hidden" name="items[2][type]" value="slogan" />
	</td>
</tr>
<tr>
	<th scope="row" colspan="2" style="font-size:1.2em;">
		Sizing Prices
	</th>
</tr>
<?php foreach( $price['plates'] as $key ){ ?>
<tr>
	<th scope="row">
		<?php echo $key['name'];?>
	</th>
	<td>
		<input type="text" name="items[<?php echo $counter;?>][price]" value="<?php echo $key['price'];?>" class="small-text" />
		<input type="hidden" name="items[<?php echo $counter;?>][type]" value="plate" />
		<input type="hidden" name="items[<?php echo $counter;?>][sid]" value="<?php echo $key['id'];?>" />
	</td>
</tr>
<?php $counter ++; ?>
<?php } ?>
<tr>
	<th scope="row" colspan="2" style="font-size:1.2em;">
		Text Styles
	</th>
</tr>
<?php foreach( $price['txstyles'] as $key ){ ?>
<tr>
	<th scope="row">
		<?php echo $key['name'];?>
	</th>
	<td>
		<input type="text" name="items[<?php echo $counter;?>][price]" value="<?php echo $key['price'];?>" class="small-text" />
		<input type="hidden" name="items[<?php echo $counter;?>][type]" value="textstyle" />
		<input type="hidden" name="items[<?php echo $counter;?>][sid]" value="<?php echo $key['id'];?>" />
	</td>
</tr>
<?php $counter ++; ?>
<?php } ?>
</tbody>
</table>
<?php
									echo '<div class="att-sections-footer">';
										echo '<input type="hidden" id="form_action" name="action" value="save_main_options" />';
										echo '<input type="submit" name="button" id="button" value="Save Changes" class="att-popup-save" />';
										echo '<input type="hidden" name="price_save_action" value="save" />';
									echo '</div>';

						echo '</div>';
					echo '</div>';	
echo '</div>';	

$output .= '</form>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';						
	
	}
	
	public function getParams() {
		global $wpdb;
		$results = $wpdb->get_results("SELECT name, ttf, xdist, maxtext, multiplier, font_vertical_dest, id FROM ".$wpdb->prefix."plgen_textstyles ORDER BY name DESC");
		
		$options = array(
			'platesgenerator_success_message' => 'Thank you for your order!',
			'platesgenerator_tel_adr' => false,
			'platesgenerator_currency_symbol' => '$',
			'platesgenerator_money_format' => '[CURRENCY_SYMBOL][VALUE]',
			'platesgenerator_textstyle' => $results[0]->name
		);	
		foreach( $options as $key=>$value ){
			$tmp_val = get_option($key);
			if( $tmp_val )
				$options[$key] = $tmp_val;
		}
		unset($tmp_val);
		return $options;
	}
	
	public function pg_payments_page(){
		$options = UPlatesGenerator::getParams();
		
		if( $_POST['buy_config_option']==='save' ){
?>
    <div id="setting-error-settings_updated" class="updated settings-error">
      <p><strong>Settings saved.</strong></p>
    </div>
<?php 
		
		$options['platesgenerator_success_message'] = sanitize_text_field( $_POST['platesgenerator_success_message'] );
		$options['platesgenerator_tel_adr'] = sanitize_text_field( $_POST['platesgenerator_tel_adr'] );
		$options['platesgenerator_currency_symbol'] = sanitize_text_field( $_POST['platesgenerator_currency_symbol'] );
		$options['platesgenerator_money_format'] = sanitize_text_field( $_POST['platesgenerator_money_format'] );
		foreach( $options as $key=>$value )
			update_option( $key, $value );
		} 
		unset($key,$value);
		
		if ($options['platesgenerator_tel_adr']==='checked') {
			$checked1 = 'checked="checked"';
			$checked2 = '';		
		} else {
			$checked1 = '';	
			$checked2 = 'checked="checked"';
		}
		
$title = 'Payment Settings';

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
	$output .= '<form action="" method="post" enctype="multipart/form-data" id="form_formdata">';	
							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
								$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
			
						// sections -------------------------------------------------
						$output .= '<div class="att-sections">';
						
						$output .= '<div id="general-att-section" class="att-section" style="display: block;">
<h3>'.$title.'</h3>
<input type="hidden" name="buy_config_option" value="save" />
<table class="form-table">
<tbody>';
						
						$output .= '<tr style="border:0px;">
	<th scope="row" colspan="2" style="">
		<span class="description">Currently, PlatesGenerator does not allow to accept money on your website, but it allows to receive orders via email.</span>
	</th>
</tr>
<tr>
	<th scope="row">
		Currency Symbol<span class="description"></span>
	</th>
	<td>
		<input type="text" name="platesgenerator_currency_symbol" value="'. $options['platesgenerator_currency_symbol'] .'" class="small-text" />
		<span class="description small-text">$, &euro;, &pound;, etc.</span>
	</td>
</tr>
<tr>
	<th scope="row">
		Money Format<span class="description"></span>
	</th>
	<td>
		<input type="text" name="platesgenerator_money_format" value="'. $options['platesgenerator_money_format'] .'" />
		<span class="description small-text">Supported variables: [CURRENCY_SYMBOL], [VALUE]</span>
	</td>
</tr>
<tr style="border:0px;">
	<th scope="row">
		Success message<span class="description">Shown on successful order submission.</span>
	</th>
	<td>
		<input name="platesgenerator_success_message" type="text" value="'. $options['platesgenerator_success_message'] .'"/>
	</td>
</tr>	
<tr style="border:0px;">
	<th scope="row">
	Request Phone Number and Address <span class="description"></span>
	</th>
	<td>';
$output .= '	
<fieldset class="buttonset">
	<input type="radio" id="responsive_0" name="platesgenerator_tel_adr"  value="checked" '.$checked1.'/>
	<label for="responsive_0">On</label>
	<input type="radio" id="responsive_1" name="platesgenerator_tel_adr"  value="" '.$checked2.'/>
	<label for="responsive_1">Off</label>
</fieldset>&nbsp;&nbsp;	
	<span class="description btn-desc">
		<b>Notice:</b> If you set this to "On", it will require customers to fill out the phone/address fields on the order form.
	</span>	
	</td>
</tr>	
</tbody>
</table>
';	
									$output .= '<div class="att-sections-footer">';
										$output .= '<input type="hidden" id="form_action" name="action" value="save_main_options" />';
										$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
									$output .= '</div>';							
						

						$output .= '</div>';
					$output .= '</div>';	

$output .= '</form>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';						

		echo $output;
		
	}

	
	public function badges_config(){
		echo '<h3>Add/Delete badges</h3>';		
		global $wpdb;
		switch ( $_POST['action'] ) {
			case 'add':
				if( $_FILES['image']['tmp_name'] != '' && $_FILES['image']['type'] == 'image/png' ){
					
					$img = imagecreatefrompng( $_FILES['image']['tmp_name'] );
					if( imagesy( $img ) != 232 || imagesx( $img ) != 100 )
						echo ( '<b style="color:red">size of the image is incorrect. Should be 100x232</b><br/>' );
					else{
						$wpdb->query( "INSERT INTO `{$wpdb->prefix}plgen_badges` (`id`) VALUES (NULL);");
						if( !is_int( $wpdb->insert_id ) )
							exit( 'db_err' );
						$uploaddir = dirname( __FILE__ ) . '/badge/';
						$filename = $wpdb->insert_id . '.png';
						$uploadfile = $uploaddir . $filename;
						move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
					}
				}
			break;
			case 'delete':
				if( intval( $_POST['badges_id'] ) >0 ){
					$image_id = intval( $_POST['badges_id'] );
					if( unlink( dirname( __FILE__ ) . "/badge/{$image_id}.png" ) ){
						$wpdb->delete( "{$wpdb->prefix}plgen_badges", array( 'id' => $image_id ) );
						echo 'Deleted';
					}
				}
			break;
		}
		
		
		$result = 'image size should be 100x232. <strong>Only PNG</strong><link rel="stylesheet" href="'.plugins_url().'/platesgenerator/config_tmpls/css/apprise.min.css"><script src="'.plugins_url().'/platesgenerator/config_tmpls/js/lib/jquery-1.10.1.min.js" language="javascript"></script><script src="'.plugins_url().'/platesgenerator/config_tmpls/js/badges.js" language="javascript"></script>
<script src="'.plugins_url().'/platesgenerator/config_tmpls/js/lib/apprise-1.5.min.js" language="javascript"></script><hr><div style="display:block; margin: 3px 3px 3px 3px; min-height:150px"><ul>';
		
		$badges = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_badges", ARRAY_A);
		foreach( $badges as $badges_b ){
			$result .= "<li style=\"float:left;margin-right:5px\"><a href='javascript:void(0);' badge_id='{$badges_b['id']}' class='delete_badge_button'><img src='".PG_PLUGIN_URL."/config_tmpls/img/delete.png'/></a><br/><img src='".PG_PLUGIN_URL."/badge_preview.php?file={$badges_b['id']}'/><br/>{$badges_b['id']}</li>";
			
		}
		$result .= '<ul></div><div style="clear:both"></div><hr>';
		
		$result .= "<form action='' method='post' enctype='multipart/form-data' id='form_badges'>
  <p>
    <input type='hidden' id='badges_action' name='action' value='add' />
    <input type='hidden' id='badges_id' name='badges_id'/>
	<input type=\"file\" name=\"image\" accept=\"application/png\" />
  <br /><input type=\"submit\"/>
	</form>";
		
		unset( $badges_b );
		
$title = 'Manage Badges';

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
	$output .= '<form action="" method="post" enctype="multipart/form-data" id="form_badges">';	
					//$output .= '<div id="att-main">';
						//$output .= '<div class="att-header">';
							//$output .= '<div class="att-buttons">';
							//$output .= '</div>';
							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
								$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
						//$output .= '</div>';
			

			
						// sections -------------------------------------------------
						$output .= '<div class="att-sections">';
						
						$output .= '<div id="general-att-section" class="att-section" style="display: block;">
<h3>'.$title.'</h3>
<input type="hidden" id="badges_action" name="action" value="add" />
<input type="hidden" id="badges_id" name="badges_id"/>
<table class="form-table" style="background:#ccc;">
<tbody>';
						
						$output .= '<tr style="border:0px;">
	<th scope="row" colspan="2" style="font-size:1.2em;padding-left:10px;">
		Upload New Badge
	</th>
</tr>
<tr style="border:0px;">
	<th scope="row" style="padding-top:2px;padding-bottom:2px;padding-left:10px;">
		Badge File<span class="description">Only PNG images are supported.</span>
	</th>
	<td style="padding-top:2px;padding-bottom:2px;">
		<input type="file" name="image" accept="application/png" />
		<span class="description small-text">Please make sure your *.png image size is 100x232</span>
	</td>
</tr>	
<tr>
	<th>
	</th>
	<td>
		<input type="submit" class="button-primary" value="Upload" />
	</td>
</tr>
</tbody>
</table>
</form>
';
						
						$output .= '<table class="form-table">
<tbody>
<tr>
	<th scope="row">
		Available Badges
	</th>
	<td><div style="display:block; margin: 3px 3px 3px 3px; min-height:150px"><ul>';				
						
		$rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}plgen_badges", ARRAY_A);
		
if (count($rows) > 0) {	
	
$i = 1;
foreach( $rows as $row ){
	$output .= "<li style=\"float:left;margin-right:5px\"><a href='javascript:void(0);' badge_id='{$row['id']}' class='delete_badge_button'><img src='".PG_PLUGIN_URL."/config_tmpls/img/delete.png'/></a><br/><img src='".PG_PLUGIN_URL."/badge_preview.php?file={$row['id']}'/><br/>{$row['id']}</li>";
	
	$i++;
}

}
		
$output .= '</ul></div></td>
</tr>';			
		
$output .= '</tbody>
</table></form>';
						$output .= '</div>';
					$output .= '</div>';	
$output .= '</div>';

$output .= '<form id="form_formdata" action="" method="post">
  <input type="hidden" id="form_action" name="action">
  <input type="hidden" id="form_groupname" name="groupname">
  <input type="hidden" id="form_groupid" name="groupid">
  <input type="hidden" id="form_plateid" name="plateid">
</form>';		
		
		echo $output;
		
	}
	
	public function add_platesgenerator_header( $header ){
	
	echo'<link rel="stylesheet" href="'.plugins_url().'/platesgenerator/css/style.css" type="text/css" media="all" />';
	echo'<link rel="stylesheet" href="'.plugins_url().'/platesgenerator/css/jquery.c2selectbox.css" type="text/css" media="all" />';
	echo'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery.ui.all.css" type="text/css" media="all" />';
	//echo'<script src="'.plugins_url().'/platesgenerator/js/jquery-1.8.3.min.js" type="text/javascript"></script>';
	echo'<script src="'.plugins_url().'/platesgenerator/js/modernizr.js" type="text/javascript"></script>';
	echo'<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';
	echo'<script src="'.plugins_url().'/platesgenerator/js/plategenerate.js" type="text/javascript"></script>';
	echo'<script src="'.plugins_url().'/platesgenerator/js/jquery.c2selectbox.js" type="text/javascript"></script>';
	echo'<script src="'.plugins_url().'/platesgenerator/js/functions.js" type="text/javascript"></script>';
	
	return $header;
	
		
	}
	
	public function content_filter( $content ){
		global $pg_options;
		$options = $pg_options;
		
		foreach( $options as $key=>$value ){
			$tmp_val = get_option($key);
			if( $tmp_val )
				$options[$key] = $tmp_val;
		}
		unset($key,$value,$tmp_val);
		//your_reg_caption your_reg_description plate_size_caption text_style_caption badge_caption border_caption slogan_caption
		
		$content_template = file_get_contents( plugins_url() . '/platesgenerator/tabs.php' );
		
		$platesgenerator_tel_adr = get_option('platesgenerator_tel_adr');
		if( !$platesgenerator_tel_adr || $platesgenerator_tel_adr =='' )
			$content_template = str_replace( "%address_phone_type%", 'hidden' , $content_template );
		else
			$content_template = str_replace( "%address_phone_type%", 'text' , $content_template );
		
		foreach( $options as $key=>$value ){
			$content_template = str_replace( "%{$key}%", $value , $content_template );
		}
		unset($key,$value);
		
		
		$content_template = str_replace( '%PATH%', plugins_url() , $content_template );
		
		return str_replace( $this->content_key ,$content_template, $content );
	}
}
$pg_plates = new UPlatesGenerator();


function platesgenerator_the_content_filter( $content ){
	global $pg_plates;
	return $pg_plates->content_filter( $content );
}

function show_config(){
	global $pg_options;
	$options = $pg_options;
		
	if($_POST['action']=='save_main_options' ){
?>
    <div id="setting-error-settings_updated" class="updated settings-error">
      <p><strong>Settings saved.</strong></p>
    </div>
<?php 	
		if (!isset($_POST['defaults'])) {
			$options = array(
				'your_reg_caption' => sanitize_text_field( $_POST['your_reg_caption'] ),
				'your_reg_description' => sanitize_text_field( str_replace("\r\n" , '<br/>', str_replace("\r\n" , "<br/>",  $_POST['your_reg_description'] ))),
				'plate_size_caption' => sanitize_text_field( $_POST['plate_size_caption'] ),
				'text_style_caption' => sanitize_text_field( $_POST['text_style_caption'] ),
				'badge_caption' => sanitize_text_field( $_POST['badge_caption'] ),
				'border_caption' => sanitize_text_field( $_POST['border_caption'] ),
				'slogan_caption' => sanitize_text_field( $_POST['slogan_caption'] ),
				'your_mail' => sanitize_text_field( $_POST['your_mail'] )
			);
		}
		foreach( $options as $key=>$value ) {
				update_option( $key, $value );
		}
	} else {
		foreach( $options as $key=>$value ){
			$tmp_val = get_option($key);
			if( $tmp_val )
				$options[$key] = $tmp_val;
		}
		unset($tmp_val);
	}
	unset($key,$value);
	
	if (pg_array_empty($options)) {
		$options = $pg_options;	
	}

	echo '<form method="post" action="" enctype="multipart/form-data" id="att-form-wrapper">';	

$output .= '
<div class="wrap">
    <div id="poststuff" class="metabox-holder has-right-sidebar">';
$output .= pg_sidebar();

$output .= '
      <div id="post-body">
        <div id="post-body-content">';


$output .= '<div id="att-wrapper">';
							$output .= '<div class="att-hgroup" style="background:#'.atatari_background().';">';
								$output .= '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
							$output .= '</div>';
							$output .= '<div class="att-strip"></div>';
			
echo $output;
			
						// sections -------------------------------------------------
						echo '<div class="att-sections">';
?>						
<div id="general-att-section" class="att-section" style="display: block;">
<h3>General Settings</h3>
<table class="form-table">
<tbody>
<tr>
	<th scope="row">
		Contact E-mail<span class="description">This e-mail address will be used for new order notifications.</span>
	</th>
	<td>
		<input type="text" name="your_mail" value="<?php echo $options['your_mail'];?>" class="small-text" />
		<span class="description small-text">Contact Form will appear only if this field will be filled correctly.</span>
	</td>
</tr>
<tr>
	<th scope="row">
		Your Reg Caption<span class="description">Appears on Your Reg tab above the input field</span>
	</th>
	<td>
		<input type="text" name="your_reg_caption" value="<?php echo $options['your_reg_caption'];?>" />
	</td>
</tr>
<tr>
	<th scope="row">
		Your Reg Description<span class="description">Appears on Your Reg tab below the input field.</span>
	</th>
	<td>
		<textarea name="your_reg_description" class="large-text" rows="6"><?php echo $options['your_reg_description'];?></textarea>
	</td>
</tr>
<tr>
	<th scope="row">
		Plate Size Caption<span class="description">Appears on Plate Size tab.</span>
	</th>
	<td>
		<input type="text" name="plate_size_caption" value="<?php echo $options['plate_size_caption'];?>" />
	</td>
</tr>
<tr>
	<th scope="row">
		Text Style Caption<span class="description">Appears on Text Style tab.</span>
	</th>
	<td>
		<input type="text" name="text_style_caption" value="<?php echo $options['text_style_caption'];?>" />
	</td>
</tr>
<tr>
	<th scope="row">
		Badge Caption<span class="description">Appears on Badge tab.</span>
	</th>
	<td>
		<input type="text" name="badge_caption" value="<?php echo $options['badge_caption'];?>" />
	</td>
</tr>
<tr>
	<th scope="row">
		Slogan Caption<span class="description">Appears on Slogan tab.</span>
	</th>
	<td>
		<input type="text" name="slogan_caption" value="<?php echo $options['slogan_caption'];?>" />
	</td>
</tr>
</tbody>
</table>
<?php
									echo '<div class="att-sections-footer">';
										echo '<input type="hidden" id="form_action" name="action" value="save_main_options" />';
										echo '<input type="submit" name="defaults" value="Reset to Defaults" class="att-popup-reset" />';
										echo '<input type="submit" name="submit" value="Save Changes" class="att-popup-save" />';
									echo '</div>';

						echo '</div>';
					echo '</div>';	
echo '</div>';	

$output = '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

$output .= '</form>';
echo $output;					
}

register_activation_hook( __FILE__, 'UPlatesGenerator::install');
register_deactivation_hook(__FILE__,'UPlatesGenerator::uninstall');


?>