<?php 
if(isset($_POST['sendata'])){
	$tglinput=date("Y-m-d H:i:s");
	$pathToSave="dokap/";
	$preid=$_SESSION['userSession'];

	$file_name 	=$_FILES['contupload']['name'];
	$file_size 	=$_FILES['contupload']['size'];
	$file_tmp 	=$_FILES['contupload']['tmp_name'];
	$file_type 	=$_FILES['contupload']['type'];
	$file_ext_arr 	=explode('.',$file_name);
	$file_ext=$file_ext_arr[1];

	if ($file_ext=="pdf") 	{
		$got_name 		= date('Ymdhis');
		$got_name 		= $preid."_"."$got_name.$file_ext";
		copy($file_tmp,"$pathToSave"."$got_name");
		$insertdokap=$DBcon->query("INSERT INTO pibk_dokap(idheader,namafile,waktuupload) VALUES('$_POST[headerid]','$got_name','$tglinput')");
		
		if($insertdokap){
			$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>File Dokap berhasil diupload</div>";	
			//$updatedata=$DBcon->query("UPDATE pibk_header SET posisi='3' WHERE idpibk_header='$_POST[headerid]'");
			$exec_ctl=$DBcon->query("INSERT INTO pibk_ctl(header_seq_id, ctl_stat, ctl_time, user_id, flag_proses) VALUES ('$_POST[headerid]', '1','$tglinput','900','1')");
        	$exec_induk=$DBcon->query("UPDATE pibk_header SET posisi='1' WHERE idpibk_header='$_POST[headerid]'");
        	$updatelaststat=$DBcon->query("INSERT INTO laststatus(idlaststatus,laststatus,wakturekam) VALUES('$_POST[headerid]','1','$tglinput')");
		}
		else {
			$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>File Gagal diupload data sudah ada</div>";
		}
			
	} else {
		$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>File Format Tidak Sesuai</div>";
	}
};
echo getbread($mod,$DBcon);  ?>

<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<div class="row">
		<form method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod; ?>">
			<div class="form-group col-md-3">
				<label for="srchawb">No HAWB.</label>
				<input type="text" class="form-control" autofocus name="srchawb">
			</div>
		</form>
	</div>
	<?php
	if(isset($_POST['srchawb'])){

		$resl=$DBcon->query("SELECT * FROM pibk_header WHERE no_hawb='$_POST[srchawb]' AND posisi='0' ");
		$jumlahdata=$resl->num_rows;
		
		if($jumlahdata>0){
			$i=0; ?>
			<table class="table table-stripped">
					<tr>
						<th>No</th>
						<th>No MAWB</th>
						<th>Tgl MAWB</th>
						<th>No HAWB</th>
						<th>Tgl HAWB</th>
						<th>No BC 1.1</th>
						<th>Tgl BC 1.1</th>
						<th>Upload</th>
					</tr>
			<?php
			$count=0;
			while($data=$resl->fetch_array()){ 
				$i++;
				$count++;
				?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $data['no_mawb']; ?></td>
						<td><?php echo $data['tgl_mawb']; ?></td>
						<td><?php echo $data['no_hawb']; ?></td>
						<td><?php echo $data['tgl_hawb']; ?></td>
						<td><?php echo $data['no_bc']; ?></td>
						<td><?php echo $data['tgl_bc']; ?></td>
						<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#md<?php echo $data['idpibk_header']; ?>">Proses Data</button></td>
					</tr>
			<?php
			$headdata[$i]=$data['idpibk_header'];
				} ?>
			</table>
		<?php
		} else {
			echo "<div class='alert alert-warning alert-dismissible' role='alert'>
  					<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden=true'>&times;</span></button>
  					<strong>maaf data yang anda cari tidak ada</strong>
					</div>";
		}

	};
	//echo $jumlahdata;?>
</div>
<?php 
if(isset($jumlahdata)){
if($jumlahdata>0){
for($j=1;$j<=$jumlahdata;$j++) { ?>
<div id="md<?php echo $headdata[$j];?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog" role="document">
  	<div class="modal-content">
	  	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Load data pelengkap PIBK. </h4>
	    </div>
	    <div class="modal-body">
	    	<form method="post" action="?" enctype="multipart/form-data">
	    		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
	    		<input type="hidden" name="headerid" value="<?php echo $headdata[$j];?>">
	    		<div class="form-group">
    				<label for="contupload">File input</label>
    				<input type="file" name="contupload">
    				<p class="help-block">File data dalam bentuk 1 file pdf ukuran max 10MB, pastikan tidak terdapat tanda "." (titik) dalam penamaan file anda</p>
    				<p class="help-block">File dimaksud memuat data invoice, packing list, airwaybill, data pendukung lainnya</p>
  				</div>
	    		<input type="submit" class="form-control btn btn-primary" name="sendata" value="submit">
	    	</form>
	    </div>
	</div>
</div>
</div>
<?php 
}
}; 
};
?>