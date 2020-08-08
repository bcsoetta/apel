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
	foreach($_POST['bartoprint'] as $value){
	$i++;
	?>
	<td style="height: 70px; width: 300px;"><img src="barcode.php?text=<?php echo gethouse($value,$DBcon); ?>&print=true&size=40&codetype=Code128b" alt="<?php echo gethouse($value,$DBcon); ?>" /></td>

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