<?php
session_start();
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
require_once("konfigurasi/menu.func.php");
error_reporting( error_reporting() & ~E_NOTICE );

if (!isset($_SESSION['userSession'])) {
 header("Location: index.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3000)) {
    session_destroy();
    unset($_SESSION['userSession']);
    header("Location: index.php");
}

$_SESSION['LAST_ACTIVITY'] = time();

$query = $DBcon->query("SELECT * FROM tbl_user WHERE id='$_SESSION[userSession]'");
$userRow=$query->fetch_array();

if(isset($_GET['mod'])){ $mod=$_GET['mod']; }
else if (isset($_POST['mod'])) { $mod=$_POST['mod']; }
else {$mod="99"; };
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome - <?php echo $userRow['nip']; ?></title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="bootstrap/css/bootstrap-datepicker.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/npdstyle.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">CN - PIBK</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
              <?php echo menu_get($_SESSION['levelSession'],$DBcon);?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="?mod=98" data-toggle="tooltip" title="Ubah Password"><span class="glyphicon glyphicon-user"></span>&nbsp; <?php echo $userRow['nama']; ?></a></li>
            <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="row"><h3>&nbsp;</h3></div>
    <?php 
    $cekHakAkses=authlevelpage($_SESSION['levelSession'],$mod,$DBcon);
    if($cekHakAkses==1){
      include "module/".getfilemenu($mod,$DBcon);
    }else{
      include "module/".getfilemenu(99,$DBcon);
    }
    ?>
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-datepicker.js"></script>
    <link href="css/editor.css" type="text/css" rel="stylesheet"/>
    <script src="js/editor.js"></script>
    <script>
      $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
      });
    </script>
    <?php
    $cekjs=cekfilejs($mod,$DBcon);
    if($cekjs==1){
      $filejs=getfilejs($mod,$DBcon);
      echo "<script src='$filejs'></script>";
    }
    ?>
  </body>
</html>