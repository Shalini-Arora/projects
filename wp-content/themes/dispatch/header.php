<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?> class="no-js">

<head>
<?php
// Fire the wp_head action required for hooking in scripts, styles, and other <head> tags.

wp_head();

?>
</head>
<script>
jQuery( document ).ready(function() {
var getUrlParameter = function getUrlParameter(sParam) {
     var sPageURL = decodeURIComponent(window.location.search.substring(1)),
         sURLVariables = sPageURL.split('&'),
         sParameterName,
         i;
     for (i = 0; i < sURLVariables.length; i++) {
         sParameterName = sURLVariables[i].split('=');
         if (sParameterName[0] === sParam) {
             return sParameterName[1] === undefined ? true : 
sParameterName[1];
         }
     }
};
var parameter_value = getUrlParameter('area');
jQuery('body').addClass(parameter_value);
});
</script>
<body <?php hoot_attr( 'body' ); ?>>

	<div <?php hoot_attr( 'page-wrapper' ); ?>>

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php _e( 'Skip to content', 'dispatch' ); ?></a>
		</div><!-- .skip-link -->

		<?php
		// Template modification Hook
		do_action( 'hoot_template_site_start' );

		// Displays a friendly note to visitors using outdated browser (Internet Explorer 8 or less)
		hoot_update_browser();
		?>

		<?php get_template_part( 'template-parts/topbar' ); ?>

		<header <?php hoot_attr( 'header', '', 'contrast-typo' ); ?>>
			<div class="grid">
				<div class="table grid-span-12">
				<?php
					// Display Branding
					hoot_header_branding();

					// Display Menu
					hoot_header_aside();
					?>
				</div>
			</div>
		</header><!-- #header -->

		<div <?php hoot_attr( 'main' ); ?>>
			<?php
			// Template modification Hook
			do_action( 'hoot_template_main_wrapper_start' );