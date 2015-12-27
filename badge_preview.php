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
error_reporting( 0 );
$nWidth = 26;
$nHeight = 61;

$filename = intval( $_GET['file'] );
if( !$filename>0 )
	die();
$im = imagecreatefrompng( "badge/{$filename}.png" );
$newImg = imagecreatetruecolor($nWidth, $nHeight);
imagealphablending($newImg, false);
imagesavealpha($newImg,true);
$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight,
					imagesx( $im ), imagesy( $im ));
header( 'Content-Type: image/png' );
imagepng( $newImg );
?>