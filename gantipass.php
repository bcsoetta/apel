<?php
include "konfigurasi/koneksi.php";
$upass='1234'; 
$hashed_password = password_hash($upass, PASSWORD_DEFAULT);
$query = "UPDATE tbl_user SET password='$hashed_password' WHERE id=1";
$DBcon->query($query);