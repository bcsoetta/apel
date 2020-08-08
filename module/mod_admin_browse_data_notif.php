<?php echo getbread($mod,$DBcon); 
		if(isset($_POST['subproses'])){
			$tglinput	=date("Y-m-d H:i:s");
			$petugas	=$petugas=$_SESSION['userSession'];
			$exec_tlast	=$DBcon->query("UPDATE laststatus SET laststatus='4',wakturekam='$tglinput' WHERE idlaststatus='$_POST[idproses]'");
			$exec_tctl	=$DBcon->query("INSERT INTO pibk_ctl(header_seq_id, ctl_stat, ctl_time, user_id, flag_proses) VALUES ('$_POST[idproses]', '4','$tglinput','$petugas','1')");
			if($exec_tlast&&$exec_tlast){
				$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil diproses</div>";
			} else {
				$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal diproses</div>";
			}
		}
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
			<input type="hidden" name="mod" value="<?php echo $mod; ?>">
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
			if(isset($_POST['submit'])){
				$query=$DBcon->query("SELECT * FROM pibk_header t1 JOIN laststatus t2 ON t1.idpibk_header=t2.idlaststatus WHERE t1.no_hawb='$_POST[awbsrc]' AND t1.posisi='1' AND t2.laststatus='3'");
				$i=0;
				while($data=$query->fetch_array()){
					$i++;
		?>
					<tr>
						<td><?php echo $i; ?><input type="hidden" name="idproses" value="<?php echo $data['idpibk_header']; ?>"></td>
						<td><?php echo $data['no_mawb']; ?></td>
						<td><?php echo getdateformatdmy($data['tgl_mawb']); ?></td>
						<td><?php echo $data['no_hawb']; ?></td>
						<td><?php echo getdateformatdmy($data['tgl_hawb']); ?></td>
						<td><input type="submit" class="btn btn-success" name="subproses" value="proses"></td>
					</tr>
		<?php
				}
			}
		?>
		</table>
		</form>
	</div>
</div>