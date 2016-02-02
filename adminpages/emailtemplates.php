<?php
//only admins can get this
if ( ! function_exists( "current_user_can" ) || ( ! current_user_can( "manage_options" ) && ! current_user_can( "pmpro_emailtemplates" ) ) ) {
	die( __( "You do not have permissions to perform this action.", "pmproet" ) );
}

global $pmproet_test_order_id, $wpdb, $msg, $msgt, $pmproet_email_defaults, $current_user;
$pmproet_test_order_id = get_option( 'pmproet_test_order_id' );

require_once( PMPRO_DIR . "/adminpages/admin_header.php" );
?>

	<form action="" method="post" enctype="multipart/form-data">
	<h2><?php _e( 'Email Templates', 'pmproet' ); ?></h2>
	<table class="form-table">
	<tr class="status hide-while-loading" style="display:none;">
		<th scope="row" valign="top"></th>
		<td>
			<div id="message">
				<p class="status_message"></p>
			</div>

		</td>
	</tr>
	<tr>
	<th scope="row" valign="top">
		<label for="pmpro_email_template_switcher">Email Template</label>
	</th>
	<td>
	<select name="pmpro_email_template_switcher" id="pmpro_email_template_switcher">
	<option value="" selected="selected">--- Select a Template to Edit ---</option>
	<option value="header"><?php _e('Email Header', 'pmproet'); ?></option>
	<option value="footer"><?php _e('Email Footer', 'pmproet'); ?></option>
	<?php foreach ( $pmproet_email_defaults as $key => $template ): ?>
	<option value="<?php echo $key; ?>"><?php echo $template['description']; ?></option>
	<?php endforeach; ?>
	</select>
	<img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" id="pmproet-spinner" style="display:none;"/>
	<hr>
	</td>
	</tr>
	<tr class="hide-while-loading">
		<th scope="row" valign="top"></th>
		<td>
			<label><input id="email_template_disable" name="email_template_disable" type="checkbox"/><span
					id="disable_label"><?php _e('Disable this email?', 'pmproet');?></span></label>

			<p id="disable_description" class="description small"><?php _e('Emails with this template will not be sent.', 'pmproet');?></p>
		</td>
	</tr>
	<tr class="hide-while-loading">
		<th scope="row" valign="top"><label for="email_template_subject"><?php _e('Subject', 'pmproet');?></label></th>
		<td>
			<input id="email_template_subject" name="email_template_subject" type="text" size="100"/>
		</td>
	</tr>
	<tr class="hide-while-loading">
		<th scope="row" valign="top"><label for="email_template_body"><?php _e('Body', 'pmproet');?></label></th>
		<td>
			<div id="template_editor_container">
				<textarea rows="10" cols="80" name="email_template_body" id="email_template_body"></textarea>
			</div>
		</td>
	</tr>
	<tr class="hide-while-loading">
		<th scope="row" valign="top"></th>
		<td>
			<?php _e( 'Send a test email to ', 'pmproet' ); ?>
			<input id="test_email_address" name="test_email_address" type="text"
			       value="<?php echo $current_user->user_email; ?>"/>
			<input id="send_test_email" class="button" name="send_test_email" value="<?php _e('Save Template and Send Email', 'pmproet');?>"
			       type="button"/>

			<p class="description">
				<a href="<?php echo add_query_arg( array( 'page'  => 'pmpro-orders',
				                                          'order' => $pmproet_test_order_id
				), admin_url( 'admin.php' ) ); ?>"
				   target="_blank"><?php _e( 'Click here to edit the order used for test emails.', 'pmproet' ); ?></a>
				<?php _e( 'Your current membership will be used for any membership level data.', 'pmproet' ); ?>
			</p>
		</td>
	</tr>
	<tr class="controls hide-while-loading">
		<th scope="row" valign="top"></th>
		<td>
			<p class="submit">
				<input id="submit_template_data" name="save_template" type="button" class="button-primary"
				       value="Save Template"/>
				<input id="reset_template_data" name="reset_template" type="button" class="button"
				       value="Reset Template"/>
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row" valign="top"></th>
		<td>
			<h3><?php _e('Variable Reference', 'pmproet');?></h3>

			<div id="template_reference" style="overflow:scroll;height:250px;width:800px;;">
				<table class="widefat striped">
					<tr>
						<th colspan=2><?php _e('General Settings / Membership Info', 'pmproet');?></th>
					</tr>
					<tr>
						<td>!!name!!</td>
						<td><?php _e('Display Name (Profile/Edit User > Display name publicly as)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!user_login!!</td>
						<td><?php _e('Username', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!sitename!!</td>
						<td><?php _e('Site Title', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!siteemail!!</td>
						<td><?php _e('Site Email Address (General Settings > Email OR Memberships > Email Settings)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!membership_id!!</td>
						<td><?php _e('Membership Level ID', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!membership_level_name!!</td>
						<td><?php _e('Membership Level Name', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!membership_change!!</td>
						<td><?php _e('Membership Level Change', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!membership_expiration!!</td>
						<td><?php _e('Membership Level Expiration', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!display_name!!</td>
						<td><?php _e('Display Name (Profile/Edit User > Display name publicly as)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!enddate!!</td>
						<td><?php _e('User Subscription End Date', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!user_email!!</td>
						<td><?php _e('User Email', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!login_link!!</td>
						<td><?php _e('Login URL', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!levels_link!!</td>
						<td><?php _e('Membership Levels Page URL', 'pmproet');?></td>
					</tr>
					<tr>
						<th colspan=2>Billing Information</th>
					</tr>
					<tr>
						<td>!!billing_address!!</td>
						<td><?php _e('Billing Info Complete Address', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_name!!</td>
						<td><?php _e('Billing Info Name', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_street!!</td>
						<td><?php _e('Billing Info Street Address', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_city!!</td>
						<td><?php _e('Billing Info City', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_state!!</td>
						<td><?php _e('Billing Info State', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_zip!!</td>
						<td><?php _e('Billing Info ZIP Code', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_country!!</td>
						<td><?php _e('Billing Info Country', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!billing_phone!!</td>
						<td><?php _e('Billing Info Phone #', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!cardtype!!</td>
						<td><?php _e('Credit Card Type', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!accountnumber!!</td>
						<td><?php _e('Credit Card Number (last 4 digits)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!expirationmonth!!</td>
						<td><?php _e('Credit Card Expiration Month (mm format)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!expirationyear!!</td>
						<td><?php _e('Credit Card Expiration Year (yyyy format)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!membership_cost!!</td>
						<td><?php _e('Membership Level Cost Text', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!instructions!!</td>
						<td><?php _e('Payment Instructions (used in Checkout - Email Template)', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!invoice_id!!</td>
						<td><?php _e('Invoice ID', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!invoice_total!!</td>
						<td><?php _e('Invoice Total', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!invoice_date!!</td>
						<td><?php _e('Invoice Date', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!discount_code!!</td>
						<td><?php _e('Discount Code Applied', 'pmproet');?></td>
					</tr>
					<tr>
						<td>!!invoice_link!!</td>
						<td><?php _e('Invoice Page URL', 'pmproet');?></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
	<?php wp_nonce_field( 'pmproet', 'security' ); ?>
	</form>

	<?php
	require_once( PMPRO_DIR . "/adminpages/admin_footer.php" );
	?>
