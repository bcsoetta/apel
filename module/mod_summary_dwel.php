<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit'])){
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
	<div class="row">
		<div class="col-md-2"><p class="text-muted"><strong>Tgl Keputusan</strong></p></div>
		<div class="col-md-2"><p class="text-muted"><strong>sd Tgl:</strong></p></div>
		<div class="col-md-2">&nbsp;</div>
		<div class="col-md-6">&nbsp;</div>
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
			    <input type="submit" class="form-control btn btn-primary" name="submit" value="submit">
			</div>
			<div class="col-md-6">&nbsp;</div>
		</form>
	</div>
	<?php
	if(isset($_POST['submit']))
	{
	?>
	<div class="row">
		<p>Data Summary Harian Waktu NPD/SPBL Periode Putus tanggal: <?php echo $tgl_awal; ?> s.d. <?php echo $tgl_akhir;?></p>
	</div>
	<?php 
	}
	?>
	<div class="row">
		<table class="table-npd">
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Jumlah Dokumen</th>
				<th>Jumlah Waktu<br>(Jam:Menit:Detik)</th>
				<th>Rata-Rata Harian Perdok<br>(Jam:Menit:Detik)</th>
				<th></th>
			</tr>
			<?php
			if(isset($_POST['submit']))
			{
				$tgl_awal_sql=getdateformatymd($tgl_awal);
				$tgl_akhir_sql=getdateformatymd($tgl_akhir);

				$sql="SELECT DISTINCT DATE(npd_last_update_time) AS tanggal, COUNT(npd_id) AS jumdok";
				$sql.=", SUM(npd_waktu_bc) AS jumwaktu FROM npd_header ";
				$sql.="WHERE npd_flag_final_status='Y' AND DATE(npd_last_update_time)>='$tgl_awal_sql' ";
				$sql.="AND DATE(npd_last_update_time)<='$tgl_akhir_sql' GROUP BY DATE(npd_last_update_time)";
				$query=$DBcon->query("$sql");

				$no=0;
				while($row=$query->fetch_array()){
				$no++;
			?>
				<tr>
					<td align="center" width="5%"><?php echo $no;?></td>
					<td align="center" width="15%"><?php echo $row['tanggal']; ?></td>
					<td align="right" width="20%"><?php echo number_format($row['jumdok'],0); ?></td>
					<td align="right" width=25%><?php echo totaltime($row['jumwaktu']); ?></td>
					<td align="right" width=25%><?php echo totaltime($row['jumwaktu']/$row['jumdok']); ?></td>
					<td align="center"><p class="detail" id="<?php echo $tgl_awal_sql."|".$tgl_akhir_sql; ?>" style="color:#000FFF; cursor:pointer">Lihat Detail</p></td>
				</tr>
			<?php		
				}
			}
			?>
		</table>
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