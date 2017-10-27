<?php
/*
Plugin Name: Register Email
Description: Changes the copy in the email sent out to new users
*/
 
// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);
 
        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);
 
        $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "rnrn";
        $message .= sprintf(__('Username: %s'), $user_login) . "rnrn";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "rn";
 
        @wp_mail(get_option('admin_email'), sprintf(__('Iowa Statewide Interoperable Communciations System Board (ISICSB)'), get_option('blogname')), $message);
 
        if ( empty($plaintext_pass) )
            return;
 
        $message  = __('Hi ,') . "rnrn";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "rnrn";
        $message .= wp_login_url() . "rn";
        $message .= sprintf(__('Username: %s'), $user_login) . "rn";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "rnrn";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "rnrn";
        $message .= __('Adios!');
 
        wp_mail($user_email, sprintf(__('Iowa Statewide Interoperable Communciations System Board (ISICSB)'), get_option('blogname')), $message);
 
    }
}
 
?>