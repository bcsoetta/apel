<?php
function createpaging($mod,$page,$prev,$next,$lastpage,$lpm1,$adjacents){
$pagination = "";
$counter=0;
if($lastpage > 1) { 
	$pagination .= "<nav class='pull-right'> <ul  class='pagination'>";
	if ($page > $counter+1) {
		$pagination.= "<li><a href=\"?mod=$mod&page=$prev\"><<</a></li>"; 
	}

	if ($lastpage < 7 + ($adjacents * 2)) { 
		for ($counter = 1; $counter <= $lastpage; $counter++){
			if ($counter == $page)
				$pagination.= "<li class='active'><a href='#'>$counter</a></li>";
			else
				$pagination.= "<li><a href=\"?mod=$mod&page=$counter\">$counter</a></li>"; 
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2)) {
		if($page < 1 + ($adjacents * 2)) {
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
				if ($counter == $page)
					$pagination.= "<li  class='active'><a href='#'>$counter</a></li>";
				else
					$pagination.= "<li><a href=\"?mod=$mod&page=$counter\">$counter</a></li>"; 
			}
			$pagination.= "<li>...</li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=$lpm1\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=$lastpage\">$lastpage</a></li>"; 
		}
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
			$pagination.= "<li><a href=\"?mod=$mod&page=1\">1</a></li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=2\">2</a></li>";
			$pagination.= "<li>...</li>";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
				if ($counter == $page)
					$pagination.= "<li class='active'><a href='#' >$counter</a></li>";
				else
					$pagination.= "<li><a href=\"?mod=$mod&page=$counter\">$counter</a></li>"; 
			}
			$pagination.= "<li>...</li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=$lpm1\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=$lastpage\">$lastpage</a></li>"; 
		}
		else{
			$pagination.= "<li><a href=\"?mod=$mod&page=1\">1</a></li>";
			$pagination.= "<li><a href=\"?mod=$mod&page=2\">2</a></li>";
			$pagination.= "<li>...</li>";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
				if ($counter == $page)
					$pagination.= "<li class='active'><a href='#'>$counter</a></li>";
				else
					$pagination.= "<li><a href=\"?mod=$mod&page=$counter\">$counter</a></li>"; 
			}
		}
	}

//next button
	if ($page < $counter - 1) 
		$pagination.= "<li><a href=\"?mod=$mod&page=$next\">>></a></li>";
	else
		$pagination.= "";
		$pagination.= "</ul></nav>\n"; 
	}
return $pagination;

}
?>