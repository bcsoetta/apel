<?php
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
$modal_title='Catatan NPD';
$reslt=$DBcon->query("SELECT * FROM npd_catatan WHERE id='$_POST[id]'");
$datadet=$reslt->fetch_array();
$tampil=stripslashes($datadet['catatan']);
$modal_content=$tampil;
echo json_encode(array('judul'=>$modal_title,'isi'=>$modal_content));
?>