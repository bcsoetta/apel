<?php
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
$modal_title='Detail Riwayat Status Dokumen';
$modal_content='<table class="table table-stripped">
	<tr class="bg-primary">
		<th>Nomor</th>
		<th>Status</th>
		<th>Waktu</th>
		<th>Keterangan</th>
		<th>Petugas</th>
	</tr>';
	$nomor=1;
	$reslt=$DBcon->query("SELECT * FROM pibk_ctl WHERE header_seq_id='$_POST[id]' ORDER BY ctl_seq_id DESC");
	while($datadet=$reslt->fetch_array()){
	$urStatus=getproses($datadet["ctl_stat"],$DBcon);
	$tmStatus=getdateformatdmyhms($datadet["ctl_time"]);
	$prStatus=$datadet["uraian_proses"];
	$ptStatus=getnamauser($datadet["user_id"],$DBcon);
	$modal_content.='<tr>
				<td><p><small>'.$nomor.'</small></p></td>
				<td><p><small>'.$urStatus.'</small></p></td>
				<td><p><small>'.$tmStatus.'</small></p></td>
				<td><p><small>'.$prStatus.'</small></p></td>
				<td><p><small>'.$ptStatus.'</small></p></td>			
			</tr>';
	$nomor++;	
	}; 

	$modal_content.='</table>';
echo json_encode(array('judul'=>$modal_title,'isi'=>$modal_content));
?>