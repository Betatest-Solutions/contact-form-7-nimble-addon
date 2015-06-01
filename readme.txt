=== Contact Form 7 - Nimble Addon ===

Contributors: joeydi, viktorixinnovative, asimgat

Author URI: http://okaypl.us

Plugin URI: http://okaypl.us

Tags: cf7, nimble, nimble crm, crm, contact form 7, form, leads

Requires at least: 3.7.0

Tested up to: 3.8.1

Stable tag: 0.4

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html



This CF7 addon integrates with Nimble CRM to automatically add leads to your account on form submission.



== Description ==

> *The plugin is currently in beta. Please take a second to report any issues you encounter either in our support forum or submit a bug report.*



Contact Form 7 - Nimble Addon plugin streamlines your lead generation process to help you import leads to your Nimble account automatically whenever CF7 form is submitted.

The plugin uses Nimble API to add a new lead record, it also checks to make sure the lead is new and will not create duplicate record.Note, this is an early beta release. Currently, the plugin will import the following fields:



* First Name

* Last Name

* Title

* Phone (work)

* Phone (mobile)

* Email

* NEW: Contact tags



We've included an easy to use field mapping interface to allow you to map your contact form fields with Nimble fields. Once you map fields, you must use the same form field tags throughout your forms to make sure information is being imported. For example, if you use `[first-name]` tag and that's what you used to map it to Nimble field, all forms must use `[first-name]` to import lead's first name.



**Usage Tip:** When your form submissions are emailed to the email connected with your Nimble, you will be able to view emails in the stream of the newly created lead. Any additional fields that were not imported, will be available in your email.



= To-do List =



* Create automatic task for each lead imported, to make sure leads are followed up with in a timely fashion

* Import fields as notes

* Add support for all other contact fields


== Installation ==


1. Upload plugin folder to the `/wp-content/plugins/` directory or simply search for the plugin and install it

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Get API credentials by sending an email to Nimble API team (http://support.nimble.com/customer/portal/articles/1194074-nimble-api-access#1)



== Frequently Asked Questions ==


= How many contact tags can I add? =



Nimble API only allows 5 tags to be added to the contact through API.



== Screenshots ==



1. Nimble connection authorization

2. Nimble and CF7 mapping fields page

3. Nimble account authentication

== Changelog ==


= 0.5 =

* Fixed bug related to change in CF7 API


= 0.4 =

* NEW: Add Contact tags

* Updated UI to match WordPress



= 0.3 =

* Fixed Nimble API issue



= 0.2.3 =

* Fixed menu position to prevent it from hiding Comments menu



= 0.2.2 = 

* Fixed stylesheet issue.



= 0.2.1 =

* Used plugin_url to fix broken paths.



= 0.2 =

* Fixed readme file.



= 0.1 =

* Initial Release.



== Upgrade Notice ==



= 0.4 =

* Upgrade to add contact tags and fix few minor issues.
