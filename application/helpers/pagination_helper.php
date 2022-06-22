<?php
	   function DisplayPages($SQLQuery, $NoRecords, $PageNumber, $QueryString){
		$config = new SqlModel();
		if(!isset($PageNumber)){$PageNumber=1;}
		$strfnPagingSql = $SQLQuery;
		$strfnPagingresult = $config->getQuery($strfnPagingSql);
		$totalreturned = $strfnPagingresult->num_rows();
		$perpage = $NoRecords;
		$pagecount = ceil($totalreturned/$perpage);
		
		if(!isset($PageNumber)){$page = 1;}
		elseif($PageNumber == ""){$page = 1;}
		elseif(($pagecount > 0) and ($PageNumber > $pagecount)){$page = $pagecount;}
		else{$page = $PageNumber;}
		
		$recordstart = ($page-1)*$perpage;
		$strfnPagingresult = array();
		$strfnPagingresult = pageminmax($page,$pagecount);
		$iPageMin = $strfnPagingresult[0];
		$iPageMax = $strfnPagingresult[1];
		$tmpcnt = 0;
		$php_page = $_SERVER['PHP_SELF'];
		$strfnPagingSql = '';
		$strPaging = '';
		if($page != 1){
			$FirstPage = "<li  class='paginate_button'><a href=\"$PHP_SELF?page=1&$PageString$QueryString\">First</a> </li>";
		}
		if($page>1){ 
			$pageprev = $page-1; 
			$PrevPage = "<li  class='paginate_button previous'><a href=\"$PHP_SELF?page=$pageprev&$PageString$QueryString\">Prev</a></li>";
		}else{$PrevPage = "&nbsp;";}
		for ($i=$iPageMin; $i <= $iPageMax;$i++){
			if($i != $page){ 
				$NumString = $NumString."<li  class='paginate_button'><a href=\"$PHP_SELF?page=$i&$PageString$QueryString\">$i</a></li>";
			}
			else{
				$NumString = $NumString. "<li  class='paginate_button active'><a href='javascript:void(0)'>$i</a></li> ";
			}
		}
		if($page < $pagecount){
			$pagenext = $page+1; 
			$NextPage = "<li  class='paginate_button next'><a href=\"$PHP_SELF?page=$pagenext&$PageString$QueryString\">Next</a></li>";
		}
		if($page != $pagecount){
			$LastPage = "<li  class='paginate_button'><a href=\"$PHP_SELF?page=$pagecount&$PageString$QueryString\" >Last</a></li>";
		}
		if($pagecount > 10){
			if($pagecount > $i){
				$Next10 = $i+4;
				$Next10Page = "<li  class='paginate_button'><a href=\"$PHP_SELF?page=$Next10&$PageString$QueryString\">Next 10 Pages</a></li>";
			}
			if($page > 10){
				$Prev10 = $page-10;
				$Prev10Page = "<li  class='paginate_button'><a href=\"$PHP_SELF?page=$Prev10&$PageString$QueryString\">Prev 10 Pages</a></li>";
			}
		}
		$str_SrchQ = $SQLQuery." LIMIT $recordstart,$perpage";
		$QuerySet = $config->getQuery($str_SrchQ);
		$str_SrchQ_RS = $QuerySet->result_array();
		$Pages_RSSet = array();
		if ($totalreturned == 0){
			$Pages_RSSet["PrevPage"] = "";
			$Pages_RSSet["NumString"] = "";
			$Pages_RSSet["NextPage"] = "";
			$Pages_RSSet["TotalPages"] = "";
			$Pages_RSSet["TotalRecords"] = "";
		}else{
			$Pages_RSSet["FirstPage"] = $FirstPage;
			$Pages_RSSet["Prev10Page"] = $Prev10Page;
			$Pages_RSSet["PrevPage"] = $PrevPage;
			$Pages_RSSet["NumString"] = $NumString;
			$Pages_RSSet["NextPage"] = $NextPage;
			$Pages_RSSet["Next10Page"] = $Next10Page;
			$Pages_RSSet["LastPage"] = $LastPage;
			$Pages_RSSet["TotalPages"] = "Total Pages : ".$pagecount."";
			$Pages_RSSet["RecordStart"] = $recordstart;
			$Pages_RSSet["TotalRecords"] = $totalreturned;
		}
		$Pages_RSSet["ResultSet"] = $str_SrchQ_RS;
		return $Pages_RSSet;
	}
	function DisplayResult($SQLQuery, $NoRecords, $PageNumber, $QueryString){
		$config = new SqlModel();
		if(!isset($PageNumber)){$PageNumber=1;}
		$strfnPagingSql = $SQLQuery;
		$strfnPagingresult = $config->getQuery($strfnPagingSql);
		$totalreturned = $strfnPagingresult->num_rows();
		$perpage = $NoRecords;
		$pagecount = ceil($totalreturned/$perpage);
		
		if(!isset($PageNumber)){$page = 1;}
		elseif($PageNumber == ""){$page = 1;}
		elseif(($pagecount > 0) and ($PageNumber > $pagecount)){$page = $pagecount;}
		else{$page = $PageNumber;}
		
		$recordstart = ($page-1)*$perpage;
		$strfnPagingresult = array();
		$strfnPagingresult = pageminmax($page,$pagecount);
		$iPageMin = $strfnPagingresult[0];
		$iPageMax = $strfnPagingresult[1];
		$tmpcnt = 0;
		$php_page = $_SERVER['PHP_SELF'];
		$strfnPagingSql = '';
		$strPaging = '';
		if($page != 1){
			$FirstPage = "<li class=''> <a href=\"$PHP_SELF?page=1&$PageString$QueryString\" class=\"pagination-link\" data-page='1' data-list-id='product'> <i class='icon-double-angle-left'></i>First</a></li> ";
		}
		if($page>1){ 
			$pageprev = $page-1; 
			$PrevPage = "<li class=''><a href=\"$PHP_SELF?page=$pageprev&$PageString$QueryString\" class=\"pagination-link\" data-page='0' data-list-id='product'><i class='icon-angle-left'></i>Prev</a> </li>";
		}else{$PrevPage = "&nbsp;";}
		for ($i=$iPageMin; $i <= $iPageMax;$i++){
			if($i != $page){ 
				$NumString = $NumString."<li><a href=\"$PHP_SELF?page=$i&$PageString$QueryString\" class=\"pagination-link\"
				data-page='1' data-list-id='product'>$i</a></li>";
			}
			else{
				$NumString = $NumString. " <li class='active'><a href='javascript:void(0);' class='pagination-link' data-page='1' 
				data-list-id='product'>$i</a></li> ";
			}
		}
		if($page < $pagecount){
			$pagenext = $page+1; 
			$NextPage = " <li > <a  data-page='2' data-list-id='product' href=\"$PHP_SELF?page=$pagenext&$PageString$QueryString\" class=\"pagination-link\">
			 <i class='icon-angle-right'></i>Next</a><li>";
		}
		if($page != $pagecount){
			$LastPage = "<li > <a  data-page='2' data-list-id='product' href=\"$PHP_SELF?page=$pagecount&$PageString$QueryString\" class=\"pagination-link\"> 
			<i class='icon-double-angle-right'></i>Last</a><li> ";
		}
		if($pagecount > 10){
			if($pagecount > $i){
				$Next10 = $i+4;
				$Next10Page = "<li><a href=\"$PHP_SELF?page=$Next10&$PageString$QueryString\" class=\"pagination-link\"> 
				<i class='icon-angle-right'></i> 10 Pages</a> </li>";
			}
			if($page > 10){
				$Prev10 = $page-10;
				$Prev10Page = "<li><a href=\"$PHP_SELF?page=$Prev10&$PageString$QueryString\" class=\"pagination-link\">
				<i class='icon-angle-left'> </i> 10 Pages</a></li>";
			}
		}
		$str_SrchQ = $SQLQuery." LIMIT $recordstart,$perpage";
		$QuerySet = $config->getQuery($str_SrchQ);
		$str_SrchQ_RS = $QuerySet->result_array();
		
		$Pages_RSSet = array();
		if ($totalreturned == 0){
			$Pages_RSSet["PrevPage"] = "";
			$Pages_RSSet["NumString"] = "";
			$Pages_RSSet["NextPage"] = "";
			$Pages_RSSet["TotalPages"] = "";
			$Pages_RSSet["TotalRecords"] = "";
		}else{
			$Pages_RSSet["FirstPage"] = $FirstPage;
			$Pages_RSSet["Prev10Page"] = $Prev10Page;
			$Pages_RSSet["PrevPage"] = $PrevPage;
			$Pages_RSSet["NumString"] = $NumString;
			$Pages_RSSet["NextPage"] = $NextPage;
			$Pages_RSSet["Next10Page"] = $Next10Page;
			$Pages_RSSet["LastPage"] = $LastPage;
			$Pages_RSSet["TotalPages"] = "Total Pages : ".$pagecount."";
			$Pages_RSSet["RecordStart"] = $recordstart;
			$Pages_RSSet["TotalRecords"] = $totalreturned;
		}
		$Pages_RSSet["ResultSet"] = $str_SrchQ_RS;
		return $Pages_RSSet;
	}
   function pageminmax($page, $pagecount)
	{
		$result = array();
		$iPageOffSet = (5);
		if ($page < (5) )
			$iPageOffSet = (5 + (5-$page));
		
		if (($page + $iPageOffSet) > $pagecount)
			$iPageMax = $page + ($pagecount - $page);
		else
			$iPageMax = $page + $iPageOffSet;
		
		if (($page - $iPageOffSet) < 1)
			$iPageMin = 1;
		else
		{
			if (($page + $iPageOffSet) > $pagecount)
				$iPageMin = (($pagecount - ($iPageOffSet + $iPageOffSet )) + 1);
			else
				$iPageMin = ($page - $iPageOffSet) + 1;
		}
		
		if ($iPageMin < 1)
			$iPageMin = 1;
		if ($iPageMax > $pagecount)
			$iPageMax = $pagecount;
		
		$result[0] = round($iPageMin);
		$result[1] = round($iPageMax);
		return $result;
	}
?>