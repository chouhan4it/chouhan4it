<?php 
	$router_class =  $this->router->fetch_class();
	$router_method = $this->router->fetch_method();
 ?>
<div id="sidebar" class="sidebar responsive ace-save-state">
	<script type="text/javascript">
    //try{ace.settings.loadState('sidebar')}catch(e){}
    </script>
	<div class="sidebar-shortcuts" id="sidebar-shortcuts">
		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
		<?php if($this->session->userdata('oprt_id')<=2){ ?>
		<a target="MainIframe" href="<?php echo  generateSeoUrlAdmin("operation","operator",""); ?>" class="btn btn-success"> <i class="ace-icon fa fa-signal"></i> </a>
		<?php }?>
		<?php if($this->session->userdata('oprt_id')<=2){ ?>
		<a target="MainIframe" href="<?php echo  generateSeoUrlAdmin("operation","operatoradd",
array("oprt_id"=>$this->session->userdata('oprt_id'),"action_request"=>"EDIT")); ?>" class="btn btn-info"> <i class="ace-icon fa fa-pencil"></i> </a>
		<?php }?>
		<a target="MainIframe" class="btn btn-warning" href="<?php echo generateSeoUrlAdmin("operation","newlistprivate",array()); ?>"> <i class="ace-icon fa fa-bullhorn"></i> </a>
		<?php if($this->session->userdata('oprt_id')<=3){ ?>
		<a  onclick="return confirm('Make sure , want to refresh caching?')" href="<?php echo  generateSeoUrlAdmin("operation","deletecaching",""); ?>" class="btn btn-danger"> <i class="ace-icon fa fa-cogs"></i> </a>
		<?php }?>
		</div>
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini"> 
            <span class="btn btn-success"></span> 
            <span class="btn btn-info"></span> 
            <span class="btn btn-warning"></span> 
            <span class="btn btn-danger"></span> 
        </div>
	</div>
	<!-- /.sidebar-shortcuts -->
	<ul class="nav nav-list">
		<li class="active"> <a href="<?php echo ADMIN_PATH."homepage"; ?>"> <i class="menu-icon fa fa-tachometer"></i> <span class="menu-text"> Dashboard </span> </a> <b class="arrow"></b> </li>
		<?php
       
        $oprt_id = $this->session->userdata('oprt_id');
        $group_id  = $this->session->userdata('group_id');
       
        $QR_MM = "SELECT DISTINCT B.ptype_id, C.type_name, C.icon_id 
				  FROM tbl_sys_menu_acs AS A, tbl_sys_menu_sub AS B 
        		  LEFT OUTER JOIN  tbl_sys_menu_main AS C ON  B.ptype_id=C.ptype_id 
        		  WHERE B.ptype_id>0 AND (A.group_id='".$group_id."') $Str_PageId 
				  AND A.page_id=B.page_id 
				  ORDER BY C.order_id ASC;";
        $RS_MMS = $this->db->query($QR_MM);
        $AR_MMS = $RS_MMS->result_array();
        $Ctrl=0;
        foreach($AR_MMS as $AR_MM):
		
			$ptype_id = $AR_MM['ptype_id'];
			$icon_id = $AR_MM['icon_id'];
			$StrWhrPage = ($oprt_id!="1")? " AND A.page_id NOT IN(205,206)":"";
			
			$Q_SBMN = "SELECT B.* 
					   FROM tbl_sys_menu_acs AS A, tbl_sys_menu_sub AS B 
					   WHERE A.page_id=B.page_id AND (A.group_id='".$group_id."' OR A.oprt_id='".$oprt_id."') 
					   $Str_PageId $StrWhrPage AND B.ptype_id='".$ptype_id."' 
					   GROUP BY B.page_id 
					   ORDER BY B.order_id ASC;";
			$RS_SBMNS = $this->db->query($Q_SBMN);
			$AR_SBMNS = $RS_SBMNS->result_array();
			
			$router_page_name = $router_class."/".$router_method;
			$access_ctrl =  $this->OperationModel->checkCountPro("tbl_sys_menu_sub","ptype_id='$ptype_id' AND page_name='$router_page_name'");
        ?>
        <li class="<?php echo ($access_ctrl>0)? 'open':''; ?>"> <a href="javascript:void(0)" class="dropdown-toggle"> <i class="menu-icon fa <?php echo DisplayICon($icon_id); ?>"></i> <span class="menu-text"> <?php echo $AR_MM['type_name']; ?> </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
			<ul class="submenu">
				<?php 	
                foreach($AR_SBMNS as $AR_SBMN): 
                ?>
                <li class=""> <a   href="<?php echo ADMIN_PATH.$AR_SBMN['page_name']; ?>"> <i class="menu-icon fa fa-caret-right"></i> <?php echo $AR_SBMN['page_title']; ?> </a> <b class="arrow"></b> </li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endforeach; ?>
		<li class="active"> <a href="<?php echo generateSeoUrlAdmin("login","logouthandler","");  ?>"> <i class="menu-icon fa fa-sign-out"></i> <span class="menu-text"> Logout </span> </a> <b class="arrow"></b> </li>
		
	</ul>
	<!-- /.nav-list -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse"> 
    	<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i> 
	</div>
</div>