/**
 * Theme Customizer
 */

// ( function( api ) {

// 	// Extends our custom "hoot-premium" section.
// 	api.sectionConstructor['hoot-premium'] = api.Section.extend( {

// 		// No events for this type of section.
// 		attachEvents: function () {},

// 		// Always make the section active.
// 		isContextuallyActive: function () {
// 			return true;
// 		}
// 	} );

// } )( wp.customize );


jQuery(document).ready(function($) {
	"use strict";


	/*** Premium USe ***/

	var $premiumhead = $('#accordion-section-premium > h3', 'body:not(.hoot-bcomp)');
	if ( $premiumhead.length ) {
		$premiumhead.prepend('<i class="fa fa-star"></i> ');
		$premiumhead.on( 'click', function(){
			$('body').addClass('hoot-display-premiumuse');
		});
		//$('#sub-accordion-section-premium .customize-section-back, #accordion-section-premium .customize-section-back').on('click', function(){
		$('.customize-section-back').on('click', function(){
			$('body').removeClass('hoot-display-premiumuse');
		});
	}


});
