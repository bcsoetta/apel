<<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Catatan NPD</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="bootstrap/css/bootstrap-datepicker.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/npdstyle.css" rel="stylesheet">
  </head>
  <body>
	<?php
	include 'konfigurasi/koneksi.php';
	include 'konfigurasi/common.func.php';

	$sql_header="SELECT * FROM npd_catatan WHERE id='$_GET[id]'";
	$query_header=$DBcon->query("$sql_header");
	$data=$query_header->fetch_array();
	?>
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
    			<h3 class="panel-title">Catatan NPD AWB no. <?php echo $data['awb']; ?></h3>
  			</div>
  			<div class="panel-body">
    			<?php echo $data['catatan']; ?>
  			</div>
		</div>
	</div>

	<script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-datepicker.js"></script>
    <link href="css/editor.css" type="text/css" rel="stylesheet"/>
    <script src="js/editor.js"></script>
</body>
</html>