<?php

class GWPreviewConfirmation1 {

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
    public static function preview_special_merge_tags($value, $input_id, $merge_tag, $field) {
        
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

    public static function preview_image_display($field, $form, $value) {

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
    public static function create_lead( $form ) {
        
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
        add_filter('gform_merge_tag_filter', array('GWPreviewConfirmation1', 'preview_special_merge_tags'), 10, 6);

        $content = GFCommon::replace_variables($content, $form, $lead, false, false, false);

        // remove filter so this function is not applied after preview functionality is complete
        remove_filter('gform_merge_tag_filter', array('GWPreviewConfirmation1', 'preview_special_merge_tags'));

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
GWPreviewConfirmation1::init();
/*
function redirect2_after_registration_form1( $user_login, $user ) {
	
	
	$user_meta = get_user_meta($user->ID);

	//echo "<pre>";print_r($user_meta);echo "</pre>";//die;
	if(isset($user_meta['force-password-change']) && ( $user_meta['force-password-change'][0] == 1 || $user_meta['force-password-change'][0] == '1') )
	{

	}else{
		
		$search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $user->user_email );
		$form_id = 6;
		$entries = GFAPI::get_entries( $form_id, $search_criteria );

		//echo "<pre>"; print_r($entries); echo "</pre>";//die;
		if(count($entries) == 0){
			wp_redirect( trailingslashit( home_url().'/wp-admin/index.php' ) );
			//wp_redirect('/join');
			exit;
		}
	}
}
add_action('wp_login', 'redirect2_after_registration_form1', 10, 2);  // 27-6-17
//add_action('after_password_reset', 'redirect2_after_registration_form1', 10, 2);
*/


/*	To populate user_id field in "after register" form after user login (if they have not filled "after register" form already) 
*/
add_filter( 'gform_field_value_user_id', 'dynamic_populate_user_id1' );
function dynamic_populate_user_id1( $value ) {

    return get_current_user_id();
}




// logo at admin
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


// registration_agency_type  7 -> 12
add_filter( 'gform_field_value_registration_agency_type', 'dynamic_populate_registration_agency_type1' );
function dynamic_populate_registration_agency_type1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
   // echo "<pre>"; print_r($entries);echo "</pre>";
    if(count($entries) >= 1)
        return $entries[0][16];
    return "";
}
// registration_agency_discipline  18 -> 13 (review)
add_filter( 'gform_field_value_registration_agency_discipline', 'dynamic_populate_registration_agency_discipline1' );
function dynamic_populate_registration_agency_discipline1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^18.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(',', $filtred);
    }
    return "";
}
// registration_agency_name  19 -> 15
add_filter( 'gform_field_value_registration_agency_name', 'dynamic_populate_registration_agency_name1' );
function dynamic_populate_registration_agency_name1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][19];
    return "";
}
// registration_address  20 -> 16
add_filter( 'gform_field_value_registration_address', 'dynamic_populate_registration_address1' );
function dynamic_populate_registration_address1( $value ) { 
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^20.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(', ', $filtred);
    }

    return "";
}
// registration_agency_phone  22 -> 17
add_filter( 'gform_field_value_registration_agency_phone', 'dynamic_populate_registration_agency_phone1' );
function dynamic_populate_registration_agency_phone1( $value ) { 
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][22];
    return "";
}
// registration_agency_website  23 -> 18
add_filter( 'gform_field_value_registration_agency_website', 'dynamic_populate_registration_agency_website1' );
function dynamic_populate_registration_agency_website1( $value ) { 
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][23];
    return "";
}
// registration_agency_country  24 -> 19 (review)
add_filter( 'gform_field_value_registration_country', 'dynamic_populate_registration_country1' );
function dynamic_populate_registration_country1( $value ) {
    $countryCode = array("AR"=>"Adair ","AD"=>"Adams ","AL"=>"Allamakee","AP"=>"Appanoose","AU"=>"Audubon","BN"=>"Benton","BH"=>"Black Hawk","BO"=>"Boone","BR"=>"Bremer","BU"=>"Buchanan","BV"=>"Buena Vista","BT"=>"Butler","CN"=>"Calhoun","CA"=>"Carroll","CS"=>"Cass","CE"=>"Cedar","CG"=>"Cerro Gordo","CH"=>"Cherokee","CW"=>"Chickasaw","CK"=>"Clarke ","CY"=>"Clay ","CT"=>"Clayton","CL"=>"Clinton","CR"=>"Crawford","DA"=>"Dallas","DV"=>"Davis","DC"=>"Decatur","DE"=>"Delaware","DM"=>"Des Moines","DK"=>"Dickinson","DQ"=>"Dubuque","EM"=>"Emmet","FA"=>"Fayette","FL"=>"Floyd","FK"=>"Franklin","FR"=>"Fremont","GR"=>"Greene","GY"=>"Grundy","GU"=>"Guthrie ","HM"=>"Hamilton","HK"=>"Hancock","HR"=>"Hardin","HA"=>"Harrison","HE"=>"Henry","HW"=>"Howard","HU"=>"Humboldt","ID"=>"Ida","IA"=>"Iowa","JK"=>"Jackson","JA"=>"Jasper","JE"=>"Jefferson","JO"=>"Johnson","JN"=>"Jones","KK"=>"Keokuk","KO"=>"Kossuth","LE"=>"Lee","LI"=>"Linn","LO"=>"Louisa","LU"=>"Lucas","LY"=>"Lyon","MD"=>"Madison ","MA"=>"Mahaska","MR"=>"Marion","MH"=>"Marshall","ML"=>"Mills","MI"=>"Mitchell","MO"=>"Monona","MN"=>"Monroe ","MG"=>"Montgomery","MU"=>"Muscatine","OB"=>"O'Brien","OS"=>"Osceola","PG"=>"Page","PA"=>"Palo Alto","PL"=>"Plymouth","PO"=>"Pocahontas","PK"=>"Polk","PT"=>"Pottawattamie","PS"=>"Poweshiek","RG"=>"Ringgold","SA"=>"Sac","SC"=>"Scott","SH"=>"Shelby","SX"=>"Sioux","ST"=>"Story","TM"=>"Tama","TA"=>"Taylor ","UN"=>"Union ","VB"=>"Van Buren","WP"=>"Wapello","WN"=>"Warren","WA"=>"Washington","WY"=>"Wayne","WE"=>"Webster","WB"=>"Winnebago","WS"=>"Winneshiek","WD"=>"Woodbury","WO"=>"Worth","WR"=>"Wright");

    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $countryCode[$entries[0][24]];
    return "";
}
// register_country_abbreviation  21 -> 20
add_filter( 'gform_field_value_register_country_abbreviation', 'dynamic_populate_register_country_abbreviation1' );
function dynamic_populate_register_country_abbreviation1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][21];
    return "";
}
// registration_country_number  26 -> 21
add_filter( 'gform_field_value_registration_country_number', 'dynamic_populate_registration_country_number1' );
function dynamic_populate_registration_country_number1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][26];
    return "";
}
// registration_region  27 -> 22
add_filter( 'gform_field_value_registration_region', 'dynamic_populate_registration_region1' );
function dynamic_populate_registration_region1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][27];
    return "";
}
// registration_agency_contact  40 -> 23 
add_filter( 'gform_field_value_registration_agency_contact', 'dynamic_populate_registration_agency_contact1' );
function dynamic_populate_registration_agency_contact1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^40.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(' ', $filtred);
    }


    return "";
}
// registration_job_title  29 -> 24
add_filter( 'gform_field_value_registration_job_title', 'dynamic_populate_registration_job_title1' );
function dynamic_populate_registration_job_title1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][29];
    return "";
}
// registration_phone  30 -> 25
add_filter( 'gform_field_value_registration_phone', 'dynamic_populate_registration_phone1' );
function dynamic_populate_registration_phone1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][30];
    return "";
}
// registration_cell  31 -> 26
add_filter( 'gform_field_value_registration_cell', 'dynamic_populate_registration_cell1' );
function dynamic_populate_registration_cell1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][31];
    return "";
}
// registration_is_same_as_above  34 -> 28
add_filter( 'gform_field_value_registration_is_same_as_above', 'dynamic_populate_registration_is_same_as_above1' );
function dynamic_populate_registration_is_same_as_above1( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][34];
    return "";
}
// registration_agency_contact_2  35 -> 29 
add_filter( 'gform_field_value_registration_agency_contact_2', 'dynamic_populate_registration_agency_contact_21' );
function dynamic_populate_registration_agency_contact_21( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^35.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(' ', $filtred);
    }
    return "";
}
// registration_job_title_2  36 -> 30
add_filter( 'gform_field_value_registration_job_title_2', 'dynamic_populate_registration_job_title_21' );
function dynamic_populate_registration_job_title_21( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][36];
    return "";
}
// registration_address_2  37 -> 31
add_filter( 'gform_field_value_registration_address_2', 'dynamic_populate_registration_address_21' );
function dynamic_populate_registration_address_21( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1){
        $filtred = array();
        foreach($entries[0] as $key => $value)
          if(preg_match('/^37.\d/',$key) && $value != '')
            $filtred[] = $value;

        return implode(', ', $filtred);
    }

    return "";
}
// registration_email_2  38 -> 32
add_filter( 'gform_field_value_registration_email_2', 'dynamic_populate_registration_email_21' );
function dynamic_populate_registration_email_21( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][38];
    return "";
}
// registration_phone_2  39 -> 33
add_filter( 'gform_field_value_registration_phone_2', 'dynamic_populate_registration_phone_21' );
function dynamic_populate_registration_phone_21( $value ) {
    $current_user = wp_get_current_user();
    $email = esc_html( $current_user->user_email );
    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $email );
    $form_id = 6;
    $entries = GFAPI::get_entries( $form_id, $search_criteria );
    if(count($entries) >= 1)
        return $entries[0][39];
    return "";
}
function my_profile_pass_update1( $user_id ) {
    if ( ! isset( $_POST['pass1'] ) || '' == $_POST['pass1'] ) {
        return;
    }

    $user = get_user_by('ID', $user_id);

    $search_criteria['field_filters'][] = array( 'key' => '32', 'value' => $user->user_email );
	$form_id = 6;
	$entries = GFAPI::get_entries( $form_id, $search_criteria );

	echo "<pre>"; print_r($entries); echo "</pre>";//die;
	if(count($entries) == 0){
		
		$username = $user->user_email;
		$user = get_user_by('login', $username );

		// Redirect URL //
		if ( !is_wp_error( $user ) )
		{
		    wp_clear_auth_cookie();
		    wp_set_current_user ( $user->ID );
		    wp_set_auth_cookie  ( $user->ID );

		    wp_redirect( trailingslashit( home_url().'/wp-admin/index.php' ) );
		    //wp_redirect( trailingslashit( home_url().'/join' ) );
		    exit();
		}
	}
}
add_action( 'profile_update', 'my_profile_pass_update1' );

function remove_dashboard_widgets1 () {
	remove_meta_box('oneandone_assistant_dashboard_widget','dashboard', 'normal'); //Activity
  

}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets1');
add_filter('wp_nav_menu_items', 'add_login_logout_link1', 10, 2);
function add_login_logout_link1($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}
add_filter( 'gform_pre_submission_filter_4', 'populate_checkbox1' );

function populate_checkbox1( $form ) {
	 foreach( $form['fields'] as &$field )  {
	 	if($field->id == 18)
	 	{
	 		foreach ($field->choices as $key => $choice) {
	 			$field->choices[$key]['text'] = $field->choices[$key]['value'];
				
	 		}
	 	}
	 }

	 return $form;
}
function users_gravity_form_entries_dashboard_widgets1() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Gravity Forms', 'after_register_dashboard_help1');
}

function after_register_dashboard_help1() {
	 echo '<p><a href="/view/user-entries" target="_blank">After Registration Form Entries</a></p>';
	
}

// Redirect to dashboard on login (instead of profile)
add_filter( 'login_redirect', 'app_login_redirect1', 10, 3 );
function app_login_redirect1( $redirect_to, $request, $user ) {
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

function user_profile_update_email1( $user_id ) {
    
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
add_action( 'profile_update', 'user_profile_update_email1', 10, 2);


function wp_admin_bar_change_howdy1( $wp_admin_bar ) {
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
    $profile_url = get_edit_profile_url( $user_id );

    if ( 0 != $user_id ) {
        /* Add the "My Account" menu */
        $avatar = get_avatar( $user_id, 28 );
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
add_action( 'admin_bar_menu', 'wp_admin_bar_change_howdy1', 11 );


// Radio Subscriber Submission Form user_name
add_filter( 'gform_field_value_user_name', 'dynamic_populate_user_name' );
function dynamic_populate_user_name( $value ) {
     $form_id = 10;
	$current_user = wp_get_current_user();
	//echo '<pre>' ;print_r($current_user); 
	if(is_user_logged_in() ){
		return $current_user->ID;
    
	}else {
		return 0 ;
	}
}

function redirect_after_download_subscriber_form(){
		if(is_user_logged_in() ){
			wp_redirect( trailingslashit( home_url().'/after-subscriber' ) );
		
		}else {
			wp_redirect( trailingslashit( home_url().'/wp-login.php?redirect_to=index.php/radio-subscriber-form' ) );
		}
	}
add_action('wp_login', 'redirect_after_download_subscriber_form', 10, 2); 


//subscriber view entry permissions
function add_grav_forms_permissions(){
	$role = get_role('subscriber');
	$role->add_cap('gravityforms_view_entries');
	//$role->add_cap('gravityforms_delete_entries');
	//$role->add_cap('gravityforms_view_entry_notes');
	//$role->add_cap('gravityforms_export_entries');
	
	
	
}
add_action('admin_init','add_grav_forms_permissions');


$user = wp_get_current_user();
if ( in_array( 'subscriber', (array) $user->roles ) ) {		
	//Filter for search_criteria_entry_list
	add_filter( 'gform_search_criteria_entry_list', 'override_search_criteria' );
}

function override_search_criteria( $search_criteria ) {
		
		$search_criteria['field_filters'][] = array( 'key' => 'created_by', 'operator' => 'is', 'value' => get_current_user_id() );
		
		return $search_criteria;
	}