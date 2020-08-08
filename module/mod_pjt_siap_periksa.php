<?php
$petugas=$_SESSION['userSession'];

if(isset($_POST['senddata'])){
	$tglinput=date("Y-m-d h:m:s");
	$insertdata=$DBcon->query("INSERT INTO pibk_ctl(header_seq_id, ctl_stat, ctl_time, user_id, flag_proses, uraian_proses) VALUES ('$_POST[headerid]', '8','$tglinput','$petugas','1','')");
	$updatedata=$DBcon->query("UPDATE laststatus SET laststatus='8',wakturekam='$tglinput' WHERE idlaststatus='$_POST[headerid]'");
	if($insertdata&&$updatedata){
		$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil diupload</div>";

	} else {
		$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal diupload</div>";
	}  
}
echo getbread($mod,$DBcon); 
?>
<div class="container">
	<?php if(isset($stat)){ echo $stat; } ?>
	<div class="row">
		<form class="form" method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<div class="col-lg-6">
			    <div class="input-group">
			      <input type="text" class="form-control" name="awbsrc" placeholder="Masukkan no hawb...">
			      <span class="input-group-btn">
			        <input name="submit" type="submit" class="btn btn-primary" value="Go!">
			      </span>
			    </div>
			</div>
		</form>
	</div>
	<hr>
	<div class="row">
		<form method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th>No.</th>
				<th>No MAWB</th>
				<th>Tgl MAWB</th>
				<th>No HAWB</th>
				<th>Tgl HAWB</th>
				<th>Proses</th>
			</tr>
		<?php
			$filt="";
				if(isset($_POST['submit'])){				
					if(!empty($_POST['awbsrc'])){
						$filt.=" AND t1.no_hawb='$_POST[awbsrc]'";
					}
				}
				$filt.=filtpjt($petugas,$DBcon);
				$query=$DBcon->query("SELECT * FROM pibk_header t1 JOIN laststatus t2 ON t1.idpibk_header=t2.idlaststatus WHERE t1.posisi='1' AND t2.laststatus='5' $filt");
				$i=0;
				while($data=$query->fetch_array()){
					$i++;
		?>
					<tr>
						<td><?php echo $i; ?><input type="hidden" name="headerid" value="<?php echo $data['idpibk_header']; ?>"></td>
						<td><?php echo $data['no_mawb']; ?></td>
						<td><?php echo getdateformatdmy($data['tgl_mawb']); ?></td>
						<td><?php echo $data['no_hawb']; ?></td>
						<td><?php echo getdateformatdmy($data['tgl_hawb']); ?></td>
						<td><input type="submit" class="btn btn-success" name="senddata" value="Nyatakan Siap Periksa"></td>
					</tr>
		<?php
				}
		?>
		</table>
		</form>
	</div>
</div>