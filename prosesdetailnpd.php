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
		<th>User</th>
		<th>PDTT</th>
	</tr>';
	$nomor=1;
	$reslt=$DBcon->query("SELECT * FROM npd_control WHERE npdc_id_header='$_POST[id]' ORDER BY npdc_id ASC");
	while($datadet=$reslt->fetch_array()){
	$urStatus=getprosesnpd($datadet["npdc_status"],$DBcon);
	$tmStatus=getdateformatdmyhms($datadet["npdc_waktu"]);
	$flStatus=$datadet["npdc_flag_file"];
	$prStatus=$datadet["npdc_uraian"];
	$usStatus=getnamauser($datadet["npdc_user"],$DBcon);
	$ptStatus=getnamauser($datadet["npdc_petugas"],$DBcon);
	if($flStatus=="Y"){ $tampil='<a target="_blank" href="dokapnpd/'.$datadet["npdc_filename"].'">&nbsp;&nbsp;[Lihat Lampiran]</a>'; } else { $tampil=""; };
	$modal_content.='<tr>
				<td><p><small>'.$nomor.'</small></p></td>
				<td><p><small>'.$urStatus.'</small></p></td>
				<td><p><small>'.$tmStatus.'</small></p></td>
				<td><p><small>'.$prStatus.$tampil.'</small></p></td>
				<td><p><small>'.$usStatus.'</small></p></td>
				<td><p><small>'.$ptStatus.'</small></p></td>			
			</tr>';
	$nomor++;	
	}; 

	$modal_content.='</table>';
echo json_encode(array('judul'=>$modal_title,'isi'=>$modal_content));
?>