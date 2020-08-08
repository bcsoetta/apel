<?php echo getbread($mod,$DBcon); ?>
<div class="container">
	<div class="row" style="padding:5px">
		<div id="txtEditor"></div>
	</div>
	<div class="row" style="padding:5px">
		<form id="formsave" method="post" enctype="multipart/form-data" action="?">
			<input type="hidden" name="mod" value="42">
			<input type="hidden" name="exec" value="true">
			<input type="hidden" name="idproses" value="<?php echo $_GET['id'];?>">
			<textarea id="inputrespon" style="display:none" name="inputrespon"></textarea>
			Lampirkan scan dokumen LHP:
			<input type="file" name="lampiran">
		</form>
	</div>
	<div class="row" style="padding:5px">
		<button id="totext" class="btn btn-primary">Simpan Respon Merah</button>
	</div>
</div>