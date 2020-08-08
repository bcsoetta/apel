<?php echo getbread($mod,$DBcon); ?>
<div class="container">
	<div class="row" style="padding:5px">
		<div id="txtEditor"></div>
	</div>
	<div class="row" style="padding:5px">
		<form id="formsave" method="post" action="?">
			<input type="hidden" name="mod" value="10">
			<input type="hidden" name="exec" value="true">
			<input type="hidden" name="idproses" value="<?php echo $_POST['idproses'];?>">
			<textarea id="inputperiksa" style="display:none" name="inputperiksa"></textarea>
			Kesimpulan:
			<label class="radio">
				<input type="radio" name="radio_kesimpulan" value="Sesuai" checked>Sesuai
			</label>
			<label class="radio">
				<input type="radio" name="radio_kesimpulan" value="Tidak Sesuai">Tidak Sesuai
			</label>
		</form>
	</div>
	<div class="row" style="padding:5px">
		<button id="totext" class="btn btn-primary">Save LHP</button>
	</div>
</div>