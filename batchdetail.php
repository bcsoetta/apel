<?php
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
$modal_title='Detail Batch';
$modal_content='<table class="table table-stripped">
	<tr class="bg-primary">
		<th>Nomor HAWB</th>
		<th>Tgl Terima</th>
		<th>Petugas</th>
	</tr>';
	$nomor=1;
	$reslt=$DBcon->query("SELECT * FROM tbl_batch_proses WHERE idbatch='$_POST[id]' ORDER BY id asc");
	while($datadet=$reslt->fetch_array()){
	$nohouse=gethouse($datadet["idheader"],$DBcon);
	$tglterima=getdateformatdmyhms($datadet["waktu"]);
	$petugas=getnamauser($datadet["idpetugas"],$DBcon);
	$modal_content.='<tr>
				<td><p><small>'.$nohouse.'</small></p></td>
				<td><p><small>'.$tglterima.'</small></p></td>
				<td><p><small>'.$petugas.'</small></p></td>			
			</tr>';
	$nomor++;	
	}; 

	$modal_content.='</table>';
echo json_encode(array('judul'=>$modal_title,'isi'=>$modal_content));
?>