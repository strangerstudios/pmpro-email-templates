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
		$test_order->notes               = __( 'This is a test order used with the PMPro Email Templates addon.', 'pmpro-email-templates' );
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
		'subject'     => __( "An Email From !!sitename!!", "pmpro-email-templates" ),
		'description' => __( 'Default Email', 'pmpro-email-templates')
	),
	'admin_change'             => array(
		'subject'     => __( "Your membership at !!sitename!! has been changed", 'pmpro-email-templates' ),
		'description' => __( 'Admin Change', 'pmpro-email-templates')
	),
	'admin_change_admin'       => array(
		'subject'     => __( "Membership for !!user_login!! at !!sitename!! has been changed", 'pmpro-email-templates' ),
		'description' => __('Admin Change (admin)', 'pmpro-email-templates')
	),
	'billing'                  => array(
		'subject'     => __( "Your billing information has been udpated at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Billing', 'pmpro-email-templates')
	),
	'billing_admin'            => array(
		'subject'     => __( "Billing information has been udpated for !!user_login!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Billing (admin)', 'pmpro-email-templates')
	),
	'billing_failure'          => array(
		'subject'     => __( "Membership Payment Failed at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Billing Failure', 'pmpro-email-templates')
	),
	'billing_failure_admin'    => array(
		'subject'     => __( "Membership Payment Failed For !!display_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Billing Failure (admin)', 'pmpro-email-templates')
	),
	'cancel'                   => array(
		'subject'     => __( "Your membership at !!sitename!! has been CANCELLED", 'pmpro-email-templates' ),
		'description' => __('Cancel', 'pmpro-email-templates')
	),
	'cancel_admin'             => array(
		'subject'     => __( "Membership for !!user_login!! at !!sitename!! has been CANCELLED", 'pmpro-email-templates' ),
		'description' => __('Cancel (admin)', 'pmpro-email-templates')
	),
	'checkout_check'           => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Check', 'pmpro-email-templates')
	),
	'checkout_check_admin'     => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Check (admin)', 'pmpro-email-templates')
	),
	'checkout_express'         => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - PayPal Express', 'pmpro-email-templates')
	),
	'checkout_express_admin'   => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - PayPal Express (admin)', 'pmpro-email-templates')
	),
	'checkout_free'            => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Free', 'pmpro-email-templates')
	),
	'checkout_free_admin'      => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Free (admin)', 'pmpro-email-templates')
	),
	'checkout_freetrial'       => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Free Trial', 'pmpro-email-templates')
	),
	'checkout_freetrial_admin' => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Free Trial (admin)', 'pmpro-email-templates')
	),
	'checkout_paid'            => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Paid', 'pmpro-email-templates')
	),
	'checkout_paid_admin'      => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Paid (admin)', 'pmpro-email-templates')
	),
	'checkout_trial'           => array(
		'subject'     => __( "Your membership confirmation for !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Trial', 'pmpro-email-templates')
	),
	'checkout_trial_admin'     => array(
		'subject'     => __( "Member Checkout for !!membership_level_name!! at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Checkout - Trial (admin)', 'pmpro-email-templates')
	),
	'credit_card_expiring'     => array(
		'subject'     => __( "Credit Card on File Expiring Soon at !!sitename!!", 'pmpro-email-templates' ),
		'description' => __('Credit Card Expiring', 'pmpro-email-templates')
	),
	'invoice'                  => array(
		'subject'     => __( "INVOICE for !!sitename!! membership", 'pmpro-email-templates' ),
		'description' => __('Invoice', 'pmpro-email-templates')
	),
	'membership_expired'       => array(
		'subject'     => __( "Your membership at !!sitename!! has ended", 'pmpro-email-templates' ),
		'description' => __('Membership Expired', 'pmpro-email-templates')
	),
	'membership_expiring'      => array(
		'subject'     => __( "Your membership at !!sitename!! will end soon", 'pmpro-email-templates' ),
		'description' => __('Membership Expiring', 'pmpro-email-templates')
	),
	'trial_ending'             => array(
		'subject'     => __( "Your trial at !!sitename!! is ending soon", 'pmpro-email-templates' ),
		'description' => __('Trial Ending', 'pmpro-email-templates')
	),
);

// add SCA payment action required emails if we're using PMPro 2.1 or later
if( defined( 'PMPRO_VERSION' ) && version_compare( PMPRO_VERSION, '2.1' ) >= 0 ) {
	$pmproet_email_defaults = array_merge( $pmproet_email_defaults, array(
		'payment_action'            => array(
			'subject'     => __( "Payment action required for your !!sitename!! membership", 'pmpro-email-templates' ),
			'description' => __('Payment Action Required', 'pmpro-email-templates')
		),
		'payment_action_admin'      => array(
			'subject'     => __( "Payment action required: membership for !!user_login!! at !!sitename!!", 'pmpro-email-templates' ),
			'description' => __('Payment Action Required (admin)', 'pmpro-email-templates')
		)
	));
}

/**
 * Filter default template settings and add new templates.
 *
 * @since 0.5.7
 */
$pmproet_email_defaults = apply_filters( 'pmproet_templates', $pmproet_email_defaults );