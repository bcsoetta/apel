<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit']) || isset($_POST['pageNum']) || isset($_POST['nextBut']) || isset($_POST['previousBut']) || isset($_POST['nextButLast']) || isset($_POST['previousButFirst']) ){
		$tgl_awal	=$_POST['tgl_awal'];
		$tgl_akhir	=$_POST['tgl_akhir'];
		$fltAwb		=$_POST['fltAwb'];
		$fltStat	=$_POST['fltStat'];
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
	<div class="row">
		<div class="col-md-2"><p class="text-muted"><strong>Tgl HAWB:</strong></p></div>
		<div class="col-md-2"><p class="text-muted"><strong>sd Tgl:</strong></p></div>
		<div class="col-md-2"><p class="text-muted"><strong>No. HAWB:</strong></p></div>
		<div class="col-md-4"><p class="text-muted"><strong>Status Dokumen:</strong></p></div>
		<div class="col-md-2">&nbsp;</div>
	</div>
	<div class="row">
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
			<div class="form-group col-md-2">
			    <input type="text" class="form-control" name="fltAwb" placeholder="no hawb" value="<?php if(isset($fltAwb)){ echo $fltAwb;} ?>">
			</div>
			<div class="form-group col-md-4">
			    <select name="fltStat" class="form-control">
			    	<?php
			    	if(empty($fltStat)){
			    		$fltStat="";
			    	}else{
			    		$fltStat=$_POST['fltStat'];
			    	}
			    	$qSlc1=$DBcon->query("SELECT * FROM ref_proses WHERE id='$fltStat'");
			    	$hSlc1=$qSlc1->fetch_array();
			    	?>
			    	<option value=<?php echo $hSlc1['id']; ?>><?php if(!empty($fltStat)){ echo $hSlc1['uraian']; } else { echo "--Semua--";}?></option>
			    	<option value="">--Semua--</option>
			    	<?php
			    	$qSlc2=$DBcon->query("SELECT * FROM ref_proses WHERE id!='$fltStat'");
			    	while($hSlc2=$qSlc2->fetch_array()){ ?>
			    		<option value=<?php echo $hSlc2['id']; ?>><?php echo $hSlc2['uraian']; ?></option>
			    	<?php
			    	}
			    	?>
			    </select>
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
				<th>No MAWB</th>
				<th>LOKASI PJT</th>
				<th>No HAWB</th>
				<th>Tgl HAWB</th>
				<th>Proses</th>
				<th>Waktu</th>
				<th></th>
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

				if(!empty($fltStat)){
					$filt.=" AND t2.laststatus='$fltStat'";
				}

				$filt.=filtpjt($petugas,$DBcon);

				$sqlhal="SELECT * FROM pibk_header t1 LEFT JOIN laststatus t2 ON t1.idpibk_header=t2.idlaststatus"; 
				$sqlhal.=" WHERE tgl_hawb>='$tgl_awal_sql' AND tgl_hawb<='$tgl_akhir_sql' AND t1.posisi='1' $filt";
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

				$sql="SELECT * FROM pibk_header t1 LEFT JOIN laststatus t2 ON t1.idpibk_header=t2.idlaststatus"; 
				$sql.=" WHERE tgl_hawb>='$tgl_awal_sql' AND tgl_hawb<='$tgl_akhir_sql' AND t1.posisi='1' $filt";
				$sql.=" ORDER BY idpibk_header ASC LIMIT $startNum,$rowPerPage";
				$query=$DBcon->query("$sql");
				$i=$startNum;
				while($data=$query->fetch_array()){
					$i++;
					$statakir= $data['laststatus'];
					if($statakir==2){ $trstyle="class='success'";}
					elseif($statakir==3){ $trstyle="class='warning'";}
					elseif($statakir==5){ $trstyle="class='danger'";}
					else{$trstyle="";}
		?>	
					<tr <?php echo $trstyle;?>>
						<td><?php echo $i; ?></td>
						<td><?php echo $data['no_mawb']; ?></td>
						<td><?php echo getpjt($data['id_pjt'],$DBcon); ?></td> 
						<td><?php echo $data['no_hawb']; ?></td>
						<td><?php echo getdateformatdmy($data['tgl_hawb']); ?></td>
						<td><?php echo getproses($data['laststatus'],$DBcon); ?></td>
						<td><?php echo getdateformatdmyhms($data['wakturekam']); ?></td>
						<td><button id="<?php echo $data['idpibk_header']; ?>" class="btn btn-default btn-show-detail"><span class="glyphicon glyphicon-zoom-in"></span>&nbsp;Detail</button></td>

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
					<input type="hidden" name="fltAwb" value="<?php echo $fltAwb;?>">
					<input type="hidden" name="fltStat" value="<?php echo $fltStat;?>">
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