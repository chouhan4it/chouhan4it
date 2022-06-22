
<div class="col-xs-12 col-sm-12 col-md-3 hidden-xs sidebar"> 
  
  <!-- ================================== TOP NAVIGATION ================================== -->
  <div class="side-menu animate-dropdown outer-bottom-xs">
    <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> Categories</div>
    <nav class="yamm megamenu-horizontal">
      <ul class="nav">
      
		<?php
        $QR_SEL_CAT = "SELECT tc.*, COUNT(tpc.post_id) AS prod_ctrl
               FROM tbl_category  AS tc  
               LEFT JOIN tbl_post_category AS tpc ON tpc.category_id=tc.category_id
               WHERE tc.category_sts>0 AND tc.delete_sts>0  AND tc.parent_id='0'
               GROUP BY tc.category_id 
               ORDER BY prod_ctrl DESC LIMIT 8";
        $RS_SEL_CAT =  $this->SqlModel->runQuery($QR_SEL_CAT);
        foreach($RS_SEL_CAT as $AR_SEL_CAT):
            $QR_SEL_PROD = "SELECT tp.*,  tpl.lang_id, tpl.post_size, tpl.post_title, tpl.post_tags, 
            tpl.post_desc, tpl.post_mrp, tpl.post_discount, tpl.short_desc,
            tpl.post_price, tpl.post_pv,  tpl.update_date , tpl.post_slug
            FROM tbl_post AS tp
            LEFT JOIN tbl_post_lang AS tpl ON tp.post_id=tpl.post_id 
            LEFT JOIN tbl_post_category AS tpc ON tpc.post_id=tp.post_id
            WHERE tp.delete_sts>0 AND tp.post_sts>0 AND tpc.category_id='$AR_SEL_CAT[category_id]'
            $StrWhr 
            GROUP BY tp.post_id  
            ORDER BY tp.display_order ASC";
            $RS_SEL_PROD = $this->SqlModel->runQuery($QR_SEL_PROD);
        ?>
      
        <li class="dropdown menu-item"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon fa fa-clock-o" aria-hidden="true"></i><?php echo ucfirst(strtolower($AR_SEL_CAT['category_name'])); ?></a>
          <ul class="dropdown-menu mega-menu">
            <li class="yamm-content">
              <div class="row">
              
              
                <div class="col-sm-12 col-md-3">
                  <ul class="links list-unstyled">
                  <?php $menu_ctrl=1;
				  	 foreach($RS_SEL_PROD as $AR_SEL_PROD): ?>
                    <li><a href="<?php echo generateSeoUrl("product","detail",array("post_id"=>$AR_SEL_PROD['post_id'])); ?>/<?php echo $AR_SEL_PROD['post_slug']; ?>"><?php echo $AR_SEL_PROD['post_title']; ?></a></li>
                   <?php  
				   	if($menu_ctrl>=8){ echo '</ul></div><div class="col-sm-12 col-md-3"><ul class="links list-unstyled">'; $menu_ctrl=0; } $menu_ctrl++;
				   	endforeach; ?>  
                    
                  </ul>
                </div>
             
                
                <!-- /.col -->
                
                <!-- /.col --> 
              </div>
              <!-- /.row --> 
            </li>
            <!-- /.yamm-content -->
          </ul>
          <!-- /.dropdown-menu --> </li>
          
        <?php  endforeach; ?>
        <!-- /.menu-item -->
        
        
        
      </ul>
      <!-- /.nav --> 
    </nav>
    <!-- /.megamenu-horizontal --> 
  </div>
  <!-- /.side-menu --> 
  <!-- ================================== TOP NAVIGATION : END ================================== --> 
  
</div>
