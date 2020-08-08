<?php
if(isset($_GET['act'])){
if($_GET['act']=='del'){
	$query=$DBcon->query("DELETE FROM ref_pjt WHERE id='$_GET[id]'");
	if($query){
		$stat="<div class='alert alert-success'>DATA BERHASIL DIHAPUS</div>";
	} else {
		$stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DIHAPUS</div>";
	}
}

}

if(isset($_POST['act'])){
	$query=$DBcon->query("INSERT INTO ref_pjt(npwp,nama_pjt,lokasi) VALUES ('$_POST[txtNpwp]','$_POST[txtNama]','$_POST[txtLokasi]')");
	if($query){
		$stat="<div class='alert alert-success'>DATA PJT BERHASIL DITAMBAH</div>";
	} else {
		$stat="<div class='alert alert-danger'>TERJADI KESALAHAN DATA GAGAL DITAMBAH</div>";
	}
}	
echo getbread($mod,$DBcon);  ?>
<div class="container">
	<?php if(isset($stat)){ echo $stat; }?>
	<div class="row">
		<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modCreate"><span class="glyphicon glyphicon-plus"></span>&nbsp;Tambah PJT</button>
	</div>
	<div class="row" style="margin-top:5px;">
		<table class="table table-condensed">
			<tr class="bg-primary">
				<th>No</th>
				<th>NPWP PJT</th>
				<th>Nama PJT</th>
				<th>Level</th>
				<th></th>
			</tr>
			<?php
				$querynum=$DBcon->query("SELECT * FROM ref_pjt");
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


				$sql	="SELECT * FROM ref_pjt ORDER BY nama_pjt ASC LIMIT $start,$limit";
				$query	=$DBcon->query("$sql");
				$nom	=$start;
				while($data=$query->fetch_array()){
					$nom++;
					$id 		=$data['id'];
					$npwp		=$data['npwp'];
					$nama_pjt	=$data['nama_pjt'];
					$lokasi 	=$data['lokasi'];
			?>
					<tr>
						<td><?php echo $nom;?></td>
						<td><?php echo $npwp;?></td>
						<td><?php echo $nama_pjt;?></td>
						<td><?php echo $lokasi;?></td>
						<td class="text-right"><a href="?mod=41&id=<?php echo $id;?>"class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</a>
							<a href="?mod=<?php echo $mod;?>&id=<?php echo $id;?>&act=del" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span>&nbsp;Hapus</a>
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
      	<h4 class="modal-title">Tambah Data PJT</h4>
      </div>
      <div class="modal-body">
      	<form method="post" action="?">
      		<input type="hidden" name="mod" value="<?php echo $mod; ?>">
      		<input type="hidden" name="act" value="add">
          	<div class="form-group">
            	<label for="txtNpwp" class="control-label">NPWP</label>
            	<input type="text" class="form-control" name="txtNpwp" required="required">
          	</div>
          	<div class="form-group">
            	<label for="txtNama" class="control-label">Nama</label>
            	<input type="text" class="form-control" name="txtNama">
          	</div>
          	<div class="form-group">
            	<label for="txtLokasi" class="control-label">Lokasi</label>
            	<input type="text" class="form-control" name="txtLokasi">
          	</div>
			<input type="submit" class="btn btn-primary" name="submit" value=" Send ">
        </form>
      </div>
      </div>
    </div>
  </div>
</div>