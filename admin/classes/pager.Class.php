<?php 
/**************************************************************************************/ 
 class Pager 
  { 
  /*********************************************************************************** 
   * int findStart (int limit) 
   * Returns the start offset based on $_REQUEST['page'] and $limit
   ***********************************************************************************/ 
   function findStart($limit) 
    { 
     if ((!isset($_REQUEST['page'])) || ($_REQUEST['page'] == "1") || ($_REQUEST['page'] < 1))
      { 
       $start = 0; 
       $_REQUEST['page'] = 1;
      } 
     else 
      { 
       $start = ($_REQUEST['page']-1) * $limit;
      } 

     return $start; 
    } 
  /*********************************************************************************** 
   * int findPages (int count, int limit) 
   * Returns the number of pages needed based on a count and a limit 
   ***********************************************************************************/ 
   function findPages($count, $limit) 
    { 
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1; 

     return $pages; 
    } 
  /*********************************************************************************** 
   * string pageList (int curpage, int pages) 
   * Returns a list of pages in the format of " < [pages] > " 
   ***********************************************************************************/ 
   function pageList($curpage, $pages,$parameter="") 
   { 
     	$page_list  = ""; 
		$pagination_mode=$_REQUEST['mode'];
		$pagination_do=$_REQUEST['do'];
		$pagination_nurseid=(isset($_REQUEST['nurseid']) && $_REQUEST['nurseid']!=0?$_REQUEST['nurseid']:0);
		$pagination_auctionid=(isset($_REQUEST['auctionid']) && $_REQUEST['auctionid']!=0?$_REQUEST['auctionid']:0);
		$pagination_hospitalid=(isset($_REQUEST['hospitalid']) && $_REQUEST['hospitalid']!=0?$_REQUEST['hospitalid']:0);
		
		
		$pagination_additional_parameters_array=array($pagination_nurseid,$pagination_auctionid,$pagination_hospitalid);
		 $pagination_additional_parameters_list=implode(",",$pagination_additional_parameters_array);

     	/* Print the first and previous page links if necessary */ 
     	if (($curpage != 1) && ($curpage))        
	       $page_list .= "  <a  href=\"javascript:;\" onclick=showpagination('".$pagination_mode."','".$pagination_do."',1,".$pagination_additional_parameters_list.") title=\"First Page\" class=\"links\">First Page</a> "; 
		   //$page_list .= "  <a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=1\" title=\"First Page\" class=\"links\"><<</a> "; 

     	if (($curpage-1) > 0) 
	       $page_list .= "<a href=\"javascript:;\" onclick=showpagination('".$_REQUEST['mode']."','".$_REQUEST['do']."',".($curpage-1).",".$pagination_additional_parameters_list.")   title=\"Previous Page\" class=\"links\">Prev</a> "; 
		  // $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage-1)."\" title=\"Previous Page\" class=\"links\">Prev</a> "; 

     /* Print the numeric page list; make the current page unlinked and bold */ 
     for ($i=1; $i<=$pages; $i++) 
     { 
       	if ($i == $curpage) 
        	 $page_list .= "<b>".$i."</b>"; 
       	else 
			$page_list .= "<a href=\"javascript:;\" title=\"Page ".$i."\" onclick=showpagination('".$_REQUEST['mode']."','".$_REQUEST['do']."',".$i.",".$pagination_additional_parameters_list.") class=\"links\">".$i."</a>"; 
        	 //$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".$i."\" title=\"Page ".$i."\" class=\"links\">".$i."</a>"; 
			 

       	$page_list .= " "; 
     } 

     /* Print the Next and Last page links if necessary */ 
     if (($curpage+1) <= $pages)      
			 $page_list .= "<a  href=\"javascript:;\" onclick=showpagination('".$_REQUEST['mode']."','".$_REQUEST['do']."',".($curpage+1).",".$pagination_additional_parameters_list.")  title=\"Next Page\"  class=\"links\">Next</a> "; 
       		//$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage+1)."\" title=\"Next Page\" class=\"links\">Next</a> "; 


     if (($curpage != $pages) && ($pages != 0)) 
       		$page_list .= "<a href=\"javascript:;\" onclick=showpagination('".$_REQUEST['mode']."','".$_REQUEST['do']."',".$pages.",".$pagination_additional_parameters_list.") title=\"Last Page\" class=\"links\">Last Page</a> "; 
			//$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".$pages."\" title=\"Last Page\" class=\"links\">>></a> "; 
	
     $page_list .= "\n"; 
     return $page_list; 
    } 

	/*********************************************************************************** 
   * string nextPrev (int curpage, int pages) 
   * Returns "Previous | Next" string for individual pagination (it's a word!) 
   ***********************************************************************************/ 
   function nextPrev($curpage, $pages,$parameter) 
    { 
     $next_prev  = ""; 

     if (($curpage-1) <= 0) 
      { 
       $next_prev .= "Previous"; 
      } 
     else 
      { 
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage-1)."\" class=\"rightnavlinks\">Previous</a>"; 
      } 

     $next_prev .= " | "; 

     if (($curpage+1) > $pages) 
      { 
       $next_prev .= "Next"; 
      } 
     else 
      { 
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage+1)."\" class=\"rightnavlinks\">Next</a>"; 
      } 

     return $next_prev; 
    } 

   function nextPrevCols($curpage,$pages,$parameter,$cols) 
    { 
     $next_prev  = "<td>&lt;&lt;"; 

     if (($curpage-1) <= 0) 
      { 
       $next_prev .= "Previous"; 
      } 
     else 
      { 
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage-1)."\" class=\"rightnavlinks\">Previous</a>"; 
      } 
	 $colspan=$cols-2;
     $next_prev .= " </td><td colspan=$colspan>&nbsp;</td><td align=right> "; 

     if (($curpage+1) > $pages) 
      { 
       $next_prev .= "Next"; 
      } 
     else 
      { 
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage+1)."\" class=\"rightnavlinks\">Next</a>"; 
      } 
     $next_prev  .= "&gt;&gt;</td>"; 

     return $next_prev; 
    } 


  } 
?>
