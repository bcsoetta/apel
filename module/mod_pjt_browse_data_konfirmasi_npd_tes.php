<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
		$waktu=date("h:i a");
	$mulai="11:30 pm";
	$akhir="07:30 am";
	$wkt1=DateTime::createFromFormat('H:i a', $waktu);
	$wkt2=DateTime::createFromFormat('H:i a', $mulai);
	$wkt3=DateTime::createFromFormat('H:i a', $akhir);
if($wkt1<$wkt2 && $wkt1>$wkt3){
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$fltAwb		=$_POST['fltAwb'];
	}

	if(isset($_POST['exec'])=="true")
	{
		$respon_id_header 	=$_POST['idproses']; 
		$respon_isi			=addslashes($_POST['inputrespon']);
		$respon_pdtt		=$_POST['statuscek'];
		$respon_waktu 		=date("YmdHis");
		
		if(!empty($_FILES['lampiran']['name']))
		{
			$pathToSave="dokapnpd/";
			$file_name 	=$_FILES['lampiran']['name'];
			$file_size 	=$_FILES['lampiran']['size'];
			$file_tmp 	=$_FILES['lampiran']['tmp_name'];
			$file_type 	=$_FILES['lampiran']['type'];
			$file_ext_arr 	=explode('.',$file_name);
			$file_ext=$file_ext_arr[1];
			//echo $file_size;
			if ($file_size<10000000) 	
			{
				if ($file_ext=="pdf") 	
				{
					$got_name 		= date('Ymdhis');
					$got_name 		= $petugas."_"."$got_name.$file_ext";
					copy($file_tmp,"$pathToSave"."$got_name");

					$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$respon_id_header'";
					$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
					$row_beda_waktu=$query_beda_waktu->fetch_array();
					$perbedaan=$row_beda_waktu['perbedaan'];

					$sql_update_header="UPDATE npd_header SET npd_flag_absen='0',npd_flag_last_status='1',npd_waktu_pjt=npd_waktu_pjt+$perbedaan, npd_last_update_time=NOW(), ";
					$sql_update_header.=" npd_uraian='$respon_isi', npd_flag_file='Y', npd_file_name='$got_name'";
					$sql_update_header.=" WHERE npd_id='$respon_id_header'";
					$query_update_header=$DBcon->query("$sql_update_header");

					$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
					$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$respon_id_header', NOW(), '$petugas', '$respon_isi', ";
					$sql_update_control.="'Y','$got_name','$petugas','1')";
					$query_update_control=$DBcon->query("$sql_update_control");

					if($query_update_header && $query_update_control)
					{
						//cek status absen
						$sql_absen ="SELECT * FROM npd_absen WHERE npda_id_user='$respon_pdtt'";
						$query_absen=$DBcon->query("$sql_absen");
						$jum_absen=$query_absen->num_rows;

						if($jum_absen>0)
						{
							$sql_update_header_absen="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_last_update_time=NOW() WHERE npd_id='$respon_id_header'"; 
							$query_update_header_absen=$DBcon->query("$sql_update_header_absen");
						

							$sql_update_control_absen="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
							$sql_update_control_absen.="npdc_filename, npdc_petugas, npdc_status) VALUES('$respon_id_header', NOW(), '900', 'Data diterima', ";
							$sql_update_control_absen.="'N','none','$respon_pdtt','1')";
							$query_update_control_absen=$DBcon->query("$sql_update_control_absen");
						}  
						$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>Respon berhasil dikirim</div>";
					}
				}
				else
				{
					$stat="<div class='alert alert-danger'>Format File Harus PDF</div>";
				}
			}
			else
			{
				$stat="<div class='alert alert-danger'>Ukuran File Lebih dari 10 MB</div>";
			}
		}
		else
		{
			$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$respon_id_header'";
			$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
			$row_beda_waktu=$query_beda_waktu->fetch_array();
			$perbedaan=$row_beda_waktu['perbedaan'];
			
			$sql_update_header="UPDATE npd_header SET npd_flag_absen='0',npd_flag_last_status='1',npd_waktu_pjt=npd_waktu_pjt+$perbedaan, npd_last_update_time=NOW(), ";
			$sql_update_header.=" npd_uraian='$respon_isi', npd_flag_file='N', npd_file_name='none'";
			$sql_update_header.=" WHERE npd_id='$respon_id_header'";
			$query_update_header=$DBcon->query("$sql_update_header");

			$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
			$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$respon_id_header', NOW(), '$petugas', '$respon_isi', ";
			$sql_update_control.="'N','none','$petugas','1')";
			$query_update_control=$DBcon->query("$sql_update_control");

			if($query_update_header && $query_update_control)
			{
				//cek status absen
				$sql_absen ="SELECT * FROM npd_absen WHERE npda_id_user='$respon_pdtt'";
				$query_absen=$DBcon->query("$sql_absen");
				$jum_absen=$query_absen->num_rows;

				if($jum_absen>0)
				{
					$sql_update_header_absen="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_last_update_time=NOW() WHERE npd_id='$respon_id_header'"; 
					$query_update_header_absen=$DBcon->query("$sql_update_header_absen");
				

					$sql_update_control_absen="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
					$sql_update_control_absen.="npdc_filename, npdc_petugas, npdc_status) VALUES('$respon_id_header', NOW(), '900', 'Data diterima', ";
					$sql_update_control_absen.="'N','none','$respon_pdtt','2')";
					$query_update_control_absen=$DBcon->query("$sql_update_control_absen");
				}  
				$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>Respon berhasil dikirim</div>";
			}
		}


	};
	
	?>
<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<form method="post" action="?" class="form-npd">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<input type="text" style="width:200px;" name="fltAwb" class="form-npd" placeholder="no hawb" value="<?php if(isset($fltAwb)){ echo $fltAwb;} ?>">
			<input type="submit" name="submit" value="submit">
		</form>
	</div>
	<div class="row">
		<table width="100%" class="table-npd">
			<tr>
				<th width="5%">No.</th>
				<th width="10%">No HAWB</th>
				<th width="10%">Tgl HAWB</th>
				<th width="45%">Uraian</th>
				<th width="20%">PDTT</th>
				<th>&nbsp;</th>
			</tr>
		<?php
			
				$rowPerPage=100;
	
				$filt="";
				
				if(!empty($fltAwb)){
					$filt.=" AND npd_hawb='$fltAwb'";
				}
				$changepjt=filtpjt($petugas,$DBcon);
				$filt.=str_replace("id_pjt", "npd_id_pjt", $changepjt);

				$sqlhal="SELECT * FROM npd_header ";
				$sqlhal.=" WHERE npd_flag_absen='1' AND npd_flag_last_status='4' AND npd_flag_final_status='N' $filt";
				$querycount	= $DBcon->query("$sqlhal");
				$count=$querycount->num_rows;
				$jumPage = ceil($count/$rowPerPage);

				if(isset($_POST['pageNum'])){$pageNum=$_POST['pageNum']; }
				if(isset($_POST['nextBut'])){$nextBut=$_POST['nextBut']; }
				if(isset($_POST['previousBut'])){$previousBut=$_POST['previousBut']; }
				if(isset($_POST['nextButLast'])){$nextButLast=$_POST['nextButLast']; }
				if(isset($_POST['previousButFirst'])){$previousButFirst=$_POST['previousButFirst']; }


				if(empty($pageNum)){ $pageNum=1;}
				if (isset($nextBut))	{ $pageNum = $pageNum+1; }
				if (isset($previousBut))	{ $pageNum = $pageNum-1;}
				if (isset($nextButLast))	{ $pageNum = $jumPage;}	
				if (isset($previousButFirst)){$pageNum = 1;};
				$startNum=($pageNum-1)*$rowPerPage;

				$sql="SELECT * FROM npd_header ";
				$sql.=" WHERE npd_flag_absen='1' AND npd_flag_last_status='4' AND npd_flag_final_status='N' $filt";
				$sql.=" ORDER BY npd_id ASC LIMIT $startNum,$rowPerPage";
				$query=$DBcon->query("$sql");
				$i=$startNum;
				while($data=$query->fetch_array()){
					$i++;
		?>	
					<tr >
						<td><?php echo $i; ?></td>
						<td><?php echo $data['npd_hawb']; ?></td>
						<td><?php echo getdateformatdmy($data['npd_tgl_hawb']); ?></td>
						<td><?php echo stripslashes($data['npd_uraian']); ?></td>
						<td><?php echo getnamauser($data['npd_id_petugas'],$DBcon); ?></td>
						<td><a href="?mod=61&id=<?php echo $data['npd_id']; ?>" target="_self"><span class="glyphicon glyphicon-pencil"></span>respon</a></td>
					</tr>
		<?php
				}
		?>
		</table>
	</div>
	 
	<div class="row">
			<?php
			
			

			if ($count > $rowPerPage )	{  
			$jVal = ($pageNum -1) * $rowPerPage +1;
			if ($pageNum==$jumPage AND $pageNum>0)	{ 
			$jumAllPage = $jumPage * $rowPerPage;
			$selisih = $jumAllPage - $count;
			$countVal = $jumAllPage - $selisih;
			}	else if ($pageNum!="")	{
			$countVal = $pageNum * $rowPerPage;
			}	else if ($count >0)	{  
				if ($count > $rowPerPage)	{
						$jVal=1;
						$pageNum = 1;
						$countVal =$pageNum * $rowPerPage;
				}	else	{
					$jVal=1; 
					$countVal = $count; 
				};								
			};
		}	else if ($count <= $rowPerPage )	{  
			$jVal=1; 
			$countVal = $count;								
		};
		if ($count > $rowPerPage)	{
				
				?>
				<form class="form-paging" name="pageForm" method="post" action="?">
					<table width=100%><tr><td style="padding:5px" align="right">
					<input type="hidden" name="mod" value="<?php echo $mod;?>">
					<input type="hidden" name="fltAwb" value="<?php if(isset($fltAwb)){ echo $fltAwb; };?>">
					<input type="hidden" name="count" value="<?php echo"$count";?>">
					<?php if ($pageNum!=1)	{	?>
					<input type="submit" name="previousButFirst" value=" First Page ">
					<input type="submit" name="previousBut" value=" << ">
					<?php	};	?>
					Page : 					
					<select name="pageNum">
						<option value="<?php echo"$pageNum";?>"><?php echo"$pageNum";?></option>
					<?php
						for ($j=1;$j<=$jumPage;$j++)	{
							if ($j!=$pageNum)	{
					?>
						<option value="<?php echo"$j";?>"><?php echo"$j";?></option>
					<?php	};	};	?>
					</select>
					<input type="submit" name="submit" value=" Send ">
					<?php if ($pageNum!=$jumPage)	{	?>
					<input type="submit" name="nextBut" value=" >> "> 
					<input type="submit" name="nextButLast" value=" Last Page ">
					<?php	};	?>
					</td></tr></table>
				</form>
				<?php	};	?>
				
		</div>
</div>
<?php }
else {
?>
<div class="container">
		<div class="alert alert-danger">Waktu untuk merespon npd telah melewati batas waktu pelayanan (07.30 s.d. 23.30)</div>
	</div>
<?php
}
?>