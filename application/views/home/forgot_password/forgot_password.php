<link href="<?php echo base_url('assets2/css/forgot_password.css'); ?>" rel="stylesheet">
<div class='mainForgotPassCont'>
    <!-- Login Cont -->
    <div class="ForgotPassSec card card-default card-center col-lg-3 col-md-3 col-sm-4 col-xs-10 clearfix">
        <div class='topHeadOfCard col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
            <h3 class='col-lg-12 col-md-12 col-sm-12 col-xs-12 '>Forgot Password</h3>
            <p style="font-weight:500;">Enter your email address and we will send you a password reset link</p>
        </div>
        <div class='middleCardContent col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
            <?php echo form_open('forgot_password/requestPasswordReset', array('id' => 'forgot_password_form', 'action' => '')); ?>
                <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="email" id="sl_user_or_email" class="col-lg-6" name="sl_user_or_email" placeholder="Email address">
                </div>
                <div class='wayBottom'>
                    <input type='submit' class='btn btn-embossed btn-wide' value='Recover Password' />
                    <p>
                        <a href="<?php echo base_url(); ?>" style="color: #b65f2b;">Back to login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>