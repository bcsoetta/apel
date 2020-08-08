<?php
if(isset($_POST['submit'])){
  if(empty($_POST['edPass'])){
    $query=$DBcon->query("UPDATE tbl_user SET nip='$_POST[edIdUser]', nama='$_POST[edNama]',level='$_POST[edLevel]',sessregpjt='$_POST[edCluster]' WHERE id='$_POST[id]'");
  }else{
    $newPass=password_hash($_POST['edPass'], PASSWORD_DEFAULT);
    $query=$DBcon->query("UPDATE tbl_user SET nip='$_POST[edIdUser]', nama='$_POST[edNama]',password='$newPass',level='$_POST[edLevel]',sessregpjt='$_POST[edCluster]' WHERE id='$_POST[id]'");
  }
  if($query){
    $stat="<div class='alert alert-success'>DATA USER BERHASIL DIUBAH</div>";
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



$sql="SELECT * FROM tbl_user WHERE id='$id'";
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
              	<label for="edIdUser" class="control-label">User ID</label>
              	<input type="text" class="form-control" name="edIdUser" required="required" value="<?php echo $data['nip']; ?>">
            	</div>
            	<div class="form-group">
              	<label for="edNama" class="control-label" >Nama</label>
              	<input type="text" class="form-control" value="<?php echo $data['nama']; ?>" name="edNama">
            	</div>
            	<div class="form-group">
              	<label for="edPass" class="control-label">Password</label>
              	<input type="text" class="form-control" name="edPass">
            	</div>
            	<div class="form-group">
            		<label for="edLevel" class="control-label">Level</label>
  			    <select name="edLevel" class="form-control">
              <?php
              $qSlcLevel=$DBcon->query("SELECT * FROM ref_level WHERE idlevel='$data[level]'");
              $dataLevel=$qSlcLevel->fetch_array();
              ?>
  			    	<option value="<?php echo $dataLevel['idlevel']; ?>"><?php echo $dataLevel['deskripsilevel']; ?></option>
  			    	<?php
              $qSlcLevel2=$DBcon->query("SELECT * FROM ref_level WHERE idlevel!='$data[level]' ORDER BY idlevel ASC");
              while($dataLevel2=$qSlcLevel2->fetch_array()){
              ?>
              <option value="<?php echo $dataLevel2['idlevel']; ?>"><?php echo $dataLevel2['deskripsilevel']; ?></option>
  			      <?php }; ?>
            </select>
  			</div>
            	<div class="form-group">
            		<label for="edCluster" class="control-label">Cluster</label>
  			    <select name="edCluster" class="form-control">
  			    	<?php

              if($data['sessregpjt']==0){?>
                <option value="0">Bea Cukai</option>
                ?>
                <?php
                $qSlcClusterBC=$DBcon->query("SELECT * FROM ref_pjt WHERE id!='$data[sessregpjt]' ORDER BY nama_pjt ASC");
                while($hSlcClusterBC=$qSlcClusterBC->fetch_array()){ ?>
                <option value=<?php echo $hSlcClusterBC['id']; ?>><?php echo $hSlcClusterBC['nama_pjt']; ?></option>
              <?php
                }
              }else{  
                $qSlcCluster=$DBcon->query("SELECT * FROM ref_pjt WHERE id='$data[sessregpjt]'");
                $hSlcCluster=$qSlcCluster->fetch_array();			    	
              ?>
                <option value="<?php echo $hSlcCluster['id']; ?>"><?php echo $hSlcCluster['nama_pjt']; ?></option>
                <option value="0">Bea Cukai</option>
              <?php
                $qSlcCluster2=$DBcon->query("SELECT * FROM ref_pjt WHERE id!='$data[sessregpjt]' ORDER BY nama_pjt ASC");
  			    	  while($hSlcCluster2=$qSlcCluster2->fetch_array()){ ?>
  			    		<option value=<?php echo $hSlcCluster2['id']; ?>><?php echo $hSlcCluster2['nama_pjt']; ?></option>
  			    	<?php
  			    	  }
              }
  			    	?>
  			    </select>
  			</div>
  			<input type="submit" class="btn btn-primary" name="submit" value=" Send ">
        <a href="?mod=15" class="btn btn-warning"><span class="glyphicon glyphicon-circle-arrow-left"></span> Kembali</a>
  </form>
  </div>
  <div class="col-md-6">
  </div>
</div>
</div>