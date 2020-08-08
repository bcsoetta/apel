<html>
<head>
	</head>
	<body>
		<table border="1">
<tr>
	<?php
	$i=0;

	include 'konfigurasi/koneksi.php';
	include 'konfigurasi/common.func.php';
	$reslt=$DBcon->query("SELECT * FROM tbl_batch_proses WHERE idbatch='$_GET[id]' ORDER BY id asc");
	while($datadet=$reslt->fetch_array()){
		$i++;
	$nohouse=gethouse($datadet["idheader"],$DBcon);
	?>
	<td style="height: 70px; width: 300px;"><img src="barcode.php?text=<?php echo $nohouse; ?>&print=true&size=40&codetype=Code128b" alt="<?php echo $nohouse; ?>" /></td>

	<?php
		if($i %2 == 0){
			if($i>0){ ?>
</tr> 
	<?php	} ?>
<tr>
	<?php	}
	 } 
	if($i>0){ ?>
</tr>
	<?php }
	 ?>
</table>
</body>
</html>