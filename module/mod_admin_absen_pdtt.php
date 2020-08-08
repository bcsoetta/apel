<?php echo getbread($mod,$DBcon); 
	if(isset($_POST['proses']))
	{ 
		if(empty($_POST['iddata']))
		{
			$stat="<div class='alert alert-danger text-center'>tidak ada data yang dipilih</div>";
		}
		else
		{
			$n=0;
			foreach ($_POST['iddata'] as $id) 
			{
				$sql_cek_absen 		= "SELECT * FROM npd_absen WHERE npda_id_user='$id'";
				$query_cek_absen	= $DBcon->query("$sql_cek_absen");
				$jumlah_cek_absen	= $query_cek_absen->num_rows;

				if($jumlah_cek_absen==0)
				{
					$sql_input		= "INSERT INTO npd_absen(npda_id_user) VALUES('$id')";
					$query_input	= $DBcon->query("$sql_input");	
					$n++;

					$sql_ambil_header="SELECT * FROM npd_header WHERE npd_id_petugas='$id'";
					$query_ambil_header=$DBcon->query($sql_ambil_header);
					while($data_ambil_header=$query_ambil_header->fetch_array())
					{
						$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$data_ambil_header[npd_id]'";
						$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
						$row_beda_waktu=$query_beda_waktu->fetch_array();
						$perbedaan=$row_beda_waktu['perbedaan'];

						$sql_update_header="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_waktu_bc=npd_waktu_bc+$perbedaan, npd_last_update_time=NOW() ";
						$sql_update_header.=" WHERE npd_id='$data_ambil_header[npd_id]' AND npd_flag_absen='0' AND npd_flag_last_status='1' AND npd_flag_final_status='N'";
						$query_update_header=$DBcon->query("$sql_update_header");


						if($data_ambil_header['npd_flag_last_status']!='3' && $data_ambil_header['npd_flag_last_status']!='4')
						{
							$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
							$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$data_ambil_header[npd_id]', NOW(), '900', 'Data diterima', ";
							$sql_update_control.="'N','None','$id','2')";
							$query_update_control=$DBcon->query("$sql_update_control");
						}
					}
				}
				
			}
			if($n>0)
			{
				$stat="<div class='alert alert-success text-center'>".$n." data berhasil diproses</div>";
			}
			else
			{
				$stat="<div class='alert alert-danger text-center'>data gagal diproses</div>";
			}
		}
	};

	if(isset($_POST['batal']))
	{ 
		if(empty($_POST['iddata']))
		{
			$stat="<div class='alert alert-danger text-center'>tidak ada data yang dipilih</div>";
		}
		else
		{
			$n=0;
			foreach ($_POST['iddata'] as $id) 
			{
				$sql_cek_absen 		= "SELECT * FROM npd_absen WHERE npda_id_user='$id'";
				$query_cek_absen	= $DBcon->query("$sql_cek_absen");
				$jumlah_cek_absen	= $query_cek_absen->num_rows;

				if($jumlah_cek_absen>0)
				{
					$sql_input		= "DELETE FROM npd_absen WHERE npda_id_user='$id'";
					$query_input	= $DBcon->query("$sql_input");	
					$n++;
					$sql_ambil_header="SELECT * FROM npd_header WHERE npd_id_petugas='$id'";
					$query_ambil_header=$DBcon->query($sql_ambil_header);
					while($data_ambil_header=$query_ambil_header->fetch_array())
					{
						$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$data_ambil_header[npd_id]'";
						$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
						$row_beda_waktu=$query_beda_waktu->fetch_array();
						$perbedaan=$row_beda_waktu['perbedaan'];
						
						$sql_update_header="UPDATE npd_header SET npd_flag_absen='0',npd_flag_last_status='1',npd_waktu_bc=npd_waktu_bc+$perbedaan, npd_last_update_time=NOW() ";
						$sql_update_header.=" WHERE npd_id='$data_ambil_header[npd_id]' AND npd_flag_absen='1' AND npd_flag_last_status='2' AND npd_flag_final_status='N'";
						$query_update_header=$DBcon->query("$sql_update_header");

						if($data_ambil_header['npd_flag_last_status']!='3' && $data_ambil_header['npd_flag_last_status']!='4')
						{
							$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
							$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$data_ambil_header[npd_id]', NOW(), '900', 'Data diterima', ";
							$sql_update_control.="'N','None','$id','1')";
							$query_update_control=$DBcon->query("$sql_update_control");
						}
					}

				}
				
			}
			if($n>0)
			{
				$stat="<div class='alert alert-success text-center'>".$n." data berhasil diproses</div>";
			}
			else
			{
				$stat="<div class='alert alert-danger text-center'>data gagal diproses</div>";
			}
		}
	};
	
	$sql			= "SELECT * FROM tbl_user WHERE level='2' AND aktif='Y' ORDER BY nama ASC";
	$query			= $DBcon->query("$sql");
	$jumlah			= $query->num_rows;
	$jumlah_atas	= ceil($jumlah/3)*3;
	$selisih		= $jumlah_atas-$jumlah;
?>
<div class="container">
	<?php 
		if(isset($stat))
		{
			echo $stat;
		}
	?>
	<form name="myform" class="form-npd" method="post" action="?">
		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
		<input name="proses" type="submit" value=" Absen " onclick="return confirm('Anda yakin absen petugas yg telah ditandai?');"> <input name="batal" type="submit" onclick="return confirm('Anda yakin mencabut absen petugas yg telah ditandai?');" value=" Cabut Absen ">
		<br><br>
		<table width="100%" class="table-npd">
			<tr>
				<th width="25%">Nama PDTT</th>
				<th width="5%">Absen</th>
				<th width="25%">Nama PDTT</th>
				<th width="5%">Absen</th>
				<th width="25%">Nama PDTT</th>
				<th width="5%">Absen</th>
			</tr>
			<tr>
				<?php
					$i=0;
					while($row=$query->fetch_array()){
						$i++;
						$sql_cek 	= "SELECT * FROM npd_absen WHERE npda_id_user='$row[id]'";
						$query_cek 	= $DBcon->query("$sql_cek");
						$row_cek	= $query_cek->num_rows;
						if($row_cek>0){
							$centang="<span class='glyphicon glyphicon-ok'></span>";
						}
						else
						{
							$centang="";
						}
						echo '<td>'.'<input name="iddata[]" type="checkbox" value="'.$row['id'].'">&nbsp;&nbsp;'.$row['nama'].'</td>';
						echo '<td align="center">'.$centang.'</td>';
						if($i % 3 ==0){
							echo '</tr><tr>';
						}
					
					}
					for($j=1;$j<=$selisih;$j++){
						echo '<td></td><td></td>';
					}
				?>
			</tr>
		</table>
	</form>
</div>