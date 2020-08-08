	<?php echo getbread($mod,$DBcon);  ?>
	<div class="container">
		<div class="row">
			<p>Data dokumen siap dilakukan peneilitian yang diajukan yang belum diproses, Urutan data berdasarkan waktu pengajuan dokumen dari yang paling lama</p>
			<p>Jumlah maksimal data untuk sekali proses sebanyak 100(seratus) dokumen</p>
		</div>
		<hr>
			<?php
			
			$rowPerPage=100;
			$querycount	= $DBcon->query("SELECT * FROM ppkp_header WHERE ppkp_status='0'");
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
		<div class="row">
				<form role="form" class="form-inline" name="pageForm" method="post" action="?">
					<input type="hidden" name="mod" value="<?php echo $mod;?>">
					<input type="hidden" name="count" value="<?php echo"$count";?>">
					<div class="input-group">
					<?php if ($pageNum!=1)	{	?>
						<button type="submit" class="btn btn-warning btn-sm" name="previousButFirst"> First Page </button>
						<button type="submit" class="btn btn-warning btn-sm" name="previousBut"> << </button>
					<?php	};	?>
					<div class="form-group">				
						<select name="pageNum" class="form-control input-sm">
							<option value="<?php echo $pageNum;?>"><?php echo $pageNum;?></option>
					<?php
						for ($j=1;$j<=$jumPage;$j++)	{
							if ($j!=$pageNum)	{?>
							<option value="<?php echo $j;?>"><?php echo $j;?></option>
					<?php	};	};	?>
						</select>
					</div>
						<button type="submit" class="btn btn-warning btn-sm" name="sendpage"> Send </button>
					<?php if ($pageNum!=$jumPage)	{	?>
						<button type="submit" class="btn btn-warning btn-sm" name="nextBut"> >> </button>
					<span class"input-group-btn">
					<button type="submit" class="btn btn-warning btn-sm" name="nextButLast"> Last Page </button>
					</span>
					<?php	};	?>
				</div>
				</form>
		</div>
		<?php	};	?>
		<hr>
		<div class="row">
			<form method="post" action="?">
				<input type="hidden" name="mod" value="29">
				<button type="submit" name="proses" class="btn btn-danger pull-right">Proses</button>
			<table class="table table-condensed">
				<tr class="warning">
					<th><input name="checkAll" id="checkAll" type="checkbox"></th>
					<th>No</th>
					<th>Nomor EMS</th>
					<th>Tanggal</th>
					<th>Pemeriksa</th>
				</tr>
				<?php
					$query	= $DBcon->query("SELECT * FROM ppkp_header WHERE ppkp_status='0' ORDER BY ppkp_id ASC LIMIT $startNum,$rowPerPage");
					$nom=$startNum+1;
					while($row= $query->fetch_array()){ ?>
				<tr>
					<td><input name="flagproses[]" type="checkbox" value="<?php echo $row['ppkp_id']; ?>"></td>
					<td><?php echo $nom; ?></td>
					<td><?php echo $row['ppkp_no_ems'];?></td>
					<td><?php echo getdateformatdmyhms($row['ppkp_tanggal_input']); ?></td>
					<td><?php echo getnamauser($row['ppkp_petugas'],$DBcon); ?></td>
				</tr>
				<?php 
					$nom++;
					}
				?>
			</table>
			</form>
		</div>
	</div>