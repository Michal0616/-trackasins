<?php 
include('../Controllers/config.inc.php');
if($_REQUEST['email']!=""&&isset($_REQUEST['cpassword'])) {
 $email=$_REQUEST['email']; 
 if($_REQUEST['password']!=$_REQUEST['cpassword']) {
   echo '<script>window.location.href = "resetpassword.php?reset=failpwd";</script>';
		     
  exit();
 }
else {

$res=$connexion->query("SELECT * FROM users WHERE email='$email'");
  while($row=$res->fetch(PDO::FETCH_ASSOC)){
  $emailfinal=$row['email'];
  break;
  }
  if($emailfinal!=$email) {
     echo '<script>window.location.href = "resetpassword.php?reset=failmail";</script>';
  exit();
  }
  $password=md5($_REQUEST['password']);
  $connexion->exec("UPDATE users SET password='$password' WHERE email='$email'");
     echo '<script>window.location.href = "resetpassword.php?reset=success";</script>';
  
 }

  } 


?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<?php include ('header.php');?>
<link href="../assets/css/lock.css" rel="stylesheet" type="text/css"/>
<body>
<div class="page-lock">
  <div class="page-body">
    <div class="lock-head"> Reset Password </div>
    <div class="lock-body">
           
         <?php  
             if(isset($_REQUEST['reset'])&&$_REQUEST['reset']=='failpwd') {?>
			 <div class="alert alert-danger">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <strong>Whoops!</strong>
                      There were some problems with your input.
            <ul>  
              <li class="error">password confirmation failed!</li>
             </ul>
        </div>
			 
		  <?php } ?> 
		   <?php  
             if(isset($_REQUEST['reset'])&&$_REQUEST['reset']=='failmail') {?>
			 <div class="alert alert-danger">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <strong>Whoops!</strong>
                      There were some problems with your input.
            <ul>  
              <li class="error">Email confirmation failed!</li>
             </ul>
        </div>
			 
		  <?php } ?> 
		  <?php  
             if(isset($_REQUEST['reset'])&&$_REQUEST['reset']=='success') {?>
			 <div class="alert alert-success">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <strong>Success</strong>
                      
            <ul>  
              <li class="success">Your password was reset successfully!</li>
             </ul>
        </div>
			 
		  <?php } ?> 
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
          <input type="password" name="password" class="form-control" required placeholder="Password">   
        
		</div>
		  <div class="form-group">
          <input type="password" name="cpassword" class="form-control" required placeholder="Confirm Password">   
        
		</div>
        <div class="form-group form-actions">
           
			<button type="submit" class="btn btn-success uppercase" value="Reset Password">Reset Password</button> 
        </div>
		<input type="hidden" value="<?php  echo ((isset($_REQUEST["email"])) ? $_REQUEST["email"] : '');?>" name="email">
		
        </form>  
    </div>
    <div class="lock-bottom">
        <a class="btn btn-primary uppercase" href="login.php"><i class="fa fa-arrow-left"></i>  Back to Login</a>
    </div>
</div>
</div>