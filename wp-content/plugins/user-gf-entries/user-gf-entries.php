<?php
/*
Plugin Name: User GF Entries
Description: A plugin to show GF entries for a user
Author: Impinge Solutions
Version: 0.1
*/

/*function this_plugin_last() {
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
        array_splice($active_plugins, $this_plugin_key, 1);
        array_push($active_plugins, $this_plugin);
        update_option('active_plugins', $active_plugins);
}
add_action("activated_plugin", "this_plugin_last");*/

/*
add_action( 'admin_menu', 'gfEntries_admin_menu' );

function gfEntries_admin_menu() {
    add_menu_page( 'My Top Level Menu Example', 'Your Forms', 'read', 'user-gf-entries', 'myplguin_admin_page', 'dashicons-tickets', 6  );
}
 */
function myplguin_admin_page(){

    //echo "<h1>Hello World!</h1>";
	//echo do_shortcode('[gravityview id=166]' , true);
	
	if ( shortcode_exists( 'gravityview' ) ) {
    	//echo do_shortcode('[gravityview id=166]');
	}
	else{
		//echo "shortcode gravityview does not exist";
	}

	/*?>
	<iframe src="http://isicsb.iotis.com/form-entries-page" width="100%" height="100%"></iframe>
	<?php*/

	?>
	<ul>
		<li><a href="<?php echo home_url().'/view/join-entries' ?>" target="_blank">Form Entries</a></li>
		<li><a href="<?php echo home_url().'/view/radio-view' ?>" target="_blank">Radio Form Entries</a></li>
		<!--<li><a href="/view/user-entries" target="_blank">After Registration Form Entries</a></li>-->
		<!--<li><a href="/view/register-gf-entries" target="_blank">Registration Form Entries</a></li>-->
	</ul>
	<?php
}
 
?>