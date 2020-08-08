		<?php
		$pathToSave="files/";
		$preid=$_SESSION['userSession'];
		$pjt		= getidpjt($preid,$DBcon);
		if(isset($_POST['submit'])==" Submit "){
			$countItem 		= 0;	 
			$countItemGood 	= 0; 
			$countItemBad 	= 0;
			$errors		=array();
			$file_name 	=$_FILES['contupload']['name'];
			$file_size 	=$_FILES['contupload']['size'];
			$file_tmp 	=$_FILES['contupload']['tmp_name'];
			$file_type 	=$_FILES['contupload']['type'];
			$file_ext_arr 	=explode('.',$file_name);
			$file_ext=$file_ext_arr[1];

			
			if ($file_ext=="txt") 	{

				

				
				$got_name 		= date('Ymdhis');
		   		$got_name 		= $preid."_"."$got_name.$file_ext";
		   		copy($file_tmp,"$pathToSave"."$got_name");
		   		//move_uploaded_file($file_tmp,"$pathToSave$got_name");
		   		$validImport 	= "true";
				
				$fcontentsVal 	= 0;	
				$fcontents 		= file ("$file_tmp");   
				$fcontentsVal 	= sizeof($fcontents);
				$fcontentsVal 	= $fcontentsVal-1;
				$i=0;
				$countItem=0;
				$prebatch=str_replace("_","BATCH",$got_name);

				while ($i<=$fcontentsVal  AND $validImport=="true")	{
		
					if ($countItemBad > 0)	{	
						$validImport = "false";	
					}	else	{
				
						$line 		= trim($fcontents[$i]); 
						$arr 		= explode("\t", $line); 			 		 
						$mawb 		= $arr[0];
						$tglmawb	= $arr[1];				
						$hawb		= $arr[2];
						$tglhawb	= $arr[3];
						$bcsatu		= $arr[4];
						$tglbcsatu	= $arr[5];
						


						$strsql = "INSERT INTO pibk_header(no_mawb,tgl_mawb,no_hawb,tgl_hawb,no_bc,tgl_bc,id_pjt,id_importir,batch,posisi)";
						$strsql.= " VALUES ('$mawb','$tglmawb','$hawb','$tglhawb','$bcsatu','$tglbcsatu','$pjt','-','$prebatch','0')";
						$result = $DBcon->query("$strsql");
						if ($result)	{
							$countItemGood++;
						}	else	{
							$countItemBad++;
						};

						$i++;	
						$countItem++;	
					};
				
				}
				if ($validImport=="true")	{
					$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>"."$i"." data berhasil diupload</div>";
				}	else	{
					$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>"."Harap cek format tanggal, tipe file dan ukuran file, tipe file harus *txt, proses import gagal</div>";
				
					$path_to_del="$pathToSave"."$got_name";
					unlink("$path_to_del");
				
					$strsqldel = "DELETE FROM pibk_header WHERE batch='$prebatch'";
					$result = $DBcon->query($strsqldel);
				};
		
			}	
		}
		echo getbread($mod,$DBcon);  ?>

		<div class="container">
			<?php if(isset($stat)){ echo $stat; }; 
			?>
			<div class="row">
				<p>Form ini digunakan untuk upload data pibk untuk monitoring status pergerakan dokumen pibk dengan ketentuan upload sebagai berikut:</p>
				<p>Data yang diupload dalam format *txt tab delimited yang terdiri dari elemen data no Master AWB, tgl Master AWB, no House AWB, tanggal House AWB, 
				no BC 1.1, tanggal BC 1.1 untuk format tanggal harus dalam format yyyy-mm-dd, format Master AWB sebelas digit tanpa spasi atau tanda lainnya
				 sebagaimana contoh di bawah ini</p>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<img src="gambar/formatdata.jpg" height="210" width="432">
				</div>
				<div class="col-md-6">
					Form Upload Data<br>
					<form class="form-inline" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<input name="contupload" type="file">
						</div>
						<div class="form-group">
							<input type="submit" name="submit" class="form-control btn btn-success" value=" Submit ">
						</div>
					</form>
				</div>
			</div>
		</div>
