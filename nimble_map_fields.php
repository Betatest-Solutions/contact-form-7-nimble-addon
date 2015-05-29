<?php

/*  Copyright 2014 Viktorix Innovative  (email : support@viktorixinnovative.com)
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

$updates = array();

cf7_plugin_check();

if ( isset( $_POST['bcpl_hidden'] ) && $_POST['bcpl_hidden'] == 'Y' ) {
    $N_Fname = $_POST['N_Fname'];
    update_option('N_Fname', $N_Fname);

    $CHKN_Fname = isset($_POST['CHKN_Fname']) ? $_POST['CHKN_Fname'] : '';
    update_option('CHKN_Fname', $CHKN_Fname);

    $N_Lname = $_POST['N_Lname'];
    update_option('N_Lname', $N_Lname);

    $CHKN_Lname = isset($_POST['CHKN_Lname']) ? $_POST['CHKN_Lname'] : '';
    update_option('CHKN_Lname', $CHKN_Lname );

    $N_title = $_POST['N_title'];
    update_option('N_title', $N_title);

    $CHKN_title = isset($_POST['CHKN_title']) ? $_POST['CHKN_title'] : '';
    update_option('CHKN_title', $CHKN_title);

    $N_phone_work = $_POST['N_phone_work'];
    update_option('N_phone_work', $N_phone_work);

    $CHKN_phone_work = isset($_POST['CHKN_phone_work']) ? $_POST['CHKN_phone_work'] : '';
    update_option('CHKN_phone_work', $CHKN_phone_work);

    $N_phone_mobile = $_POST['N_phone_mobile'];
    update_option('N_phone_mobile', $N_phone_mobile );

    $CHKN_phone_mobile = isset($_POST['CHKN_phone_mobile']) ? $_POST['CHKN_phone_mobile'] : '';
    update_option('CHKN_phone_mobile', $CHKN_phone_mobile);

    $N_email  = $_POST['N_email'];
    update_option('N_email',$N_email );

    $CHKN_email = isset($_POST['CHKN_email']) ? $_POST['CHKN_email'] : '';
    update_option('CHKN_email', $CHKN_email);

    $CHKN_support = isset($_POST['CHKN_support']) ? $_POST['CHKN_support'] : '';
    update_option('CHKN_support', $CHKN_support);

    $N_tags = $_POST['N_tags'];
    update_option('N_tags', $N_tags);

    $updates[] = '<strong>Options saved.</strong>';
} else {
    $N_Fname = get_option('N_Fname');
    $CHKN_Fname = get_option('CHKN_Fname');

    $N_Lname = stripslashes(get_option('N_Lname'));
    $CHKN_Lname = get_option('CHKN_Lname');

    $N_title = get_option('N_title');
    $CHKN_title = get_option('CHKN_title');

    $N_phone_work = get_option('N_phone_work');
    $CHKN_phone_work = get_option('CHKN_phone_work');

    $N_phone_mobile = get_option('N_phone_mobile');
    $CHKN_phone_mobile = get_option('CHKN_phone_mobile');

    $N_email = get_option('N_email');
    $CHKN_email = get_option('CHKN_email');

    $CHKN_support = get_option('CHKN_support');

    $N_tags = get_option('N_tags');
}

?>

<div class="wrap">
    <h2>Nimble Mapping Fields</h2>

    <?php foreach ( $updates as $update ) : ?>
    <div class="updated"><p><?php echo $update ?></p></div>
    <?php endforeach; ?>

    <form name="bcpl_form" method="post" action="<?php echo admin_url( 'admin.php?page=mapping-fields' ); ?>">
        <table class="form-table">
            <tr>
                <th>Nimble Fields</th>
                <th>Contact Form 7 Fields <small>(Without square brackets)</small></th>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_Fname" <?php echo $CHKN_Fname == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_Fname">First Name</label>
                </th>
                <td><input type="text" name="N_Fname" id="N_Fname" value="<?php echo $N_Fname; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_Lname" <?php echo $CHKN_Lname == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_Lname">Last Name</label>
                </th>
                <td><input type="text" name="N_Lname" id="N_Lname" value="<?php echo $N_Lname; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_title" <?php echo $CHKN_title == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_title">Title</label>
                </th>
                <td><input type="text" name="N_title" id="N_title" value="<?php echo $N_title; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_phone_work" <?php echo $CHKN_phone_work == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_phone_work">Phone (work)</label>
                </th>
                <td><input type="text" name="N_phone_work" id="N_phone_work" value="<?php echo $N_phone_work; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_phone_mobile" <?php echo $CHKN_phone_mobile == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_phone_mobile">Phone (mobile)</label>
                </th>
                <td><input type="text" name="N_phone_mobile" id="N_phone_mobile" value="<?php echo $N_phone_mobile; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row">
                    <input type="checkbox" name="CHKN_email" <?php echo $CHKN_email == 'on' ? 'checked="checked"' : ''; ?>/>
                    <label for="N_email">Email</label>
                </th>
                <td><input type="text" name="N_email" id="N_email" value="<?php echo $N_email; ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>Contact Tags</h3>
                    <p><small>Add up to 5 tags for your contacts, separated with comma. For example, website lead. In Nimble, you can view all leads from your website using this tag.</small></p>
                    <input type="text" name="N_tags" value="<?php echo $N_tags; ?>" size="40" />
                </td>
            </tr>
        </table>

        <input type="hidden" name="bcpl_hidden" value="Y" />
        <p class="submit">
            <input type="submit" class="button button-primary" name="Submit" value="Save Changes" />
        </p>
    </form>
</div>

<div class="info-box" style="border-top: 1px solid #ccc; padding-top:10px;width:620px;">
    <a href="http://twitter.com/vxhq" class="button button-secondary" target="_blank">Follow @vxhq</a>
    <a href="http://viktorixinnovative.com/track/support-cf7-nimble" class="button button-secondary" target="_blank">Get Support</a>
    <a href="http://viktorixinnovative.com/track/bug-cf7-nimble" class="button button-secondary" target="_blank">Report a Bug</a>
    <a href="http://www.projectarmy.net" class="button button-primary" target="_blank">Need WordPress help?</a>
</div>
