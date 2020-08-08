<?php
	$i=0;
    $count=0;
    $nobatch=date("YmdHis");
    $tglinput=date("Y-m-d H:i:s");
    $petugas=$_SESSION['userSession'];

    foreach($_POST['flagproses'] as $value){        
        $exec_status=$DBcon->query("INSERT INTO tbl_batch_proses_respon_npd (idbatch,idheader,waktu,idpetugas) VALUES ('$nobatch','$value','$tglinput','$petugas')");
        $exec_ctl=$DBcon->query("INSERT INTO npd_ctrl(npdc_id_header, npdc_status, npd_uraian, npd_flag_file, npd_file_name,npdc_waktu,npdc_petugas) VALUES ('$value', '6','Data dikirim','N','None','$respon_waktu','$petugas')");
        $updatelaststat=$DBcon->query("UPDATE npd_ctrl SET npdc_last='6' WHERE npdc_id_header='$_POST[idproses]'");
        $exec_thead=$DBcon->query("UPDATE npd_header SET npdh_last='6' WHERE npdh_id_header='$_POST[idproses]'");
        $i++;
        $count++;
        if($exec_status&&$exec_ctl&&$updatelaststat&&$exec_thead){ $out=1; };
   	}

?>

<div class="container">
	<div class="row text-center">
		<h4><?php echo $count; ?> DATA BERHASIL DIPROSES,</h4>
	</div>
	<div class="row text-center">
		<form method="post" target="_blank" action="printbar.php">
			<input type="hidden" name="jumlah" value="<?php echo $count;?>">
		<?php
			foreach($_POST['flagproses'] as $value){ ?>
			<input type="hidden" name="bartoprint[]" value="<?php echo $value;?>">
		<?php	
		}
		?>
		<div class="form-group col-md-4 col-md-offset-4">
			<input type="submit" class="form-control btn btn-primary" value="Print Barcode">
		</div>
		</form>
	</div>
	<div class="row text-center">
		<a class="btn btn-primary" href="?mod=38" role="button">Kembali ke data baru</a>
	</div>
</div>