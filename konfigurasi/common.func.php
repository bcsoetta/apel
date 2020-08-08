<?php
//fungsi untuk zona waktu default
date_default_timezone_set("Asia/Jakarta");

//fungsi untuk mendefinisikan mod ke file menu
function getfilemenu($mod,$konek){
	$query 	= $konek->query("SELECT * FROM mainmenu WHERE idmenu='$mod'");
	$rowdata=$query->fetch_array();
	$filemenu=$rowdata['file'];
	return $filemenu;
}

//fungsi untuk mengecek ada tidaknya file js untuk modul
function cekfilejs($mod,$konek){
	
	$query 	= $konek->query("SELECT * FROM mainmenu WHERE idmenu='$mod'");
	$rowdata=$query->fetch_array();
	$jsfile=$rowdata['flagjavascript'];
	return $jsfile;
}
function getfilejs($mod,$konek){	
	$query 	= $konek->query("SELECT * FROM mainmenu WHERE idmenu='$mod'");
	$rowdata=$query->fetch_array();
	$file=$rowdata['file'];
	$fileremovemod=str_replace("mod_","", $file);
	$fileremoveundscr=str_replace("_", "", $fileremovemod);
	$pecah=explode(".",$fileremoveundscr);
	$jsfilename=$pecah[0].".js";
	$jspathfile='js/'.$jsfilename;
	return $jspathfile;
}

//fungsi untuk mencari house berdasarkan id record
function gethouse($id,$konek){
	$query 	= $konek->query("SELECT * FROM pibk_header WHERE idpibk_header='$id'");
	$rowdata=$query->fetch_array();
	$filemenu=$rowdata['no_hawb'];
	return $filemenu;
}


//fungsi untuk mencari PDF berdasarkan id record
function getpdf($id,$konek){
	$query 	= $konek->query("SELECT * FROM pibk_dokap WHERE idheader='$id'");
	$rowdata=$query->fetch_array();
	$filepdf="dokap/".$rowdata['namafile'].".pdf";
	return $filepdf;
}


//fungsi untuk mencari nama PJT berdasarkan id connect
function getpjt($id,$konek){
	$query 	= $konek->query("SELECT * FROM ref_pjt WHERE id='$id'");
	$rowdata=$query->fetch_array();
	$filemenu=$rowdata['nama_pjt'];
	$filemenu=strtoupper($filemenu);
	return $filemenu;
}

//fungsi untuk mencari namauser berdasarkan id record
function getnamauser($id,$konek){
	$query 	= $konek->query("SELECT * FROM tbl_user WHERE id='$id'");
	$rowdata=$query->fetch_array();
	$nama=$rowdata['nama'];
	$nama=strtoupper($nama);
	return $nama;
}

//fungsi untuk mendapatkan status terakhir dokumen
function getstatusterakhir($id,$konek){
	$query 	= $konek->query("SELECT * FROM pibk_ctl WHERE header_seq_id='$id' ORDER BY ctl_seq_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['ctl_stat'];
	return $dataterakhir;
}

//fungsi get uraian npd
function geturaiannpd($id,$konek){
	$query 	= $konek->query("SELECT * FROM dbpibk.npd_control WHERE npdc_id_header='$id' ORDER BY npdc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['npdc_uraian'];
	return $dataterakhir;
}

//fungsi untuk mendapatkan uraian proses
function getproses($id,$konek){
	$query 	= $konek->query("SELECT * FROM ref_proses WHERE id='$id'");
	$rowdata=$query->fetch_array();
	$proses=$rowdata['uraian'];
	return $proses;
}



//fungsi untuk mengubah format tanggal dari Y-m-d hms ke d-m-Y h:m:s
function getdateformatdmyhms($data){
	$tgl=substr($data,0,10);
	$pecah=explode("-",$tgl);
	$tgldmy=$pecah[2]."-".$pecah[1]."-".$pecah[0];
	$jam=substr($data,11,8);
	return $tgldmy." ".$jam;
}

//fungsi untuk mengubah format tanggal dari Y-m-d ke d-m-Y
function getdateformatdmy($tgl){
	$pecah=explode("-",$tgl);
	$tgldmy=$pecah[2]."-".$pecah[1]."-".$pecah[0];
	return $tgldmy;
}

//fungsi untuk mengubah format tanggal dari d/m/Y ke Y-m-d
function getdateformatymd($tgl){
	$pecah=explode("/",$tgl);
	$tgldmy=$pecah[2]."-".$pecah[1]."-".$pecah[0];
	return $tgldmy;
}

//fungsi breadcumb
function getbread($mod,$con){
	$query 	= $con->query("SELECT * FROM mainmenu WHERE idmenu='$mod'");
	$rowdata= $query->fetch_array();
	$datalyr= $rowdata['judul'];

	$sql 	= $con->query("SELECT * FROM mainmenu WHERE idmenu='$rowdata[idinduk]'");
	$row	= $sql->fetch_array();
	$data 	= $row['judul'];

	$tampil = "<ol class='breadcrumb'>"."\n";
	$tampil.= "\t"."<li><a href='#'>$data</a></li>"."\n";
	$tampil.= "\t"."<li class='active'><a href='#'>$datalyr</a></li>"."\n";
	$tampil.= "</ol>";
	return $tampil;
}

//fungsi untuk browse data berdasarkan pjt
function filtpjt($id,$con){
	$query 	= $con->query("SELECT * FROM tbl_user WHERE id='$id'");
	$rowdata= $query->fetch_array();
	$flagpjt=$rowdata['sessregpjt'];
	if($flagpjt==0){
		$filt="";
	}else{
		$filt=" AND id_pjt='$flagpjt'";
	}
	return $filt;
} 

//fungsi untuk browse data berdasarkan pjt
function filtpjtnpd($id,$con){
	$query 	= $con->query("SELECT * FROM tbl_user WHERE id='$id'");
	$rowdata= $query->fetch_array();
	$flagpjt=$rowdata['sessregpjt'];
	if($flagpjt==0){
		$filt="";
	}else{
		$filt=" AND npdh_pjt='$flagpjt'";
	}
	return $filt;
} 


//fungsi untuk auth level boleh mengakses modul atau tidak
function authlevelpage($level,$mod,$con){
	$query 	= $con->query("SELECT * FROM hakmenu WHERE menuid='$mod' AND level='$level'");
	$jumlah	= $query->num_rows;
	return $jumlah;
} 

//fungsi untuk mendapatkan nilai id pjt
function getidpjt($id,$con){
	$query 	= $con->query("SELECT * FROM tbl_user WHERE id='$id'");
	$rowdata= $query->fetch_array();
	$idpjt=$rowdata['sessregpjt'];
	
	return $idpjt;
}

//fungsi untuk mencari noems berdasarkan id record
function getems($id,$konek){
	$query 	= $konek->query("SELECT * FROM ppkp_header WHERE ppkp_id='$id'");
	$rowdata=$query->fetch_array();
	$filemenu=$rowdata['ppkp_no_ems'];
	return $filemenu;
}

//fungsi untuk mendapatkan uraian proses ppkp
function getprosesppkp($id,$konek){
	$query 	= $konek->query("SELECT * FROM ref_proses_ppkp WHERE id='$id'");
	$rowdata=$query->fetch_array();
	$proses=$rowdata['uraian'];
	return $proses;
}

//fungsi untuk mendapatkan status terakhir dokumen ppkp
function getstatusterakhirppkp($id,$konek){
	$query 	= $konek->query("SELECT * FROM ppkp_ctl WHERE pc_header_id='$id' ORDER BY pc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['pc_stat'];
	return $dataterakhir;
}
//fungsi untuk mendapatkan status terakhir dokumen npd
function getstatusterakhirnpd($id,$konek){
	$query 	= $konek->query("SELECT * FROM npd_ctrl WHERE npdc_id_header='$id' ORDER BY npdc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['npdc_status'];
	return $dataterakhir;
	}
//fungsi untuk mendapatkan status terakhir dokumen npd
function getstatusterakhirnpdpetugas($id,$konek){
	$query 	= $konek->query("SELECT * FROM npd_ctrl WHERE npdc_id_header='$id' ORDER BY npdc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['npdc_petugas'];
	return $dataterakhir;
	}
function getstatusterakhirnpduraian($id,$konek){
	$query 	= $konek->query("SELECT * FROM npd_ctrl WHERE npdc_id_header='$id' ORDER BY npdc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['npd_uraian'];
	return $dataterakhir;
	}

function getstatusterakhirnpdwaktu($id,$konek){
	$query 	= $konek->query("SELECT * FROM npd_ctrl WHERE npdc_id_header='$id' ORDER BY npdc_id DESC LIMIT 1");
	$rowdata=$query->fetch_array();
	$dataterakhir=$rowdata['npdc_waktu'];
	return $dataterakhir;
	}
//fungsi untuk mendapatkan uraian proses npd
function getprosesnpd($id,$konek){
	$query 	= $konek->query("SELECT * FROM npd_status WHERE npds_id='$id'");
	$rowdata=$query->fetch_array();
	$proses=$rowdata['npds_uraian'];
	return $proses;
}

function totaltime($seconds){
	$hours 	= floor($seconds / 3600);
	$mins 	= floor($seconds / 60 % 60);
	$secs 	= floor($seconds % 60);
	$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
	return $timeFormat;
}