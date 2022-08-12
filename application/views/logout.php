<?php
session_start();
session_destroy();
echo '<font color="blue">You are logged out. Redirecting to login page ..</font>';
sleep(2);  
 echo "<script>document.location.href='login.php'</script>";
?>