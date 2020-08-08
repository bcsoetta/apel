<html>
<head>
	</head>
	<body>
		<table width="100%">
<tr>
<?php
$i=0;

include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
foreach($_POST['bartoprint'] as $value){
$i++;
?>
<td width="50%">
<img src="barcode.php?text=<?php echo getems($value,$DBcon); ?>&print=true&size=40&codetype=Code39" alt="<?php echo getems($value,$DBcon); ?>" /></td>

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