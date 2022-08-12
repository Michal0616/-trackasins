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
                    <h3>Basic Information and Profile</h3>
                </div>
                <div class="bottomContent col-lg-12">
                    <!-- FOR PROFILE PICTURE -->
                    <div class="profilePictureHolder">
                        <?php echo form_open_multipart('/settings/change_profile_picture', array('id' => 'settings_profile_pic_form')); ?>
                            <div class="topProfilePicHolder setting_index_profile">
                                <?php $profile_image = $this->user->GetInfo('users', 'profile_pic', $_SESSION['uid']);?>
                                <img id="profilePicHold" src="<?php if($profile_image != "default_pic.jpg"){ echo 'assets2/user_data/'.$profile_image; }
                                                                    else { echo base_url('assets2/user_data/default_pic.jpg'); } ?>" />
                            </div>
                            <div class="bottomProfilePicForm">
                                <input type="file" name="profilePicSelect" id="profilePicSelect" />
                                <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" id='profileLoading' value="Change Profile Picture" />
                                <a href='javascript:void(0)' class="btn btn-embossed btn-danger" id='removePP' <?php if($profile_image != "default_pic.jpg"){ echo 'style = "display: inline-block"'; } else {echo 'style = "display: none"';}?>>Remove Profile Pic</a>


                            </div>
                        <?php echo '</form>'; ?>
                    </div>
                    <!-- FOR BASIC INFORMATION -->
                    <div class="basicInfoCont col-lg-6" style="">
                        <?php echo form_open('settings/change_basic_information', array('id' => 'settings_change_basic_information', 'action' => '')); ?>
                            <div class="inputType">
                                <label>Full Name</label>
                                <input type="text" class="" id="sl_settings_firstname" name="sl_settings_firstname" placeholder="Full Name" value="<?php echo $this->user->GetInfo('users', 'fname', $_SESSION['uid']); ?>"/>
                            </div>
                            <!-- <div class="inputType">
                                <label>Last name</label>
                                <input type="text" class="" id="sl_settings_lastname" name="sl_settings_lastname" placeholder="Last name" value="<?php echo $this->user->GetInfo('users', 'lname', $_SESSION['uid']); ?>"/>
                            </div> -->
                            <div class="inputType">
                                <label>Email Address (For logging in, invoices and communication)</label>
                                <input type="email" class="" id="sl_settings_email" name="sl_settings_email" placeholder="Email Address" value="<?php /*echo $this->encryption->decryptText($this->user->GetInfo('users', 'email', $_SESSION['uid'])); */ echo $this->user->GetInfo('users', 'email', $_SESSION['uid']); ?>"/>
                            </div>
                            <div class="inputType">
                                <label>Company Name</label>
                                <input type="text" class="" id="sl_settings_company_name" name="sl_settings_fcompany_name" placeholder="Company Name" value="<?php echo $this->user->GetInfo('users', 'company', $_SESSION['uid']); ?>"/>
                            </div>
                            <!-- <div class="inputType">
                                <label>Merchant Token</label>
                                <input type="text" class="" id="sl_settings_seller_id" name="sl_settings_seller_id" placeholder="Merchant Token" value="<?php echo $this->user->GetInfo('users', 'sellerID', $_SESSION['uid']); ?>"/>
                            </div> -->
                            <div class="inputType">
                                <label>Phone Number</label>
                                <input type="tel" class="" id="sl_settings_phone_number" name="sl_settings_phone_number" placeholder="Phone Number" value="<?php echo $this->user->GetInfo('users', 'phone', $_SESSION['uid']); ?>"/>
                            </div>
                            <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Update Basic Information" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
