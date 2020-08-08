<?php echo getbread($mod,$DBcon);  ?>
<div class="container">
	<?php
		$id=$_GET['id'];
		$query=$DBcon->query("SELECT * FROM pibk_periksa WHERE periksa_id_header='$id'");
		$nomor=0;
		while($data=$query->fetch_array()){
			$nomor++;
	?>	
			<div class="row" style="padding:5px;">
				<h4>LAPORAN HASIL PEMERIKSAAN <?php if($nomor>1){ echo "Ke-".$nomor." "; }; echo "HOUSE AWB NO ".gethouse($id,$DBcon); ?></h4>
				<p><?php echo stripslashes($data['periksa_lhp']); ?></p>
				<p><small><?php echo "Pemeriksa: ".getnamauser($data['periksa_petugas'],$DBcon)."<br>"."jam: ".getdateformatdmyhms($data['periksa_waktu']);?></small></p>
			</div>
	<?php
		}
	?>
</div>