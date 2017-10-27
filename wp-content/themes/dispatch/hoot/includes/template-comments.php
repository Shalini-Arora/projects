<?php
/**
 * Functions for handling how comments are displayed and used on the site. This allows more precise 
 * control over their display and makes more filter and action hooks available to developers to use in their 
 * customizations.
 *
 * @package hoot
 * @subpackage framework
 * @since hoot 1.0.0
 */

/**
 * Outputs the comment reply link.  Only use outside of `wp_list_comments()`.
 *
 * @since 1.0.0
 * @access public
 * @param array   $args
 * @return void
 */
function hoot_comment_reply_link( $args = array() ) {
	echo hoot_get_comment_reply_link( $args );
}

/**
 * Outputs the comment reply link.  Note that WP's `comment_reply_link()` doesn't work outside of 
 * `wp_list_comments()` without passing in the proper arguments (it isn't meant to).  This function is just a 
 * wrapper for `get_comment_reply_link()`, which adds in the arguments automatically.
 *
 * @since 1.0.0
 * @access public
 * @param array  $args
 * @return string
 */
function hoot_get_comment_reply_link( $args = array() ) {

	if ( !get_option( 'thread_comments' ) || in_array( get_comment_type(), array( 'pingback', 'trackback' ) ) )
		return '';

	$args = wp_parse_args(
		$args,
		array(
			'depth'     => intval( $GLOBALS['comment_depth'] ),
			'max_depth' => get_option( 'thread_comments_depth' ),
		)
	);

	return get_comment_reply_link( $args );
}

/**
 * Arguments for the wp_list_comments_function() used in comments.php. Users can set up a 
 * custom comments callback function by changing $callback to the custom function.  Note that 
 * $style should remain 'ol' since this is hardcoded into the theme and is the semantically correct
 * element to use for listing comments.
 *
 * @since 1.0.0
 * @access public
 * @param array  $args 
 * @return array
 */
function hoot_list_comments_args( $args = array() ) {

	/* Set the default arguments for listing comments. */
	$defaults = array(
		'style'        => 'ol',
		'type'         => 'all',
		'avatar_size'  => 80,
		'callback'     => 'hoot_comments_callback',
		'end-callback' => 'hoot_comments_end_callback'
	);

	/* Return the arguments and allow devs to overwrite them. */
	return apply_filters( 'hoot_list_comments_args', wp_parse_args( $args, $defaults ) );
}

/**
 * Uses the $comment_type to determine which comment template should be used. Once the 
 * template is located, it is loaded for use. Child themes can create custom templates based off
 * the $comment_type. The comment template hierarchy is comment-$comment_type.php, 
 * comment.php.
 *
 * The templates are saved in $hoot->comment_template[$comment_type], so each comment template
 * is only located once if it is needed. Following comments will use the saved template.
 *
 * @since 1.0.0
 * @access public
 * @param $comment The comment object.
 * @param $args    Array of arguments passed from wp_list_comments().
 * @param $depth   What level the particular comment is.
 * @return void
 */
function hoot_comments_callback( $comment, $args, $depth ) {
	global $hoot;

	/* Get the comment type of the current comment. */
	$comment_type = get_comment_type( $comment->comment_ID );

	/* Create an empty array if the comment template array is not set. */
	if ( !isset( $hoot->comment_template) || !is_array( $hoot->comment_template ) )
		$hoot->comment_template = array();

	/* Check if a template has been provided for the specific comment type.  If not, get the template. */
	if ( !isset( $hoot->comment_template[$comment_type] ) ) {

		/* Create an array of template files to look for. */
		$templates = array(
			"comment-{$comment_type}.php",
			"comment/{$comment_type}.php",
			"template-parts/comment-{$comment_type}.php",
		);

		/* If the comment type is a 'pingback' or 'trackback', allow the use of 'comment-ping.php'. */
		if ( 'pingback' == $comment_type || 'trackback' == $comment_type ) {
			$templates[] = 'comment-ping.php';
			$templates[] = 'comment/ping.php';
			$templates[] = 'template-parts/comment-ping.php';
		}

		/* Add the fallback 'comment.php' template. */
		$templates[] = 'comment/comment.php';
		$templates[] = 'comment.php';
		$templates[] = 'template-parts/comment.php';

		/* Allow devs to filter the template hierarchy. */
		$templates = apply_filters( 'hoot_comment_template_hierarchy', $templates, $comment_type );

		/* Locate the comment template. */
		$template = locate_template( $templates );

		/* Set the template in the comment template array. */
		$hoot->comment_template[ $comment_type ] = $template;
	}

	/* If a template was found, load the template. */
	if ( !empty( $hoot->comment_template[ $comment_type ] ) )
		require( $hoot->comment_template[ $comment_type ] );
}

/**
 * Ends the display of individual comments. Uses the callback parameter for wp_list_comments(). 
 * Needs to be used in conjunction with hoot_comments_callback(). Not needed but used just in 
 * case something is changed.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hoot_comments_end_callback() {
	echo '</li><!-- .comment -->';
}
