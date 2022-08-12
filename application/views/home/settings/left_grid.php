<?php $current_url = current_url();?>
<div class="settingsNav col-lg-3 col-md-4 col-xs-12 col-sm-12 pull-left">
    <div class="innerNav card card-default">
        <div class="topCard">
            <h3>Navigation:</h3>
        </div>
        <div class="innerNav">
            <ul class="nav">
                <li <?php if($current_url == base_url('/profile')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>profile">Company Information and Profile</a></li>
                <li <?php if($current_url == base_url('/profile/membership')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>profile/membership">Membership and Account</a></li>
                <li <?php if($current_url == base_url('/profile/notifications')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>profile/notifications">Notification Settings</a></li>
                <li <?php if($current_url == base_url('/profile/password')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>profile/password">Password Reset</a></li>
            </ul>
        </div>
    </div>
</div>
