<?php echo getbread($mod,$DBcon); 
if(isset($_POST['sendata'])){
	$tglinput=date("Y-m-d H:i:s");
	$petugas=$_SESSION['userSession'];
	$insertdata=$DBcon->query("INSERT INTO ppkp_ctl(pc_header_id, pc_stat, pc_time, pc_user_id, pc_flag_proses, pc_uraian_proses) VALUES ('$_POST[headerid]', '$_POST[statuscek]','$tglinput','$petugas','1','$_POST[prosesdetail]')");
	$updatedata=$DBcon->query("UPDATE ppkplaststatus SET laststatus='$_POST[statuscek]',wakturekam='$tglinput' WHERE idlaststatus='$_POST[headerid]'");
	if($insertdata&&$updatedata){
		$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil disimpan</div>";

	} else {
		$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal disimpan</div>";
	}
}
?>

<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<form method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod; ?>">
			<div class="form-group col-md-3">
				<label for="srcems">No EMS.</label>
				<input type="text" class="form-control" autofocus name="srcems">
			</div>
		</form>
	</div>
	<?php
	if(isset($_POST['srcems'])){

		$resl=$DBcon->query("SELECT * FROM ppkp_header WHERE ppkp_no_ems='$_POST[srcems]' AND ppkp_status='1' ");
		$jumlahdata=$resl->num_rows;
		
		if($jumlahdata>0){
			$i=0;
			while($data=$resl->fetch_array()){ ?>
				<div class="row" style="border-bottom:solid thin #191970">
					<h5><strong>RIWAYAT DOKUMEN EMS:<?php echo $data['ppkp_no_ems'];?> TANGGAL PERIKSA: <?php echo getdateformatdmyhms($data['ppkp_tanggal_input']);?></strong></h5>
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
				$reslt=$DBcon->query("SELECT * FROM ppkp_ctl WHERE pc_header_id='$data[ppkp_id]' ORDER BY pc_id DESC");
				while($datadet=$reslt->fetch_array()){ ?>
					<div class="row bg-info" style="border-bottom:solid thin #FFF5EE">
						<div class="col-md-1">
							<p><small><?php echo $nomor;?></small></p>
						</div>
						<div class="col-md-4">
							<p><small><?php echo getprosesppkp($datadet['pc_stat'],$DBcon);?></small></p>
						</div>
						<div class="col-md-2">
							<p><small><?php echo getdateformatdmyhms($datadet['pc_time']);?></small></p>
						</div>
						<div class="col-md-3">
							<p><small><?php echo $datadet['pc_uraian_proses'];?></small></p>
						</div>
						<div class="col-md-2">
							<p><small><?php echo getnamauser($datadet['pc_user_id'],$DBcon);?></small></p>
						</div>
					</div>			
			<?php
				$nomor++;	
				} 
				$statakhir=getstatusterakhirppkp($data['ppkp_id'],$DBcon);
				if($statakhir!=2 AND $statakhir!=3 AND $statakhir!=5){ ?>
				<div class="row" style="padding:5px;">
					<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#md<?php echo $data['ppkp_id']; ?>">Proses Data</button>
				</div>
				<hr>
			<?php
				} else { ?>
				<div class="row"  style="padding:5px;">
					<?php if($statakhir==2){ ?>
						<p class="pull-right"><strong>Release</strong></p>
					<?php 
					} elseif($statakhir==3) { ?>
						<p class="pull-right"><strong>Belum ada respon admin pos</strong></p>
					<?php
					} elseif($statakhir==5) { ?>
						<p class="pull-right"><strong>Belum diproses admin</strong></p>
					<?php
					} 
					?>
				</div>
				<hr>
			<?php
				}


				$i++;
				$headdata[$i]=$data['ppkp_id'];
			}
		} else {
			echo "<div class='alert alert-warning alert-dismissible' role='alert'>
  					<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden=true'>&times;</span></button>
  					<strong>maaf data yang masbro/mbaksis cari belum terproses</strong>
					</div>";
		}

	};
	?>
</div>
<?php 
if(isset($jumlahdata)>0){
for($j=1;$j<=$jumlahdata;$j++) { ?>
<div id="md<?php echo $headdata[$j];?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog" role="document">
  	<div class="modal-content">
	  	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Proses Status Dokumen</h4>
	    </div>
	    <div class="modal-body">
	    	<form method="post" action="?">
	    		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
	    		<input type="hidden" name="headerid" value="<?php echo $headdata[$j];?>">
	    		<div class="radio">
	    			<label>
	    				<input name="statuscek" type="radio" value="2">
	    				Release
	    			</label>
	    		</div>
	    		<div class="radio">
	    			<label>
	    				<input name="statuscek" type="radio" value="3">
	    				Notifikasi
	    			</label>
	    		</div>
	    		<div class="form-group">
	    			<textarea name="prosesdetail" class="form-control"></textarea>
	    		</div>
	    		<input type="submit" class="form-control btn btn-primary" name="sendata" value="submit">
	    	</form>
	    </div>
	</div>
</div>
</div>
<?php }; 
};
?>