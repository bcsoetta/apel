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
				$statakhir=getstatusterakhir($data['idpibk_header'],$DBcon);
				$queryfile=$DBcon->query("SELECT*FROM pibk_dokap WHERE idheader='$data[idpibk_header]'");
					$datafile=$queryfile->fetch_array();
					$filehref="dokap/".$datafile['namafile'];
				if($statakhir!=2 AND $statakhir!=3 AND $statakhir!=5 AND $statakhir!=7 AND $statakhir!=10){ ?>
				<div class="row" style="padding:5px;">
					<a class="btn btn-primary" target="_blank" href="<?php echo $filehref;?>">Buka Dokumen Pelengkap</a>
					<?php //echo  createlhplink($data['idpibk_header'],$DBcon); 
						//cek dokap tambahan
						$sqlcekdokdua="SELECT * FROM pibk_dokap_tambahan WHERE idheader='$data[idpibk_header]'";
						$querycount	= $DBcon->query("$sqlcekdokdua");
						$jum=$querycount->num_rows;
						if($jum>0){
							while($datacount=$querycount->fetch_array()){ ?>
								<br><a class="btn btn-success" target="_blank" href="dokaptambahan/<?php echo $datacount['namafile'];?>">Dokap Tambahan</a>
						<?php
							}
						}
						//cek lhp
						$sqlceklhp="SELECT * FROM pibk_lhp WHERE idheader='$data[idpibk_header]'";
						$querycountlhp	= $DBcon->query("$sqlceklhp");
						$jumlhp=$querycountlhp->num_rows;
						if($jumlhp>0){
							while($datacountlhp=$querycountlhp->fetch_array()){ ?>
								<br><a class="btn btn-success" target="_blank" href="lhp/<?php echo $datacountlhp['namafile'];?>">Scan LHP</a>
						<?php
							}
						}
				
					?>
					<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#md<?php echo $data['idpibk_header']; ?>">Proses Data</button>
				</div>
				<hr>
			<?php
				} else { ?>
				<div class="row"  style="padding:5px;">
					<a class="btn btn-primary" target="_blank" href="<?php echo $filehref;?>">Buka Dokumen Pelengkap</a>
					<?php // echo createlhplink($data['idpibk_header'],$DBcon);
					//cek dokap tambahan
						$sqlcekdokdua="SELECT * FROM pibk_dokap_tambahan WHERE idheader='$data[idpibk_header]'";
						$querycount	= $DBcon->query("$sqlcekdokdua");
						$jum=$querycount->num_rows;
						if($jum>0){
							while($datacount=$querycount->fetch_array()){ ?>
								<br><a class="btn btn-success" target="_blank" href="dokaptambahan/<?php echo $datacount['namafile'];?>">Dokap Tambahan</a>
						<?php
							}
						}
					//cek lhp
						$sqlceklhpdua="SELECT * FROM pibk_lhp WHERE idheader='$data[idpibk_header]'";
						$querycountlhpdua	= $DBcon->query("$sqlceklhpdua");
						$jumlhpdua=$querycountlhpdua->num_rows;
						if($jumlhpdua>0){
							while($datacountlhpdua=$querycountlhpdua->fetch_array()){ ?>
								<br><a class="btn btn-success" target="_blank" href="lhp/<?php echo $datacountlhpdua['namafile'];?>">Scan LHP</a>
						<?php
							}
						}
					if($statakhir==2){ ?>
						<p class="pull-right"><strong>Release</strong></p>
					<?php 
					} elseif($statakhir==3) { ?>
						<p class="pull-right"><strong>Belum ada respon</strong></p>
					<?php
					} elseif($statakhir==5) { ?>
						<p class="pull-right"><strong>Pemeriksaan Fisik Masih dalam Proses</strong></p>
					<?php
					} elseif($statakhir==7) { ?>
						<p class="pull-right"><strong>Reject</strong></p>
					<?php
				} elseif($statakhir==10) { ?>
						<p class="pull-right"><strong>Respon dari PJT atas notifikasi</strong></p>
					<?php
					} 
					?>
				</div>
				<hr>
			<?php
				}


				$i++;
				$headdata[$i]=$data['idpibk_header'];
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
	    		<div class="radio">
	    			<label>
	    				<input name="statuscek" type="radio" value="5">
	    				Merah
	    			</label>
	    		</div>
	    		<div class="radio">
	    			<label>
	    				<input name="statuscek" type="radio" value="7">
	    				Reject Dokumen
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