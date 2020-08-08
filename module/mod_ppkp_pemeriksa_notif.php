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
	<?php if(isset($stat)) { echo $stat; }; ?>

		<table width="100%" style="border:solid thin #DDD; border-collapse:collapse;">
		
	<?php

		$resl=$DBcon->query("SELECT * FROM ppkp_header t1 JOIN ppkplaststatus t2 ON t1.ppkp_id=t2.idlaststatus WHERE  t1.ppkp_status='1' AND t2.laststatus='3'");
		$jumlahdata=$resl->num_rows;
		
		if($jumlahdata>0){
			$i=0;
			while($data=$resl->fetch_array()){ ?>
			<tr style="border:solid thin #DDD">
				<td colspan="5" style="border:solid thin #DDD; padding:5px; background-color:#000; color:#FFF;">RIWAYAT DOKUMEN EMS:<?php echo $data['ppkp_no_ems'];?> TANGGAL PERIKSA: <?php echo getdateformatdmyhms($data['ppkp_tanggal_input']);?></td>
			</tr>
			<tr style="border:solid thin #DDD; background-color:#ff471a;">
				<td width="5%" style="border:solid thin #DDD; padding:5px;">Nomor</td>
				<td width="30%" style="border:solid thin #DDD; padding:5px;">Status</td>
				<td width="15%" style="border:solid thin #DDD; padding:5px;">Waktu Status</td>
				<td width="40%" style="border:solid thin #DDD; padding:5px;">Keterangan</td>
				<td width="10%" style="border:solid thin #DDD; padding:5px;">Petugas</td>
			</tr>
			<?php
				$nomor=1;
				$reslt=$DBcon->query("SELECT * FROM ppkp_ctl WHERE pc_header_id='$data[ppkp_id]' ORDER BY pc_id DESC");
				while($datadet=$reslt->fetch_array()){ ?>
					<tr style="border:solid thin #DDD">
						<td style="border:solid thin #DDD; padding:5px;"><?php echo $nomor;?></td>
						<td style="border:solid thin #DDD; padding:5px;"><?php echo getprosesppkp($datadet['pc_stat'],$DBcon);?></td>
						<td style="border:solid thin #DDD; padding:5px;"><?php echo getdateformatdmyhms($datadet['pc_time']);?></td>
						<td style="border:solid thin #DDD; padding:5px;"><?php echo $datadet['pc_uraian_proses'];?></td>
						<td style="border:solid thin #DDD; padding:5px;"><?php echo getnamauser($datadet['pc_user_id'],$DBcon);?></td>
					</tr>			
			<?php
				$nomor++;	
				} 
				?>
				<tr style="border:solid thin #DDD">
					<td colspan="5" style="border:solid thin #DDD; padding:5px;"><button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#md<?php echo $data['ppkp_id']; ?>">Proses Data</button></td>
				</tr>
				<tr style="border-top:double #DDD;">
					<td colspan="5" style="border-right:hidden; border-left:hidden;">&nbsp;</td>
				</tr>
			<?php
				$i++;
				$headdata[$i]=$data['ppkp_id'];
			}
		?>
	</table>
		<?php
		} else {
			echo "<div class='alert alert-warning alert-dismissible' role='alert'>
  					<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden=true'>&times;</span></button>
  					<strong>Tidak Terdapat Data</strong>
					</div>";
		}

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
	    		<div class="form-group">
	    			<textarea name="prosesdetail" class="form-control"></textarea>
	    		</div>
	    		<div class="checkbox">
	    			<label>
	    				<input name="statuscek" type="checkbox" required value="5">
	    			</label>
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