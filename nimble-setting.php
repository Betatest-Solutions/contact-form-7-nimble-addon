<?php

/*
Plugin Name:  Contact Form 7 - Nimble Addon
Plugin URI: http://viktorixinnovative.com/app/contact-form-7-nimble-addon/
Description: This plugin integrates Contact Form 7 with Nimble CRM to automatically import leads on form submission.
Author: Viktorix Innovative
Author URI: http://viktorixinnovative.com/
Version: 0.4
License: GPL2 or Later

Copyright 2013 Viktorix Innovative (email: support@viktorixinnovative.com)
Copyright 2015 Okay Plus (email: joeydi@okaypl.us)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function nimble_add_pages() {
    add_menu_page('Nimble', 'Nimble', 'edit_pages', 'nimble', 'nimble_main_admin', plugins_url('/images/nimble.png', __FILE__));
    add_submenu_page( 'nimble', 'Mapping Fields', 'Mapping Fields', 'edit_pages', 'mapping-fields', 'nimble_mapping_fields');
}
add_action('admin_menu', 'nimble_add_pages');

function nimble_mapping_fields() {
    include('nimble_map_fields.php');
}

function nimble_main_admin() {
    if(!isset($_GET['action'])) {
       include('nimble_admin_setting.php');
    }
}

function contact7_nimble( $cfdata ) {
    if ( $submission = WPCF7_Submission::get_instance() ) {
        $formdata = $submission->get_posted_data();
    }

    require_once('api_nimble.php');
    $nimble = new NimbleAPI();

    try {
        $N_Fname        = get_option('N_Fname');
        $N_Lname        = stripslashes(get_option('N_Lname'));
        $N_email        = get_option('N_email');
        $N_phone_work   = get_option('N_phone_work');
        $N_phone_mobile = get_option('N_phone_mobile');
        $N_title        = get_option('N_title');

        $first_name     = isset( $formdata[$N_Fname] )          ? $formdata[$N_Fname]           : '';
        $last_name      = isset( $formdata[$N_Lname] )          ? $formdata[$N_Lname]           : '';
        $email          = isset( $formdata[$N_email] )          ? $formdata[$N_email]           : '';
        $phone_work     = isset( $formdata[$N_phone_work] )     ? $formdata[$N_phone_work]      : '';
        $phone_mobile   = isset( $formdata[$N_phone_mobile] )   ? $formdata[$N_phone_mobile]    : '';
        $title          = isset( $formdata[$N_title] )          ? $formdata[$N_title]           : '';

        $access_token = $nimble->nimble_refreshtoken_get_access_token();
        update_option('nimble_access_token', $access_token);

        $counter = get_option('nimble_refresh_token_counter');
        $counter += 1;
        update_option('nimble_refresh_token_counter', $counter);

        $response = $nimble->nimble_add_contact($first_name, $last_name, $email, $phone_work, $phone_mobile, $title);
    } catch(AWeberAPIException $exc) {

    }
}
add_action('wpcf7_mail_sent', 'contact7_nimble', 1);
