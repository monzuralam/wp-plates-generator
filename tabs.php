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
$options = UPlatesGenerator::getParams();
?>
<script>
var pg_your_reg_here = '<?php _e('YOUR REG HERE', 'platesgenerator');?>';
var pg_your_slogan = '<?php _e('YOUR SLOGAN', 'platesgenerator');?>';
var pg_currency_symbol = '<?php echo $options['platesgenerator_currency_symbol'];?>';
var pg_money_format = '<?php echo $options['platesgenerator_money_format'];?>';
var pg_nonce = '<?php echo AtatariPromo::getNonce();?>';
</script>
<div id="platesGeneratorWrapper">
		<section class="main">
			<section class="tabs"><!-- /.tabs-title -->

			  <div class="tabs-navigation">
					<ul>
						<li style="margin-left: 0px;float:left;"><a href="#"><?php _e('YOUR REG', 'platesgenerator');?></a></li>
						<li style="margin-left: 0px;float:left;"><a href="#"><?php _e('PLATE SIZE', 'platesgenerator');?></a></li>
						<li style="margin-left: 0px;float:left;"><a href="#"><?php _e('TEXT STYLE', 'platesgenerator');?></a></li>
						<li style="margin-left: 0px;float:left;"><a href="#"><?php _e('BADGE', 'platesgenerator');?></a></li>
						<li style="margin-left: 0px;float:left;"><a href="#"><?php _e('BORDER', 'platesgenerator');?></a></li>
						<li class="active" style="margin-left: 0px;"><a href="#"><?php _e('SLOGAN', 'platesgenerator');?></a></li>
					</ul>
				</div>
				<!-- /.tabs-navigation -->

				<div class="tabs-content">
					<div class="tab">
						<h3>%your_reg_caption%</h3>
						<input type="text" id="reghere" class="big-field" value="<?php _e('YOUR REG HERE', 'platesgenerator');?>" title="<?php _e('YOUR REG HERE', 'platesgenerator');?>"/>

						<p>%your_reg_description%</p>
					</div>
					<!-- /.tab -->
					<div class="tab tab-select">
					  <h3>%plate_size_caption%</h3>

						<ul>
					 		<li>
					 			<ul>
					 				
                                        <?php
										$checkedd = 'checked="checked"';
										if ($results = $wpdb->get_results("SELECT name, id FROM ".$wpdb->prefix."plgen_platesizes_groups"))
												foreach ($results as $result) {	
													echo '<li>
					 					<div style="margin-bottom:10px;"><span id="std_plate" class="radio-custom plate-size-span">
										<input type="radio" value="standard_plate'. $result->id .'" name="plate_type" id="standard_plate5" '.$checkedd.' />
					 					</span>
					 					  <label for="standard_plate5">'.$result->name.'</label>
				 					  
					 					<div class="color-plate-select custom-select"  style="margin-top:0px;">
					 					  <select class="select2 choose_plate" name="standard_plate'. $result->id .'" id="standard_plate'. $result->id .'" style="margin-top:0px;">';
										  $checkedd = '';
													if( $subresults = $wpdb->get_results("SELECT name, width, height, id FROM ".$wpdb->prefix."plgen_platesizes WHERE `group` = '{$result->id}'") )
														foreach ($subresults as $subresult) 	
															echo '<option w="'.$subresult->width.'" plateid="'. $subresult->id .'" h="'.$subresult->height.'" value="'.$subresult->name.'">'.$subresult->name.'</option>' ;
													
													echo '</select></div>
													</div>
													</li>';
												
												}								
										
										
										?>
					 			</ul>
					 		</li>
					 	</ul>
					</div>
					<!-- /.tab -->
					<div class="tab tab-style">
						<h3>%text_style_caption%</h3>

					 			<ul>
                                   <?php
								   	$temp_checked = 'checked="checked"';
								   	if ($results = $wpdb->get_results("SELECT name, ttf, xdist, maxtext, multiplier, font_vertical_dest, id FROM ".$wpdb->prefix."plgen_textstyles"))
												foreach ($results as $result) {	
													echo '<li><div class="color-plate-select custom-select">
					 					<p class="radio">
					 						<span id="std_style" class="radio-custom"><input type="radio" value="'. $result->ttf .'" plateid="'. $result->id .'" xdist="'. $result->xdist .'" maxtext="'. $result->maxtext .'" multiplier="'. $result->multiplier .'" font_vertical_dest="'. $result->font_vertical_dest .'" name="fonttype" id="standard_plate4" '.($result->name == $options['platesgenerator_textstyle'] ? $temp_checked : '').' /></span>
                                            
					 						<label for="standard_plate4">'. $result->name .'</label></p></div>
					 				</li>';
												
												}?>
					 			</ul>
				  </div>
					<!-- /.tab -->
					<div class="tab">
						<h3>%badge_caption%</h3>
                        	<ul class="ico-plate">
                        <?php if ($results = $wpdb->get_results("SELECT id FROM ".$wpdb->prefix."plgen_badges"))
												foreach ($results as $result) {	
													echo '<li><span badge="'. $result->id .'" class="badgeclick"><img src="%PATH%/platesgenerator/badge_preview.php?file='. $result->id .'" /></span></li>';
													
												}?>
						</ul>
					</div>
					<!-- /.tab -->
					<div class="tab">
						<h3>%border_caption%</h3>

						<ul class="colors">
							<li><span color="none" class="color1 changecolor" style="margin-left: -30px;margin-right:3px;">color1</span></li>
							<li><span color="black" class="color2 changecolor">color2</span></li>
							<li><span color="white" class="color3 changecolor">color3</span></li>
							<li><span color="brass" class="color4 changecolor">color4</span></li>
							<li><span color="grey" class="color5 changecolor">color5</span></li>
							<li><span color="red" class="color6 changecolor">color6</span></li>
							<li><span color="brown" class="color7 changecolor">color7</span></li>
							<li><span color="darkblue" class="color8 changecolor">color8</span></li>
							<li><span color="darkgreen" class="color9 changecolor">color9</span></li>
							<li><span color="orange" class="color10 changecolor">color10</span></li>
							<li><span color="violet" class="color11 changecolor">color11</span></li>
							<li><span color="blue" class="color12 changecolor">color12</span></li>
							<li><span color="green" class="color13 changecolor">color13</span></li>
							<li><span color="yellow" class="color14 changecolor">color14</span></li>
							<li><span color="pink" class="color15 changecolor">color15</span></li>
							<li><span color="lightblue" class="color16 changecolor">color16</span></li>
							<li><span color="lightgreen" class="color17 changecolor">color17</span></li>
						</ul>

						<div class="color-plate-select custom-select" style="margin-top:0px;">
							<label><?php _e('Border Thickness', 'platesgenerator');?></label>
							<select class="select2 " name="border_thickness">
							  <option value="2">2mm</option>
							  <option value="3">3mm</option>
							  <option value="4">4mm</option>
							  <option value="5">5mm</option>
                            </select>
						</div>
						<!-- /.plate-radio -->
					</div>
					<!-- /.tab -->
					<div class="tab">
						<h3>%slogan_caption%</h3>
						<input name="sloganhere" type="text" class="big-field" id="sloganhere" title="<?php _e('YOUR SLOGAN', 'platesgenerator');?>" value="<?php _e('YOUR SLOGAN', 'platesgenerator');?>"/>
					</div>
					<!-- /.tab -->
				</div>
				<!-- /.tabs-content -->

				<div class="plate-holder" style="padding-top:10px">
					<div align="center"><img id="platepic" src="<?php echo PG_PLUGIN_URL.'img.php?pg_nonce='.AtatariPromo::getNonce();?>" />
						<span class="plate-light"></span>
					</div>
					<!-- /.plate -->
                    <div align="center"><img src="<?php echo PG_PLUGIN_URL.'img.php?color=yellow&pg_nonce='.AtatariPromo::getNonce();?>" name="platepicy" id="platepicy" />
						<span class="plate-light"></span>
					</div>
					<!-- /.plate -->
<input name="one_plate" type="checkbox" value="checked" /> <?php _e('I need only one plate', 'platesgenerator');?>
					<div class="plate-content"><!-- /.plate-radio -->
						
						<div class="plate-price">
						  <p><span><?php _e('Price', 'platesgenerator');?>:</span> <strong><span id="pricetotal"></span></strong></p>
					  </div>
						<!-- /.plate-price -->

						<div class="plate-buttons">
							<a href="javascript:void(0);" id="buynow" class="button-buy"><?php _e('Buy NOW', 'platesgenerator');?></a>
							<a href="javascript:void(0);" class="button" id="resetplace"><?php _e('reset plate', 'platesgenerator');?></a>
						</div>
						<!-- /.plate-buttons -->
					<!-- /.plate-content -->
				</div>
				</div>
				<!-- /.plate-holder -->
			</section>				
		</section><!-- /.main -->
        <div id="dialog-modal" style="<?='display:none'?>" title="<?php _e('Buy Now', 'platesgenerator');?>">
  <center><p><?php _e('All fields are required', 'platesgenerator');?></p>
  
  <p><?php _e('NAME', 'platesgenerator');?>:	<input type="text" id="pg_dialog_name" size="18" /></p>
  <p><?php _e('EMAIL', 'platesgenerator');?>:	<input type="text" id="pg_dialog_email" size="18" /></p>
  <span id="pg_dialog_additional_fields"><p><?php _e('ADDRESS', 'platesgenerator');?>:	<input type="%address_phone_type%" id="pg_dialog_address" size="18" /></p>
  <p><?php _e('PHONE', 'platesgenerator');?>:	<input type="%address_phone_type%" id="pg_dialog_phone" size="18" /></p></span>
  <hr />
  <div id="dialog-info"></div>
  <p><input name="" value="order" id="dialog-order-button" type="button" /></p></center>
</div>
</div>