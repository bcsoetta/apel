<?php
if(isset($_GET['act'])){
if($_GET['act']=='del'){
	$query=$DBcon->query("UPDATE tbl_user SET aktif='N' WHERE id='$_GET[id]'");
	if($query){
		$stat="<div class='alert alert-success'>DATA BERHASIL DIHAPUS</div>";
	} else {
		$stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DIHAPUS</div>";
	}
}

elseif($_GET['act']=='ret'){
	$query=$DBcon->query("UPDATE tbl_user SET aktif='Y' WHERE id='$_GET[id]'");
	if($query){
		$stat="<div class='alert alert-success'>DATA USER BERHASIL DI AKTIFKAN KEMBALI</div>";
	} else {
		$stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DIAKTIFKAN</div>";
	}
}
}

if(isset($_POST['act'])){
	$passhash=password_hash($_POST['addPass'], PASSWORD_DEFAULT);
	$query=$DBcon->query("INSERT INTO tbl_user(nip,nama,password,level,sessregpjt,aktif) VALUES ('$_POST[addIdUser]','$_POST[addNama]','$passhash','$_POST[addLevel]','$_POST[addCluster]','Y')");
	if($query){
		$stat="<div class='alert alert-success'>DATA USER BERHASIL DITAMBAH</div>";
	} else {
		$stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DITAMBAH</div>";
	}
}	
echo getbread($mod,$DBcon);  ?>
<div class="container">
	<?php if(isset($stat)){ echo $stat; }?>
	<div class="row">
		<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modCreate"><span class="glyphicon glyphicon-plus"></span>&nbsp;Tambah User</button>
	</div>
	<div class="row" style="margin-top:5px;">
		<table class="table table-condensed">
			<tr class="bg-primary">
				<th>No</th>
				<th>ID User</th>
				<th>Nama User</th>
				<th>Level</th>
				<th></th>
			</tr>
			<?php
				$querynum=$DBcon->query("SELECT * FROM tbl_user");
				$total=$querynum->num_rows;
				$adjacents = 3;
				$limit = 25; //jumlah halaman
				if(isset($_GET['page'])){ $page = $_GET['page']; } else { $page=0;};
				if($page){ 
					$start = ($page - 1) * $limit; //record pertama yang ditampilkan
				}else{
					$start = 0;
				}

				if ($page == 0) $page = 1; 
				$prev = $page - 1; 
				$next = $page + 1; 
				$lastpage = ceil($total/$limit); 
				$lpm1 = $lastpage - 1; 


				$sql	="SELECT * FROM tbl_user ORDER BY nama ASC LIMIT $start,$limit";
				$query	=$DBcon->query("$sql");
				$nom	=$start;
				while($data=$query->fetch_array()){
					$nom++;
					$id 		=$data['id'];
					$user_id	=$data['nip'];
					$nama_user	=$data['nama'];
					$level 		=$data['level'];
					$aktif		=$data['aktif'];
			?>
					<tr>
						<td><?php echo $nom;?></td>
						<td><?php echo $user_id;?></td>
						<td><?php echo $nama_user;?></td>
						<td><?php echo $level;?></td>
						<td class="text-right"><a href="?mod=17&id=<?php echo $id;?>"class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</a>
							<?php if($aktif=="Y"){ ?>
							<a href="?mod=<?php echo $mod;?>&id=<?php echo $id;?>&act=del" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span>&nbsp;Hapus</a>
							<?php } elseif($aktif=="N"){ ?>
							<a href="?mod=<?php echo $mod;?>&id=<?php echo $id;?>&act=ret" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span>&nbsp;Aktifkan</a>
							<?php }; ?>
						</td>
					</tr>
			<?php
				}
			?>
		</table>
		<?php
		include './konfigurasi/paging.php';
		echo createpaging($mod,$page,$prev,$next,$lastpage,$lpm1,$adjacents); ?>
	</div>
</div>
<div id="modCreate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myAddModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      	<h4 class="modal-title">Tambah Data User</h4>
      </div>
      <div class="modal-body">
      	<form method="post" action="?">
      		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
      		<input type="hidden" name="act" value="add">
          	<div class="form-group">
            	<label for="addIdUser" class="control-label">User ID</label>
            	<input type="text" class="form-control" name="addIdUser" required="required">
          	</div>
          	<div class="form-group">
            	<label for="addNama" class="control-label">Nama</label>
            	<input type="text" class="form-control" name="addNama">
          	</div>
          	<div class="form-group">
            	<label for="addPass" class="control-label">Password</label>
            	<input type="text" class="form-control" name="addPass">
          	</div>
          	<div class="form-group">
          		<label for="addLevel" class="control-label">Level</label>
			    <select name="addLevel" class="form-control">
			    	<option value="1">Admin</option>
			    	<option value="2">PFPPL</option>
			    	<option value="3">PJT</option>
			    	<option value="5">Admin Pos</option>
			    </select>
			</div>
          	<div class="form-group">
          		<label for="addCluster" class="control-label">Cluster</label>
			    <select name="addCluster" class="form-control">
			    	<option value="0">Bea Cukai</option>
			    	<?php
			    	$qSlc=$DBcon->query("SELECT * FROM ref_pjt ORDER BY nama_pjt ASC");
			    	while($hSlc=$qSlc->fetch_array()){ ?>
			    		<option value=<?php echo $hSlc['id']; ?>><?php echo $hSlc['nama_pjt']; ?></option>
			    	<?php
			    	}
			    	?>
			    </select>
			</div>
			<input type="submit" class="btn btn-primary" name="submit" value=" Send ">
        </form>
      </div>
      </div>
    </div>
  </div>
</div>