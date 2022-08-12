<!-- Main login container -->
<div class='mainWrapper container-fluid'>
    <div class='container trueWrap'>
        <div class='row'>
            <div class='animated fadeIn col-lg-5 col-md-5 col-sm-6 col-xs-12 col-md-offset-1 card card-default RegisterSec pull-left clearfix' id='RegisterSection'>
                <!--Code remove from here-->
                <div class='topHeadOfCard col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
                    <h3 class='pull-left col-lg-6 col-md-6 col-sm-6'>Sign Up</h3>
                    <h3 class='pull-right col-lg-6 col-md-6 col-sm-6'>14 days free!</h3>
                </div>
                <div class='middleCardContent'>
                   <form action="" method="post">
                <?php if(isset($msg)){ ?>
                <div class="col-lg-6">
                    <span style="color:red"><?php echo$msg; ?></span>
                 </div>
                 <?php  }?>
                    <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="sl_firstname_signup" class="" name="sl_firstname_signup" placeholder="First and middle name" required>
                            <?php echo form_error('sl_firstname_signup');?>
                        </div>
                        <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="sl_lastname_signup" class="" name="sl_lastname_signup" placeholder="Last Name" required>
                        </div>
                        <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="sl_company_name_signup" class="" name="sl_company_name_signup" placeholder="Company Name" required>
                        </div>
                        <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="sl_seller_id_signup" class="" name="sl_seller_id_signup" placeholder="Seller ID (Must match with amazon seller name)" required>
                        </div>
                        <div class="topSideBySide">
                            <div class="inputType f col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left">
                                <input type="text" class="" id="ta_email_signup" name="ta_email_signup" placeholder="Email" required />
                            </div>
                            <div class="inputType l col-lg-6 col-md-6 col-sm-6 col-xs-6 col-lg-6 pull-right">
                                <input type="text" class="" id="ta_email_confirm_signup" name="ta_email_confirm_signup" placeholder="Re-enter email address" required />
                                <div class="civ forEmail">
                                    <span class='hiddenT toge forEmailS green'><i class="fa fa-check" aria-hidden="true"></i></span>
                                    <span class='hiddenT toge forEmailE red'><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="topSideBySide">
                            <div class="inputType f col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left">
                                <input type="password" class="" id="ta_password_signup" name="ta_password_signup" placeholder="Password" required />
                            </div>
                            <div class="inputType l col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-right">
                                <input type="password" class="" id="ta_password_confirm_signup" name="ta_password_confirm_signup" placeholder="Re-enter password" required />
                                <div class="civ forPassword">
                                    <span class='hiddenT togp forPasswordS green'><i class="fa fa-check" aria-hidden="true"></i></span>
                                    <span class='hiddenT togp forPasswordE red'><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="" id="sl_phone_number_signup" name="sl_phone_number" placeholder="Phone Number" required />
                        </div>
                        <div class='wayBottom'>
                            <p class='terms col-lg-12'>Creating an account means you agree with our <a class="primarycolor" href="<?php echo base_url(); ?>help/terms" target="_blank">Term of Service</a> and <a class="primarycolor" href="<?php echo base_url(); ?>help/policies" target="_blank">Privacy Policy</a></p>
                            <input type='submit' name="signup" class='btn btn-embossed btn-wide' value='Sign Up' />
                        </div>
                    </form><!--Registration form end-->
                </div>
            </div>
            <div class='animated fadeIn col-lg-5 col-md-5 col-sm-5 col-xs-12 col-md-offset-1 card card-default LoginSec pull-right' id="LoginSection">
                <div class='topHeadOfCard col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
                    <h3 class='col-lg-12 col-md-12 col-sm-12 col-xs-12 ' style="padding-bottom: 0px;">Login</h3>
                </div>
                <div class='middleCardContent col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix'>
                <!--login form -->
                <form action="" method="post">

                 <?php if(isset($msg1)){ ?>
                <div class="col-lg-6">
                    <span style="color:red"><?php echo$msg1; ?></span>
                 </div>
                 <?php  }?>
                   <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <input type="email" id="sl_email_login" class="col-lg-6" name="sl_email_login" placeholder="Email address" required>
                            <?php echo form_error('sl_email_login');?>
                        </div>
                        <div class="inputType col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="password" id="sl_password_login" class="col-lg-6" name="sl_password_login" placeholder="Password" required>
                            <?php echo form_error('sl_password_login');?>
                        </div>
                        <div class="col-md-offset-1 col-md-11 col-sm-11 col-xs-11" style="padding-left: 0px;margin-left: 0px;">
                            <div class="checkbox" >
                                <div class="c-hold" style="float: left;">
                                    <input type='checkbox' value='' data-val="true" id='RememberMe' style="margin: 0px;"/>
                                    <label for='RememberMe' class='cb-label' style="padding-left: 0px;"></label>
                                </div>
                                <label for="RememberMe" style="margin-top: 5px;">Remember me?</label>
                            </div>
                        </div>
                        <div class='wayBottom'>
                            <input type='submit' class='btn btn-embossed btn-wide' name="login" value='Login' />
                            <p>
                                <a class="primarycolor" href="<?php echo base_url(); ?>forgot_password">Forgot your password?</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
