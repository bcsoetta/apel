<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	if(isset($_POST['submit']) ){
		$tgl	=$_POST['tgl'];
	}
		if (empty($tgl))	{
			$tglval 	= date('d');
			$blnval 	= date('m');
			$thnval 	= date('Y');
			$tgl 		= "$tglval/$blnval/$thnval";
		};
	?>
<div class="container">
	<div class="row">
		<div class="col-md-4"><p class="text-muted"><strong>Tgl Keputusan</strong></p></div>
		<div class="col-md-2">&nbsp;</div>
		<div class="col-md-6">&nbsp;</div>
	</div>
	<div class="row">
		<form class="form" method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<div class="form-group col-md-4">
                <div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
                	<input type="text" class="form-control" id="tgl" name="tgl" value="<?php echo $tgl;?>">
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
	<hr>
	<?php if(isset($_POST['submit'])){ ?>
	<div class="row">
		<div class="col-md-3">
		<?php

		$pecahtgl=explode('/',$tgl);
				$hari=(intval($pecahtgl[0]))+1;
				$tgldua=$hari."/".$pecahtgl[1]."/".$pecahtgl[2];
				$tgl_awal_sql=getdateformatymd($tgl);
				$tgl_akhir_sql=getdateformatymd($tgldua);

		$sqlrelease="SELECT user_id,count(header_seq_id) AS jumdok ";
				$sqlrelease.="FROM db_apel.pibk_ctl ";
				$sqlrelease.="WHERE ctl_time>='$tgl_awal_sql'  ";
				$sqlrelease.="AND ctl_time<'$tgl_akhir_sql'  ";
				$sqlrelease.="AND ctl_stat='2'";
				$sqlrelease.="GROUP BY user_id ORDER BY count(header_seq_id) DESC LIMIT 10";
			?>
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th colspan="3">TOP 10 Release Dokumen</th>
			</tr>
			<?php
			$queryrelease=$DBcon->query("$sqlrelease");
				$a=0;
				while($datarelease=$queryrelease->fetch_array()){
					$a++;
					?>
			<tr class="bg-success">
				<td><?php echo $a;?></td>
				<td><?php echo getnamauser($datarelease['user_id'],$DBcon); ?></td>
				<td><?php echo $datarelease['jumdok'];?> </td>
			</tr>
			<?php }; ?>
		</table>
		</div>
		<div class="col-md-3">
		<?php
		$sqlmerah="SELECT user_id,count(header_seq_id) AS jumdok ";
				$sqlmerah.="FROM db_apel.pibk_ctl ";
				$sqlmerah.="WHERE ctl_time>='$tgl_awal_sql'  ";
				$sqlmerah.="AND ctl_time<'$tgl_akhir_sql'  ";
				$sqlmerah.="AND ctl_stat='5'";
				$sqlmerah.="GROUP BY user_id ORDER BY count(header_seq_id) DESC LIMIT 10";
			?>
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th colspan="3">TOP 10 Merahin Dokumen</th>
			</tr>
			<?php
			$querymerah=$DBcon->query("$sqlmerah");
				$b=0;
				while($datamerah=$querymerah->fetch_array()){
					$b++;
					?>
			<tr class="bg-danger">
				<td><?php echo $b;?></td>
				<td><?php echo getnamauser($datamerah['user_id'],$DBcon); ?></td>
				<td><?php echo $datamerah['jumdok'];?> </td>
			</tr>
			<?php }; ?>
		</table>
		</div>
		<div class="col-md-3">
		<?php
		$sqlnotif="SELECT user_id,count(header_seq_id) AS jumdok ";
				$sqlnotif.="FROM db_apel.pibk_ctl ";
				$sqlnotif.="WHERE ctl_time>='$tgl_awal_sql'  ";
				$sqlnotif.="AND ctl_time<'$tgl_akhir_sql'  ";
				$sqlnotif.="AND ctl_stat='3'";
				$sqlnotif.="GROUP BY user_id ORDER BY count(header_seq_id) DESC LIMIT 10";
			?>
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th colspan="3">TOP 10 Notifin Dokumen</th>
			</tr>
			<?php
			$querynotif=$DBcon->query("$sqlnotif");
				$c=0;
				while($datanotif=$querynotif->fetch_array()){
					$c++;
					?>
			<tr class="bg-warning">
				<td><?php echo $c;?></td>
				<td><?php echo getnamauser($datanotif['user_id'],$DBcon); ?></td>
				<td><?php echo $datanotif['jumdok'];?> </td>
			</tr>
			<?php }; ?>
		</table>
		</div>
		<div class="col-md-3">
		<?php
		$sqlall="SELECT user_id,count(header_seq_id) AS jumdok ";
				$sqlall.="FROM db_apel.pibk_ctl ";
				$sqlall.="WHERE ctl_time>='$tgl_awal_sql'  ";
				$sqlall.="AND ctl_time<'$tgl_akhir_sql'  ";
				$sqlall.="AND (ctl_stat='3' OR ctl_stat='5' OR ctl_stat='2') ";
				$sqlall.="GROUP BY user_id ORDER BY count(header_seq_id) DESC LIMIT 10";
			?>
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th colspan="3">TOP 10 Ngerjain Dokumen</th>
			</tr>
			<?php
			$queryall=$DBcon->query("$sqlall");
				$d=0;
				while($dataall=$queryall->fetch_array()){
					$d++;
					?>
			<tr>
				<td><?php echo $d;?></td>
				<td><?php echo getnamauser($dataall['user_id'],$DBcon); ?></td>
				<td><?php echo $dataall['jumdok'];?> </td>
			</tr>
			<?php }; ?>
		</table>
		</div>
	</div>
	<?php }; ?>
	<?php if(isset($_POST['submit'])){ ?>
	<div class="row">
	<?php
		$sqlrekapall="SELECT id_pjt,ctl_stat,count(ctl_seq_id) AS jumdok ";
		$sqlrekapall.="FROM db_apel.pibk_ctl JOIN db_apel.pibk_header ON idpibk_header=header_seq_id ";
		$sqlrekapall.="WHERE ctl_time>='$tgl_awal_sql'  ";
		$sqlrekapall.="AND ctl_time<'$tgl_akhir_sql'  ";
		$sqlrekapall.="AND (ctl_stat='3' OR ctl_stat='5' OR ctl_stat='2') AND (id_pjt='1' OR id_pjt='2' OR id_pjt='3')";
		$sqlrekapall.="GROUP BY id_pjt,ctl_stat ORDER BY id_pjt ASC";
	?>
		<table class="table table-bordered">
			<?php
			$queryrekapall=$DBcon->query("$sqlrekapall");
				$ww=0;
				while($datarekapall=$queryrekapall->fetch_array()){
					$ww++;
					$statak=$datarekapall['ctl_stat'];
					if($statak==2){ $trstyles="class='success'";}
					elseif($statak==3){ $trstyles="class='warning'";}
					elseif($statak==5){ $trstyles="class='danger'";}
					else{$trstyles="";}
					?>
			<tr <?php echo $trstyles; ?>>
				<td><?php echo $ww;?></td>
				<td><?php echo getpjt($datarekapall['id_pjt'],$DBcon); ?></td>
				<td align="right"><?php echo number_format($datarekapall['jumdok'],0);?> </td>
			</tr>
			<?php }; ?>
		</table>
	</div>
	<?php }; ?>
	<div class="row">
		<table class="table table-bordered">
			<tr class="bg-primary">
				<th>Nama Petugas</th>
				<th>Keputusan</th>
				<th>Jumlah</th>
			</tr>
		<?php
			if(isset($_POST['submit'])){
				

				$sql="SELECT user_id,ctl_stat,count(header_seq_id) AS jumdok ";
				$sql.="FROM db_apel.pibk_ctl ";
				$sql.="WHERE ctl_time>='$tgl_awal_sql'  ";
				$sql.="AND ctl_time<'$tgl_akhir_sql'  ";
				$sql.="AND (ctl_stat='2' OR ctl_stat='3' OR ctl_stat='5')   ";
				$sql.="GROUP BY user_id,ctl_stat with rollup";
//echo $sql;
				$query=$DBcon->query("$sql");
				$i=0;
				while($data=$query->fetch_array()){
					$i++;
					$user[$i]=$data['user_id'];
					$statakir=$data['ctl_stat'];
					if($statakir==2){ $trstyle="class='success'";}
					elseif($statakir==3){ $trstyle="class='warning'";}
					elseif($statakir==5){ $trstyle="class='danger'";}
					else{$trstyle="";}
		?>	
					<tr <?php echo $trstyle; ?>>
						<td><?php if($user[$i]!=$user[$i-1]){ echo getnamauser($data['user_id'],$DBcon); }  ?></td>
						<td><?php echo getproses($data['ctl_stat'],$DBcon); ?></td>
						<td align="right"><?php echo number_format($data['jumdok'],0); ?></td>
					</tr>

		<?php
				;
				}
			}
		?>
		</table>
	</div>
</div>