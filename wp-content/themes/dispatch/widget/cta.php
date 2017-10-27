<?php
$top_class = hoot_widget_border_class( $border, 0, 'topborder-');
$bottom_class = hoot_widget_border_class( $border, 1, 'bottomborder-');
?>

<div class="cta-widget-wrap <?php echo sanitize_html_class( $top_class ); ?>">
	<div class="cta-widget-box <?php echo sanitize_html_class( $bottom_class ); ?>">
		<div class="cta-widget">
			<?php if ( !empty( $headline ) ) { ?>
				<h3 class="cta-headine"><?php echo do_shortcode( esc_html( $headline ) ); ?></h3>
			<?php } ?>
			<?php if ( !empty( $description ) ) { ?>
				<div class="cta-description"><?php echo do_shortcode( wp_kses_post( wpautop( $description ) ) ); ?></div>
			<?php } ?>
			<?php if ( !empty( $url ) ) { ?>
				<a href="<?php echo esc_url( $url ); ?>" <?php hoot_attr( 'cta-widget-button', 'widget', 'button button-large border-box titlefont' ); ?>><?php echo esc_html( $button_text ); ?></a>
			<?php } ?>
		</div>
	</div>
</div>