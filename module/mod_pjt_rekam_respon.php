<?php echo getbread($mod,$DBcon); ?>
<div class="container">
	<div class="row" style="padding:5px">
		<div id="txtEditor"></div>
	</div>
	<div class="row" style="padding:5px">
		<form id="formsave" method="post" enctype="multipart/form-data" action="?">
			<input type="hidden" name="mod" value="34">
			<input type="hidden" name="exec" value="true">
			<input type="hidden" name="idproses" value="<?php echo $_GET['id'];?>">
			<textarea id="inputrespon" style="display:none" name="inputrespon"></textarea>
			Lampirkan dokumen pendukung apabila diperlukan:
			<input type="file" name="lampiran">
		</form>
	</div>
	<div class="row" style="padding:5px">
		<button id="totext" class="btn btn-primary">Simpan Respon Notifikasi</button>
	</div>
</div>