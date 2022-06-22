<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {
	
	public function __construct(){
	  // Call the Model constructor
	   parent::__construct();
	   
	    $mem_id =  $this->session->userdata('mem_id');
	   	if(!$mem_id){
			redirect(BASE_PATH);		
		}
	}

	
	public function processpayment(){
		$model = new OperationModel();
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$minimum_deposit = 10;
		$member_id = $this->session->userdata('mem_id');
		$wallet_id = FCrtRplc($form_data['wallet_id']);
		$deposit_amount = FCrtRplc($form_data['deposit_amount']);
		$AR_SEND['wallet_id']=$wallet_id;
		
		if($deposit_amount>0 && $deposit_amount>=$minimum_deposit){
			set_message("warning","Online deposite is disabled");
			redirect_member("financial","deposit","");
			exit;
			$AR_SEND['deposit_amount']= $deposit_amount;
			$data['POST'] = $AR_SEND;
			$this->load->view(MEMBER_FOLDER.'/payment/deposit-cashfree',$data); 
			exit;
		}else{
			set_message("warning","Invalid deposit amount");
			redirect_member("financial","deposit",""); 
		}
	}
	
	
	public function bankwiredownload(){
		$output .= '<div class="row inv-wrap" id="print_area">
                  <div class="col-md-12 block">
                    <h4> <strong> Account Details: </strong> </h4>
                    <ul class="inv-lst">
                      <li> Account <span class="hg-txt"> COINX TRADING LLC </span> </li>
                      <li> Address: One World Trade Center
                        Suite 8500 , New York, NY 10007
                        United States. </li>
                      <li> SWIFT CODE: <span class="hg-txt"> ABCNCTSSA </span> </li>
                      <li> RTGS/NEFT IFSC CODE: <span class="hg-txt"> ABC0000154 </span> </li>
                      <li> NAME OF BANK: <span class="hg-txt"> ABC BANK </span> </li>
                      <li> BANK ADDRESS: 9C Pulaski St.Des Moines, IA 50310. </li>
                      <li> ACCOUNT NUMBER: <span class="hg-txt"> 015405500642 </span> </li>
                      <li> BRANCH NUMBER/CODE: 0514 Moines Branch </li>
                      <li> Comments or Special Instructions: </li>
                      <li> PAYMENT DESCRIPTION: Invoice No.: 1043 </li>
                    </ul>
                  </div>
                  <div class="col-md-12 text-center">
                    <h4> <strong> THANK YOU FOR YOUR BUSINESS!!! </strong> </h4>
                    <ul class="text-center comp-info">
                      <li> One World Trade Center
                        Suite 8500 , New York, NY 10007
                        United States </li>
                      <li> <i class="fa fa-envelope"></i> : support@coinxtrading.com, <i class="fa fa-phone"></i> : 1-646-583-1495 </li>
                    </ul>
                  </div>
                </div>';
		$FileName="BANK_WIRE_";
		$CurrTime = InsertDate(getLocalTime());
		$CurrTime = StringReplace($CurrTime, "-", "_");
		$FileName = $FileName.$CurrTime.".docx";
		header('Content-type: application/msword');
		header('Content-Disposition: attachment; filename='.$FileName);
		echo $output;
		exit;	
	}
	
	public function upgrademembership(){
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		
		$date_expire = InsertDate(AddToDate($today_date,"+ 1 Year"));
		$form_data = $this->input->post();
		$segment = $this->uri->uri_to_assoc(2);
		$member_id = $this->session->userdata('mem_id');
		$package_id = _d($form_data['package_id']);
		$fldvType = FCrtRplc($form_data['fldvType']);
		$AR_MEM = $model->getMember($member_id);
		$AR_PACK = $model->getPackage($package_id);
		
		$AR_SEND['package_id']=$package_id;
		switch($fldvType){
			case "EWALLET":
				$wallet_id = $model->getDefaultWallet();
				$LDGR = $model->getCurrentBalance($member_id,$wallet_id,"","");

				$order_no = UniqueId("ORDER_NO");
				if($form_data['upgradeMemberShip']==1 && $this->input->post()!=''){
					if($package_id>0){
						if($LDGR['net_balance']>=$AR_PACK['package_price'] && $LDGR['net_balance']>0){
							$data_sub = array("order_no"=>$order_no,
								"member_id"=>$member_id,
								"package_id"=>$AR_PACK['package_id'],
								"package_price"=>$AR_PACK['package_price'],
								"package_name"=>$AR_PACK['package_name'],
								
								"package_ccl"=>$AR_PACK['package_ccl'],
								"package_credit"=>$AR_PACK['package_credit'],
								"monthly_rent"=>$AR_PACK['monthly_rent'],
								"yearly_rent"=>$AR_PACK['yearly_rent'],
								"int_ccl_month"=>$AR_PACK['int_ccl_month'],
								"max_ccl"=>$AR_PACK['max_ccl'],
								"max_transaction"=>$AR_PACK['max_transaction'],
								"max_limit_receive"=>$AR_PACK['max_limit_receive'],
								"max_trns_day"=>$AR_PACK['max_trns_day']
							);
							$model->wallet_transaction($wallet_id,$member_id,"Dr",$AR_PACK['package_price'],"Package Upgrade",$today_date,$order_no,"UPGRADE");
							
							$this->SqlModel->insertRecord("tbl_subscription",$data_sub);
							$this->SqlModel->updateRecord("tbl_members",array("package_id"=>$AR_PACK['package_id']),array("member_id"=>$member_id));
							set_message("success","You have successfully upgraded your package");
							redirect_member("account","upgradepackage",'');
						}else{
							set_message("warning","You don't have enough credit to upgrade this package");
							redirect_member("account","paymentpackage",array("package_id"=>_e($AR_PACK['package_id'])));
						}
					}else{
						set_message("warning","Invalid , package selection");
						redirect_member("account","paymentpackage",array("package_id"=>_e($AR_PACK['package_id'])));
					}
				}
			break;
		}
	}	
	
}
