<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$tgl_awal	=$_POST['tgl_awal'];
		$tgl_akhir	=$_POST['tgl_akhir'];
		$fltAwb		=$_POST['fltAwb'];
	}
		if (empty($tgl_awal) and empty($tgl_akhir))	{
			$tglval 	= date('d');
			$blnval 	= date('m');
			$thnval 	= date('Y');
			$tgl_awal 	= "$tglval/$blnval/$thnval";
			$tgl_akhir 	= "$tglval/$blnval/$thnval";
		};

	if($_GET[del]=="true")
	{
		$deletedata=$DBcon->query("DELETE FROM npd_catatan WHERE id='$_GET[id]'");
	}
	?>
<div class="container">
	<div class="panel panel-default">
  		<div class="panel-body">
   			<form class="form" method="post" action="?">
				<input type="hidden" name="mod" value="<?php echo $mod;?>">
				<div class="form-group col-md-2">
	                <div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
	                	<input type="text" class="form-control" id="tgl_awal" name="tgl_awal" value="<?php echo $tgl_awal;?>">
	                    	<div class="input-group-addon">
	                            <span class="glyphicon glyphicon-th"></span>
	                        </div>
	                </div>
	            </div>
				<div class="form-group col-md-2">
	                <div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
	                	<input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?php echo $tgl_akhir;?>">
	                    	<div class="input-group-addon">
	                            <span class="glyphicon glyphicon-th"></span>
	                        </div>
	                </div>
	            </div>
				<div class="form-group col-md-4">
				    <input type="text" class="form-control" name="fltAwb" placeholder="no hawb" value="<?php if(isset($fltAwb)){ echo $fltAwb;} ?>">
				</div>
				<div class="form-group col-md-2">
				    <input type="submit" class="form-control btn btn-primary" name="submit" value="submit">
				</div>
			</form>
  		</div>
	</div>
	<div class="panel panel-default">
  		<div class="panel-heading">Data Catatan NPD tanggal AWB <?php echo $tgl_awal;?> s.d. <?php echo $tgl_akhir;?><button class="btn btn-warning pull-right btn-sm" onClick="return window.location='?mod=64'"><span class="glyphicon glyphicon-plus"></span>Tambah Catatan</button></div>
  		<div class="panel-body">
		    <table class="table table-bordered table-striped">
					<tr class="bg-warning">
						<th>No.</th>
						<th>No HAWB</th>
						<th>Tgl HAWB</th>
						<th>PDTT</th>
						<th></th>
					</tr>
				<?php
					if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){

						$rowPerPage=25;
						$tgl_awal_sql=getdateformatymd($tgl_awal);
						$tgl_akhir_sql=getdateformatymd($tgl_akhir);

						$filt="";
						
						if(!empty($fltAwb)){
							$filt.=" AND awb='$fltAwb'";
						}


						$sqlhal="SELECT * FROM npd_catatan WHERE tgl>='$tgl_awal_sql' AND tgl<='$tgl_akhir_sql' $filt";
						//echo $sqlhal;
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

						$sql="SELECT * FROM npd_catatan WHERE tgl>='$tgl_awal_sql' AND tgl<='$tgl_akhir_sql' $filt";
						$sql.=" ORDER BY id ASC LIMIT $startNum,$rowPerPage";
						$query=$DBcon->query("$sql");
						$i=$startNum;
						while($data=$query->fetch_array()){
							$i++;
				?>	
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $data['awb']; ?></td>
								<td><?php echo getdateformatdmy($data['tgl']); ?></td>
								<td><?php echo getnamauser($data['petugas'],$DBcon); ?></td>
								<td><button id="<?php echo $data['id']; ?>" class="btn btn-default btn-show-detail"><span class="glyphicon glyphicon-zoom-in"></span>&nbsp;Detail</button> <button id="<?php echo $data['id']; ?>" class="btn btn-default btn-delete-data"><span class="glyphicon glyphicon-trash"></span>&nbsp;Delete</button></td>
							</tr>
				<?php
						}
					}
				?>
			</table>
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
					<input type="hidden" name="fltAwb" value="<?php echo $fltAwb;?>">
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