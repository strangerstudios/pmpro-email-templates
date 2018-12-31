=== Paid Memberships Pro - Email Templates Add On ===
Contributors: strangerstudios, messica
Tags: pmpro, paid memberships pro, email, templates, customize, member, membership, subscription, addon
Requires at least: 3.5
Tested up to: 5.0.2
Stable tag: 0.7.2

== Description ==
Customize PMPro email templates right from the WordPress dashboard!
Simply select an email template from the dropdown list, edit, and save!

== Features ==
* Edit email templates directly from the WordPress dashboard!
* Imports existing email templates from paid-memberships-pro/email/ directory
* Ability to disable header, footer, and even entire emails.
* Filter to handle all $data variables
* Variable reference on Email Templates page

== Installation ==
1. Upload the `pmpro-email-templates` directory to the `/wp-content/plugins/` directory of your site.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==
* I found a bug in the plugin.
  * Please post it in the issues section of GitHub and we'll fix it as soon as we can. Thanks for helping. https://github.com/strangerstudios/pmpro-email-templates/issues

== Screenshots ==

1. The Email Templates admin page: Select the template to edit. Includes a list of template tags to include in your message body.

== Changelog ==
= .7.2 =
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

