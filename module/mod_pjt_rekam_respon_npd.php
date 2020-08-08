<?php 
	echo getbread($mod,$DBcon); 
	$uservalid=$_SESSION['userSession'];
	$filt="";
	$changepjt=filtpjt($uservalid,$DBcon);
	$filt.=str_replace("id_pjt", "npd_id_pjt", $changepjt);
	
	$sql_header="SELECT * FROM npd_header WHERE npd_id='$_GET[id]' $filt";
	$query_header=$DBcon->query("$sql_header");
	$data=$query_header->fetch_array();
	$jumlah=$query_header->num_rows;
?>
<div class="container">
	<?php
	if($jumlah==0)
	{
		echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>Anda tidak dapat merespon data ini</div>";
	}
	else
	{
	?>
	<div class="row" style="padding:5px">
		<form method="post" class="form-respon" action="?"  enctype="multipart/form-data">
			<input type="hidden" name="mod" value="60">
			<input type="hidden" name="exec" value="true">
			<input type="hidden" name="idproses" value="<?php echo $_GET['id'];?>">
			<input type="hidden" name="statuscek" value="<?php echo $data['npd_id_petugas']; ?>">
			<table width="750px" style="border:solid thin #AAAAAA">
				<tr>
					<td colspan="2" style="padding:5px;"><textarea name="inputrespon" placeholder="masukkan keterangan Respon Konfirmasi NPD/SPBL di sini" required></textarea></td>
				</tr>
				<tr>
					<td style="padding:5px;">Lampirkan Dokumen Pendukung<input type="file" name="lampiran"></td>
					<td style="padding:5px;" align="right"><input type="submit" value="simpan respon" onclick="return confirm('Anda yakin menyimpan data ini?');"></td>
				</tr>
			</table>
		</form>
	</div>
	<?php
		$sql_dokap		="SELECT * FROM pibk_dokap WHERE idheader='$_GET[id]'";
		$query_dokap	= $DBcon->query("$sql_dokap");
		$data_dokap		= $query_dokap->fetch_array();
	?>
	<div class="row pull-right" style="padding:5px;">
		<a target="_blank" href="dokap/<?php echo $data_dokap['namafile'];?>" class="buton-dokap"> Lihat Dokumen Pelengkap </a>	
	</div>
	<div class="row" style="padding:5px;">
	<?php	
		
	?>
		<table width="100%" class="table-npd">
		
			<tr>
				<th colspan="6">Riwayat dokumen HAWB : <?php echo $data['npd_hawb']; ?> tanggal : <?php echo $data['npd_tgl_hawb']; ?></th>
			</tr>
			<tr style="font-weight:bold; background-color:#AAAAAA;">
				<td width="5%" >No.</td>
				<td width="25%">Uraian Status</td>
				<td width="20%">Waktu</td>
				<td width="20%">Status</td>
				<td width="15%">User</td>
				<td width="15%">PDTT</td>
			</tr>
		<?php
			$sql="SELECT * FROM npd_control WHERE npdc_id_header='$_GET[id]' ORDER BY npdc_id ASC";
			$query=$DBcon->query("$sql");
			$i=0;
			while($row=$query->fetch_array())
			{
				$i++
		?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo stripslashes($row['npdc_uraian']); if($row['npdc_flag_file']=="Y"){ echo '<a target="_blank" href="dokapnpd/'.$row['npdc_filename'].'">&nbsp;&nbsp;[Lihat Lampiran]</a>'; }; ?></td>
				<td><?php echo $row['npdc_waktu']; ?></td>
				<td><?php echo getprosesnpd($row['npdc_status'],$DBcon); ?></td>
				<td><?php echo getnamauser($row['npdc_user'],$DBcon); ?></td>
				<td><?php echo getnamauser($row['npdc_petugas'],$DBcon); ?></td>
			</tr>
		<?php
			}
		?>
	</div>
	<?php
	}
	?>
</div>