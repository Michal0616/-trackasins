<link href="<?php echo base_url('assets2/css/forgot_password.css'); ?>" rel="stylesheet">
<div class='mainForgotPassCont'>
    <!-- Login Cont -->
    <div class="ForgotPassSec card card-default card-center col-lg-3 col-md-3 col-sm-4 col-xs-10 clearfix">
        <div class='topHeadOfCard col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
            <h3 class='col-lg-12 col-md-12 col-sm-12 col-xs-12 '>Change Password</h3>
            <p>Please enter a new password</p>
        </div>
        <div class='middleCardContent col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
            <?php echo form_open('forgot_password/changePassProcess', array('id' => 'change_password_form', 'action' => '')); ?>
            <input type="hidden" name="request_id" id="request_id" value="<?php echo $request_id; ?>" style="display:none;">
            <input type="hidden" name="unique_id" id="unique_id" value="<?php echo $unique_id; ?>" style="display:none;">

            <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="password" id="sl_password" class="col-lg-6" name="sl_password" placeholder="New Password">
            </div>
            <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="password" id="sl_password_confirm" class="col-lg-6" name="sl_password_confirm" placeholder="Confirm New Password">
            </div>
            <div class='wayBottom'>
                <input type='submit' class='btn btn-embossed btn-wide' value='Change Password' />
                <p>
                    <a href="<?php echo base_url(); ?>"  style="color: #b65f2b;">Back to login</a>
                </p>
            </div>
            </form>
        </div>
    </div>
</div>