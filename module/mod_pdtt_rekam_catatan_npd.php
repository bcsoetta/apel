<?php echo getbread($mod,$DBcon);  
	$petugas=$_SESSION['userSession'];
	if($_POST['exec']=="true"){
		$catatan = addslashes($_POST[inputrespon]);
		$tglsql = getdateformatymd($_POST[tgl]);
		$sql="INSERT INTO npd_catatan(awb, tgl, catatan, petugas) VALUES ('$_POST[awb]', '$tgsql','$catatan','$petugas'";
		$insertdata=$DBcon->query("INSERT INTO npd_catatan(awb, tgl, catatan, petugas) VALUES ('$_POST[awb]', '$tglsql','$catatan','$petugas')");
		if($insertdata){
			$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil disimpan</div>";
		
		} else {
			$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal disimpan</div>";

		}
		//echo $sql;
	};
	?>
<div class="container">
<?php if(isset($stat)) { echo $stat; }?>
	<div class="row" style="padding:5px">
		<strong>Catatan NPD:</strong>
		<div id="txtEditor"></div>
	</div>
	<div class="row" style="padding:5px">
		<form id="formsave" method="post" enctype="multipart/form-data" action="?">
			<input type="hidden" name="mod" value="64">
			<input type="hidden" name="exec" value="true">
			<div class="row">
				<div class="form-group col-md-4">
					<label for="awb">Nomor House AWB:</label>
					<input type="text" name="awb" class="form-control">
				</div>
				<div class="form-group col-md-4">
					<label for="tgl">Tanggal House AWB:</label>
                	<div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
                	<input type="text" class="form-control" id="tgl" name="tgl" value="">
                    	<div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                	</div>
            	</div>
			</div>
			<textarea id="inputrespon" style="display:none" name="inputrespon"></textarea>
		</form>
	</div>
	<div class="row" style="padding:5px">
		<button id="totext" class="btn btn-primary">Simpan Respon Notifikasi</button>
	</div>
</div>