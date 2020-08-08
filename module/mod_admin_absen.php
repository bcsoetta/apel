<?php echo getbread($mod,$DBcon); 
	$petugas=$_SESSION['userSession'];
	echo "\n";
?>
<div class="container">
	<form name="myform" method="post" action="?">
		<table width="100%">
			<tr>
				<td style="padding:5px; color:#6c6c67"  bgcolor="#b9e8f4" colspan="2"><h4>DAFTAR PDTT AKTIF</h4></td><td align="right" bgcolor="#b9e8f4" style="padding:5px; color:#6c6c67;"><input type="submit" value=" absen "></td>
			</tr>
			<tr>
		<?php
			$sql 		="SELECT * FROM tbl_user WHERE level='2' AND aktif='Y' ORDER BY nama ASC";
			$query		=$DBcon->query("$sql");
			$i 			=0;
			$jumlah		=$query->num_rows;
			$jum_atas	=ceil($jumlah/3)*3;
			$selisih=$jum_atas-$jumlah;
			while($data=$query->fetch_array()){	
				$i++;
				echo '<td style="padding:5px;"  bgcolor="#f4edb9" width="33%"><input name="flagproses[]" type="checkbox" value="'.$data[id].'"><small>  '.strtoupper($data['nama']).'</small></td>';
				if ($i % 3 == 0){
				echo '</tr>'."\n"."\t\t".'<tr>';
    			}
    		 
			}
			for($j=1;$j<=$selisih;$j++){
    					echo '<td style="padding:5px;"  bgcolor="#f4edb9" width="33%"></td>';
    				}
		?>
			</tr>
			<tr>
				<td style="padding:5px;"  bgcolor="#b9e8f4" colspan="3"></td>
			</tr>
		</table>
	</form>
</div>
