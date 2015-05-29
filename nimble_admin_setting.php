<?php
/*  Copyright 2013 Viktorix Innovative (email: support@viktorixinnovative.com)
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

$plugin_name = 'contact-form-7/wp-contact-form-7.php';
if ( !is_plugin_active($plugin_name) ) {
    echo '<br /><br /><div class="alert alert-error">Contact Form 7 plugin is required to configure this Nimble plugin. Please install and activate Contact Form 7 plugin and then return to this page.</div>';
    exit;
}

require_once('api_nimble.php');
$nimble = new NimbleAPI();
$updates = array();
$errors = array();

// Process Token Reset
if ( isset( $_POST['nimble_reset'] ) ) {
    update_option('nimble_access_token','');
    update_option('nimble_refresh_token','');
}

// Process Authentication
if ( isset( $_POST['nimble_authorize'] ) ) {
    update_option('nimble_client_id', $_POST['client_id']);
    update_option('nimble_client_secret', $_POST['client_secret']);
    update_option('nimble_redirect_uri', $_POST['redirect_uri']);

    if ( empty( $_POST['client_id'] ) ) {
        $errors[] = 'You must provide a valid Client ID';
    }

    if ( empty( $_POST['client_secret'] ) ) {
        $errors[] = 'You must provide a valid Client Secret';
    }

    if ( empty( $_POST['redirect_uri'] ) ) {
        $errors[] = 'You must provide a valid Redirect URI';
    }

    if ( empty( $errors ) ) {
        $handle = 'https://api.nimble.com/oauth/authorize?client_id=' . $_POST["client_id"] . '&redirect_uri=' . $_POST["redirect_uri"] . '&response_type=code';
        echo '<script type="text/javascript">  window.location = "' . $handle . '"  </script>';
    }
}

// Process Authentication Redirect
if ( isset( $_GET['code'] ) ) {
    $response_data = $nimble->nimble_get_access_token( $_GET['code'] );

    if ($response_data[0] == 200) {
        update_option('nimble_access_token', $response_data[1]->access_token);
        update_option('nimble_refresh_token', $response_data[1]->refresh_token);
        $updates[] = 'Nimble API settings have been sucessfully saved. Please map Nimble fields with Contact Form 7 fields to complete the integration process.';
    } else {
        $errors[] = sprintf( 'Error %s: %s', $response_data[1]->error, $response_data[1]->error_description );
    }
}

?>

<div class="wrap">

    <h2>Nimble App Connection</h2>

    <?php foreach ( $errors as $error ) : ?>
    <div class="error"><p><?php echo $error ?></p></div>
    <?php endforeach; ?>

    <?php foreach ( $updates as $update ) : ?>
    <div class="updated"><p><?php echo $update ?></p></div>
    <?php endforeach; ?>

    <?php if ( '' == get_option('nimble_access_token') || '' == get_option('nimble_refresh_token') ) : ?>

    <div class="updated">
        <p>You must request API credentials from Nimble, they have closed their developer center for now. <a href="http://support.nimble.com/customer/portal/articles/1194074-nimble-api-access#1" target="_blank">Get API credentials &raquo;</a></p>
    </div>

    <form name="nimbleform" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="client_id">Client ID</label></th>
                <td><input type="text" name="client_id" id="client_id" value="<?php echo get_option('nimble_client_id'); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="client_secret">Client Secret</label></th>
                <td><input type="text" name="client_secret" id="client_secret" value="<?php echo get_option('nimble_client_secret'); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="redirect_uri">Redirect URI</label></th>
                <?php
                    $redirect_uri = get_option('nimble_redirect_uri');
                    $redirect_uri = !empty( $redirect_uri ) ? $redirect_uri : 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                ?>
                <td><input type="text" name="redirect_uri" id="redirect_uri" value="<?php echo $redirect_uri; ?>" class="regular-text" /></td>
            </tr>
        </table>
        <p class="submit">
            <button type="submit" name="nimble_authorize" value="true" class="button button-primary">Authorize Connection &raquo;</button>
        </p>
    </form>

    <?php else : ?>

    <div class="error">
        <p>Resetting settings will disconnect plugin from your Nimble account.</p>
    </div>

    <table class="form-table">
        <tr>
            <th scope="row"><label for="client_id">Client ID</label></th>
            <td><input disabled="disabled" type="text" name="client_id" id="client_id" value="<?php echo get_option('nimble_client_id'); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="client_secret">Client Secret</label></th>
            <td><input disabled="disabled" type="text" name="client_secret" id="client_secret" value="<?php echo get_option('nimble_client_secret'); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="redirect_uri">Redirect URI</label></th>
            <?php
                $redirect_uri = get_option('nimble_redirect_uri');
                $redirect_uri = !empty( $redirect_uri ) ? $redirect_uri : 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            ?>
            <td><input disabled="disabled" type="text" name="redirect_uri" id="redirect_uri" value="<?php echo $redirect_uri; ?>" class="regular-text" /></td>
        </tr>
    </table>

    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <button type="submit" name="nimble_reset" value="true" class="button button-secondary">Reset Settings</button>
    </form>

    <?php endif; ?>

</div>
