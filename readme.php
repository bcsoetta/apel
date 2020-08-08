<?php
//tambahin di baris setelah link dokap pada mod_pdtt_proses.php
//cek dokap tambahan
$sqlcekdokdua	= "SELECT * FROM pibk_dokap_tambahan WHERE idheader='$data[idpibk_header]'";
$querycount		= $DBcon->query("$sqlcekdokdua");
$jum			= $querycount->num_rows;
	if($jum>0){
		while($datacount=$querycount->fetch_array()){ ?>
			<br><a class="btn btn-success" target="_blank" href="dokaptambahan/<?php echo $datacount['namafile'];?>">Dokap Tambahan</a>
<?php
		}
	}

//tambahin setelah elseif pada baris if($statakhir==2){......... terakhir status pada mod_pdtt_proses.php
elseif($statakhir==10) { ?>
	<p class="pull-right"><strong>Respon dari PJT atas notifikasi</strong></p>
<?php
}

// tambahin setelah if($statakhir!=2 AND $statakhir!=3 AND $statakhir!=5 .......
AND $statakhir!=10

//main.php setelah datepicker css
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
//main.php setelah datepicker js
    <link href="css/editor.css" type="text/css" rel="stylesheet"/>
    <script src="js/editor.js"></script>

# tambahin menu 
# idmenu, judul, url, file, idgrup, idinduk, tampil, isparent, flagjavascript
'34', 'Browse Data Notifikasi', '?mod=34', 'mod_pjt_browse_data_notif.php', '0209', '2', '1', '0', '0'
'37', 'Rekam Respon Notifikasi', '?mod=37', 'mod_pjt_rekam_respon.php', '0913', '9', '0', '0', '1'
'38', 'Browse Data Respon PJT', '?mod=38', 'mod_admin_browse_data_respon.php', '0213', '2', '1', '0', '1'
'39', 'Proses Respon PJT', '?mod=39', 'mod_proses_terima_respon.php', '0916', '9', '0', '0', '0'

# tambahin hak menu
# idhakmenu, menuid, level
'66', '34', '3'
'67', '37', '3'
'68', '38', '1'
'69', '39', '1'

#tambahin referensi status nambah siji maning
# id, domain_proses, uraian
'10', '3', 'Respon atas notifikasi'

#tambah table

CREATE TABLE `pibk_dokap_tambahan` (
  `iddokap` int(11) NOT NULL AUTO_INCREMENT,
  `idheader` int(11) NOT NULL,
  `namafile` varchar(200) DEFAULT NULL,
  `waktuupload` datetime DEFAULT NULL,
  PRIMARY KEY (`iddokap`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

CREATE TABLE `tbl_batch_proses_respon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbatch` varchar(100) DEFAULT NULL,
  `idheader` int(11) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  `idpetugas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=352 DEFAULT CHARSET=latin1;

#tambahi folder dokaptambahan ndek njerone folder html

# buat log time untuk iku tambahan table
CREATE TABLE `pdtt_time` (
  `pt_id` int(11) NOT NULL AUTO_INCREMENT,
  `pt_awal` int(11) DEFAULT NULL,
  `pt_waktu_awal` datetime DEFAULT NULL,
  `pt_akhir` int(11) DEFAULT NULL,
  `pt_waktu_akhir` datetime DEFAULT NULL,
  `pt_header_id` int(11) DEFAULT NULL,
  `pt_stat_akhir` int(11) DEFAULT NULL,
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;


#trus bikin triger insert otomatis ketika pibk_ctl diinsert
CREATE DEFINER=`root`@`localhost` TRIGGER `dbpibk`.`pibk_ctl_BEFORE_INSERT` BEFORE INSERT ON `pibk_ctl` FOR EACH ROW
BEGIN
IF NEW.ctl_stat>1 THEN
 INSERT INTO `dbpibk`.`pdtt_time`(
	pt_awal,
    pt_waktu_awal,
    pt_akhir,
    pt_waktu_akhir,
    pt_header_id) 
 SELECT 
	ctl_stat,
	ctl_time,
    NEW.ctl_stat,
    NEW.ctl_time,
    NEW.header_seq_id
 FROM `dbpibk`.`pibk_ctl`
 WHERE header_seq_id=NEW.header_seq_id 
 ORDER BY ctl_seq_id DESC LIMIT 1;
 UPDATE `dbpibk`.`pdtt_time`
	SET pt_stat_akhir=NEW.ctl_stat
 WHERE pt_header_id=NEW.header_seq_id;
 END IF;
END

#mudah2an dah semua...
?>