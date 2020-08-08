<?php

session_start();

require_once 'konfigurasi/koneksi.php';

if (isset($_SESSION['userSession'])!="") {
  header("Location: main.php?mod=97");
  exit;
}

if (isset($_POST['btn-login'])) {
 
  $username     = strip_tags($_POST['username']);
  $password     = strip_tags($_POST['password']);
 
  $username     = $DBcon->real_escape_string($username);
  $password     = $DBcon->real_escape_string($password);
 
  $query        = $DBcon->query("SELECT * FROM tbl_user WHERE nip='$username' and aktif='Y'");
  $row          = $query->fetch_array();
 
  $count        = $query->num_rows;
 
  if (password_verify($password, $row['password']) && $count==1) {
    $_SESSION['userSession']  = $row['id'];
    $_SESSION['levelSession'] = $row['level'];
    header("Location: main.php?mod=97");
  } else {
    $msg = "<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span> &nbsp; Username / Password tidak valid !";
    $msg.= "</div>";
  }
  $DBcon->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Monitoring PIBK</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
    <link rel="stylesheet" href="css/style.css" type="text/css" />
  </head>
  <body>
    <div class="signin-form">
      <div class="container">
        <form class="form-signin" method="post" id="login-form">
          <h4 class="form-signin-heading">Silahkan Login</h4><hr />     
            <?php
            if(isset($msg)){
              echo $msg;
            }
            ?>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Username" name="username" required />
              <span id="check-e"></span>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Password" name="password" required />
            </div>
            <hr />
            <div class="form-group">
              <button type="submit" class="btn btn-primary" name="btn-login" id="btn-login">
              <span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In
              </button> 
            </div>  
        </form>
      </div>
    </div>
  </body>
</html>