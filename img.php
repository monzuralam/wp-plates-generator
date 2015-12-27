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
error_reporting(0);
define( 'source_image_path' , 'plate/' );
define( 'default_text' , 'YOUR PLATE' );

include_once '../../../wp-config.php';
include_once '../../../wp-load.php';
include_once '../../../wp-includes/wp-db.php'; 

ob_start();
	_e('YOUR SLOGAN', 'platesgenerator');
	$default_slogan	= ob_get_contents();
ob_end_clean();

define( 'default_slogan' , $default_slogan );

if (isset($_GET['text']) && $_REQUEST['pg_nonce'] != AtatariPromo::getNonce()) exit;

class UPlate {
	public $genTime, $color,$image,$text = default_text,
		$multiplier = array(
			'standard' => 1.65,
			'fullsize' => 3
		),
		$badgeDist = 0, 
		$selectedMultiplier = 'standard',
		$yellow = '',
		$imageParams = array( 'w' => 520, 'h'=> 111 ),
		$fonts = array( 'name' => 'uknumberplate-webfont.ttf', 'maxtext' => 70, 'multiplier' => .77, 'xDist'=>.8, 'font_vertical_dest' => .86 ),
		$colors = array(
			'black' => array( 'r' =>0 , 'g'=>0 , 'b'=>0 ),
			'white' => array( 'r' =>255 , 'g'=>255 , 'b'=>255 ),
			'brass' => array( 'r' =>206 , 'g'=>172 , 'b'=>93 ),
			'grey' => array( 'r' =>189 , 'g'=>191 , 'b'=>192 ),
			'red' => array( 'r' =>239 , 'g'=>35 , 'b'=>28 ),
			'brown' => array( 'r' =>133 , 'g'=>39 , 'b'=>48 ),
			'darkblue' => array( 'r' =>34 , 'g'=>42 , 'b'=>119 ),
			'darkgreen' => array( 'r' =>50 , 'g'=>98 , 'b'=>66 ),
			'orange' => array( 'r' =>248 , 'g'=>159 , 'b'=>91 ),
			'violet' => array( 'r' =>148 , 'g'=>70 , 'b'=>126 ),
			'blue' => array( 'r' =>22 , 'g'=>153 , 'b'=>220 ),
			'green' => array( 'r' =>0 , 'g'=>138 , 'b'=>60 ),
			'yellow' => array( 'r' =>245 , 'g'=>235 , 'b'=>1 ),
			'pink' => array( 'r' =>218 , 'g'=>141 , 'b'=>159 ),
			'lightblue' => array( 'r' =>45 , 'g'=>178 , 'b'=>202 ),
			'lightgreen' => array( 'r' =>163 , 'g'=>195 , 'b'=>58 ),
			'none' => false
		),
		$badges = array(
			'gb' => 'gb.png',
			'uk' => 'uk.png',
			'eng1' => 'eng1.png',
			'eng2' => 'eng2.png',
			'nl' => 'nl.png',
			'sco1' => 'sco1.png',
			'sco2' => 'sco2.png',
			'cym1' => 'cym1.png',
			'cym2' => 'cym2.png',
			'greatbritain1' => 'greatbritain1.png',
			'greatbritain2' => 'greatbritain2.png',
			'unitedkingdom2' => 'unitedkingdom2.png',
			'unitedkingdom1' => 'unitedkingdom1.png',
			'england1' => 'england1.png',
			'england2' => 'england2.png',
			'scotland1' => 'scotland1.png',
			'scotland2' => 'scotland2.png',
			'ecosse1' => 'ecosse1.png',
			'ecosse2' => 'ecosse2.png',
			'wales1' => 'wales1.png',
			'wales2' => 'wales2.png',
			'cymru1' => 'cymru1.png',
			'cymru2' => 'cymru2.png',
			'ulster1' => 'ulster1.png',
			'ulster2' => 'ulster2.png',
			'nortenireland1' => 'nortenireland1.png',
			'nortenireland2' => 'nortenireland2.png',
			
			'f1_eng1' => 'f1_eng1.png',
			'f1_eng2' => 'f1_eng2.png',
			'f1_gb' => 'f1_gb.png',
			'f1_greatbritain1' => 'f1_greatbritain1.png',
			'f1_greatbritain2' => 'f1_greatbritain2.png',
			'f1_uk' => 'f1_uk.png',
			'f1_unitedkingdom1' => 'f1_unitedkingdom1.png',
			'f1_unitedkingdom2' => 'f1_unitedkingdom2.png',
			
			'f2_eng1' => 'f2_eng1.png',
			'f2_eng2' => 'f2_eng2.png',
			'f2_england2' => 'f2_england2.png',
			'f2_nl' => 'f2_nl.png',
			'f2_nortenireland1' => 'f2_nortenireland1.png',
			'f2_nortenireland2' => 'f2_nortenireland2.png',
			'f2_scotland1' => 'f2_scotland1.png',
			'f2_ulster1' => 'f2_ulster1.png',
			'f2_ulster2' => 'f2_ulster2.png',
			
			'f3_cymru2' => 'f3_cymru2.png',
			'f3_cymru1' => 'f3_cymru1.png',
			'f3_wales2' => 'f3_wales2.png',
			'f3_wales1' => 'f3_wales1.png',
			'f3_cym2' => 'f3_cym2.png',
			'f3_cym1' => 'f3_cym1.png',
			
			'f4_ecosse2' => 'f4_ecosse2.png',
			'f4_ecosse1' => 'f4_ecosse1.png',
			'f4_scotland2' => 'f4_scotland2.png',
			'f4_scotland1' => 'f4_scotland1.png',
			'f4_sco2' => 'f4_sco2.png',
			'f4_sco1' => 'f4_sco1.png'
			
		),
		$selected = 'standard',
		$textDrawed = false;
		
	public function __construct(){
		if( intval( $_GET['w'] ) >0 && intval( $_GET['h'] ) >0)
			$this->imageParams = array( 'w'=>intval( $_GET['w'] ) ,'h'=> intval( $_GET['h'] ) );
			
		if( $_GET['font_ttf'] != '' && $_GET['font_xdist'] != '' && $_GET['font_maxtext'] != '' && $_GET['font_multiplier'] != '' && $_GET['font_font_vertical_dest'] != '' )
			$this->fonts = array( 
				'name' => mysql_escape_string( $_GET['font_ttf'] ),
				'maxtext' => intval( $_GET['font_maxtext'] ),
				'multiplier' => floatval( $_GET['font_multiplier'] ),
				'xDist'=> floatval( $_GET['font_xdist'] ),
				'font_vertical_dest' => floatval( $_GET['font_font_vertical_dest'] )
			);
			
			define( 'max_text', $this->fonts['maxtext']);
			define( 'vert_fdest', $this->fonts['font_vertical_dest']);
			define( 'text_multiplier' , $this->fonts['multiplier'] );
			define( 'font_path' , dirname( __FILE__ ) . "/textstyles/{$this->fonts['name']}"  );
			define( 'x_distort' , $this->fonts['xDist'] );
	}
	public function selectPattern($pattern){
		$this->selectedMultiplier = ((is_null($_GET['multiplier']) || $_GET['multiplier']==''))?$this->selectedMultiplier:$_GET['multiplier'];
		$w = round( $this->imageParams['w'] * $this->multiplier[ $this->selectedMultiplier ] );
		$h = round( $this->imageParams['h'] * $this->multiplier[ $this->selectedMultiplier ] );
		
		$this->image = imagecreatetruecolor($w,$h);
		imagealphablending($this->image, false);
		imagesavealpha($this->image, true);
		
		$this->yellow = ($_GET['color']=='yellow')?'y':'';
		if(  $this->yellow == 'y')
			$this->color = imagecolorallocate( $this->image , 255, 239, 117);
		else
			$this->color = imagecolorallocate( $this->image , 255, 255, 255);
			
		imagefill($this->image, 0, 0, $this->color);
		$this->createPlate();
	}
	
	public function drawText( $text ){
		$this->text = $this->processText( $text );
		$textcolor = imagecolorallocate($this->image, 0, 0, 0);
		$fontsize = $this->getFontSize();
		
		$rows = array();
		if( $this->fewLines($this->text)){
			$rows[] = substr( $this->text, 0, round( strlen($this->text)/2 ));
			$rows[] = substr( $this->text, round( strlen($this->text)/2) );
		} else $rows[] = $this->text;
			
		foreach( $rows as $key=>$value ){
			$value = trim( $value );
			imagettftext( $this->image , $fontsize , 0 , $this->getCenterX( $value )*x_distort + 20*$this->badgeDist , $this->getCenterY()+$fontsize*vert_fdest*$key+10*$key , $textcolor , font_path, $value  );
		}
		unset( $key, $value );
		$this->textDrawed = true;
	}
	
	public function drawSlogan( $text ){
		$text = $this->processText( $text, true );
		if( $text == default_slogan )
			return false;
		if( strlen($text)*imagefontwidth(5) > imagesx($this->image) )
			$text = 'TOO LONG SLOGAN';
		$fw = imagefontwidth(5);     // width of a character
		$l = strlen($text);          // number of characters
		$tw = $l * $fw;              // text width
		$iw = imagesx($this->image); // image width
		
		
		$xpos = ($iw - $tw)/2;
		$ypos = imagesy($this->image) - 20;
		
		imagefilledrectangle($this->image, $xpos, $ypos, $xpos+$tw, $ypos+imagefontheight(5), $this->color);
		
		imagestring ($this->image, 5, $xpos, $ypos, $text, imagecolorallocate($this->image, 0, 0, 0));
	}
	
	public function showImage(){
		//imagestring( $this->image , 10 , 5,10,microtime(true)-$this->genTime . ' secs. generated',imagecolorallocate($this->image, 0, 0, 0));
		header('Content-Type: image/png');
		imagepng($this->image);
		imagedestroy($this->image);
	}
	
	public function saveImage(){
		$filename = 'pics/' . sha1( microtime(true) . rand(1,24) . $_SERVER['REMOTE_ADDR'] ) . '.png';
		imagepng($this->image,$filename);
		imagedestroy($this->image);
		echo $filename;
	}
	
	public function addBadge( $badge ){
		if( $this->textDrawed || !intval( $badge )>0 )
			return false;
		$this->badgeDist = 1.8;
		
		$color = imagecolorallocate( $this->image, 37 , 57 , 127 );
		
		$tempImg = imagecreatefrompng( "badge/$badge.png" );
		
		$h = round( $this->imageParams['h'] * $this->multiplier[ $this->selectedMultiplier ] );
		
		
		$ypos = round( ( $h - imagesy($tempImg)/2 )/2 );
		
		ImageRectangleWithRoundedCorners($this->image,14,15,80,$h - 13, 10 , $color, true );
		
		imagecopyresized($this->image,$tempImg,18,$ypos,0,0,50,116,imagesx($tempImg),imagesy($tempImg));
		imagedestroy( $tempImg );
		
	}
	
	public function fillBorder( $color = false, $mm = 2 ){
		$mm = (is_int($mm) && $mm > 2 && $mm< 6)?$mm:2;
		$wr = 5+ $mm*1.67;
		
		if( is_bool( $color ))
			return false;
		
		if( !is_array( $color ) )
			$color = $this->colors[ 'black' ];
		
		$w = round( $this->imageParams['w'] * $this->multiplier[ $this->selectedMultiplier ] );
		$h = round( $this->imageParams['h'] * $this->multiplier[ $this->selectedMultiplier ] );
		
		$img = imagecreatetruecolor($w, $h);
		$color = imagecolorallocate($img, $color['r'], $color['g'], $color['b']);
		$black = imagecolorallocate($img, 0, 0, 0);
		$transparent = imagecolorallocate($img, 21, 83, 64);
		imagefilledrectangle($img, 0, 0, $w, $h, $transparent);
		
		ImageRectangleWithRoundedCorners($img,5,5,$w-5,$h-5, 12 , $color );
		ImageRectangleWithRoundedCorners($img, $wr , $wr ,$w-$wr,$h-$wr, 12 , $transparent );
		imagecolortransparent( $img , $transparent );
		
		imagecopyresized($this->image,$img,0,0,0,0,imagesx($this->image),imagesy($this->image),imagesx($img),imagesy($img));
		imagedestroy( $img );
		
	}
	
	private function createPlate(){
		$pos = array('r','t','l','b');
		$anglePos = array(
			'rb' => array('x'=> imagesx($this->image)-20, 'y'=> imagesy($this->image)-21 ),
			'rt' => array('x'=> imagesx($this->image)-20, 'y'=> 0 ),
			'lb' => array('x'=> 0, 'y'=> imagesy($this->image)-21 ),
			'lt' => array('x'=> 0, 'y'=> 0 )
		);
		foreach( $pos as $key )
			$this->fillSide($key);
		$key=NULL;
		foreach( $anglePos as $key=>$value ){
			$tempImg = imagecreatefrompng( source_image_path . "{$this->yellow}{$key}.png" );
			imagecopy($this->image,$tempImg, $value['x'] , $value['y'] ,0,0,imagesx($tempImg),imagesy($tempImg));
			imagedestroy( $tempImg );
		}
		
		imagealphablending($this->image, true);
	}
	
	private function fillSide( $side ){
		switch($side){
			case 'l':
				$xSide = false;
				$sPos = 0;
			break;
			case 't':
				$xSide = true;
				$sPos = 0;
			break;
			case 'r':
				$xSide = false;
				$sPos = imagesx($this->image)-20;
			break;
			case 'b':
				$xSide = true;
				$sPos = imagesy($this->image)-20;
			break;
			default:
				$side = 'l';
				$xSide = true;
				$sPos = 0;
			break;
			
		}
		$sideImg = imagecreatefrompng( source_image_path . "{$this->yellow}{$side}.png");
		$steps = ($xSide)?imagesx($this->image)-20:imagesy($this->image)-20;
		for( $i = 20; $i<$steps; $i++ )
			if( $xSide )
				imagecopy($this->image,$sideImg, $i , $sPos ,0,0,imagesx($sideImg),imagesy($sideImg));
			else
				imagecopy($this->image,$sideImg, $sPos , $i ,0,0,imagesx($sideImg),imagesy($sideImg));
		imagedestroy( $sideImg );
	
	}
	
	public function mergeGlossy(){
		$image = imagecreatefrompng( source_image_path . "glossy.png");
		imagecopyresized($this->image,$image,13,13,0,0,imagesx($this->image)-25,imagesy($this->image)-50,imagesx($image),imagesy($image));
		imagedestroy( $image );
	}
	
	private function processText( $text, $dots = false ){
		$text = mb_strtoupper( $text );
		$pattern = $dots ? "/^[A-Z0-9\s\.]+$/" : "/^[A-Z0-9\s]+$/";
		if (!preg_match($pattern,$text))
			$text = default_text;
		return $text;
	}
	
	private function getCenterX( $text = -1 ){
		$text = ($text===-1)?$this->text:$text;
		$box = imagettfbbox($this->getFontSize(), 0, font_path, $text );
		return round(($this->imageParams['w'] * $this->multiplier[$this->selectedMultiplier] )/2-($box[2]-$box[0])/2);
	}
	
	private function getCenterY(){
		$indent = ($this->fewLines($this->text))?0:1;
		return round(($this->imageParams['h'] * $this->multiplier[$this->selectedMultiplier] )/2 + $this->getFontSize()*.5*$indent);
	}
	
	private function fewLines($text){
		$size = $this->imageParams['w']*$this->multiplier[$this->selectedMultiplier];
		$size /= ( strlen($text) * text_multiplier );
		$fontFromHeight = $this->imageParams['h']*$this->multiplier[$this->selectedMultiplier]/2/.86/1.4;
		
		return $fontFromHeight>$size;
	}
	
	private function getFontSize(){
		$maxtext = max_text*$this->multiplier[$this->selectedMultiplier];
		
		$size = $this->imageParams['w']*$this->multiplier[$this->selectedMultiplier];
		$size /= ( strlen($this->text) * text_multiplier );
		$size = round( $size );
		
		// .86 is a font height coefficient
		$fontFromHeight = round($this->imageParams['h']*$this->multiplier[$this->selectedMultiplier]/2/.86/1.4);
		$fontFromHeight = ($fontFromHeight<$maxtext)?$fontFromHeight:$maxtext;
		
		if( $fontFromHeight > $size ){
			$size = $this->imageParams['w']*$this->multiplier[$this->selectedMultiplier];
			$size /= ( strlen($this->text)/2 * text_multiplier );
			$size = round( $size );		
			$size = ($size > $fontFromHeight)?$fontFromHeight:$size;
		}
		
		$size = round( $size - 15*$this->badgeDist );
		
		
		return ($size>$maxtext)?$maxtext:$size;
	}
}

function ImageRectangleWithRoundedCorners(&$im, $x1, $y1, $x2, $y2, $r, $color, $leftrect = false ) {
			// draw to rectangles without corners
			imagefilledrectangle($im, $x1+$r, $y1, $x2-$r, $y2, $color);
			$tx = ($leftrect)?$x2-$r:$x2;
			imagefilledrectangle($im, $x1, $y1+$r, $tx, $y2-$r, $color);
			// draw circles in the corners
			imagefilledellipse($im, $x1+$r, $y1+$r, $r*2, $r*2, $color);
			imagefilledellipse($im, $x1+$r, $y2-$r, $r*2, $r*2, $color);
			
			if( !$leftrect ){
				imagefilledellipse($im, $x2-$r, $y1+$r, $r*2, $r*2, $color);
				imagefilledellipse($im, $x2-$r, $y2-$r, $r*2, $r*2, $color);
			}
			
}


$plate = new UPlate();
$plate->selectPattern( $_GET['type'] );
$plate->addBadge( $_GET['badge']  );
$plate->drawText( $_GET['text'] );
$plate->fillBorder( $plate->colors[ ($_GET[ 'borderthick' ]=='')?'none':$_GET[ 'border' ] ], intval( $_GET[ 'borderthick' ]) );
$plate->drawSlogan( $_GET['slogan'] );
$plate->mergeGlossy();
if( $_GET['act'] == 'save' )
	$plate->saveImage();
else
	$plate->showImage();
?>