<?php
//session_start();
//if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
//
//
//    $res = $connexion->query("SELECT * FROM users WHERE username='" . $_POST['username'] . "'");
//
//    if ($res->fetchColumn() > 0) {
//        echo '<script>window.location.href = "register.php?signup=failusername";</script>';
//    }
//    if ($_POST['password'] != $_POST['cpassword']) {
//        echo '<script>window.location.href = "register.php?signup=failpwd";</script>';
//    }
//    $password = md5($_POST['password']);
//    $connexion->exec("INSERT INTO users(username,password,email) values('" . $_POST['username'] . "','" . $password . "','" . $_POST['email'] . "')");
//
//    echo '<script>window.location.href = "register.php?signup=success";</script>';
//}
?>



<link href="<?= base_url() ?>assets/css/lock.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertify/alertify.core.css" />
<!-- include a theme, can be included into the core instead of 2 separate files -->
<link rel="stylesheet" href="<?= base_url() ?>assets/alertify/alertify.default.css" />

<script src="<?= base_url() ?>assets/alertify/alertify.min.js"></script>
<body>
    <div class="page-lock">
        <div class="page-body">
            <div class="page-body">
                <div class="lock-head"> New User Registration </div>
                <div class="lock-body">
                    <?php if (strlen(validation_errors())) { ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                            <strong>Whoops!</strong>
                            There were some problems with your input.
                            <!--<ul>-->  
                                <?= validation_errors() ?>
                            <!--</ul>-->
                        </div>

<?php } ?> 
                    
                    <?php if (isset($done)) { ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                            <strong>Success</strong>

                            <ul>  
                                <li class="success">Your account was created successfully!</li>
                            </ul>
                        </div>

<?php } ?> 

                    <form action="" method="POST">
                        <div class="form-group">

                            <input type="text" name="username" value="<?php if (!isset($done)) echo set_value("username"); ?>" id="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group">

                            <input type="password" name="passconf" id="cpassword" class="form-control" placeholder="Confirm Password" required>

                        </div>
                        <div class="form-group">
                            <input type="email" name="email" value="<?php if (!isset($done)) echo set_value("email"); ?>" id="email" class="form-control" placeholder="Email" required>
                        </div>

                        <div class="form-group form-actions">

                            <button type="submit" class="btn btn-success uppercase" name="register">Register</button>
                        </div>

                    </form>
                </div>
                <div class="lock-bottom">
                    <a class="btn btn-primary uppercase" href="<?php echo base_url()?>index.php/Welcome/index"><i class="fa fa-arrow-left"></i>  Back to Login</a>
                </div>
            </div>

        </div>
    </div>

</body>


