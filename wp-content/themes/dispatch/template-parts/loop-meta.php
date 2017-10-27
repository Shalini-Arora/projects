<?php
/**
 * Template modification Hooks
 */
$display_loop_meta = apply_filters( 'hoot_default_loop_meta', true );
do_action ( 'hoot_default_loop_meta', 'start' );

if ( !$display_loop_meta )
	return;

/**
 * If viewing a multi post page 
 */
if ( !is_front_page() && !is_singular() && !hoot_is_404() ) :

	$display_title = apply_filters( 'loop_meta_display_title', true, 'plural' );
	if ( $display_title !== 'hide' ) :
	?>

		<div <?php hoot_attr( 'loop-meta-wrap', 'multi' ); ?>>
			<div class="grid">

				<div <?php hoot_attr( 'loop-meta', '', 'grid-span-12' ); ?>>

					<h1 <?php hoot_attr( 'loop-title' ); ?>><?php hoot_loop_title(); // Displays title for archive type (multi post) pages. ?></h1>

					<?php if ( $desc = hoot_get_loop_description() ) : ?>
						<div <?php hoot_attr( 'loop-description' ); ?>>
							<?php echo $desc; // Displays description for archive type (multi post) pages. ?>
						</div><!-- .loop-description -->
					<?php endif; // End paged check. ?>

				</div><!-- .loop-meta -->

			</div>
		</div>

	<?php
	endif;

/**
 * If viewing a single post/page (including frontpage not using Widgetized Template)
 */
elseif ( is_singular() && !hoot_is_404() ) :

	if ( have_posts() ) :

		// Begins the loop through found posts, and load the post data.
		while ( have_posts() ) : the_post();

			$display_title = apply_filters( 'loop_meta_display_title', '', 'singular' );
			if ( $display_title !== 'hide' ) :
			?>

				<div <?php hoot_attr( 'loop-meta-wrap', 'singular' ); ?>>
					<div class="grid">

						<div <?php hoot_attr( 'loop-meta', '', 'grid-span-12' ); ?>>
							<div class="entry-header">

								<?php
								global $post;
								$pretitle = ( !isset( $post->post_parent ) || empty( $post->post_parent ) ) ? '' : get_the_title( $post->post_parent ) . ' &raquo; ';
								$pretitle = apply_filters( 'loop_pretitle_singular', $pretitle );
								?>
								<h1 <?php hoot_attr( 'loop-title' ); ?>><?php the_title( $pretitle ); ?></h1>

								<?php $hide_meta_info = apply_filters( 'hoot_hide_meta_info', false, 'top' ); ?>
								<?php if ( !$hide_meta_info && 'top' == hoot_get_mod( 'post_meta_location' ) && !is_attachment() ) : ?>
									<div <?php hoot_attr( 'loop-description' ); ?>>
										<?php
										if ( is_page() )
											hoot_meta_info_blocks( hoot_get_mod('page_meta'), 'loop-meta' );
										else
											hoot_meta_info_blocks( hoot_get_mod('post_meta'), 'loop-meta' );
										?>
									</div><!-- .loop-description -->
								<?php endif; ?>

							</div><!-- .entry-header -->
						</div><!-- .loop-meta -->

					</div>
				</div>

			<?php
			endif;

		endwhile;
		rewind_posts();

	endif;

endif;

/**
 * Template modification Hooks
 */
do_action ( 'hoot_default_loop_meta', 'end' );