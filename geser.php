<?php

//koneksi ke mysql server
	$DBhost = "localhost";
  	$DBuser = "root";
  	$DBpass = "1q2w3e4r";
  	$DBname = "dbpibk";
  
  	$DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    
    if ($DBcon->connect_errno) {
        die("ERROR : -> ".$DBcon->connect_error);
    }


$sql="SELECT * FROM laststatus JOIN pibk_dokap ON idheader=idlaststatus WHERE waktuupload<DATE_ADD(NOW(), INTERVAL -30 DAY) AND waktuupload>=DATE_ADD(NOW(), INTERVAL -35 DAY) AND laststatus='2'";
$query	= $DBcon->query("$sql");
function create_folder_satu($data){
	$pecah=explode("_",$data);
	$datadua=$pecah[1];
	$fold_one=substr($datadua,0,4);
return $fold_one;
}

function create_folder_dua($data){
	$pecah=explode("_",$data);
	$datadua=$pecah[1];
	$fold_two=substr($datadua,4,2);
return $fold_two;
}

function create_folder_tiga($data){
	$pecah=explode("_",$data);
	$datadua=$pecah[1];
	$fold_tri=substr($datadua,6,2);
return $fold_tri;
}


while($row= $query->fetch_array()){ 
	$fold_satu=create_folder_satu($row['namafile']);
	$pathsatu='arsip/'.$fold_satu;
	if (!file_exists($pathsatu)) {
    	mkdir($pathsatu, 0755, true);
	}
	$fold_dua=create_folder_dua($row['namafile']);
	$pathdua='arsip/'.$fold_satu.'/'.$fold_dua;
	if (!file_exists($pathdua)) {
    mkdir($pathdua, 0755, true);
	}
	$fold_tiga=create_folder_tiga($row['namafile']);
	$pathtiga='arsip/'.$fold_satu.'/'.$fold_dua.'/'.$fold_tiga;
	if (!file_exists($pathtiga)) {
    mkdir($pathtiga, 0755, true);
	}
	rename('dokap/'.$row['namafile'], $pathtiga.'/'.$row['namafile']);
	//$DBcon->query("INSERT INTO pibk_dokap_arsip (idheader,namafile,waktuupload) SELECT * FROM pibk_dokap WHERE idheader='$row[idheader]'");
	//$DBcon->query("DELETE FROM pibk_dokap WHERE idheader='$row[idheader]'");
}

?>
