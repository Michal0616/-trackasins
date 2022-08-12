    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
        <!--<![endif]-->
        <!-- BEGIN HEAD -->
        <?php  include ('header.php'); ?>
        <link href="<?= base_url() ?>assets/css/lock.css" rel="stylesheet" type="text/css"/>
        <body>
            <div class="page-lock">
                <div class="page-body">
                    <div class="lock-head">
                        Login
                    </div>
                    <div class="lock-body">
                        <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                                <strong>Whoops!</strong>
                                There were some problems with your input.
                                <ul>
                                    <li class="error">These credentials do not match our records.</li>
                                </ul>
                            </div>

                        <?php } ?>

                        <form  action=" " method="POST">
                            <div class="form-group">

                                <input type="text" name="username" class="form-control" required placeholder="Username">
                            </div>
                            <div class="form-group">

                                <input type="password" name="password" class="form-control" required placeholder="Password">

                            </div>
                            <div class="form-group form-actions">

                                <button type="submit" name="login" class="btn btn-success uppercase" value="sign in">sign in</button> 
                            </div>
                        </form>
                    </div>
                    <div class="lock-bottom">  
                        <a class="pull-left" href="">Forgot Password?</a>
                        <a class="pull-right" href="<?php echo base_url();?>index.php/Register/index/">Register</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    