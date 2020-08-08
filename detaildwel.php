<?php
include 'konfigurasi/koneksi.php';
include 'konfigurasi/common.func.php';
$modal_title='Detail Data';
$modal_content='<table class="table-npd">
	<tr>
		<th>Nomor</th>
		<th>No HAWB</th>
		<th>Tanggal HAWB</th>
		<th>Waktu BC</th>
		<th>Waktu Upload</th>
		<th>Waktu Putus</th>
		<th>PDTT</th>
	</tr>';
	$nomor=1;
	$sql="SELECT * FROM npd_header  ";
	$sql.="WHERE npd_flag_final_status='Y' AND DATE(npd_last_update_time)>='$_POST[ta]' ";
	$sql.="AND DATE(npd_last_update_time)<='$_POST[tr]'";
	$reslt=$DBcon->query("$sql");
	while($datadet=$reslt->fetch_array()){
	$hawb=$datadet["npd_hawb"];
	$tglhawb=getdateformatdmyhms($datadet["npd_tgl_hawb"]);
	$waktu=totaltime($datadet["npd_waktu_bc"]);
	$pdtt=getnamauser($datadet["npd_id_petugas"],$DBcon);
	$waktu_up=getdateformatdmyhms($datadet["npd_waktu_upload"]);
	$waktu_putus=getdateformatdmyhms($datadet["npd_last_update_time"]);
	$modal_content.='<tr>
				<td><p><small>'.$nomor.'</small></p></td>
				<td><p><small>'.$hawb.'</small></p></td>
				<td><p><small>'.$tglhawb.'</small></p></td>
				<td><p><small>'.$waktu.'</small></p></td>
				<td><p><small>'.$waktu_up.'</small></p></td>
				<td><p><small>'.$waktu_putus.'</small></p></td>
				<td><p><small>'.$pdtt.'</small></p></td>			
			</tr>';
	$nomor++;	
	}; 

	$modal_content.='</table>';
echo json_encode(array('judul'=>$modal_title,'isi'=>$modal_content));
?>