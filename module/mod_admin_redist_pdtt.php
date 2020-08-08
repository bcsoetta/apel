<?php echo getbread($mod,$DBcon);
$petugas=$_SESSION['userSession'];
if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$fltAwb		=$_POST['fltAwb'];
		$fltNip		=$_POST['fltNip'];
	}
if(isset($_POST['proses'])){
	foreach ($_POST['flagproses'] as $key =>$value) {
		$pdtt=$_POST['id_redis'][$key];
		//echo $key."--".$value."--".$pdtt."<br>";
		$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$value'";
		$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
		$row_beda_waktu=$query_beda_waktu->fetch_array();
		$perbedaan=$row_beda_waktu['perbedaan'];
		
		$sql_update_header="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_waktu_bc=npd_waktu_bc+$perbedaan, "; 
		$sql_update_header.="npd_id_petugas='$pdtt', npd_last_update_time=NOW() WHERE npd_id='$value'"; 
		$query_update_header=$DBcon->query("$sql_update_header");
							
		$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
		$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$value', NOW(), '$petugas', 'Data diterima', ";
		$sql_update_control.="'N','none','$pdtt','2')";
		$query_update_control=$DBcon->query("$sql_update_control");

		if($query_update_header && $query_update_control){
			$stat="<div class='row'><div class='alert alert-success'>Respon berhasil dikirim</div>";
		}
		
	}
}
?>
<div class="container-fluid">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<div class="alert alert-warning">Untuk PDTT NPD dengan nama "System" adalah respon dari PJT untuk submitan PIBK yang mendapat respon NPBL yang telah memenuhi persyaratan lartas atau mendapatkan pengecualian lartas supaya di redist ke PDTT yang standby</div>
	</div>
	<div class="row">
		<form method="post" action="?" class="form-npd">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<input type="text" style="width:200px;" name="fltAwb" class="form-npd" placeholder="no hawb" value="<?php if(isset($fltAwb)){ echo $fltAwb;} ?>">
			<input type="text" style="width:450px;" name="fltNip" class="form-npd" placeholder="Nama PDTT dapat diisi lebih dari satu pisahkan dengan tanda ; " value="<?php if(isset($fltNip)){ echo $fltNip;} ?>">
			<input type="submit" name="submit" value="submit">
		</form>
	</div>
	<div class="row">
	<form method="post" action="?" class="form-npd">
		<input name="proses" type="submit" value="Redistribusi" onclick="return confirm('Anda yakin meredistribusi petugas pada data yg telah ditandai?');">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<br><br>
		<table width="100%" class="table-npd">
			<tr>
				<th width="5%"><input name="checkAll" id="checkAll" type="checkbox"></th>
				<th width="5%">No.</th>
				<th width="10%">No HAWB</th>
				<th width="10%">Tgl HAWB</th>
				<th width="10%">PJT</th>
				<th width="30%">Uraian</th>
				<th width="15%">PDTT NPD</th>
				<th width="15%">PDTT Redistribusi</th>
			</tr>
<?php
			
				$rowPerPage=25;
	
				$filt="";
				
				if(!empty($fltAwb)){
					$filt.=" AND npd_hawb='$fltAwb'";
				}

				if(!empty($fltNip)){
					$filts =" AND (nama LIKE '%";
					$filts.= str_replace(";" ,"%' OR nama LIKE '%", $fltNip);
					$filts.="%')";
					$filt.=$filts;


				}
				//echo $filts;

				$sqlhal="SELECT * FROM npd_header JOIN tbl_user ON npd_header.npd_id_petugas = tbl_user.id";
				$sqlhal.=" WHERE npd_flag_absen='0' AND npd_flag_last_status='1' AND npd_flag_final_status='N' $filt";
				$querycount	= $DBcon->query("$sqlhal");
				$count=$querycount->num_rows;
				$jumPage = ceil($count/$rowPerPage);
				//echo $sqlhal;

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

				$sql="SELECT * FROM npd_header JOIN tbl_user ON npd_header.npd_id_petugas = tbl_user.id";
				$sql.=" WHERE npd_flag_absen='0' AND npd_flag_last_status='1' AND npd_flag_final_status='N' $filt";
				$sql.=" ORDER BY npd_waktu_upload ASC LIMIT $startNum,$rowPerPage";
				$query=$DBcon->query("$sql");
				$i=$startNum;
				$j=0;
				while($data=$query->fetch_array()){
					$i++;
		?>	
					<tr >
						<td valign="top" align="center"><input name="flagproses[<?php echo $j;?>]" type="checkbox" value="<?php echo $data['npd_id']; ?>"></td>
						<td valign="top"><?php echo $i; ?></td>
						<td valign="top"><?php echo $data['npd_hawb']; ?></td>
						<td valign="top"><?php echo getdateformatdmy($data['npd_tgl_hawb']); ?></td>
						<td valign="top"><?php echo getpjt($data['npd_id_pjt'],$DBcon); ?></td>
						<td valign="top"><?php echo geturaiannpd($data['npd_id'],$DBcon); ?></td>
						<td valign="top"><?php echo getnamauser($data['npd_id_petugas'],$DBcon); ?></td>
						<td><select name="id_redis[<?php echo $j;?>]" style="width:250px"><?php 
							$query_petugas=$DBcon->query("SELECT * FROM npd_absen t1 JOIN tbl_user t2 ON t1.npda_id_user=t2.id ORDER BY nama ASC");
							while($row_petugas=$query_petugas->fetch_array()){?>
							<option value="<?php echo $row_petugas['id'];?>"><?php echo $row_petugas['nama'];?></option><?php }; ?></select></td>
					</tr>
		<?php
				$j++;
				}
		?>
		</table>
		</form>
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
					<input type="hidden" name="fltNip" value="<?php if(isset($fltNip)){ echo $fltNip; };?>">
					<input type="hidden" name="count" value="<?php echo"$count";?>">
					<?php if ($pageNum!=1)	{	?>
					<input type="submit" name="previousButFirst" value=" First Page ">&nbsp;
					<input type="submit"name="previousBut" value=" << ">&nbsp;
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
					</select>&nbsp;
					<input type="submit" name="submit" value=" Send ">&nbsp;
					<?php if ($pageNum!=$jumPage)	{	?>
					<input type="submit" name="nextBut" value=" >> "> &nbsp;
					<input type="submit" name="nextButLast" value=" Last Page ">&nbsp;
					<?php	};	?>
				</td></tr></table>
				</form>
				<?php	};	?>
				
		</div>
</div>