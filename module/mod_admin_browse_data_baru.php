	<?php echo getbread($mod,$DBcon);  ?>
	<div class="container">
		<div class="row">
			<p>Data dokumen siap dilakukan peneilitian yang diajukan yang belum diproses, Urutan data berdasarkan waktu pengajuan dokumen dari yang paling lama</p>
			<p>Jumlah maksimal data untuk sekali proses sebanyak 100(seratus) dokumen</p>
		</div>
		<hr>
		<div class="row">
			<form name="formFilt" action="?" method="POST">
				<input type="hidden" name="mod" value="<?php echo $mod;?>">
				Filter PJT:
				<select class="form-control" name="pjtFilt">
			    	<?php
			    	$qSlc=$DBcon->query("SELECT * FROM ref_pjt ORDER BY nama_pjt ASC");
			    	while($hSlc=$qSlc->fetch_array()){ ?>
			    		<option value=<?php echo $hSlc['id']; ?>><?php echo $hSlc['nama_pjt']; ?></option>
			    	<?php
			    	}
			    	?>
			    </select>
				<input type="submit" class="btn btn-success" name="cari" value=" Cari ">
			</form>
		</div>
		<div class="row">
			<?php
			
			$rowPerPage=100;
			if(isset($_POST['pjtFilt'])){$pjtFilt=$_POST['pjtFilt']; }
			if(!empty($pjtFilt)){
				$filterPjt=" AND id_pjt='$pjtFilt'";
			}else{
				$filterPjt="";
			}
			$querycount	= $DBcon->query("SELECT * FROM pibk_header JOIN pibk_dokap ON idpibk_header=idheader WHERE posisi='3' $filterPjt");
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
				<form class="form form-inline pull-right" name="pageForm" method="post" action="?">
					<input type="hidden" name="mod" value="<?php echo $mod;?>">
					<input type="hidden" name="pjtFilt" value="<?php echo"$pjtFilt";?>">
				<input type="hidden" name="count" value="<?php echo"$count";?>">
					<?php if ($pageNum!=1)	{	?>
					<input type="submit" class="btn btn-primary form-control" name="previousButFirst" value=" First Page ">
					<input type="submit" class="btn btn-primary form-control" name="previousBut" value=" << ">
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
					<input type="submit" class="btn btn-primary form-control" name="sendpage" value=" Send ">
					<?php if ($pageNum!=$jumPage)	{	?>
					<input type="submit" class="btn btn-primary form-control" name="nextBut" value=" >> "> 
					<input type="submit" class="btn btn-primary form-control" name="nextButLast" value=" Last Page ">
					<?php	};	?>
				
				<?php	};	?>
				</form>
		</div>
		<hr>
		<div class="row">
			<form method="post" action="?">
				<input type="hidden" name="mod" value="13">
			<div class="form-group  col-md-1">
				<input type="submit" name="proses" class="form-control btn btn-primary" value="proses">
			</div>
			<table class="table table-bordered">
				<tr class="bg-primary">
					<th><input name="checkAll" id="checkAll" type="checkbox"></th>
					<th>No</th>
					<th>Master AWB</th>
					<th>Tanggal</th>
					<th>House AWB</th>
					<th>Tanggal</th>
					<th>BC11</th>
					<th>Tanggal</th>
					<th>PJT</th>
					<th>Waktu Upload</th>
				</tr>
				<?php
					$query	= $DBcon->query("SELECT * FROM pibk_header JOIN pibk_dokap ON idpibk_header=idheader WHERE posisi='3' $filterPjt ORDER BY waktuupload ASC LIMIT $startNum,$rowPerPage");
					$nom=$startNum+1;
					while($row= $query->fetch_array()){ ?>
				<tr>
					<td><input name="flagproses[]" type="checkbox" value="<?php echo $row['idpibk_header']; ?>"></td>
					<td><?php echo $nom; ?></td>
					<td><?php echo $row['no_mawb'];?></td>
					<td><?php echo getdateformatdmy($row['tgl_mawb']); ?></td>
					<td><?php echo $row['no_hawb']; ?></td>
					<td><?php echo getdateformatdmy($row['tgl_hawb']); ?></td>
					<td><?php echo $row['no_bc']; ?></td>
					<td><?php echo getdateformatdmy($row['tgl_bc']);?></td>
					<td><?php echo getpjt($row['id_pjt'],$DBcon); ?></td>
					<td><?php echo getdateformatdmyhms($row['waktuupload']); ?></td>
				</tr>
				<?php 
					$nom++;
					}
				?>
			</table>
			</form>
		</div>
	</div>