<?php
/**
 * Upsell page
 *
 * @package hoot
 * @subpackage framework
 * @since hoot 2.1.6
 */

/** Determine whether to load upsell subpage **/
$premium_features_file = trailingslashit( HOOT_THEMEDIR ) . 'admin/premium.php';
$hoot_load_upsell_subpage = apply_filters( 'hoot_load_upsell_subpage', file_exists( $premium_features_file ) );
if ( !$hoot_load_upsell_subpage )
	return;

/* Add the admin setup function to the 'admin_menu' hook. */
add_action( 'admin_menu', 'hoot_appearance_subpage' );

/**
 * Sets up the Appearance Subpage
 *
 * @since 4.1
 * @access public
 * @return void
 */
function hoot_appearance_subpage() {

	add_theme_page(
		__( 'Dispatch Premium', 'dispatch' ),	// Page Title
		__( 'Premium Options', 'dispatch' ),	// Menu Title
		'edit_theme_options',					// capability
		'dispatch-premium',						// menu-slug
		'hoot_theme_appearance_subpage'			// function name
		);

	add_action( 'admin_enqueue_scripts', 'hoot_admin_enqueue_upsell_styles' );

}

/**
 * Enqueue CSS
 *
 * @since 4.1
 * @access public
 * @return void
 */
function hoot_admin_enqueue_upsell_styles( $hook ) {
	if ( $hook == 'appearance_page_dispatch-premium' )
		wp_enqueue_style( 'hoot-admin-upsell', trailingslashit( HOOT_THEMEURI ) . 'admin/css/upsell.css', array(),  HOOT_VERSION );
}

/**
 * Display the Appearance Subpage
 *
 * @since 4.1
 * @access public
 * @return void
 */
function hoot_theme_appearance_subpage() {
	/** Load Premium Features Data **/
	include( trailingslashit( HOOT_THEMEDIR ) . 'admin/premium.php' );

	/** Display Premium Teasers **/
	$pcontent = '';
	$containeropen = $topdisplayed = false;
	$plink = ( !empty( $hoot_premium_cta_url ) ) ? $hoot_premium_cta_url : '';
	$pdemo = ( !empty( $hoot_premium_cta_demo ) ) ? $hoot_premium_cta_demo : '';
	$plinklabeltop = ( !empty( $hoot_premium_cta_labeltop ) ) ? $hoot_premium_cta_labeltop : __( 'Click here to know more', 'dispatch' );
	$plinklabelbottom = ( !empty( $hoot_premium_cta_labelbottom ) ) ? $hoot_premium_cta_labelbottom : __( 'Buy Now', 'dispatch' );
	$plinklabeldemo = ( !empty( $hoot_premium_cta_labeldemo ) ) ? $hoot_premium_cta_labeldemo : __( 'View Demo Site', 'dispatch' );

	$pcontent .= '<div id="hoot-upsell" class="wrap">';
		if ( !empty( $hoot_options_premium ) && is_array( $hoot_options_premium ) ):
			foreach ( $hoot_options_premium as $pkey => $pfeature ) :

				if ( !empty( $pfeature['type'] ) && $pfeature['type'] == 'top' ) {

					if ( !empty( $plink ) ) $pcontent .= '<p class="hoot-upsell-cta centered"><a class="button button-primary button-buy-premium" href="' . $plink . '" target="_blank">' . $plinklabeltop . '</a></p>';
					if ( !empty( $pdemo ) ) $pcontent .= '<p class="hoot-upsell-demo centered"><a class="button button-secondary button-view-demo" href="' . $pdemo . '" target="_blank">' . $plinklabeldemo . '</a></p>';
					$pcontent .= '<div class="hoot-upsell-sub">';
					$containeropen = $topdisplayed = true;

				} elseif ( !empty( $pfeature['type'] ) && $pfeature['type'] == 'bottom' ) {

					if ( !empty( $plink ) ) $pcontent .= '<p class="section-premium-info hoot-upsell-cta centered"><a class="button button-primary button-buy-premium" href="' . $plink . '" target="_blank">' . $plinklabelbottom . '</a></p>';
					if ( $containeropen ) $pcontent .= '</div>';
					$containeropen = false;

				} else {

					if ( !$topdisplayed ) {
						if ( !empty( $pfeature['name'] ) ) $pcontent .= '<h1 class="centered">' . $pfeature['name'] . '</h1>';
						if ( !empty( $pfeature['std'] ) ) $pcontent .= '<p class="hoot-upsell-intro centered">' . $pfeature['std'] . '</p>';
					} else {
						$pcontent .= '<div class="section-premium-info">';
							if ( !empty( $pfeature['desc'] ) ) :
								$pcontent .= '<div class="premium-info">';
									$pcontent .= '<div class="premium-info-text">';
										if ( !empty( $pfeature['name'] ) ) $pcontent .= '<h4 class="heading">' . $pfeature['name'] . '</h4>';
										$pcontent .= $pfeature['desc'];
									$pcontent .= '</div>';
									if ( !empty( $pfeature['img'] ) ) $pcontent .= '<div class="premium-info-img"><img src="' . esc_url( $pfeature['img'] ) . '" /></div>';
									$pcontent .= '<div class="clear"></div>';
								$pcontent .= '</div>';
							elseif ( !empty( $pfeature['name'] ) ) :
								$pcontent .= '<h4 class="heading">' . $pfeature['name'] . '</h4>';
							endif;
							if ( !empty( $pfeature['std'] ) ) $pcontent .= $pfeature['std'];
						$pcontent .= '</div>';
					}

				}

			endforeach;
			if ( $containeropen ) $pcontent .= '</div>';
		endif;
	$pcontent .= '</div>';

	echo $pcontent;
}

/**
 * Reorder subpage in the appearance menu.
 *
 * @since 4.1
 */
function hoot_appearance_subpage_reorder() {
	global $submenu;
	$menu_slug = 'dispatch-premium';
	$index = '';

	if ( !isset( $submenu['themes.php'] ) ) {
		// probably current user doesn't have this item in menu
		return;
	}

	foreach ( $submenu['themes.php'] as $key => $sm ) {
		if ( $sm[2] == $menu_slug ) {
			$index = $key;
			break;
		}
	}

	if ( ! empty( $index ) ) {
		//$item = $submenu['themes.php'][ $index ];
		//unset( $submenu['themes.php'][ $index ] );
		//array_splice( $submenu['themes.php'], 1, 0, array($item) );

		/* array_splice does not preserve numeric keys, so instead we do our own rearranging. */
		$smthemes = array();
		foreach ( $submenu['themes.php'] as $key => $sm ) {
			if ( $key != $index ) {
				$setkey = $key;
				for ( $i = $key; $i < 1000; $i++ ) { 
					if( !isset( $smthemes[$i] ) ) {
						$setkey = $i;
						break;
					}
				}
				$smthemes[ $setkey ] = $sm;
				if ( $sm[2] == 'themes.php' ) {
					$smthemes[ $setkey + 1 ] = $submenu['themes.php'][ $index ];
				}
			}
		}
		$submenu['themes.php'] = $smthemes;
	}

}
add_action( 'admin_menu', 'hoot_appearance_subpage_reorder', 9999 );