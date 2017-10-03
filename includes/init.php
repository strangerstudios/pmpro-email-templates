<?php

global $pmproet_email_functions, $pmproet_email_defaults, $pmproet_test_order_id;

/*
 * Make sure we have a test order for testing emails
 */
function pmproet_admin_init_test_order() {
	global $current_user, $pmproet_test_order_id;

	//make sure PMPro is activated
	if ( ! class_exists( '\MemberOrder' ) ) {
		return;
	}
	
	$pmproet_test_order_id = get_option( 'pmproet_test_order_id', false );
	
	// Create dummy/test order record if ID isn't set yet
	if ( empty( $pmproet_test_order_id ) ) {
		
		$test_order            = new MemberOrder();
		$all_levels = pmpro_getAllLevels();
		
		if ( ! empty( $all_levels ) ) {
			$first_level                = array_shift( $all_levels );
			$test_order->membership_id  = $first_level->id;
			$test_order->InitialPayment = $first_level->initial_payment;
		} else {
			$test_order->membership_id  = 1;
			$test_order->InitialPayment = 1;
		}
		$test_order->user_id             = $current_user->ID;
		$test_order->cardtype            = "Visa";
		$test_order->accountnumber       = "4111111111111111";
		$test_order->expirationmonth     = date( 'm', current_time( 'timestamp' ) );
		$test_order->expirationyear      = ( intval( date( 'Y', current_time( 'timestamp' ) ) ) + 1 );
		$test_order->ExpirationDate      = $test_order->expirationmonth . $test_order->expirationyear;
		$test_order->CVV2                = '123';
		$test_order->FirstName           = 'Jane';
		$test_order->LastName            = 'Doe';
		$test_order->Address1            = '123 Street';
		$test_order->billing             = new stdClass();
		$test_order->billing->name       = 'Jane Doe';
		$test_order->billing->street     = '123 Street';
		$test_order->billing->city       = 'City';
		$test_order->billing->state      = 'ST';
		$test_order->billing->country    = 'US';
		$test_order->billing->zip        = '12345';
		$test_order->billing->phone      = '5558675309';
		$test_order->gateway_environment = 'sandbox';
		$test_order->notes               = __( 'This is a test order used with the PMPro Email Templates addon.', 'pmproet' );
		$test_order->saveOrder();
		$pmproet_test_order_id = $test_order->id;
		update_option( 'pmproet_test_order_id', $pmproet_test_order_id, 'no' );
	}
}

add_action( 'admin_init', 'pmproet_admin_init_test_order'  );

/**
 * Default email templates.
 */
$pmproet_email_defaults = array(
	'default'                  => array(
		'subject'     => __( "An Email From !!sitename!!", "pmproet" ),
		'description' => __( 'Default Email', 'pmproet')
	),
	'admin_change'             => array(
		'subject'     => __( "Your membership at !!sitename!! has been changed", 'pmproet' ),
		'description' => __( 'Admin Change', 'pmproet')
	),
	'admin_change_admin'       => array(
		'subject'     => __( "Membership for !!user_login!! at !!sitename!! has been changed", 'pmproet' ),
		'description' => __('Admin Change (admin)', 'pmproet')
	),
	'billing'                  => array(
		'subject'     => __( "Your billing information has been udpated at !!sitename!!", 'pmproet' ),
		'description' => __('Billing', 'pmproet')
	),
	'billing_admin'            => array(
		'subject'     => __( "Billing information has been udpated for !!user_login!! at !!sitename!!", 'pmproet' ),
		'description' => __('Billing (admin)', 'pmproet')
	),
	'billing_failure'          => array(
		'subject'     => __( "Membership Payment Failed at !!sitename!!", 'pmproet' ),
		'description' => __('Billing Failure', 'pmproet')
	),
	'billing_failure_admin'    => array(
		'subject'     => __( "Membership Payment Failed For !!display_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Billing Failure (admin)', 'pmproet')
	),
	'cancel'                   => array(
		'subject'     => __( "Your membership at !!sitename!! has been CANCELLED", 'pmproet' ),
		'description' => __('Cancel', 'pmproet')
	),
	'cancel_admin'             => array(
		'subject'     => __( "Membership for !!user_login!! at !!sitename!! has been CANCELLED", 'pmproet' ),
		'description' => __('Cancel (admin)', 'pmproet')
	),
	'checkout_check'           => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Check', 'pmproet')
	),
	'checkout_check_admin'     => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Check (admin)', 'pmproet')
	),
	'checkout_express'         => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - PayPal Express', 'pmproet')
	),
	'checkout_express_admin'   => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - PayPal Express (admin)', 'pmproet')
	),
	'checkout_free'            => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Free', 'pmproet')
	),
	'checkout_free_admin'      => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Free (admin)', 'pmproet')
	),
	'checkout_freetrial'       => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Free Trial', 'pmproet')
	),
	'checkout_freetrial_admin' => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Free Trial (admin)', 'pmproet')
	),
	'checkout_paid'            => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Paid', 'pmproet')
	),
	'checkout_paid_admin'      => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Paid (admin)', 'pmproet')
	),
	'checkout_trial'           => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Trial', 'pmproet')
	),
	'checkout_trial_admin'     => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmproet' ),
		'description' => __('Checkout - Trial (admin)', 'pmproet')
	),
	'credit_card_expiring'     => array(
		'subject'     => __( "Credit Card on File Expiring Soon at !!sitename!!", 'pmproet' ),
		'description' => __('Credit Card Expiring', 'pmproet')
	),
	'invoice'                  => array(
		'subject'     => __( "INVOICE for !!sitename!! membership", 'pmproet' ),
		'description' => __('Invoice', 'pmproet')
	),
	'membership_expired'       => array(
		'subject'     => __( "Your membership at !!sitename!! has ended", 'pmproet' ),
		'description' => __('Membership Expired', 'pmproet')
	),
	'membership_expiring'      => array(
		'subject'     => __( "Your membership at !!sitename!! will end soon", 'pmproet' ),
		'description' => __('Membership Expiring', 'pmproet')
	),
	'trial_ending'             => array(
		'subject'     => __( "Your trial at !!sitename!! is ending soon", 'pmproet' ),
		'description' => __('Trial Ending', 'pmproet')
	),
);

/**
 * Filter default template settings and add new templates.
 *
 * @since 0.5.7
 */
$pmproet_email_defaults = apply_filters( 'pmproet_templates', $pmproet_email_defaults );