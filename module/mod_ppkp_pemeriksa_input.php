<?php 
	echo getbread($mod,$DBcon); 
	if(!empty($_POST['noems']))
	{
		$petugas	=$_SESSION['userSession'];
		$tglinput 	=date("Y-m-d H:i:s");
		$noems		=$_POST['noems'];
		$posisi		="0";
		$exec		=$DBcon->query("INSERT INTO ppkp_header(ppkp_no_ems, ppkp_petugas, ppkp_tanggal_input, ppkp_status) VALUES ('$noems', '$petugas','$tglinput','$posisi')");
		
		if($exec)
		{
			$stat="<div class='alert alert-success'><span class='glyphicon glyphicon-info-sign'></span>data berhasil diproses</div>";
		} 
		else 
		{
			$stat="<div class='alert alert-danger'><span class='glyphicon glyphicon-info-sign'></span>data gagal diproses</div>";
		}
	}
	
	if(isset($stat))
	{ 
		echo $stat; 
	}
?>
<div class="container">
	<div class="row">
		<form class="form" method="post" action="?">
			<input type="hidden" name="mod" value="<?php echo $mod;?>">
			<div class="form-group col-md-4 col-md-offset-4">
	            <input type="text" class="form-control" autofocus name="noems" placeholder="Scan Barcode disini...">
	        </div>
	    </form>
	</div>
    <hr style="border-top:dotted thin #DDD;">
    <div class="row">
    	<table class="table table-bordered">
    		<tr class="warning">
    			<th class="text-center" width="30">No</th>
    			<th class="text-center">No EMS</th>
    			<th class="text-center">Waktu Upload</th>
    			<th class="text-center">Pemeriksa</th>
    		</tr>
    		<?php
    			$sql	="SELECT * FROM ppkp_header WHERE ppkp_status='0' ORDER BY ppkp_id DESC";
    			$query	=$DBcon->query($sql);
    			$count	=$query->num_rows;
    			if($count>0)
    			{
    				$nomor 	=0;
					while($data=$query->fetch_array())
					{
						$nomor++;
						$tampil_noems		=$data['ppkp_no_ems'];
						$tampil_waktu		=getdateformatdmyhms($data['ppkp_tanggal_input']);
						$tampil_pemeriksa	=$data['ppkp_petugas'];
    		?>
    		<tr>
    			<td><?php echo $nomor; ?></td>
    			<td><?php echo $tampil_noems;?></td>
    			<td><?php echo $tampil_waktu;?></td>
    			<td><?php echo getnamauser($tampil_pemeriksa,$DBcon);?></td>
    		</tr>
    		<?php 
    				}
    			}
    		?>
    	</table>
    </div>
</div>