<?php
	$i=0;
    $count=0;
    $nobatch=date("YmdHis");
    $tglinput=date("Y-m-d H:i:s");
    $petugas=$_SESSION['userSession'];

    foreach($_POST['flagproses'] as $value){        
        $exec_status=$DBcon->query("INSERT INTO tbl_batch_proses (idbatch,idheader,waktu,idpetugas) VALUES ('$nobatch','$value','$tglinput','$petugas')");
        $exec_ctl=$DBcon->query("INSERT INTO pibk_ctl(header_seq_id, ctl_stat, ctl_time, user_id, flag_proses) VALUES ($value, '1','$tglinput','$petugas','1')");
        $exec_induk=$DBcon->query("UPDATE pibk_header SET posisi='1' WHERE idpibk_header='$value'");
        $updatelaststat=$DBcon->query("INSERT INTO laststatus(idlaststatus,laststatus,wakturekam) VALUES('$value','1','$tglinput')");
        $i++;
        $count++;
        if($exec_status&&$exec_ctl&&$exec_induk&&$updatelaststat){ $out=1; };
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
		<a class="btn btn-primary" href="?mod=11" role="button">Kembali ke data baru</a>
	</div>
</div>