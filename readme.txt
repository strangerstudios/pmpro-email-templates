=== Paid Memberships Pro - Email Templates Add On ===
Contributors: strangerstudios
Tags: email, notification, pmpro, paid memberships pro, welcome email
Requires at least: 3.5
Tested up to: 5.4
Stable tag: 0.8.1

Customize member emails for Paid Memberships Pro using an interactive admin editor within the WordPress dashboard.

== Description ==

Customize member emails for Paid Memberships Pro using an interactive admin editor within the WordPress dashboard.

Every email sent by Paid Memberships Pro is included in the editor. This editor also supports emails included in other PMPro Add On plugins, include:

* [Approval Process for Membership Checkout](https://www.paidmembershipspro.com/add-ons/approval-process-membership/)
* [Recurring Payment Email Reminders](https://www.paidmembershipspro.com/add-ons/recurring-payment-email-reminders/)
* [Series: Drip-Feed Content](https://www.paidmembershipspro.com/add-ons/pmpro-series-for-drip-feed-content/)
* [Email Confirmation](https://www.paidmembershipspro.com/add-ons/email-confirmation-add-on/)

[Read the full documentation for the Email Templates Add On](https://www.paidmembershipspro.com/add-ons/email-templates-admin-editor/) or [view the list of emails](https://www.paidmembershipspro.com/documentation/member-communications/list-of-pmpro-email-templates/) by name and when they may be sent to your members.

= Official Paid Memberships Pro Add On =

This is an official Add On for [Paid Memberships Pro](https://www.paidmembershipspro.com), the most complete member management and membership subscriptions plugin for WordPress.

= Features =

* Edit email templates directly from the WordPress dashboard.
* Imports existing email templates from paid-memberships-pro/email/ directory.
* Customize the default email header and footer or disable them.
* Disable specific emails from being sent to your members or the admin.
* Send yourself a test version of any email.
* Filter to handle all $data variables.
* Field variable reference within the editor.

[youtube https://www.youtube.com/watch?v=xbzYVdA6y2s]

== Installation ==

1. Upload the `pmpro-email-templates` directory to the `/wp-content/plugins/` directory of your site.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate to Memberships > Email Templates to begin customizing emails.

== Frequently Asked Questions ==

= I found a bug in the plugin. =

Please post it in the issues section of GitHub and we'll fix it as soon as we can. Thanks for helping. https://github.com/strangerstudios/pmpro-email-templates/issues

== Screenshots ==

1. Select the template to edit. Modify the content, disable the email entirely, or send yourself a test version of any email.
2. A list of general and membership related template variables.
3. A list of billing related template variables.

== Changelog ==
= 0.8.1 - 2020-06-04 =
* BUG Fix: Fixed issue where !!membership_change!! may not show user's new membership level.

= 0.8 - 2020-05-15 =
* BUG Fix: Fixed issue where emails could sometimes be sent without body content.
* BUG FIX: Quotes are now being decoded correctly in email subjects.
* BUG FIX: Resolved notice shown when email header was disabled.
* BUG FIX/ENHANCEMENT: Periods now included at the end of the !!membership_change!! variable's contents.
* ENHANCEMENT: "Payment Action Required" emails are now editable.
* ENHANCEMENT: Now ensuring that only one version of PMPro Email Templates is active.

= 0.7.2 =
* BUG FIX: Fixed admin menu code to work with PMPro 2.0

= .7.1 =
* BUG: Fixed typo when editing the email header.

= .7 =
* BUG: Fixed link to edit the test order.
* FEATURE: Updated for localization with new pmproet.pot/po files.

= .6.2. =
* BUG: Now ignoring emails if $email->template is not included in the default array of templates.

= .6.1 =
* BUG: Fixed bug when choosing header or footer from the email templates dashboard page.

= .6 =
* FEATURE: Added new pmproet_templates filter, which can be used to modify the default template settings and add new custom templates.
* ENHANCEMENT: Now checking for custom templates in stylesheet and template directory before loading PMPro defaults.
* BUG/ENHANCEMENT: Changed plugin text domain to "pmproet" instead of "pmpro" to support custom translations.

= .5.6 =
* ENHANCEMENT: Security hardening - verifying nonces on all AJAX requests.

= .5.5 =
* ENHANCEMENT: You can add any user meta field to an email template now by using a variable like !!meta_key!!. This will work as long as there isn't already data using that variable.

= .5.4.3 =
* BUG: Fixed issue where test emails were being sent to the admin email instead of the one entered into the send test email form. (Thanks, John Hamlin)

= .5.4.2 =
* BUG: Now forcing the selected template for test emails.

= .5.4.1 =
* BUG: Fixed issue where plugin would fail if the MemberOrder class was not yet loaded (i.e. PMPro is inactive).

= .5.4 =
* ENAHANCEMENT: Added test email feature

= .5.3 =
* BUG: Fixed bug where the wp_login_url() was not being set properly and throwing a warning that could break checkout.
* BUG: Fixed typo/warning in setting of enddate in data vars. (Thanks, John Hamlin.)

= .5.2 =
* Added ability to disable header, footer, or even entire emails.

= .5.1.4 =
* Fixed magic quotes bug when saving template data.

= .5.1.3 =
* Fixed issue with database table name

= .5.1.2 =
* Hotfix: WordPress repo trunk was missing files

= .5.1.1 =
* Fixed various bugs with building the email body

= .5 =
* Fixed Subject bug
* Submit button is now disabled instead of hidden when saving templates - it just looked weird.

= .4 =
* Fixed warnings when setting up $data array.
* Fixed issue where emails were getting double content.

= .3 =
* Added reset button
* Added AJAX saving.
* Bug/style fixes

= .2 =
* Removed wp editor.
* Fixed some warnings.

= .1 =
* This is the initial version of the plugin.

