<?php
if(isset($_POST['submit'])){
    $query=$DBcon->query("UPDATE ref_pjt SET npwp='$_POST[txtNpwp]', nama_pjt='$_POST[txtNama]',lokasi='$_POST[txtLokasi]' WHERE id='$_POST[id]'");
  if($query){
    $stat="<div class='alert alert-success'>DATA PJT BERHASIL DIUBAH</div>";
  } else {
    $stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DIUBAH</div>";
  }

};
if(isset($_GET['id'])){
  $id=$_GET['id'];
}
if(isset($_POST['id'])){
  $id=$_POST['id'];
}


$sql="SELECT * FROM ref_pjt WHERE id='$id'";
$query=$DBcon->query("$sql");
$data=$query->fetch_array();
echo getbread($mod,$DBcon); 
if(!empty($testsql)){echo $testsql;} ?>
<div class="container">
<?php if(isset($stat)){ echo $stat; }?>
<div class="row">
  <div class="col-md-6">
  <form method="post" action="?">
        		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
        		<input type="hidden" name="id" value="<?php echo $id; ?>">
            	<div class="form-group">
              	<label for="txtNpwp" class="control-label">NPWP</label>
              	<input type="text" class="form-control" name="txtNpwp" required="required" value="<?php echo $data['npwp']; ?>">
            	</div>
            	<div class="form-group">
              	<label for="txtNama" class="control-label" >Nama</label>
              	<input type="text" class="form-control" value="<?php echo $data['nama_pjt']; ?>" name="txtNama">
            	</div>
            	<div class="form-group">
              	<label for="txtLokasi" class="control-label">Lokasi</label>
              	<input type="text" class="form-control" value="<?php echo $data['lokasi']; ?>" name="txtLokasi">
            	</div>
  			<input type="submit" class="btn btn-primary" name="submit" value=" Send ">
        <a href="?mod=40" class="btn btn-warning"><span class="glyphicon glyphicon-circle-arrow-left"></span> Kembali</a>
  </form>
  </div>
  <div class="col-md-6">
  </div>
</div>
</div>