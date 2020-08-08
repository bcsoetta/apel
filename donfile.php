<?php
error_reporting(E_ALL);
ini_set('display_error',1);
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
$query=$DBcon->query("SELECT*FROM pibk_dokap WHERE idheader='$_GET[id]'");
$data=$query->fetch_array();
$file="dokap/".$data['namafile'];
if(! file_exists($file)) die("$file doesnt exist");
if(! is_readable($file)) die("$file doesnt readable");

$filename=gethouse($_GET['id'],$DBcon).".pdf";
header('Cache-Control: public');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' .$filename. '"');
header('Content-Length: '.filesize($file));

readfile($file);
?>