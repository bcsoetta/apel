<?php
function menu_get($level,$kon){
	$query="SELECT t1.idmenu AS id, t1.judul AS judul, t1.url AS url,t1.idgrup AS idgrup,t1.idinduk AS idinduk,t1.tampil AS tampil,t1.isparent AS isparent";
	$query.=" FROM mainmenu t1 JOIN hakmenu t2 ON t1.idmenu=t2.menuid WHERE t1.tampil='1' AND t2.level='$level' ORDER BY t1.idgrup ASC";
	$hasil=$kon->query($query);
	if(!$hasil){ mysqli_error(); }
	$a=1;
	while($row=$hasil->fetch_array()){
		$menu[$a]['id']			= $row['id'];
		$menu[$a]['judul']		= $row['judul'];
		$menu[$a]['url']		= $row['url'];
		$menu[$a]['idgrup']		= $row['idgrup'];
		$menu[$a]['idinduk']	= $row['idinduk'];
		$menu[$a]['tampil']		= $row['tampil'];
		$menu[$a]['isparent']	= $row['isparent'];
		$a++;
	}
	$html_out="";
	for($i=1;$i <= count($menu); $i++){
		if(is_array($menu[$i])){
			if($menu[$i]['tampil'] && $menu[$i]['idinduk']==0){
				if ($menu[$i]['isparent'] == TRUE){
					$html_out .= "\t\t\t\t\t\t".'<li class="dropdown"><a href="'.$menu[$i]['url'].'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$menu[$i]['judul'].'</a>';
				} 
				else{
					$html_out .= "\t\t\t\t\t\t\t".'<li><a href="'.$menu[$i]['url'].'">'.$menu[$i]['judul'].'</a></li>';
				}
				$html_out .= get_childs($menu, $menu[$i]['id']);
				$html_out .= "\n";
			}
		}
		else{
			exit (sprintf('menu nr %s must be an array', $i));
        }

	}
	return $html_out;
}	

function get_childs($menu, $parent_id){
        $has_subcats = FALSE;
        $html_out  	= '';
        $html_out .= "\n\t\t\t\t".'<ul class="dropdown-menu">'."\n";

        for ($i = 1; $i <= count($menu); $i++)
        {
            if ($menu[$i]['tampil'] && $menu[$i]['idinduk'] == $parent_id)    // are we allowed to see this menu?
            {
                $has_subcats = TRUE;

                    $html_out .= "\t\t\t\t\t\t".'<li><a href="'.$menu[$i]['url'].'">'.$menu[$i]['judul'].'</a></li>';

                // Recurse call to get more child submenus.
                $html_out .= get_childs($menu, $menu[$i]['id']);

                $html_out .= "\n";
            }
        }
        $html_out .= "\t\t\t\t".'</ul></li>' . "\n";

        return ($has_subcats) ? $html_out : FALSE;
    }
?>