<?php
if(isset($_POST['submit'])){
  $oldPassword=$_POST['oldPassword'];
  $newPassword=$_POST['newPassword'];
  $conPassword=$_POST['conPassword'];

  $query        = $DBcon->query("SELECT * FROM tbl_user WHERE id='$_SESSION[userSession]'");
  $row          = $query->fetch_array();
  $count        = $query->num_rows;
  if (password_verify($oldPassword, $row['password']) && $count==1) {
    if($newPassword==$conPassword){
      $newPass=password_hash($newPassword, PASSWORD_DEFAULT);
      $query=$DBcon->query("UPDATE tbl_user SET password='$newPass' WHERE id='$_POST[id]'");
      if($query){ 
        $stat="<div class='alert alert-success'>Passwor Berhasil diganti</div>";
      }
    }
    else{
      $stat="<div class='alert alert-danger'>Konfirmasi Password tidak sama</div>";
    }
  }
  else{
    $stat="<div class='alert alert-danger'>Password lama anda tidak sesuai</div>";
  }

};

echo getbread($mod,$DBcon); 
?>
<div class="container">
<?php if(isset($stat)){ echo $stat; }?>
<div class="row">
  <div class="col-md-6">
    <form method="post" action="?">
  		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
  		<input type="hidden" name="id" value="<?php echo $_SESSION['userSession']; ?>">
      <div class="form-group">
        <label for="oldPassword" class="control-label">Password lama</label>
        <input type="password" class="form-control" name="oldPassword" required="required">
      </div>
      <div class="form-group">
        <label for="oldPassword" class="control-label" >Password baru</label>
        <input type="password" class="form-control" name="newPassword">
      </div>
      <div class="form-group">
        <label for="conPassword" class="control-label">Konfirmasi Password baru</label>
        <input type="password" class="form-control" name="conPassword">
      </div>	
  			<input type="submit" class="btn btn-primary" name="submit" value=" Send ">
    </form>
  </div>
  <div class="col-md-6">
  </div>
</div>
</div>