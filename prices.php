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

$result = array('plates' => array(), 'textstyles' => array(), 'badge' => 0, 'border' => 0, 'slogan' => 0);

    	if ($rows = $wpdb->get_results("SELECT elementid, price FROM ".$wpdb->prefix."plgen_prices WHERE type='plate'")){
			foreach ($rows as $row) {	
				$result['plates']["pl_{$row->elementid}"] = $row->price;
			}
		}
		
		if ($rows = $wpdb->get_results("SELECT elementid, price FROM ".$wpdb->prefix."plgen_prices WHERE type='textstyle'")){
			foreach ($rows as $row) {			
				$result['textstyles']["tl_{$row->elementid}"] = $row->price;
			}
		}
		
		if ($rows = $wpdb->get_results("SELECT price FROM ".$wpdb->prefix."plgen_prices WHERE type='badge' LIMIT 0,1"))
			$result['badge'] = $rows[0]->price;	

		if ($rows = $wpdb->get_results("SELECT price FROM ".$wpdb->prefix."plgen_prices WHERE type='border' LIMIT 0,1"))
			$result['border'] = $rows[0]->price;

		if ($rows = $wpdb->get_results("SELECT price FROM ".$wpdb->prefix."plgen_prices WHERE type='slogan' LIMIT 0,1"))
			$result['slogan'] = $rows[0]->price;	
			
		echo json_encode($result);
		exit(0);	
