<div class="settingsHeadline headline headline-site-color">
    <div class="container-fluid inner">
        <div class="topHeadline container text-center">
            <h3>Settings</h3>
        </div>
    </div>
</div>
<div class="mainSettingsCont">
    <div class="container mainSettingCont">
        <?php include('left_grid.php');?>
        <div class="settingsMainCont col-lg-9 col-sm-12 pull-right">
            <div class="innerCont card card-default clearfix">
                <div class="topHeader col-lg-12">
                    <h3>Change Password</h3>
                </div>
                <div class="bottomContent col-lg-12">
                    <!-- FOR CHANGING PASSWORDS -->
                    <div class="changePasswordHolder">
                        <?php echo form_open('settings/change_password/changePasswordProcess', array('id' => 'settings_change_password_form', 'action' => '')); ?>
                        <div class="inputType">
                            <label>Current password</label>
                            <input type="password" class="" id="sl_settings_current_password" name="sl_settings_current_password" placeholder="Current Password" />
                        </div>
                        <div class="inputType">
                            <label>New password</label>
                            <input type="password" class="" id="sl_settings_new_password" name="sl_settings_new_password" placeholder="New Password" />
                        </div>
                        <div class="inputType">
                            <label>Confirm new password</label>
                            <input type="password" class="" id="sl_settings_confirm_new_password" name="sl_settings_confirm_new_password" placeholder="New Password" />
                        </div>
                        <div class="bottomProfilePicForm">
                            <input type="submit" class="btn btn-embossed btn-success" value="Change Password" />
                        </div>
                        <?php echo '</form>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>