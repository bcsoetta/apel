<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$fltAwb		=$_POST['fltAwb'];
	}

	if(isset($_POST['exec'])=="true"){
		$respon_id_header 	=$_POST['idproses']; 
		$respon_isi			=addslashes($_POST['inputrespon']);
		$respon_status		=$_POST['statuscek'];
		$respon_waktu 		=date("YmdHis");
		
		if($respon_status=='3')
		{
			$respon_final="Y";
		}
		elseif($respon_status=='4')
		{
			$respon_final="N";
		}

		$sql_beda_waktu="SELECT TIMESTAMPDIFF(second,npd_last_update_time,NOW()) AS perbedaan FROM npd_header WHERE npd_id='$respon_id_header'";
		$query_beda_waktu=$DBcon->query("$sql_beda_waktu");
		$row_beda_waktu=$query_beda_waktu->fetch_array();
		$perbedaan=$row_beda_waktu['perbedaan'];

		$sql_update_header="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='$respon_status', npd_last_update_time=NOW(), ";
		$sql_update_header.=" npd_flag_final_status='$respon_final', npd_uraian='$respon_isi', npd_waktu_bc=npd_waktu_bc+$perbedaan";
		$sql_update_header.=" WHERE npd_id='$respon_id_header'";
		$query_update_header=$DBcon->query("$sql_update_header");

		$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
		$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$respon_id_header', NOW(), '$petugas', '$respon_isi', ";
		$sql_update_control.="'N','None','$petugas','$respon_status')";
		$query_update_control=$DBcon->query("$sql_update_control");

		if($query_update_header && $query_update_control){
			$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>Respon berhasil dikirim</div>";
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
				<th width="20%">PJT</th>
				<th>&nbsp;</th>
			</tr>
		<?php
			
				$rowPerPage=100;
	
				$filt="";
				
				if(!empty($fltAwb)){
					$filt.=" AND npd_hawb='$fltAwb'";
				}

				$sqlhal="SELECT * FROM npd_header ";
				$sqlhal.=" WHERE npd_id_petugas='$petugas'";
				$sqlhal.=" AND npd_flag_absen='1' AND npd_flag_last_status='2' AND npd_flag_final_status='N' $filt";
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
				$sql.=" WHERE npd_id_petugas='$petugas'";
				$sql.=" AND npd_flag_absen='1' AND npd_flag_last_status='2' AND npd_flag_final_status='N' $filt";
				$sql.=" ORDER BY npd_last_update_time ASC LIMIT $startNum,$rowPerPage";
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
						<td><?php echo getpjt($data['npd_id_pjt'],$DBcon); ?></td>
						<td><a href="?mod=58&id=<?php echo $data['npd_id']; ?>" target="_self"><span class="glyphicon glyphicon-pencil"></span>respon</a></td>
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
					<select name="pageNum" class="form-control">
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
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" >
  <div class="modal-dialog modal-lg" role="document">
  	<div class="modal-content">
  		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 id="myModalLabel" class="modal-title"></h4>
		</div>
		<div class="modal-body">
		</div>
	</div>
</div>
</div>