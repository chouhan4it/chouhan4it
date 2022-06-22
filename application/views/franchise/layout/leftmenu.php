<?php
$model = new OperationModel();
$fran_id = $this->session->userdata('fran_id');
?>
<div class="col-md-2">
    <div class="sidebar content-box" style="display: block;">
	    <ul class="nav">
    	<li class="current"><a href="<?php echo FRANCHISE_PATH; ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
        <li ><a  target="MainIframe" href="<?php echo generateSeoUrlFranchise("account","addmember",array()); ?>"><i class="glyphicon glyphicon-plus"></i> New Member</a></li>
    	<li class="submenu"><a href="javascript:void(0)"> <i class="glyphicon glyphicon-asterisk"></i> Setting<span class="caret pull-right"></span></a>
            <ul>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("setting","profile",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;View Profile</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("setting","accountprofile",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Bank Detail</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("setting","password",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Change Password</a></li>

            </ul>
	    </li>
        <li class="submenu"><a href="javascript:void(0)"> <i class="glyphicon glyphicon-star"></i> Product Master<span class="caret pull-right"></span></a>
            <ul>
                
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("shop","postproduct",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;New Product</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("shop","productlist",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Product List</a></li>
            </ul>
	    </li>
        <li class="submenu"><a href="javascript:void(0)"> <i class="glyphicon glyphicon-euro"></i> E-Pin<span class="caret pull-right"></span></a>
            <ul>
                
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("epin","usedpin",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Used E-Pin</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("epin","unusedpin",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Un-Used E-Pin</a></li>
            </ul>
	    </li>
    	<li class="submenu"><a href="javascript:void(0)"> <i class="glyphicon glyphicon-road"></i> Invoice<span class="caret pull-right"></span></a>
            <ul>
            	
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","placeorder",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;New Invoice</a></li>
              
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","orderlist",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Order List</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","invoicelist",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;View Invoice</a></li>
                
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","orderreturnlist",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Order Return</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","orderlastinvoice",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;View Last Invoice</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("order","taxsummary",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Tax Summary</a></li>
            </ul>
	    </li>
    	<li class="submenu"><a href="javascript:void(0)"> <i class="glyphicon glyphicon-barcode"></i> Stock Master<span class="caret pull-right"></span></a>
            <ul>
            	<li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("stock","stockentry",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;New Stock</a></li>
                                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("stock","stocktransaction",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Stock Transaction</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("stock","stockreport",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Stock Report</a></li>
                                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("stock","stocksummary",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Stock Summary</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("stock","stockreportmonthwise",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Monthwise Stock Report</a></li>
            </ul>
    	</li>
        <li class="submenu"> <a href="javascript:void(0)"> <i class="glyphicon glyphicon-tag"></i> Sales <span class="caret pull-right"></span> </a>
            <ul>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","collection",""); ?>?from_date=<?php echo $today_date; ?>&to_date=<?php echo $today_date; ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Today Sale</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","collectiongraph",""); ?>?from_date=<?php echo $month_date; ?>&to_date=<?php echo $end_date; ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Monthly Graph</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","salesreport",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Weekly Sale Report</a></li>
               
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","cashinhanddaily",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Cash in Hand Daily</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","cashinhandannual",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Cash in Hand Annual</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("report","gsthsnreport",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;GST HSN Summary</a></li>
            </ul>
        </li>
    	
    	<li class="submenu"> <a href="javascript:void(0)"> <i class="glyphicon glyphicon-user"></i> Account <span class="caret pull-right"></span> </a>
            <ul>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("account","ledger",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Ledger</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("account","statement",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Statement</a></li>
                <li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("bonus","retailcommission",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Commission</a></li>
                <!--<li><a target="MainIframe" href="<?php echo generateSeoUrlFranchise("account","fundtransfer",""); ?>"><i class="fa fa-arrow-circle-right"></i>&nbsp;Fund Transfers</a></li>-->
            </ul>
	    </li>
    	<li class="current"><a href="<?php echo generateSeoUrlFranchise("dashboard","logout",array()); ?>"><i class="glyphicon glyphicon-lock"></i> Logout</a></li>
    	</ul>
    </div>
</div>