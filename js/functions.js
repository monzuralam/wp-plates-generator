// functions.js
// do not distribute without platesgenerator.php
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
jQuery(function() {
	jQuery(document).on('focusin', '.field, .field-welcome, .big-field, textarea', function() {
		if(this.title==this.value) {
			this.value = '';
		}
	}).on('focusout', '.field, .big-field, .field-welcome, textarea', function(){
		if(this.value=='') {
			this.value = this.title;
		}
	});

	if( jQuery('.custom-select').length ){
		jQuery('.custom-select select').c2Selectbox();
	}

	jQuery('.faq-list ul li a').on('click', function(){
		var tab_idx = jQuery(this).parents('li').index();
		if( jQuery(this).parents('li').find('.faq-answer').is(':hidden') ){
			jQuery('.faq-list ul li a').removeClass('expanded');
			jQuery(this).addClass('expanded');
			jQuery('.faq-answer').stop().slideUp(200);
			jQuery('.faq-answer:eq(' + tab_idx + ')').stop().slideDown(200);
		} else { 
			jQuery(this).parents('li').find('.faq-answer').stop().slideUp(200);
			jQuery('.faq-list ul li a').removeClass('expanded');
		}
		return false; 
	});

	if ( jQuery('.tabs').length ){
		init_tabs();
	}
	if ( jQuery('.welcome-tabs').length ){
		init_tabs();
	}
	if ( jQuery('.radio').length ){
		radio_btns()
	};

	if (jQuery( "#slider-range" ).length){
		var slider_vals = [ 10, 100 ]
		jQuery( "#slider-range" ).slider({
			range: true,
			min: 10,
			max: 100,
			values: slider_vals,
			create: function(event, ui){
				jQuery( "#slider-range" ).find('.ui-slider-handle').append('<span/>');
				jQuery( "#slider-range" ).find('.ui-slider-handle').first().find('span').text("jQuery" + slider_vals[ 0 ]);
				jQuery( "#slider-range" ).find('.ui-slider-handle').last().find('span').text("jQuery" + slider_vals[ 1 ]);
			},
			slide: function( event, ui ) {
				jQuery( "#slider-range" ).find('.ui-slider-handle').first().find('span').text("jQuery" + ui.values[ 0 ]);
				jQuery( "#slider-range" ).find('.ui-slider-handle').last().find('span').text("jQuery" + ui.values[ 1 ]);
			}
		});
	};

	if ( jQuery('.boxes').length ){
		jQuery('.boxes li:nth-child(3n)').addClass('last');
	};

	jQuery('.cart a').click( function (){
		jQuery(this).parent().find('.drop-down').slideToggle();
	})
});

function radio_btns(){
	jQuery('.radio input[type=radio]').change(function(){
		var name = jQuery(this).attr('name');
		jQuery('input[type=radio][name="'+name+'"]').each(function(){
			if( jQuery(this).is(':checked') ) {
				jQuery(this).parent().addClass('checked');
			}else {
				jQuery(this).parent().removeClass('checked');
			}	
		});
	});
	jQuery('.radio input[type=radio]').change();
}

function init_tabs() {

	jQuery('.tabs-navigation, .welcome-tabs-navigation').on('click', 'a', function(){
		var index = jQuery(this).parents('ul').find('a').index(this);
		show_tab(this, index);

		return false;
	});
	jQuery('.tabs-navigation').find('a').eq(1).trigger('click');	
	jQuery('.welcome-tabs-navigation').find('a').eq(0).trigger('click');
}

function show_tab(link, index) {
	var tabs = jQuery(link).parents('.tabs');
	var tabsSmall = jQuery(link).parents('.welcome-tabs');

	var tab = tabs.find('.tab');
	var tabSmall = tabsSmall.find('.welcome-tab');

	var tab_lis = tabs.find('.tabs-navigation').find('li');
	var tab_lisSmall = tabsSmall.find('.welcome-tabs-navigation').find('li');

	tab.hide();
	tabSmall.hide();

	tab.eq(index).fadeIn();
	tabSmall.eq(index).fadeIn();

	tab_lis.removeClass('active');
	tab_lisSmall.removeClass('active');

	tab_lis.eq(index).addClass('active');
	tab_lisSmall.eq(index).addClass('active');
}