// platesgenerate.js
// do not distribute without platesgenerator.php
/*
	This file is part of PlatesGenerator.

    PlatesGenerator is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PlatesGenerator is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PlatesGenerator.  If not, see <http://www.gnu.org/licenses/>.
*/
(function(){
	var site_path = '';
	var UPlate = function(){
		jQuery(function(){
			UPlate.prototype.setDefaults();
		});
	}
	
	UPlate.prototype.setDefaults = function(){
		site_path = '';
		var dump = jQuery('#platepic').attr('src').split('/');
		for( i=0;i<dump.length - 1;i++ )
		site_path += dump[i] + '/';
		
		UPlate.prototype.path = site_path + 'img.php';
		UPlate.prototype.site_path = site_path;
		UPlate.prototype.w = 522;
		UPlate.prototype.h = 111;
		
		UPlate.prototype.font = '';
		UPlate.prototype.ttf = '';
		UPlate.prototype.xdist = '';
		UPlate.prototype.maxtext = '';
		UPlate.prototype.multiplier = '';
		UPlate.prototype.font_vertical_dest = '';
		
		UPlate.prototype.borderColor = '';
		UPlate.prototype.borderThickness = '';
		UPlate.prototype.badge = '';
		UPlate.prototype.price = {};
		
		UPlate.prototype.total = {
			plate:0,
			textstyle:0,
			badge:0,
			border:0,
			slogan:0
		};
		
		jQuery('#reghere').val(pg_your_reg_here);
		jQuery('#sloganhere').val(pg_your_slogan);
		
		jQuery('#std_plate').addClass('checked');
		jQuery('#std_style').addClass('checked');
		jQuery('#std_p').addClass('checked');
		
		
		jQuery.ajax({
				type: "GET",
				url: site_path + "prices.php?pg_nonce=" + pg_nonce,
				dataType: 'json',
				success: UPlate.prototype.preparePrices
			});
		
		UPlate.prototype.updatePlate();
	}
	
	UPlate.prototype.getParams = function(){
		params = {
			text: jQuery('#reghere').val(),
			slogan: jQuery('#sloganhere').val(),
			w: UPlate.prototype.w,
			h: UPlate.prototype.h,
			font_ttf:UPlate.prototype.ttf,
			font_xdist:UPlate.prototype.xdist,
			font_maxtext:UPlate.prototype.maxtext,
			font_multiplier:UPlate.prototype.multiplier,
			font_font_vertical_dest:UPlate.prototype.font_vertical_dest,
			border: UPlate.prototype.borderColor,
			borderthick: UPlate.prototype.borderThickness,
			badge: UPlate.prototype.badge,
			pg_nonce: pg_nonce 
		};
		
		out = '?';
		andStr = '';
		for( key in params )
			if( typeof params[key]!= 'undefined' && params[key] != ''){
				out += andStr+key+'='+params[key];
				andStr = '&';
			}
		return out;
	}
	
	UPlate.prototype.updatePlate = function(){
		
		out = UPlate.prototype.getParams();
		jQuery('#platepic').attr('src',UPlate.prototype.path + out);
		jQuery('#platepicy').attr('src',UPlate.prototype.path + out + '&color=yellow');
		UPlate.prototype.updatePriceTotal();
	}
	UPlate.prototype.updatePriceTotal = function () {
		total = 0;
		for( key in UPlate.prototype.total )
			if( typeof UPlate.prototype.total[key]!= 'undefined' && UPlate.prototype.total[key] != '')
				total += Number(UPlate.prototype.total[key]);
		if( jQuery('input[name=one_plate]').attr('checked')=='checked' )
			total /= 2;
		var price = pg_money_format.replace('[CURRENCY_SYMBOL]', pg_currency_symbol);
		price = price.replace('[VALUE]', total);
		jQuery('#pricetotal').html( price );
		console.log( price );
	}
	UPlate.prototype.choosePlateType = function (){
		console.log( jQuery('select[name='+jQuery('input[name=plate_type]:checked').val()+']' ));
		UPlate.prototype.w = jQuery('select[name='+jQuery('input[name=plate_type]:checked').val()+'] option:selected' ).attr('w');
		UPlate.prototype.h = jQuery('select[name='+jQuery('input[name=plate_type]:checked').val()+'] option:selected' ).attr('h');
		try {
			UPlate.prototype.total.plate = (typeof UPlate.prototype.price.plates['pl_' + jQuery('select[name='+jQuery('input[name=plate_type]:checked').val()+'] option:selected' ).attr('plateid') ] != 'undefined')?UPlate.prototype.price.plates['pl_' + jQuery('select[name='+jQuery('input[name=plate_type]:checked').val()+'] option:selected' ).attr('plateid') ]:0;//(typeof UPlate.prototype.price.plates[UPlate.prototype.type] != 'undefined')?UPlate.prototype.price.plates[UPlate.prototype.type]:UPlate.prototype.price.plates['standard'];
		} catch(e) {
			UPlate.prototype.total.plate = 0;
		}
		UPlate.prototype.updatePlate();
	}
	UPlate.prototype.choosePlate = function (e){
		jQuery('input[value='+e.currentTarget.id+']').attr('checked', true);
		var tree = jQuery('input[value='+e.currentTarget.id+']').parents();
		jQuery('.plate-size-span').removeClass('checked');
    	jQuery(tree[0]).addClass( 'checked' );
		UPlate.prototype.choosePlateType();
	}
	UPlate.prototype.chooseFont = function (){
		UPlate.prototype.font = 'tl_' +jQuery( this ).attr( 'plateid' );
		
		UPlate.prototype.ttf = jQuery( this ).val();
		UPlate.prototype.xdist = jQuery( this ).attr('xdist');
		UPlate.prototype.maxtext = jQuery( this ).attr('maxtext');
		UPlate.prototype.multiplier = jQuery( this ).attr('multiplier');
		UPlate.prototype.font_vertical_dest = jQuery( this ).attr('font_vertical_dest');
		
		try{
			UPlate.prototype.total.textstyle = (UPlate.prototype.font=='')?UPlate.prototype.price.textstyles.uknumberplate:UPlate.prototype.price.textstyles[UPlate.prototype.font];
		} catch(e) {
			UPlate.prototype.total.textstyle = 0;
		}
		
		UPlate.prototype.updatePlate();
	}
	UPlate.prototype.chooseBorder = function (){
		UPlate.prototype.borderColor = ( typeof jQuery( this ).attr('color') != 'undefined')?jQuery( this ).attr('color'):UPlate.prototype.borderColor;
		
		if( UPlate.prototype.borderColor != 'none')
			UPlate.prototype.total.border = UPlate.prototype.price.border;
		else
			UPlate.prototype.total.border = 0;
		
		UPlate.prototype.borderThickness = jQuery( 'select[name=border_thickness]' ).val(); 
		UPlate.prototype.updatePlate();
	}
	UPlate.prototype.regKeyUp = function(){
		jQuery('#reghere').val(jQuery('#reghere').val().toUpperCase());
		UPlate.prototype.updatePlate();
	}
	UPlate.prototype.sloganKeyUp = function(){
		jQuery('#sloganhere').val(jQuery('#sloganhere').val().toUpperCase());
		
		if( jQuery('#sloganhere').val()!='YOUR SLOGAN...' && jQuery('#sloganhere').val()!='')
			UPlate.prototype.total.slogan = UPlate.prototype.price.slogan;
		else
			UPlate.prototype.total.slogan = 0;
			
		UPlate.prototype.updatePlate();
	}
	UPlate.prototype.preparePrices = function( prices ){
		UPlate.prototype.price = prices;		
		for (var i in prices.plates) {
			UPlate.prototype.total.plate = prices.plates[i];
			break;
		}		
		for (var i in prices.textstyles) {
			UPlate.prototype.total.textstyle = prices.textstyles[i];
			break;
		}
	
		UPlate.prototype.updatePriceTotal();
	}
	
	UPlate.prototype.openPopUp = function (){
		if( jQuery('#pg_dialog_address').attr('type')=='hidden' )
			jQuery( '#pg_dialog_additional_fields' ).css({
				display: 'none'
			});
		
		jQuery( "#dialog-modal" ).dialog({
			height: 250,
			width: 350,
			modal: true
		});
		
		jQuery( ".ui-button-icon-primary.ui-icon.ui-icon-closethick" ).css({
			margin: '-8px'
		});
		jQuery('.ui-dialog.ui-widget.ui-widget-content.ui-corner-all').css({
			'z-index':1000000
		})
		jQuery('#dialog-order-button').bind( 'click' , UPlate.prototype.orderNow )
		
		jQuery('.ui-dialog,.ui-widget-overlay.ui-front').css({
			'position':'fixed'
		});
		
	}
	
	UPlate.prototype.orderNow = function (){
		
		var checkmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))jQuery/;//"
		
		jQuery( '#dialog-info' ).html('');
		jQuery('#pg_dialog_email,#pg_dialog_name').attr( 'disabled',true )
		
		if( ! checkmail.test( jQuery('#pg_dialog_email').val() ) || jQuery('#pg_dialog_name').val()=='' || ( jQuery('#pg_dialog_address').val()=='' && jQuery('#pg_dialog_address').attr('type')=='text' )|| ( jQuery('#pg_dialog_phone').val()=='' && jQuery('#pg_dialog_phone').attr('type')=='text' ) ){
			jQuery( '#dialog-info' ).html( '<span style="color:red">type a correct all fields</span>' );
			jQuery('#pg_dialog_email,#pg_dialog_name').attr( 'disabled',false )
			return false;
		}
		
		
		img_url = UPlate.prototype.path + UPlate.prototype.getParams();// + '&act=save';
		url = UPlate.prototype.site_path + 'send.php';
		
		var cost_total = UPlate.prototype.total;
		
		if( jQuery('input[name=one_plate]').attr('checked')=='checked' )
			cost_total.one_plate = 'half price';
		
		jQuery.ajax({
			type: "POST",
			url: url,
			data: {
				img_url:img_url,
				cost: cost_total,
				customer: jQuery('#pg_dialog_name').val(),
				customer_mail: jQuery('#pg_dialog_email').val(),
				customer_address: (jQuery('#pg_dialog_address').attr( 'type' )=='text')?jQuery('#pg_dialog_address').val():'',
				customer_phone: (jQuery('#pg_dialog_phone').attr( 'type' )=='text')?jQuery('#pg_dialog_phone').val():''
			},
			success: function (html){
				jQuery( '#dialog-info' ).html( '<span style="color:green">' +html+ '</span>' );//console.log(html);
				jQuery('#dialog-order-button').unbind( 'click' ).val( 'OK' );
				jQuery('#dialog-order-button').bind( 'click', function(){ location.reload() });
			}
		});
	}
	
	UPlate.prototype.addBadge = function(){
		if( jQuery(this).attr('badge')==UPlate.prototype.badge ){
			UPlate.prototype.badge = '';
			UPlate.prototype.total.badge = 0;
		}else{
			UPlate.prototype.badge = jQuery(this).attr('badge');
			try{
			UPlate.prototype.total.badge = UPlate.prototype.price.badge;
			} catch(e) {
				UPlate.prototype.total.badge = 0;
			}
		}
		UPlate.prototype.updatePlate();
	}
	
	UPlate.prototype.bindAll = function(){
		jQuery(function() {
			jQuery('#reghere').bind('keyup', UPlate.prototype.regKeyUp);
			jQuery('#sloganhere').bind('keyup', UPlate.prototype.sloganKeyUp);
			jQuery('input[name=plate_type]').bind('click', UPlate.prototype.choosePlateType);
			jQuery('input[name=fonttype]').bind('click', UPlate.prototype.chooseFont);
			jQuery('.choose_plate').bind('change',UPlate.prototype.choosePlate);
			jQuery('.changecolor').bind('click',UPlate.prototype.chooseBorder);
			jQuery('select[name=border_thickness]').bind('change',UPlate.prototype.chooseBorder);
			jQuery('#resetplace').bind('click',UPlate.prototype.setDefaults);
			jQuery('#buynow').bind('click',function(){
				UPlate.prototype.openPopUp();
				UPlate.prototype.openPopUp();
			});
			jQuery('input[name=one_plate]').bind( 'click', function(){
				if( jQuery(this).attr('checked')=='checked')
					jQuery('#platepicy').css({
						display:'none'
					});
				else
					jQuery('#platepicy').css({
						display:'block'
					});
				UPlate.prototype.updatePlate();
			});
			
			//badges
			jQuery('.badgeclick').bind('click', UPlate.prototype.addBadge);		
		});
		
		
	}
	plate = new UPlate();
	plate.bindAll();
})();
