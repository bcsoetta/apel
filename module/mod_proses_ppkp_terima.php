<?php
	$i=0;
    $count=0;
    $nobatch=date("YmdHis");
    $tglinput=date("Y-m-d H:i:s");
    $petugas=$_SESSION['userSession'];

    foreach($_POST['flagproses'] as $value){        
        $exec_status=$DBcon->query("INSERT INTO tbl_batch_proses_ppkp (idbatch,idheader,waktu,idpetugas) VALUES ('$nobatch','$value','$tglinput','$petugas')");
        $exec_ctl=$DBcon->query("INSERT INTO ppkp_ctl(pc_header_id, pc_stat, pc_time, pc_user_id, pc_flag_proses) VALUES ($value, '1','$tglinput','$petugas','1')");
        $exec_induk=$DBcon->query("UPDATE ppkp_header SET ppkp_status='1' WHERE ppkp_id='$value'");
        $updatelaststat=$DBcon->query("INSERT INTO ppkplaststatus(idlaststatus,laststatus,wakturekam) VALUES('$value','1','$tglinput')");
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
		<form method="post" target="_blank" action="printbarppkp.php">
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
		<a class="btn btn-primary" href="?mod=28" role="button">Kembali ke data baru</a>
	</div>
</div>