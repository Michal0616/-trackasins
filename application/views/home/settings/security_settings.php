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
        <div class="settingsMainCont col-lg-9 col-md-8 col-xs-12 col-sm-12 pull-right">
            <div class="innerCont card card-default clearfix">
                <div class="topHeader col-lg-12">
                    <h3>Password Reset</h3>
                </div>
               <!--   <div class="bottomContent col-lg-12">
                    <!-- FOR CHANGING PASSWORDS -->
                   <div class="changeLoginEmaileHolder">
                        <?php //echo form_open('settings/change_security_settings', array('id' => 'settings_security_settings_form', 'action' => '')); ?>
<!--                        <div class="inputType">-->
<!--                            <label>Two Factor</label>-->
<!--                            <div class="checkbox-type">-->
<!--                                <input type="checkbox" data-checked="false" id="amazon_connection"/>-->
<!--                                <label for="amazon_connection" class="checkb2"></label>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="inputType">-->
<!--                            <label>Phone Number</label>-->
<!--                            <input type="text" class="" id="sl_settings_phone_number" name="sl_settings_phone_number" placeholder="Phone Number"  value="--><?php // echo $this->user->GetInfo('users', 'phone', $_SESSION['uid']);?><!--"/>-->
<!--                        </div>
                        <div class="inputType">
                            <label>Current login email</label>
                            <input type="email" id="sl_current_login_email" name="sl_current_login_email" placeholder="Current login email" required>
                         </div>
                        <div class="inputType">
                            <label>New login email</label>
                            <input type="email" id="sl_new_login_email" name="sl_new_login_email" placeholder="New login email" required>
                        </div>
                        <div class="inputType">
                            <label>Confirm new login email</label>
                            <input type="email" id="sl_confirm_new_login_email" name="sl_confirm_new_login_email" placeholder="Confirm new login email" required>
                        </div>
<!--                        <div class="inputType">-->
<!--                            <label>Two factor and login email</label>-->
<!--                            <input type="email" class="" id="sl_settings_email" name="sl_settings_email" placeholder="Email" value="--><?php // echo $this->user->GetInfo('users', 'email', $_SESSION['uid']);?><!--" />-->
<!--                        </div>-->
<!--                        <div class="inputType">-->
<!--                            <label>Browser remembered two factor</label>-->
<!--                            <div class="checkbox-type">-->
<!--                                <input type="checkbox" data-checked="false" id="amazon_connection_browser" />-->
<!--                                <label for="amazon_connection_browser" class="checkb2"></label>-->
<!--                            </div>-->
<!--                        </div>
                        <div class="bottomProfilePicForm">
                            <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Update Information" />
                        </div>
                        <?php //echo '</form>'; ?>
                    </div>-->
                    <div class="basicInfoCont col-lg-12" style="padding-left: 0px;padding-right: 0px;margin-top: 25px;">
                        <!-- FOR CHANGING PASSWORDS -->
                        <div class="changePasswordHolder">
                            <?php echo form_open('settings/change_password/changePasswordProcess', array('id' => 'settings_change_password_form', 'action' => '')); ?>
                            <div class="inputType">
                                <label>Current Password</label>
                                <input type="password" class="" id="sl_settings_current_password" name="sl_settings_current_password" placeholder="Current Password" />
                            </div>
                            <div class="inputType">
                                <label>New Password</label>
                                <input type="password" class="" id="sl_settings_new_password" name="sl_settings_new_password" placeholder="New Password" />
                            </div>
                            <div class="inputType">
                                <label>Confirm New Password</label>
                                <input type="password" class="" id="sl_settings_confirm_new_password" name="sl_settings_confirm_new_password" placeholder="New Password" />
                            </div>
                            <div class="bottomProfilePicForm">
                                <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Change Password" />
                            </div>
                            <?php echo '</form>'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
