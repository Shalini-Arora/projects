<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		wp_die( 'Permission not provided' );

}
 
	delete_option( WB_CHANGE_EMAIL_OPTION );
	delete_option( WB_ADVANCE_EMAIL_OPTION );
	delete_option( WB_SMTP_EMAIL_OPTION );
	delete_option( 'wb-sender-email-version' );
	delete_option( 'wb-sender-email-updater-id' );

?>
