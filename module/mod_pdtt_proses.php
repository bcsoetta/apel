<?php echo getbread($mod,$DBcon); 
if(isset($_POST['sendata'])){
	$tglinput=date("Y-m-d H:i:s");
	$petugas=$_SESSION['userSession'];
	$insertdata=$DBcon->query("INSERT INTO pibk_ctl(header_seq_id, ctl_stat, ctl_time, user_id, flag_proses, uraian_proses) VALUES ('$_POST[headerid]', '$_POST[statuscek]','$tglinput','$petugas','1','$_POST[prosesdetail]')");
	$updatedata=$DBcon->query("UPDATE laststatus SET laststatus='$_POST[statuscek]',wakturekam='$tglinput' WHERE idlaststatus='$_POST[headerid]'");
	if($insertdata&&$updatedata){
		$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil diupload</div>";
		
	} else {
		$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal diupload</div>";

	}
}
?>

<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<form method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod; ?>">
			<div class="form-group col-md-3">
				<label for="srchawb">No HAWB.</label>
				<input type="text" class="form-control" autofocus name="srchawb">
			</div>
		</form>
	</div>
	<?php
	if(isset($_POST['srchawb'])){

		$resl=$DBcon->query("SELECT * FROM pibk_header WHERE no_hawb='$_POST[srchawb]' AND posisi='1' ");
		$jumlahdata=$resl->num_rows;
		
		if($jumlahdata>0){
			$i=0;
			while($data=$resl->fetch_array()){ ?>
				<div class="row" style="border-bottom:solid thin #191970">
					<h5><strong>RIWAYAT DOKUMEN HAWB:<?php echo $data['no_hawb'];?> TANGGAL:<?php echo $data['tgl_hawb'];?></strong></h5>
				</div>
				<div class="row bg-primary" style="border-bottom:solid thin #FFF5EE;">
					<div class="col-md-1">
						<p><small><strong>Nomor</strong></small></p>
					</div>
					<div class="col-md-4">
						<p><small><strong>Status</strong></small></p>
					</div>
					<div class="col-md-2">
						<p><small><strong>Waktu Status</strong></small></p>
					</div>
					<div class="col-md-3">
						<p><small><strong>Keterangan</strong></small></p>
					</div>
					<div class="col-md-2">
						<p><small><strong>Petugas</strong></small></p>
					</div>
				</div>

			<?php
				$nomor=1;
				$reslt=$DBcon->query("SELECT * FROM pibk_ctl WHERE header_seq_id='$data[idpibk_header]' ORDER BY ctl_seq_id DESC");
				while($datadet=$reslt->fetch_array()){ ?>
					<div class="row bg-info" style="border-bottom:solid thin #FFF5EE">
						<div class="col-md-1">
							<p><small><?php echo $nomor;?></small></p>
						</div>
						<div class="col-md-4">
							<p><small><?php echo getproses($datadet['ctl_stat'],$DBcon);?></small></p>
						</div>
						<div class="col-md-2">
							<p><small><?php echo getdateformatdmyhms($datadet['ctl_time']);?></small></p>
						</div>
						<div class="col-md-3">
							<p><small><?php echo $datadet['uraian_proses'];?></small></p>
						</div>
						<div class="col-md-2">
							<p><small><?php echo getnamauser($datadet['user_id'],$DBcon);?></small></p>
						</div>
					</div>			
			<?php
				$nomor++;	
				} 

				$queryfile=$DBcon->query("SELECT*FROM pibk_dokap WHERE idheader='$data[idpibk_header]'");
				$datafile=$queryfile->fetch_array();
				$filehref="dokap/".$datafile['namafile'];
				?>
				<div class="row" style="padding:5px;">
					<a class="btn btn-primary" target="_blank" href="<?php echo $filehref;?>">Buka Dokumen Pelengkap</a>
				</div>
			<?php


				$i++;
				$headdata[$i]=$data['idpibk_header'];
			}
		} else {
			echo "<div class='alert alert-warning alert-dismissible' role='alert'>
  					<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden=true'>&times;</span></button>
  					<strong>DATA TIDAK DITEMUKAN</strong>
					</div>";
		}

	};
	?>
</div>
