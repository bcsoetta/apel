<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	$waktu=date("h:i a");
	$mulai="11:30 pm";
	$akhir="07:30 am";
	$wkt1=DateTime::createFromFormat('H:i a', $waktu);
	$wkt2=DateTime::createFromFormat('H:i a', $mulai);
	$wkt3=DateTime::createFromFormat('H:i a', $akhir);
if($wkt1<$wkt2 && $wkt1>$wkt3){
	if(isset($_POST['submit'])){
		$awb_tgl	=$_POST['awb_tgl'];
		$awb_tgl_sql=getdateformatymd($awb_tgl);
		$awb_in		=$_POST['awb_in'];
		$pdtt		=$_POST['pdtt'];
		$keterangan	=addslashes($_POST['keterangan']);

		$filt="";
		$filt.=filtpjt($petugas,$DBcon);

		$sql_cek_header= "SELECT idpibk_header,id_pjt FROM pibk_header JOIN pibk_dokap ON idpibk_header=idheader WHERE no_hawb='$awb_in' AND tgl_hawb='$awb_tgl_sql' $filt ORDER BY idpibk_header ASC LIMIT 1";
		$cekdata=$DBcon->query("$sql_cek_header");
		$jumdata=$cekdata->fetch_array();
		$jumlah=$cekdata->num_rows;
		$id_pibk_header=$jumdata['idpibk_header'];
		$id_pjt=$jumdata['id_pjt'];

		$sql_cek_header_npd= "SELECT npd_id FROM npd_header WHERE npd_hawb='$awb_in' AND npd_tgl_hawb='$awb_tgl_sql'";
		//echo "$sql_cek_header_npd";
		//echo "$sql_cek_header";
		$cekdatanpd=$DBcon->query("$sql_cek_header_npd");
		$jumlahnpd=$cekdatanpd->num_rows;
		//echo $jumlahnpd;
		if($jumlah>0){
			if($jumlahnpd==0){
				if(!empty($_FILES['lampiran']['name'])){
					$pathToSave="dokapnpd/";
					$file_name 	=$_FILES['lampiran']['name'];
					$file_size 	=$_FILES['lampiran']['size'];
					$file_tmp 	=$_FILES['lampiran']['tmp_name'];
					$file_type 	=$_FILES['lampiran']['type'];
					$file_ext_arr 	=explode('.',$file_name);
					$file_ext=$file_ext_arr[1];
					if ($file_size<10000000) 
					{
						if ($file_ext=="pdf") 	{
							$got_name 		= date('Ymdhis');
							$got_name 		= $petugas."_"."$got_name.$file_ext";
							copy($file_tmp,"$pathToSave"."$got_name");
							$sql_header="INSERT INTO npd_header(npd_id, npd_hawb, npd_tgl_hawb, npd_id_petugas, npd_waktu_upload, npd_uraian,";
							$sql_header.=" npd_flag_absen, npd_flag_file, npd_file_name,npd_id_pjt, npd_last_update_time, ";
							$sql_header.=" npd_waktu_pjt, npd_waktu_bc, npd_flag_last_status, npd_flag_final_status)";
							$sql_header.=" VALUES('$id_pibk_header', '$awb_in', '$awb_tgl_sql', '$pdtt', NOW(), '$keterangan', '0', 'Y', ";
							$sql_header.=" '$got_name', '$id_pjt', NOW(), '0', '0', '1', 'N') ";

							$insertdata_header=$DBcon->query("$sql_header");

							$sql_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
							$sql_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$id_pibk_header', NOW(), '$petugas', '$keterangan', ";
							$sql_control.="'Y','$got_name','$pdtt','1')";

							$insertdata_control=$DBcon->query("$sql_control");

							if($insertdata_header && $insertdata_control){
								//cek status absen
								$sql_absen ="SELECT * FROM npd_absen WHERE npda_id_user='$pdtt'";
								$query_absen=$DBcon->query("$sql_absen");
								$jum_absen=$query_absen->num_rows;

								if($jum_absen>0){
									$sql_update_header="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_last_update_time=NOW() WHERE npd_id='$id_pibk_header'"; 
									$query_update_header=$DBcon->query("$sql_update_header");
								

									$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
									$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$id_pibk_header', NOW(), '900', 'Data diterima', ";
									$sql_update_control.="'N','none','$pdtt','2')";
									$query_update_control=$DBcon->query("$sql_update_control");
								}  
								$stat="<div class='row'><div class='alert alert-success col-md-8'><span class='glyphicon glyphicon-info-sign'></span> Respon berhasil dikirim</div><div class='col-md-4'></div></div>";
							}
							
						} else {
							$stat="<div class='row'><div class='alert alert-danger col-md-8'><span class='glyphicon glyphicon-info-sign'></span> Format File Tidak Sesuai</div><div class='col-md-4'></div></div>";
						}
					}
					else
					{
						$stat="<div class='alert alert-danger'>Ukuran File Lebih dari 10 MB</div>";
					}
				}
				else{
					
					$sql_header="INSERT INTO npd_header(npd_id, npd_hawb, npd_tgl_hawb, npd_id_petugas, npd_waktu_upload, npd_uraian,";
					$sql_header.=" npd_flag_absen, npd_flag_file, npd_file_name,npd_id_pjt, npd_last_update_time, ";
					$sql_header.=" npd_waktu_pjt, npd_waktu_bc, npd_flag_last_status, npd_flag_final_status)";
					$sql_header.=" VALUES('$id_pibk_header', '$awb_in', '$awb_tgl_sql', '$pdtt', NOW(), '$keterangan', '0', 'N', ";
					$sql_header.=" 'none', '$id_pjt', NOW(), '0', '0', '1', 'N') ";
					
					//echo $sql_header;
					
					$insertdata_header=$DBcon->query("$sql_header");

					$sql_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
					$sql_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$id_pibk_header', NOW(), '$petugas', '$keterangan', ";
					$sql_control.="'N','none','$pdtt','1')";

					$insertdata_control=$DBcon->query("$sql_control");
					
					if($insertdata_header && $insertdata_control)
					{
							//cek status absen
							$sql_absen ="SELECT * FROM npd_absen WHERE npda_id_user='$pdtt'";
							$query_absen=$DBcon->query("$sql_absen");
							$jum_absen=$query_absen->num_rows;
							if($jum_absen>0){
								$sql_update_header="UPDATE npd_header SET npd_flag_absen='1',npd_flag_last_status='2', npd_last_update_time=NOW() WHERE npd_id='$id_pibk_header'"; 
								$query_update_header=$DBcon->query("$sql_update_header");

								$sql_update_control="INSERT INTO npd_control(npdc_id_header, npdc_waktu, npdc_user, npdc_uraian, npdc_flag_file, ";
								$sql_update_control.="npdc_filename, npdc_petugas, npdc_status) VALUES('$id_pibk_header', NOW(), '900', 'Data diterima', ";
								$sql_update_control.="'N','none','$pdtt','2')";
							}  
							$stat="<div class='row'><div class='alert alert-success col-md-8'><span class='glyphicon glyphicon-info-sign'></span> Respon berhasil dikirim</div><div class='col-md-4'></div></div>";
					}
				}
			}
			else{
				$stat="<div class='row'><div class='alert alert-danger col-md-8'><span class='glyphicon glyphicon-info-sign'></span> Respon NPD sudah pernah direkam harap cek kembali</div><div class='col-md-4'></div></div>";
			};
		}
		else{
				$stat="<div class='row'><div class='alert alert-danger col-md-8'><span class='glyphicon glyphicon-info-sign'></span> Data AWB tidak ditemukan atau data dokap blm disubmit, Harap Upload terlebih dahulu</div><div class='col-md-4'></div></div>";
		}
	}
	?>
<div class="container">
	<?php if(isset($stat)) { echo $stat; }?>
	<form class="form" method="post" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<div class="row">
			<div class="form-group col-md-4">
				<label >Nomor House Airwaybill</label>
				<input type="text" class="form-control" required name="awb_in" placeholder="nomor hawb" value="<?php if(isset($awb_in)){ echo $awb_in;} ?>">
			</div>
			<div class="form-group col-md-4">
				<label >Tanggal House Airwaybill</label>
        		<div class="input-group date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
        			<input type="text" required class="form-control" id="awb_tgl" placeholder="tanggal hawb" name="awb_tgl" value="<?php if(isset($awb_in)){ echo $awb_tgl; }; ?>">
            		<div class="input-group-addon">
                    	<span class="glyphicon glyphicon-th"></span>
                	</div>
        		</div>
        	</div>
        	<div class="col-md-4"></div>
        </div>
        <div class="row">
        	<div class="form-group col-md-8">
        		<label >Keterangan</label>
        		<textarea class="form-control" name="keterangan" placeholder="Keterangan"><?php if(isset($keterangan)){ echo $keterangan;} ?></textarea>
        	</div>
        </div>
        <div class="row">
        	<div class="form-group col-md-8">
        		<input type="file" name="lampiran">
        	</div>
        </div>
        <div class="row">
        	<div class="form-group col-md-8">
      			<label for="pdtt" class="control-label">Nama Petugas</label>
			    <select name="pdtt" class="form-control">
			    	<?php
			    	$qSlc=$DBcon->query("SELECT * FROM tbl_user WHERE aktif='Y' AND level='2' AND id!='124' ORDER BY nama ASC");
			    	while($hSlc=$qSlc->fetch_array()){ ?>
			    		<option value=<?php echo $hSlc['id']; ?>><?php echo strtoupper($hSlc['nama']); ?></option>
			    	<?php
			    	}
			    	?>
			    </select>
			</div>
		</div>
        <div class="row">
        	<div class="form-group col-md-8">
				<input type="submit" class="form-control btn btn-primary col-md-2" name="submit" value="submit">
			</div>
		</div>
	</form>
	<div class="row">
		<div class="form-group col-md-8">
			<div class="alert alert-danger">Pemberitahuan:</div>
			<div class="alert alert-success">Untuk submit dokumen PIBK yang mendapatkan respon NPBL dari sistem apabila barang impor yang bersangkutan telah memenuhi persyaratan lartasnya atau termasuk dalam pengecualian atau NPD/SPBL yang PDTTnya telah pindah tugas silahkan direspon dengan menu ini dengan nama PDTT "SYSTEM"</div>
		</div>
	</div>
</div>
<?php }
else {
?>
	<div class="container">
		<div class="alert alert-danger">Waktu untuk merespon npd telah melewati batas waktu pelayanan (07.30 s.d. 23.30)</div>
	</div>
<?php
}
?>