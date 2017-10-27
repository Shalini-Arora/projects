<?php
/**
 *                  _   _             _   
 *  __      ___ __ | | | | ___   ___ | |_ 
 *  \ \ /\ / / '_ \| |_| |/ _ \ / _ \| __|
 *   \ V  V /| |_) |  _  | (_) | (_) | |_ 
 *    \_/\_/ | .__/|_| |_|\___/ \___/ \__|
 *           |_|                          
 * ------------------------------------------
 * ---- WP THEME BUILT ON HOOT FRAMEWORK ----
 * ------------------------------------------
 *
 * :: Theme's main functions file :::::::::::::::::::::::::::::::::::::::::::::
 * :: Initialize and setup the theme framework, helper functions and objects ::
 *
 * To modify this theme, its a good idea to create a child theme. This way you can easily update
 * the main theme without loosing your changes. To know more about how to create child themes 
 * @see http://codex.wordpress.org/Theme_Development
 * @see http://codex.wordpress.org/Child_Themes
 *
 * Hooks, Actions and Filters are used throughout this theme. You should be able to do most of your
 * customizations without touching the main code. For more information on hooks, actions, and filters
 * @see http://codex.wordpress.org/Plugin_API
 *
 * @package hoot
 * @subpackage dispatch
 * @since dispatch 1.0
 */

/**
 * Uncomment the line below to load unminified CSS and JS, and add other developer data to code.
 * - You can set this to true (default) for loading unminified files (useful for development/debugging)
 * - Or set it to false for loading minified files (for production i.e. live site)
 * 
 * NOTE: If you uncomment this line, HOOT_DEBUG value will override any option for minifying files (if
 * available) set via the theme options (customizer) in WordPress Admin
 */
// define( 'HOOT_DEBUG', true );

/* Get the template directory and make sure it has a trailing slash. */
$hoot_base_dir = trailingslashit( get_template_directory() );

/* Load the Core framework */
require_once( $hoot_base_dir . 'hoot/hoot.php' );

/* Load the Theme files */
require_once( $hoot_base_dir . 'hoot-theme/hoot-theme.php' );

include get_template_directory() . "/function-gravity.php";
/* Framework and Theme files loaded */
do_action( 'hoot_loaded' );

/* Launch the Core framework. */
$hoot = new Hoot();

/* Core Framework Setup complete */
do_action( 'hoot_after_setup' );

/* Launch the Theme */
$hoot_theme = new Hoot_Theme();

/* Hoot Theme Setup complete */
do_action( 'hoot_theme_after_setup' );


/**
* Better Pre-submission Confirmation
* http://gravitywiz.com/2012/08/04/better-pre-submission-confirmation/
* 21-6-17 custom for creating gravity form review before submit
*/
//$GLOBALS['registrationData_4'] = null;
/*


/*
class GWPreviewConfirmation {

    private static $lead;

    public static function init() {
        add_filter( 'gform_pre_render', array( __class__, 'replace_merge_tags' ) );
    }

    public static function replace_merge_tags( $form ) {
    	/*global $registrationData_4;

    	$search_criteria['field_filters'][] = array( 'key' => '32', 'value' => get_current_user_id() );
		$form_id = 4;
		$entries = GFAPI::get_entries( $form_id, $search_criteria );
    	$registrationData_4 = $entries;*/



/*

        $current_page = isset(GFFormDisplay::$submission[$form['id']]) ? GFFormDisplay::$submission[$form['id']]['page_number'] : 1;
        $fields = array();

        // get all HTML fields on the current page
        foreach($form['fields'] as &$field) {

            // skip all fields on the first page
            if(rgar($field, 'pageNumber') <= 1)
                continue;

            $default_value = rgar($field, 'defaultValue');
            preg_match_all('/{.+}/', $default_value, $matches, PREG_SET_ORDER);
            if(!empty($matches)) {
                // if default value needs to be replaced but is not on current page, wait until on the current page to replace it
                if(rgar($field, 'pageNumber') != $current_page) {
                    $field['defaultValue'] = '';
                } else {
                    $field['defaultValue'] = self::preview_replace_variables($default_value, $form);
                }
            }

            // only run 'content' filter for fields on the current page
            if(rgar($field, 'pageNumber') != $current_page)
                continue;

            $html_content = rgar($field, 'content');
            preg_match_all('/{.+}/', $html_content, $matches, PREG_SET_ORDER);
            if(!empty($matches)) {
                $field['content'] = self::preview_replace_variables($html_content, $form);
            }

        }

        return $form;
    }

    /**
    * Adds special support for file upload, post image and multi input merge tags.
    */
 /*   public static function preview_special_merge_tags($value, $input_id, $merge_tag, $field) {
        
        // added to prevent overriding :noadmin filter (and other filters that remove fields)
        if( ! $value )
            return $value;
        
        $input_type = RGFormsModel::get_input_type($field);
        
        $is_upload_field = in_array( $input_type, array('post_image', 'fileupload') );
        $is_multi_input = is_array( rgar($field, 'inputs') );
        $is_input = intval( $input_id ) != $input_id;
        
        if( !$is_upload_field && !$is_multi_input )
            return $value;

        // if is individual input of multi-input field, return just that input value
        if( $is_input )
            return $value;
            
        $form = RGFormsModel::get_form_meta($field['formId']);
        $lead = self::create_lead($form);
        $currency = GFCommon::get_currency();

        if(is_array(rgar($field, 'inputs'))) {
            $value = RGFormsModel::get_lead_field_value($lead, $field);
            return GFCommon::get_lead_field_display($field, $value, $currency);
        }

        switch($input_type) {
        case 'fileupload':
            $value = self::preview_image_value("input_{$field['id']}", $field, $form, $lead);
            $value = self::preview_image_display($field, $form, $value);
            break;
        default:
            $value = self::preview_image_value("input_{$field['id']}", $field, $form, $lead);
            $value = GFCommon::get_lead_field_display($field, $value, $currency);
            break;
        }

        return $value;
    }

    public static function preview_image_value($input_name, $field, $form, $lead) {

        $field_id = $field['id'];
        $file_info = RGFormsModel::get_temp_filename($form['id'], $input_name);
        $source = RGFormsModel::get_upload_url($form['id']) . "/tmp/" . $file_info["temp_filename"];

        if(!$file_info)
            return '';

        switch(RGFormsModel::get_input_type($field)){

            case "post_image":
                list(,$image_title, $image_caption, $image_description) = explode("|:|", $lead[$field['id']]);
                $value = !empty($source) ? $source . "|:|" . $image_title . "|:|" . $image_caption . "|:|" . $image_description : "";
                break;

            case "fileupload" :
                $value = $source;
                break;

        }

        return $value;
    }

 /*   public static function preview_image_display($field, $form, $value) {

        // need to get the tmp $file_info to retrieve real uploaded filename, otherwise will display ugly tmp name
        $input_name = "input_" . str_replace('.', '_', $field['id']);
        $file_info = RGFormsModel::get_temp_filename($form['id'], $input_name);

        $file_path = $value;
        if(!empty($file_path)){
            $file_path = esc_attr(str_replace(" ", "%20", $file_path));
            $value = "<a href='$file_path' target='_blank' title='" . __("Click to view", "gravityforms") . "'>" . $file_info['uploaded_filename'] . "</a>";
        }
        return $value;

    }

    /**
    * Retrieves $lead object from class if it has already been created; otherwise creates a new $lead object.
    */
 /*    public static function create_lead( $form ) {
        
        if( empty( self::$lead ) ) {
            self::$lead = GFFormsModel::create_lead( $form );
            self::clear_field_value_cache( $form );
        }
        
        return self::$lead;
    }

    public static function preview_replace_variables( $content, $form ) {

        $lead = self::create_lead($form);

        // add filter that will handle getting temporary URLs for file uploads and post image fields (removed below)
        // beware, the RGFormsModel::create_lead() function also triggers the gform_merge_tag_filter at some point and will
        // result in an infinite loop if not called first above
       add_filter('gform_merge_tag_filter', array('GWPreviewConfirmation', 'preview_special_merge_tags'), 10, 4);

        $content = GFCommon::replace_variables($content, $form, $lead, false, false, false);

        // remove filter so this function is not applied after preview functionality is complete
        remove_filter('gform_merge_tag_filter', array('GWPreviewConfirmation', 'preview_special_merge_tags'));

        return $content;
    }
   
    public static function clear_field_value_cache( $form ) {
        
        if( ! class_exists( 'GFCache' ) )
            return;
            
        foreach( $form['fields'] as &$field ) {
            if( GFFormsModel::get_input_type( $field ) == 'total' )
                GFCache::delete( 'GFFormsModel::get_lead_field_value__' . $field['id'] );
        }
    }
}
GWPreviewConfirmation::init();
*/

/*
*	redirect user to "after register" form page after login after filling "Registration" Form
*/
/*
function redirect2_after_registration_form( $user_login, $user ) {
	
	$user_meta = get_user_meta($user->ID);

	//echo "<pre>";print_r($user_meta);echo "</pre>";die;
	if(isset($user_meta['force-password-change']) && ( $user_meta['force-password-change'][0] == 1 || $user_meta['force-password-change'][0] == '1') )
	{

	}else{

		$search_criteria['field_filters'][] = array( 'key' => '53', 'value' => $user->user_email );
		$form_id = 5;
		$entries = GFAPI::get_entries( $form_id, $search_criteria );

		//echo "<pre>"; print_r($entries); echo "</pre>";die;
		if(count($entries) == 0){
			wp_redirect('/after-registration');
			exit;
		}
	}
} */
//add_action('wp_login', 'redirect2_after_registration_form', 10, 2);  // 27-6-17
//add_action('after_password_reset', 'redirect2_after_registration_form', 10, 2);


/*
*	To populate user_id field in "after register" form after user login (if they have not filled "after register" form already) 
*/
/*
add_filter( 'gform_field_value_user_id', 'dynamic_populate_user_id' );
function dynamic_populate_user_id( $value ) {

    return get_current_user_id();
}

*/

/*
*	To redirect user, if they have already filled "After Registration" Form
*/
/*add_filter( 'gform_pre_render_5', 'check_pre_filled' );
function check_pre_filled($form){

	$search_criteria['field_filters'][] = array( 'key' => '32', 'value' => get_current_user_id() );
	$form_id = 5;
	$entries = GFAPI::get_entries( $form_id, $search_criteria );

	//echo "<pre>"; print_r($entries); echo "</pre>";die;
	if(count($entries) >= 1){
		wp_redirect('/');
		exit;
	}
	else{
		return $form;
	}
}*/


/* 21-6-17 end */


/* 23-6-17 start */
/*
function my_login_logo_one() { 
?> 
<style type="text/css"> 
body.login div#login h1 a {
background-image: url(/wp-content/uploads/custom_logos/ISICS_Login_Logo.png);
background-size: cover;
width: 210px;
height: 205px;
} 
</style>
<?php 
} add_action( 'login_enqueue_scripts', 'my_login_logo_one' );


*/

// registration_agency_type  7 -> 42
/*
add_filter( 'gform_field_value_registration_agency_type', 'dynamic_populate_registration_agency_type' );
function dynamic_populate_registration_agency_type( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    //echo "<pre>"; print_r($entries);echo "</pre>";
    if(count($entries) >= 1)
        return $entries[0][7];
    return "";
}
*/
// registration_agency_discipline  56 -> 60 (review)
/*
add_filter( 'gform_field_value_registration_agency_discipline', 'dynamic_populate_registration_agency_discipline' );
function dynamic_populate_registration_agency_discipline( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^56.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(',', $filtred);
    }
    return "";
}
*/
// registration_agency_name  13 -> 44
/*
add_filter( 'gform_field_value_registration_agency_name', 'dynamic_populate_registration_agency_name' );
function dynamic_populate_registration_agency_name( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][13];
    return "";
}

// registration_address  12 -> 61
add_filter( 'gform_field_value_registration_address', 'dynamic_populate_registration_address' );
function dynamic_populate_registration_address( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^12.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(', ', $filtred);
    }

    return "";
}
// registration_agency_phone  14 -> 45
add_filter( 'gform_field_value_registration_agency_phone', 'dynamic_populate_registration_agency_phone' );
function dynamic_populate_registration_agency_phone( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][14];
    return "";
}
// registration_agency_website  16 -> 46
add_filter( 'gform_field_value_registration_agency_website', 'dynamic_populate_registration_agency_website' );
function dynamic_populate_registration_agency_website( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][16];
    return "";
}
// registration_agency_country  21 -> 47 (review)
add_filter( 'gform_field_value_registration_country', 'dynamic_populate_registration_country' );
function dynamic_populate_registration_country( $value ) {
    $countryCode = array("AR"=>"Adair ","AD"=>"Adams ","AL"=>"Allamakee","AP"=>"Appanoose","AU"=>"Audubon","BN"=>"Benton","BH"=>"Black Hawk","BO"=>"Boone","BR"=>"Bremer","BU"=>"Buchanan","BV"=>"Buena Vista","BT"=>"Butler","CN"=>"Calhoun","CA"=>"Carroll","CS"=>"Cass","CE"=>"Cedar","CG"=>"Cerro Gordo","CH"=>"Cherokee","CW"=>"Chickasaw","CK"=>"Clarke ","CY"=>"Clay ","CT"=>"Clayton","CL"=>"Clinton","CR"=>"Crawford","DA"=>"Dallas","DV"=>"Davis","DC"=>"Decatur","DE"=>"Delaware","DM"=>"Des Moines","DK"=>"Dickinson","DQ"=>"Dubuque","EM"=>"Emmet","FA"=>"Fayette","FL"=>"Floyd","FK"=>"Franklin","FR"=>"Fremont","GR"=>"Greene","GY"=>"Grundy","GU"=>"Guthrie ","HM"=>"Hamilton","HK"=>"Hancock","HR"=>"Hardin","HA"=>"Harrison","HE"=>"Henry","HW"=>"Howard","HU"=>"Humboldt","ID"=>"Ida","IA"=>"Iowa","JK"=>"Jackson","JA"=>"Jasper","JE"=>"Jefferson","JO"=>"Johnson","JN"=>"Jones","KK"=>"Keokuk","KO"=>"Kossuth","LE"=>"Lee","LI"=>"Linn","LO"=>"Louisa","LU"=>"Lucas","LY"=>"Lyon","MD"=>"Madison ","MA"=>"Mahaska","MR"=>"Marion","MH"=>"Marshall","ML"=>"Mills","MI"=>"Mitchell","MO"=>"Monona","MN"=>"Monroe ","MG"=>"Montgomery","MU"=>"Muscatine","OB"=>"O'Brien","OS"=>"Osceola","PG"=>"Page","PA"=>"Palo Alto","PL"=>"Plymouth","PO"=>"Pocahontas","PK"=>"Polk","PT"=>"Pottawattamie","PS"=>"Poweshiek","RG"=>"Ringgold","SA"=>"Sac","SC"=>"Scott","SH"=>"Shelby","SX"=>"Sioux","ST"=>"Story","TM"=>"Tama","TA"=>"Taylor ","UN"=>"Union ","VB"=>"Van Buren","WP"=>"Wapello","WN"=>"Warren","WA"=>"Washington","WY"=>"Wayne","WE"=>"Webster","WB"=>"Winnebago","WS"=>"Winneshiek","WD"=>"Woodbury","WO"=>"Worth","WR"=>"Wright");

    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $countryCode[$entries[0][21]];
    return "";
}
// register_country_abbreviation  65 -> 64
add_filter( 'gform_field_value_register_country_abbreviation', 'dynamic_populate_register_country_abbreviation' );
function dynamic_populate_register_country_abbreviation( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][65];
    return "";
}
// registration_country_number  66 -> 65
add_filter( 'gform_field_value_registration_country_number', 'dynamic_populate_registration_country_number' );
function dynamic_populate_registration_country_number( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][66];
    return "";
}
// registration_region  64 -> 48
add_filter( 'gform_field_value_registration_region', 'dynamic_populate_registration_region' );
function dynamic_populate_registration_region( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][64];
    return "";
}
// registration_agency_contact  1 -> 49 
add_filter( 'gform_field_value_registration_agency_contact', 'dynamic_populate_registration_agency_contact' );
function dynamic_populate_registration_agency_contact( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^1.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(' ', $filtred);
    }


    return "";
}
// registration_job_title  63 -> 50
add_filter( 'gform_field_value_registration_job_title', 'dynamic_populate_registration_job_title' );
function dynamic_populate_registration_job_title( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][63];
    return "";
}
// registration_phone  2 -> 51
add_filter( 'gform_field_value_registration_phone', 'dynamic_populate_registration_phone' );
function dynamic_populate_registration_phone( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][2];
    return "";
}
// registration_cell  15 -> 52
add_filter( 'gform_field_value_registration_cell', 'dynamic_populate_registration_cell' );
function dynamic_populate_registration_cell( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][15];
    return "";
}
// registration_is_same_as_above  61 -> 54
add_filter( 'gform_field_value_registration_is_same_as_above', 'dynamic_populate_registration_is_same_as_above' );
function dynamic_populate_registration_is_same_as_above( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][61];
    return "";
}
// registration_agency_contact_2  50 -> 55 
add_filter( 'gform_field_value_registration_agency_contact_2', 'dynamic_populate_registration_agency_contact_2' );
function dynamic_populate_registration_agency_contact_2( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^50.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(' ', $filtred);
    }
    return "";
}
// registration_job_title_2  8 -> 56
add_filter( 'gform_field_value_registration_job_title_2', 'dynamic_populate_registration_job_title_2' );
function dynamic_populate_registration_job_title_2( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][8];
    return "";
}
// registration_address_2  51 -> 62
add_filter( 'gform_field_value_registration_address_2', 'dynamic_populate_registration_address_2' );
function dynamic_populate_registration_address_2( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^51.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(', ', $filtred);
    }

    return "";
}
// registration_email_2  52 -> 57
add_filter( 'gform_field_value_registration_email_2', 'dynamic_populate_registration_email_2' );
function dynamic_populate_registration_email_2( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][52];
    return "";
}
// registration_phone_2  53 -> 58
add_filter( 'gform_field_value_registration_phone_2', 'dynamic_populate_registration_phone_2' );
function dynamic_populate_registration_phone_2( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '3', 'value' => $email );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][53];
    return "";
}

*/

/**
 * Gravity Forms Easy Form Population
 *
 * Populate form fields with values from a previous form entry.
 *
 * @version   1.0
 * @author    Travis Lopes <tl@travislop.es>
 * @license   GPL-2.0+
 * @link      http://travislop.es/
 */
/*class Gravity_Forms_Easy_Form_Population {
    
    public function __construct() {
        
        add_filter( 'gform_field_value', array( $this, 'populate_fields' ), 10, 3 );
        
    }
    
    public function populate_fields( $value, $field, $name ) {
        
        // If the entry ID parameter is not set, return the form. //
        if ( ! rgget( 'entry_id' ) ) {
            return $form;
        }
        
        // Get the entry and its form. //
        $original_entry = GFAPI::get_entry( rgget( 'entry_id' ) );
        $original_form  = GFAPI::get_form( $original_entry['form_id'] );
        $field_values   = array();
        
        // Loop through the original form's fields and push set values to field values array. //
        foreach ( $original_form['fields'] as $field ) {
            
            // If this field has multiple inputs, loop through them. //
            if ( $field->inputs ) {
                
                foreach ( $field->inputs as $input ) {
                    if ( $input['name'] ) {
                        $field_values[ $input['name'] ] = rgar( $original_entry, $input['id'] );
                    }
                }
                
            } else {
                
                if ( $field->inputName ) {
                    $field_values[ $field->inputName ] = rgar( $original_entry, $field->id );
                }
                
            }
            
        }
        
        // Return the field value. //
        return rgar( $field_values, $name ) ? rgar( $field_values, $name ) : $value;
        
    }
    
}
if ( class_exists( 'GFForms' ) ) {
    //new Gravity_Forms_Easy_Form_Population();
}*/


/* 23-6-17 end */


/* 27-6-17 start 
function my_profile_pass_update( $user_id ) {
    if ( ! isset( $_POST['pass1'] ) || '' == $_POST['pass1'] ) {
        return;
    }

    $user = get_user_by('ID', $user_id);

    $search_criteria['field_filters'][] = array( 'key' => '53', 'value' => $user->user_email );
	$form_id = 5;
	$entries = GFAPI::get_entries( $form_id, $search_criteria );

	//echo "<pre>"; print_r($entries); echo "</pre>";die;
	if(count($entries) == 0){

		$username = $user->user_email;
		$user = get_user_by('login', $username );

		// Redirect URL //
		if ( !is_wp_error( $user ) )
		{
		    wp_clear_auth_cookie();
		    wp_set_current_user ( $user->ID );
		    wp_set_auth_cookie  ( $user->ID );

		    wp_redirect( trailingslashit( home_url().'/after-registration' ) );
		    exit();
		}
	}
}
add_action( 'profile_update', 'my_profile_pass_update' );
*/
/*
function remove_dashboard_widgets () {

  /*remove_meta_box('dashboard_quick_press','dashboard','side'); //Quick Press widget
  remove_meta_box('dashboard_recent_drafts','dashboard','side'); //Recent Drafts
  remove_meta_box('dashboard_primary','dashboard','side'); //WordPress.com Blog
  remove_meta_box('dashboard_secondary','dashboard','side'); //Other WordPress News
  remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
  remove_meta_box('dashboard_plugins','dashboard','normal'); //Plugins
  remove_meta_box('dashboard_right_now','dashboard', 'normal'); //Right Now
  remove_meta_box('rg_forms_dashboard','dashboard','normal'); //Gravity Forms
  remove_meta_box('dashboard_recent_comments','dashboard','normal'); //Recent Comments
  remove_meta_box('icl_dashboard_widget','dashboard','normal'); //Multi Language Plugin
  remove_meta_box('dashboard_activity','dashboard', 'normal'); //Activity
  remove_action('welcome_panel','wp_welcome_panel');*/

  //remove_meta_box('oneandone_assistant_dashboard_widget','dashboard', 'normal'); //Activity
  

//}
//add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/* 27-6-17 end */


/* 3-7-17 start */
/*
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}


add_filter( 'gform_pre_submission_filter_4', 'populate_checkbox' );
function populate_checkbox( $form ) {
	 foreach( $form['fields'] as &$field )  {
	 	if($field->id == 56)
	 	{
	 		foreach ($field->choices as $key => $choice) {
	 			$field->choices[$key]['text'] = $field->choices[$key]['value'];
	 		}
	 	}
	 }

	 return $form;
}
*/
/* 3-7-17 end */

/* 19-7-17 start gravity form entries for users*/
//add_action('wp_dashboard_setup', 'users_gravity_form_entries_dashboard_widgets');
 /*
function users_gravity_form_entries_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Gravity Forms', 'after_register_dashboard_help');
}

function after_register_dashboard_help() {
//echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';

    //echo do_shortcode('[gravityview id="166"]');

    //echo '<p><a href="/view/user-entries" target="_blank">After Registration Form Entries</a></p>';

    


    /*$current_user = wp_get_current_user();
    $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => 5 );
    $form_id = 4;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        echo "<pre>"; print_r($entries);
    }else{
        echo "no entry found";
    }*/



//}
/* 19-7-17 end */


/* 20-7-17 start */
// Redirect to dashboard on login (instead of profile) 
/*
add_filter( 'login_redirect', 'app_login_redirect', 10, 3 );
function app_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } else {
            return admin_url( 'index.php' );
        }
    } else {
        return $redirect_to;
    }
}
/* 20-7-17 end */


/* 21-7-17 start */

// user profile update email
/*
function user_profile_update_email( $user_id ) {
    
    $site_url = get_bloginfo('wpurl');
    $site_email = get_bloginfo('admin_email');
    $user_info = get_userdata( $user_id );
    
    $to = $user_info->user_email;
    $subject = "Profile Updated: ".$site_url."";
    $message = "Hello " .$user_info->first_name.' '.$user_info->last_name . "\n\nYour profile has been updated!\n\nThank you for visiting\n ".$site_url."";
    
    $messageAdmin = "Hello Admin \n\n";
    $messageAdmin .= "User Profile has been Updated for ".$user_info->first_name.' '.$user_info->last_name."\n\n";

    wp_mail( $to, $subject, $message);
    wp_mail( $site_email, $subject, $messageAdmin);

}
add_action( 'profile_update', 'user_profile_update_email', 10, 2);



function wp_admin_bar_change_howdy( $wp_admin_bar ) {
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
    $profile_url = get_edit_profile_url( $user_id );

    if ( 0 != $user_id ) {
        /* Add the "My Account" menu */
 /*       $avatar = get_avatar( $user_id, 28 );
        $howdy = sprintf( __('Hello, %1$s'), $current_user->display_name );
        $class = empty( $avatar ) ? '' : 'with-avatar';

        $wp_admin_bar->add_menu( array(
                                    'id' => 'my-account',
                                    'parent' => 'top-secondary',
                                    'title' => $howdy . $avatar,
                                    'href' => $profile_url,
                                    'meta' => array(
                                        'class' => $class,
                                        ),
                                    ) 
                                );
    }
}
add_action( 'admin_bar_menu', 'wp_admin_bar_change_howdy', 11 );
*/




/* 21-7-17 end */