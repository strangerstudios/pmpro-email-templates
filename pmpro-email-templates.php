<?php
/*
Plugin Name: Paid Memberships Pro - Email Templates Add On
Plugin URI: https://www.paidmembershipspro.com/add-ons/email-templates-admin-editor/
Description: Define your own members email templates including HTML for Paid Memberships Pro.
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com
Version: 0.7.2
*/

/*
 * Includes
 */
function pmproet_init() {
    require_once(dirname(__FILE__) . '/includes/init.php');
}
add_action('plugins_loaded', 'pmproet_init');

/*
	Load plugin textdomain.
*/
function pmproet_load_textdomain() {
  load_plugin_textdomain( 'pmproet', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'pmproet_load_textdomain' );

/*
 * Setup admin pages
 */
function pmproet_setup() {
    if ( ! defined( 'PMPRO_VERSION' ) ) {
        return;
    }
    
    if( version_compare( PMPRO_VERSION, '2.0' ) >= 0 ) {
        add_submenu_page('pmpro-dashboard', __('Email Templates', 'pmproet'), __('Email Templates', 'pmproet'), 'manage_options', 'pmpro-email-templates', 'pmproet_admin_page');
    } else {
        add_submenu_page('pmpro-membershiplevels', __('Email Templates', 'pmproet'), __('Email Templates', 'pmproet'), 'manage_options', 'pmpro-email-templates', 'pmproet_admin_page');
    }
}
add_action('admin_menu', 'pmproet_setup', 20);

function pmproet_admin_page()
{
    require_once( plugin_dir_path(__FILE__) ) . "adminpages/emailtemplates.php";
}

/*
	Add page to admin bar
*/
function pmproet_admin_bar_menu() {
	global $wp_admin_bar;
	if ( !is_super_admin() || !is_admin_bar_showing() )
		return;	
	$wp_admin_bar->add_menu( array(
	'id' => 'pmpro-email-templates',
	'parent' => 'paid-memberships-pro',
	'title' => __( 'Email Templates', 'pmproet'),
	'href' => get_admin_url(NULL, '/admin.php?page=pmpro-email-templates') ) );	
}
add_action('admin_bar_menu', 'pmproet_admin_bar_menu', 1000);


//enqueue js/css
function pmproet_scripts() {
    if (!empty($_REQUEST['page']) && $_REQUEST['page'] == 'pmpro-email-templates') {
        wp_enqueue_script('pmproet', plugin_dir_url(__FILE__) . 'js/pmproet.js', array('jquery'), null, false);
        wp_enqueue_style('pmproet', plugin_dir_url(__FILE__) . 'css/pmproet.css');
    }
}
add_action('admin_enqueue_scripts', 'pmproet_scripts');

/*
 * AJAX Functions
 */

//get template data
function pmproet_get_template_data() {

    check_ajax_referer('pmproet', 'security');

    global $pmproet_email_defaults;

    $template = $_REQUEST['template'];

    //get template data
    $template_data['body'] = pmpro_getOption('email_' . $template . '_body');
    $template_data['subject'] = pmpro_getOption('email_' . $template . '_subject');
    $template_data['disabled'] = pmpro_getOption('email_' . $template . '_disabled');

    if (empty($template_data['body'])) {
        //if not found, load template
        $template_data['body'] = pmproet_getTemplateBody($template);
    }

    if (empty($template_data['subject']) && $template != "header" && $template != "footer") {
        $template_data['subject'] = $pmproet_email_defaults[$template]['subject'];
    }

    echo json_encode($template_data);
	
    exit;
}
add_action('wp_ajax_pmproet_get_template_data', 'pmproet_get_template_data');

//save template data
function pmproet_save_template_data() {

    check_ajax_referer('pmproet', 'security');

    //update this template's settings
    pmpro_setOption('email_' . $_REQUEST['template'] . '_subject', stripslashes($_REQUEST['subject']));
    pmpro_setOption('email_' . $_REQUEST['template'] . '_body', stripslashes($_REQUEST['body']));
    echo 'Template Saved';
    
	exit;
}
add_action('wp_ajax_pmproet_save_template_data', 'pmproet_save_template_data');

//reset template data
function pmproet_reset_template_data() {

    check_ajax_referer('pmproet', 'security');

    global $pmproet_email_defaults;

    $template = $_REQUEST['template'];

    delete_option('pmpro_email_' . $template . '_subject');
    delete_option('pmpro_email_' . $template . '_body');

    $template_data['subject'] = $pmproet_email_defaults[$template]['subject'];
    $template_data['body'] = pmproet_getTemplateBody($template);

    echo json_encode($template_data);
	exit;
}
add_action('wp_ajax_pmproet_reset_template_data', 'pmproet_reset_template_data');

// disable template
function pmproet_disable_template() {

    check_ajax_referer('pmproet', 'security');

    $template = $_REQUEST['template'];
    $response['result'] = update_option('pmpro_email_' . $template . '_disabled', $_REQUEST['disabled']);
    $response['status'] = $_REQUEST['disabled'];
    echo json_encode($response);
	exit;
}
add_action('wp_ajax_pmproet_disable_template', 'pmproet_disable_template');

//send test email
function pmproet_send_test() {

    check_ajax_referer('pmproet', 'security');

    global $pmproet_test_order_id, $current_user;

    //setup test email
    $test_email = new PMProEmail();
    $test_email->to = $_REQUEST['email'];
    $test_email->template = str_replace('email_', '', $_REQUEST['template']);
	
	//add filter to change recipient
	add_filter('pmpro_email_recipient', 'pmproet_test_pmpro_email_recipient', 10, 2);
	
    //load test order
    $pmproet_test_order_id = get_option('pmproet_test_order_id');
    $test_order = new MemberOrder($pmproet_test_order_id);
	
    $test_user = $current_user;
    
    // Grab the first membership level defined as a "test level" to use
	$all_levels = pmpro_getAllLevels( true);
    $test_user->membership_level = array_pop( $all_levels );
    
    //add notice to email body
    add_filter('pmpro_email_body', 'pmproet_test_email_body', 10, 2);

    //force the template
    add_filter('pmpro_email_filter', 'pmproet_test_email_template', 5, 1);

    //figure out how to send the email
    switch($test_email->template) {
        case 'cancel':
            $send_email = 'sendCancelEmail';
            $params = array($test_user);
            break;
        case 'cancel_admin':
            $send_email = 'sendCancelAdminEmail';
            $params = array($current_user, $current_user->membership_level->id);
            break;
        case 'checkout_check':
        case 'checkout_express':
        case 'checkout_free':
        case 'checkout_freetrial':
        case 'checkout_paid':
        case 'checkout_trial':
            $send_email = 'sendCheckoutEmail';
            $params = array($test_user, $test_order);
            break;
        case 'checkout_check_admin':
        case 'checkout_express_admin':
        case 'checkout_free_admin':
        case 'checkout_freetrial_admin':
        case 'checkout_paid_admin':
        case 'checkout_trial_admin':
            $send_email = 'sendCheckoutAdminEmail';
            $params = array($test_user, $test_order);
            break;
        case 'billing':
            $send_email = 'sendBillingEmail';
            $params = array($test_user, $test_order);
            break;
        case 'billing_admin':
            $send_email = 'sendBillingAdminEmail';
            $params = array($test_user, $test_order);
            break;
        case 'billing_failure':
            $send_email = 'sendBillingFailureEmail';
            $params = array($test_user, $test_order);
            break;
        case 'billing_failure_admin':
            $send_email = 'sendBillingFailureAdminEmail';
            $params = array($test_user->user_email, $test_order);
            break;
        case 'credit_card_expiring':
            $send_email = 'sendCreditCardExpiringEmail';
            $params = array($test_user, $test_order);
            break;
        case 'invoice':
            $send_email = 'sendInvoiceEmail';
            $params = array($test_user, $test_order);
            break;
        case 'trial_ending':
            $send_email = 'sendTrialEndingEmail';
            $params = array($test_user);
            break;
        case 'membership_expired';
            $send_email = 'sendMembershipExpiredEmail';
            $params = array($test_user);
            break;
        case 'membership_expiring';
            $send_email = 'sendMembershipExpiringEmail';
            $params = array($test_user);
            break;
        default:
            $send_email = 'sendEmail';
            $params = array();
    }

    //send the email
    $response = call_user_func_array(array($test_email, $send_email), $params);

    //return the response
    echo $response;
    exit;
}
add_action('wp_ajax_pmproet_send_test', 'pmproet_send_test');

function pmproet_test_pmpro_email_recipient($email)
{
	if(!empty($_REQUEST['email']))
		$email = $_REQUEST['email'];
	return $email;
}

/* Filter Subject and Body */
function pmproet_email_filter($email ) {

    global $pmproet_email_defaults;

    //is this email disabled or is it not in the templates array?
    if(pmpro_getOption('email_' . $email->template . '_disabled') == 'true')
        return false;

    //leave the email alone if it's not in the list of templates
    if( empty( $pmproet_email_defaults[$email->template] ) )
        return $email;

    $et_subject = pmpro_getOption('email_' . $email->template . '_subject');
    $et_header = pmpro_getOption('email_header_body');
    $et_body = pmpro_getOption('email_' . $email->template . '_body');
    $et_footer = pmpro_getOption('email_footer_body');

    if(!empty($et_subject))
        $email->subject = $et_subject;

    //is header disabled?
    if(pmpro_getOption('email_header_disabled') != 'true') {
        if(!empty($et_header))
            $temp_content = $et_header;
        else
            $temp_content = pmproet_getTemplateBody('header');
    }

    if(!empty($et_body))
        $temp_content .= $et_body;
    else
        $temp_content .= pmproet_getTemplateBody($email->template);

    //is footer disabled?
    if(pmpro_getOption('email_footer_disabled') != 'true') {
        if(!empty($et_footer))
            $temp_content .= $et_footer;
        else
            $temp_content .= pmproet_getTemplateBody('footer');
    }
    
    $email->body = $temp_content;

    //replace data
    foreach($email->data as $key => $value)
    {
        $email->body = str_replace("!!" . $key . "!!", $value, $email->body);
        $email->subject = str_replace("!!" . $key . "!!", $value, $email->subject);
    }

    return $email;
}
add_filter('pmpro_email_filter', 'pmproet_email_filter', 10, 1);

//for test emails
function pmproet_test_email_body($body, $email = null) {
    $body .= '<br><br><b>--- ' . __('THIS IS A TEST EMAIL', 'pmproet') . ' --</b>';
    return $body;
}

function pmproet_test_email_template($email)
{
    if(!empty($_REQUEST['template']))
        $email->template = str_replace('email_', '', $_REQUEST['template']);

    return $email;
}

/* Filter for Variables */
function pmproet_email_data($data, $email) {

    global $current_user, $pmpro_currency_symbol, $wpdb;

	if(!empty($data) && !empty($data['user_login']))
		$user = get_user_by('login', $data['user_login']);
    if(empty($user))
        $user = $current_user;
    $pmpro_user_meta = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . $user->ID . "' AND status='active'");

	//make sure data is an array
	if(!is_array($data))
		$data = array();
	
	//general data	
    $new_data['sitename'] = get_option("blogname");
	$new_data['siteemail'] = pmpro_getOption("from_email");
	if(empty($new_data['login_link']))
		$new_data['login_link'] = wp_login_url();
	$new_data['levels_link'] = pmpro_url("levels");        
	
	//user data
	if(!empty($user))
	{
		$new_data['name'] = $user->display_name;
		$new_data['user_login'] = $user->user_login;
		$new_data['display_name'] = $user->display_name;
		$new_data['user_email'] = $user->user_email;
	}
	
	//membership data
	if(!empty($user->membership_level))
		$new_data['enddate'] = date(get_option('date_format'), $user->membership_level->enddate);
	
	//invoice data
	if(!empty($data['invoice_id']))
	{
	    $invoice = new MemberOrder($data['invoice_id']);
		if(!empty($invoice) && !empty($invoice->code))
		{
			$new_data['billing_name'] = $invoice->billing->name;
			$new_data['billing_street'] = $invoice->billing->street;
			$new_data['billing_city'] = $invoice->billing->city;
			$new_data['billing_state'] = $invoice->billing->state;
			$new_data['billing_zip'] = $invoice->billing->zip;
			$new_data['billing_country'] = $invoice->billing->country;
			$new_data['billing_phone'] = $invoice->billing->phone;
			$new_data['cardtype'] = $invoice->cardtype;
			$new_data['accountnumber'] = hideCardNumber($invoice->accountnumber);
			$new_data['expirationmonth'] = $invoice->expirationmonth;
			$new_data['expirationyear'] = $invoice->expirationyear;
			$new_data['instructions'] = wpautop(pmpro_getOption('instructions'));
			$new_data['invoice_id'] = $invoice->code;
			$new_data['invoice_total'] = $pmpro_currency_symbol . number_format($invoice->total, 2);
			$new_data['invoice_link'] = pmpro_url('invoice', '?invoice=' . $invoice->code);
			
			 //billing address
			$new_data["billing_address"] = pmpro_formatAddress($invoice->billing->name,
				$invoice->billing->street,
				"", //address 2
				$invoice->billing->city,
				$invoice->billing->state,
				$invoice->billing->zip,
				$invoice->billing->country,
				$invoice->billing->phone);
		}
	}        

    //membership change
    if(!empty($user->membership_level) && !empty($user->membership_level->ID))
       $new_data["membership_change"] = sprintf(__("The new level is %s.", "pmproet"), $user->membership_level->name);
    else
       $new_data["membership_change"] = __("Your membership has been cancelled", "pmproet");

    if(!empty($user->membership_level) && !empty($user->membership_level->enddate))
        $new_data["membership_change"] .= ". " . sprintf(__("This membership will expire on %s", "pmproet"), date(get_option('date_format'), $user->membership_level->enddate));

    elseif(!empty($email->expiration_changed))
        $new_data["membership_change"] .= ". " . __("This membership does not expire", "pmproet");

    //membership expiration
    $new_data['membership_expiration'] = '';
    if(!empty($pmpro_user_meta->enddate))
        $new_data['membership_expiration'] = "<p>" . sprintf(__("This membership will expire on %s.", "pmproet"), $pmpro_user_meta->enddate . "</p>\n");

    //if others are used in the email look in usermeta
    $et_body = pmpro_getOption('email_' . $email->template . '_body');
    $templates_in_email = preg_match_all("/!!([^!]+)!!/", $et_body, $matches);
    if(!empty($templates_in_email))
    {
    	$matches = $matches[1];
    	foreach($matches as $match)
    	{
    		if(empty($new_data[$match]))
    		{
    			$usermeta = get_user_meta($user->ID, $match, true);
    			if(!empty($usermeta))
    			{
    				if(is_array($usermeta) && !empty($usermeta['fullurl']))
						$new_data[$match] = $usermeta['fullurl'];
					elseif(is_array($usermeta))
						$new_data[$match] = implode(", ", $usermeta);					
					else
						$new_data[$match] = $usermeta;
    			}
    		}
    	}
    }

	//now replace any new_data not already in data
	foreach($new_data as $key => $value)
	{
		if(!isset($data[$key]))
			$data[$key] = $value;
	}
		
    return $data;
}
add_filter('pmpro_email_data', 'pmproet_email_data', 10, 2);


/**
 * Load the default email template.
 *
 * Checks theme, then template, then PMPro directory.
 *
 * @since 0.6
 *
 * @param $template string
 *
 * @return string
 */
function pmproet_getTemplateBody($template) {

    global $pmproet_email_defaults;

	// Defaults
	$body = "";
	$file = false;
	
    // Load template
    if(!empty($pmproet_email_defaults[$template]['body']))
        $body = $pmproet_email_defaults[$template]['body'];
    elseif ( file_exists( get_stylesheet_directory() . '/paid-memberships-pro/email/' . $template . '.html' ) ) {
        $file = get_stylesheet_directory() . '/paid-memberships-pro/email/' . $template . '.html';
    } elseif ( file_exists( get_template_directory() . '/paid-memberships-pro/email/' . $template . '.html') ) {
	    $file = get_template_directory() . '/paid-memberships-pro/email/' . $template . '.html';
    } elseif( file_exists( PMPRO_DIR . '/email/' . $template . '.html')) {
        $file = PMPRO_DIR . '/email/' . $template . '.html';
    } 
		
    if( $file && ! $body ) {
        ob_start();
        require_once( $file );
        $body = ob_get_contents();
        ob_end_clean();
    }

    return $body;
}

/* Register activation hook. */
register_activation_hook( __FILE__, 'pmproet_admin_notice_activation_hook' );
/**
 * Runs only when the plugin is activated.
 *
 * @since 0.1.0
 */
function pmproet_admin_notice_activation_hook() {
    // Create transient data.
    set_transient( 'pmproet-admin-notice', true, 5 );
}
/**
 * Admin Notice on Activation.
 *
 * @since 0.1.0
 */
function pmproet_admin_notice() {
    // Check transient, if available display notice.
    if ( get_transient( 'pmproet-admin-notice' ) ) { ?>
        <div class="updated notice is-dismissible">
            <p><?php printf( __( 'Thank you for activating. <a href="%s">Visit the settings page</a> to get started with the Email Templates Add On.', 'pmpro-slack' ), get_admin_url( null, 'admin.php?page=pmpro-email-templates' ) ); ?></p>
        </div>
        <?php
        // Delete transient, only display this notice once.
        delete_transient( 'pmproet-admin-notice' );
    }
}
add_action( 'admin_notices', 'pmproet_admin_notice' );

/**
 * Function to add links to the plugin action links
 *
 * @param array $links Array of links to be shown in plugin action links.
 */
function pmproet_add_action_links($links) {	
	$new_links = array(
			'<a href="' . get_admin_url(NULL, 'admin.php?page=pmpro-email-templates') . '">Settings</a>',
	);
	return array_merge($new_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pmproet_add_action_links');

/**
 * Function to add links to the plugin row meta
 *
 * @param array  $links Array of links to be shown in plugin meta.
 * @param string $file Filename of the plugin meta is being shown for.
 */
function pmproet_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-email-templates.php') !== false)
	{
		$new_links = array(
			'<a href="' . esc_url('https://www.paidmembershipspro.com/add-ons/email-templates-admin-editor/')  . '" title="' . esc_attr( __( 'View Documentation', 'pmproet' ) ) . '">' . __( 'Docs', 'pmproet' ) . '</a>',
			'<a href="' . esc_url('https://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmproet' ) ) . '">' . __( 'Support', 'pmproet' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'pmproet_plugin_row_meta', 10, 2);
