<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$tgl_awal	=$_POST['tgl_awal'];
		$tgl_akhir	=$_POST['tgl_akhir'];
	}
	if (empty($tgl_awal) and empty($tgl_akhir))	{
			$tglval 	= date('d');
			$blnval 	= date('m');
			$thnval 	= date('Y');
			$tgl_awal 	= "$tglval/$blnval/$thnval";
			$tgl_akhir 	= "$tglval/$blnval/$thnval";
	};
	?>
<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<div class="col-md-3"><p class="text-muted"><strong>Tgl Proses:</strong></p></div>
		<div class="col-md-3"><p class="text-muted"><strong>sd Tgl:</strong></p></div>
		<div class="col-md-4"><p class="text-muted"><strong></strong></p></div>
		<div class="col-md-2">&nbsp;</div>
	</div>
	<div class="row">
		<form class="form" method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<div class="form-group col-md-3">
                <div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
                	<input type="text" class="form-control" id="tgl_awal" name="tgl_awal" value="<?php echo $tgl_awal;?>">
                    	<div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                </div>
            </div>
			<div class="form-group col-md-3">
                <div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
                	<input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?php echo $tgl_akhir;?>">
                    	<div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                </div>
            </div>
			<div class="form-group col-md-4">
			</div>
			<div class="form-group col-md-2">
			    <input type="submit" class="form-control btn btn-primary" name="submit" value="submit">
			</div>
		</form>
	</div>
	<hr>
	<div class="row">
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th>No.</th>
				<th>BATCH ID</th>
				<th>TGL BATCH</th>
				<th>PETUGAS</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		<?php
			if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){

				$rowPerPage=100;
				$tgl_awal_sql=getdateformatymd($tgl_awal);
				$tgl_akhir_sql=getdateformatymd($tgl_akhir);

				$filt="";
				
				if(!empty($fltAwb)){
					$filt.=" AND t1.no_hawb='$fltAwb'";
				}


				$filt.=filtpjt($petugas,$DBcon);

				$sqlhal="SELECT DISTINCT idbatch, waktu, idpetugas FROM tbl_batch_proses ";
				$sqlhal.=" WHERE waktu>='$tgl_awal_sql' AND waktu<='$tgl_akhir_sql'";
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

				$sql="SELECT DISTINCT idbatch, waktu, idpetugas FROM tbl_batch_proses ";
				$sql.="WHERE waktu>='$tgl_awal_sql' AND waktu<='$tgl_akhir_sql' ";
				$sql.=" ORDER BY idbatch ASC LIMIT $startNum,$rowPerPage";
				$query=$DBcon->query("$sql");
				$i=$startNum;
				while($data=$query->fetch_array()){
					$i++;
		?>	
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $data['idbatch']; ?></td>
						<td><?php echo getdateformatdmyhms($data['waktu']); ?></td>
						<td><?php echo getnamauser($data['idpetugas'],$DBcon); ?></td>
						<td><a class="btn btn-warning" href="printbarbatch.php?&id=<?php echo $data['idbatch']; ?>" target="_blank">Print Barcode</a></td>
						<td><button id="<?php echo $data['idbatch']; ?>" class="btn btn-default btn-show-detail"><span class="glyphicon glyphicon-zoom-in"></span>&nbsp;Detail</button></td>
					</tr>
		<?php
				}
			}
		?>
		</table>
	</div>
	<?php 
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){ ?>
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
				<form class="form form-inline pull-right" name="pageForm" method="post" action="?">
					<input type="hidden" name="mod" value="<?php echo $mod;?>">
					<input type="hidden" name="tgl_awal" value="<?php echo $tgl_awal;?>">
					<input type="hidden" name="tgl_akhir" value="<?php echo $tgl_akhir;?>">
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
					<input type="submit" class="btn btn-primary form-control" name="submit" value=" Send ">
					<?php if ($pageNum!=$jumPage)	{	?>
					<input type="submit" class="btn btn-primary form-control" name="nextBut" value=" >> "> 
					<input type="submit" class="btn btn-primary form-control" name="nextButLast" value=" Last Page ">
					<?php	};	?>
				</form>
				<?php	};	?>
				
		</div>
		<?php }; ?>
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