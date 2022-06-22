<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cronjob extends CI_Controller {
	 public function __construct() {
        parent::__construct();
    }
	
	
	public function deletecaching(){
		$this->db->cache_delete_all();
	}
	
	
	public function upgradememberrank(){ #cron daily #
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		$start_date = InsertDate("2021-12-01");  #InsertDate(AddToDate($today_date,"-1 Day")); 
		$end_date = InsertDate($today_date);
		
				
		
		
		$QR_MEM = "SELECT tm.*
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 $StrWhr
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			$rank_id = getTool($AR_MEM['rank_id'],1);
			
			#$total_join = $model->getMemberJoining($member_id,"",$start_date,$end_date);
			$QR_RANK = "SELECT tr.* 
						FROM tbl_rank AS tr 
						WHERE tr.rank_id NOT IN (SELECT to_rank_id FROM tbl_mem_rank WHERE member_id='$member_id')
						AND tr.rank_id>1
						ORDER BY tr.rank_id ASC LIMIT 1";
			$AR_RANK = $this->SqlModel->runQuery($QR_RANK,true);
			$to_rank_id = $AR_RANK['rank_id'];
			$rank_id_sub = $AR_RANK['rank_id_sub'];
			$rank_id_sub_count = $AR_RANK['rank_id_sub_count'];
			$min_point = $AR_RANK['min_point'];
			$max_point = $AR_RANK['max_point'];
			$team_count = $AR_RANK['team_count'];
			
				
			if($to_rank_id==2){
				$direct_count = $model->BinaryCount($member_id, "DirectCount");
				if($direct_count>=$rank_id_sub_count){
					$model->upgradeRank($member_id,$rank_id,$to_rank_id,$rank_id_sub_count,$direct_count);
				}
				
			}
			if($to_rank_id>2){
				$AR_BSNS = $model->getDirectMemberCollection($member_id, "","");
				$AR_RANK_COUNT = $model->getDirectMemberCountByRank($member_id,$rank_id_sub,"","");
				arsort($AR_BSNS);
				arsort($AR_RANK_COUNT);
				$j=1;
				foreach($AR_BSNS as $key=>$key_value):
					if($j==1){
						$first_point =$key_value;
					}else{
						$reset_point += $key_value;
					}
					
					$j++;
				endforeach;
				foreach($AR_RANK_COUNT as $key=>$key_rank):
					if($key_rank>0){
						$total_rank += ($key_rank>1 && $to_rank_id>3)? 2:1;
					}
				endforeach;
				if($min_point>0 && $max_point>0){
					if( ($first_point>=$min_point && $reset_point>=$max_point) || ($first_point>=$max_point && $reset_point>=$min_point) ){
						$model->upgradeRank($member_id,$rank_id,$to_rank_id,"","");
					}
				}
				
				if($total_rank>=$rank_id_sub_count){
					$model->upgradeRank($member_id,$rank_id,$to_rank_id,"","");
				}
					
				unset($AR_BSNS,$AR_RANK_COUNT,$first_point,$reset_point,$total_rank);	
			}
			unset($member_id,$total_join,$to_rank_id,$rank_id_sub,$rank_id_sub_count,$min_point,$max_point,$team_count);
		endforeach;
	}
	
	public function royaltyupgrade(){ #cron daily #
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		$last_month = InsertDate(AddToDate($today_date,"-0 Month")); 
		$AR_DATE = getMonthDates($last_month);
		$start_date = InsertDate($AR_DATE['flddFDate']);
		$end_date = InsertDate($AR_DATE['flddTDate']);
		
		$QR_MEM = "SELECT tm.*
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 $StrWhr
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			
			$AR_BSNS = $model->getDirectMemberCollection($member_id,$start_date,$end_date);
			arsort($AR_BSNS);
			$j=1;
			foreach($AR_BSNS as $key=>$key_value):
				if($j==1){
					$point_first_get = $key_value;
				}else{
					$point_rest_get += $key_value;
				}
				
				$j++;
			endforeach;
			$QR_SEL = "SELECT tsr.* FROM tbl_setup_royalty AS tsr 
					   WHERE tsr.point_first<='$point_first_get' AND  tsr.point_rest<='$point_rest_get' 
					   ORDER BY tsr.royalty_id DESC LIMIT 1";
			$AR_SEL = $this->SqlModel->runQuery($QR_SEL,true);
			$royalty_id = $AR_SEL['royalty_id'];
			$point_first_set = $AR_SEL['point_first'];
			$point_rest_set = $AR_SEL['point_rest'];
			if($royalty_id>0){
				$data_set = array("member_id"=>$member_id,
					"royalty_id"=>$royalty_id,
					"point_first_set"=>getTool($point_first_set,0),
					"point_first_get"=>getTool($point_first_get,0),
					
					"point_rest_set"=>getTool($point_rest_set,0),
					"point_rest_get"=>getTool($point_rest_get,0),
					
					"from_date"=>$start_date,
					"end_date"=>$end_date					
				);
				if($model->checkCountPro("tbl_mem_royalty","member_id='$member_id' AND from_date='$start_date' AND end_date='$end_date'")==0){
					$this->SqlModel->insertRecord("tbl_mem_royalty",$data_set);
				}
			}
			unset($j,$member_id,$point_first_get,$point_rest_get,$point_rest_set,$point_rest_get,$royalty_id);
			unset($data_set);
		endforeach;
	}
	
	
	public function royaltydistribute1(){ #cron monthly on every 1 of month
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		$royalty_type = "R1";
		$last_month = InsertDate(AddToDate($today_date,"-0 Month")); 
		$AR_DATE = getMonthDates($last_month);
		$start_date = InsertDate($AR_DATE['flddFDate']);
		$end_date = InsertDate($AR_DATE['flddTDate']);
		
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		$trns_remark = "ROYALTY INCOME [".$start_date."][".$end_date."]";
		$this->SqlModel->deleteRecord("tbl_cmsn_royalty",array("date_from"=>$start_date,"date_end"=>$end_date));
		$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
		
		
		$QR_MEM = "SELECT tm.*
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 $StrWhr
				 AND tm.rank_id>1
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			$rank_id = $AR_MEM['rank_id'];
			$trans_no = UniqueId("TRNS_NO");
			
			$AR_BSNS = $model->getDirectMemberRoyaltyCollection($member_id,$start_date,$end_date);
			$j=1;
			foreach($AR_BSNS as $key=>$key_value):
				if($j<=5){
					$royalty_total += $key_value;
				}				
				$j++;
			endforeach;
			if($royalty_total>0){
				$QR_RANK = "SELECT royalty_cmsn AS royalty_ratio FROM tbl_rank WHERE rank_id='$rank_id'";
				$AR_RANK = $this->SqlModel->runQuery($QR_RANK,true);
				$royalty_ratio = $AR_RANK['royalty_ratio'];
				if($royalty_ratio>0){
					$total_income = $royalty_total * $royalty_ratio / 100;
					$royalty_point = $royalty_total - $total_income;
					$tds_charge = $total_income * $tds_ratio/100;
					$admin_charge = $total_income * $admin_ratio/100;
					$net_income = $total_income - ( $tds_charge + $admin_charge );
			
					$data_set = array("member_id"=>$member_id,
						"trans_no"=>$trans_no,
						"royalty_total"=>getTool($royalty_total,0),
						"royalty_ratio"=>getTool($royalty_ratio,0),
						
						"royalty_point"=>getTool($royalty_point,0),
						"total_income"=>getTool($total_income,0),
						"admin_charge"=>getTool($admin_charge,0),
						"tds_charge"=>getTool($tds_charge,0),
						"net_income"=>getTool($net_income,0),
						
						"date_from"=>$start_date,
						"date_end"=>$end_date,
						"royalty_type"=>$royalty_type
											
					);
					if($model->checkCountPro("tbl_cmsn_royalty","member_id='$member_id' AND date_from='$start_date' AND date_end='$end_date' AND royalty_type='$royalty_type'")==0){
						$this->SqlModel->insertRecord("tbl_cmsn_royalty",$data_set);
					}
				}
			}
			
			
			unset($j,$member_id,$rank_id,$trans_no,$royalty_total,$royalty_ratio,$total_income);
			unset($data_set);
		endforeach;
	}
	
	public function royaltydistribute2(){ #cron monthly on every 1 of month
		$model = new OperationModel();
		$wallet_id = $model->getWallet(WALLET1);
		
		$today_date = InsertDate(getLocalTime());
		$last_month_date = InsertDate(AddToDate($today_date,"-1 Month"));
		$current_month = InsertDate(AddToDate($today_date,"-0 Month"));
		 
		$X_DATE = getMonthDates($last_month_date);
		$start_date_x = InsertDate($X_DATE['flddFDate']);
		$end_date_x = InsertDate($X_DATE['flddTDate']);
		
		$AR_DATE = getMonthDates($current_month);
		$start_date = InsertDate($AR_DATE['flddFDate']);
		$end_date = InsertDate($AR_DATE['flddTDate']);
		
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		$royalty_type = "R2";
		$trns_remark = "ROYALTY INCOME [".$start_date."][".$end_date."]";
		$this->SqlModel->deleteRecord("tbl_cmsn_royalty",array("date_from"=>$start_date,"date_end"=>$end_date));
		$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
		
		$QR_MEM = "SELECT tm.*
				 FROM tbl_members AS tm 
				 WHERE  tm.rank_id IN(3,4)
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		$royalty_total = $model->getTotalSubscription($start_date,$end_date)*30/100;
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			$rank_id = $AR_MEM['rank_id'];
			$date_join = InsertDate($AR_MEM['date_join']);
			$process_status = "N";
			if(strtotime($date_join)>=strtotime($start_date) && strtotime($date_join)<=strtotime($end_date)){
				$process_status = "Y";
			}elseif(strtotime($date_join)>=strtotime($start_date_x) && strtotime($date_join)<=strtotime($end_date_x)){
				if($model->checkCountPro("tbl_mem_royalty","member_id='$member_id' AND from_date='$start_date_x' AND end_date='$end_date_x'")>0){
					$process_status = "Y";
				}
			}
			
			if($process_status=="Y" && $royalty_total>0){
					$royalty_point = $royalty_total * $royalty_ratio / 100;
								
					$data_set = array("member_id"=>$member_id,
						"royalty_total"=>getTool($royalty_total,0),
												
						"date_from"=>$start_date,
						"date_end"=>$end_date,
						"royalty_type"=>$royalty_type
											
					);
					if($model->checkCountPro("tbl_cmsn_royalty","member_id='$member_id' AND date_from='$start_date' AND date_end='$end_date' AND royalty_type='$royalty_type'")==0){
						$this->SqlModel->insertRecord("tbl_cmsn_royalty",$data_set);
					}
					
			}
			unset($member_id,$date_join,$process_status);
		endforeach;
		$QR_SEL = "SELECT * FROM tbl_cmsn_royalty WHERE  date_from='$start_date' AND date_end='$end_date' AND royalty_type='$royalty_type' ORDER BY royalty_cmsn_id  ASC";
		$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
		$royalty_achiver = count($RS_SEL);
		#$royalty_cmsn = $model->getTotalRoyalty($start_date,$end_date,$royalty_type);
		foreach($RS_SEL as $AR_SEL):
			$member_id = $AR_SEL['member_id'];
			$total_income = $royalty_total / $royalty_achiver;
			
			$tds_charge = $total_income * $tds_ratio/100;
			$admin_charge = $total_income * $admin_ratio/100;
			$net_income = $total_income - ( $tds_charge + $admin_charge );
			$trans_no = UniqueId("TRNS_NO");
			$data_up = array("trans_no"=>$trans_no,
				"royalty_achiver"=>$royalty_achiver,
				"total_income"=>getTool($total_income,0),
				"admin_charge"=>getTool($admin_charge,0),
				"tds_charge"=>getTool($tds_charge,0),
				"net_income"=>getTool($net_income,0)				
			);
			$this->SqlModel->updateRecord("tbl_cmsn_royalty",$data_up,array("member_id"=>$member_id,"date_from"=>$start_date,"date_end"=>$end_date));
			$model->wallet_transaction($wallet_id,$member_id,"Cr",$net_income,$trns_remark,$today_date,$trans_no,array("trns_for"=>"RYL","trans_ref_no"=>$trans_no));
		endforeach;
	}
	
	
	public function levelincome(){ #cron 1 
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(1);
		$wallet_id = $model->getWallet(WALLET1);
		
		$today_date =  InsertDate(getLocalTime());
		$cmsn_date = ($_REQUEST['cmsn_date']!='')? InsertDate($_REQUEST['cmsn_date']):InsertDate(AddToDate($today_date,"-1 Day")); 
		$day = getDateFormat($cmsn_date,"d");
		

		
		$tree_type = "LVL";
		
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		
		$trns_remark = "LEVEL INCOME [".$cmsn_date."]";
		$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit",array("cmsn_date"=>$cmsn_date));
		$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit_mstr",array("trns_remark"=>$trns_remark));
		$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
		
		$QR_MEM = "SELECT tm.*, tree.nlevel, tree.nleft, tree.nright
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
				 
		$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
		$Ctrl=1;
		foreach($RS_MEM as $AR_MEM):
			$member_id = FCrtRplc($AR_MEM['member_id']);
			$rank_id = $model->getMemberRankId($member_id,$cmsn_date);
			$nlevel = FCrtRplc($AR_MEM['nlevel']);
			$nleft = FCrtRplc($AR_MEM['nleft']);
			$nright = FCrtRplc($AR_MEM['nright']);
			$trans_no = UniqueId("TRNS_NO");
		
			if($member_id>0){
				$Q_ALLLVL = "SELECT * FROM tbl_setup_level_cmsn WHERE level_sts>0 AND rank_id='$rank_id'   ORDER BY level_id ASC";
				$RS_ALLLVL = $this->SqlModel->runQuery($Q_ALLLVL);
		
				foreach($RS_ALLLVL as $AR_ALLLVL):
					$level_ratio = $AR_ALLLVL['level_cmsn'];
					
					#$StrWhrIn = " AND tmp.point_id NOT IN(SELECT MIN(point_id) FROM tbl_mem_point WHERE member_id=tmp.member_id $StrWhrIn)
					#			 AND DATE(date_time) BETWEEN '".InsertDate($start_date)."' AND '".InsertDate($end_date)."'";
					$QR_BSNS = "SELECT 
						ts.subcription_id , ts.member_id, ts.prod_pv, tree.nlevel
						FROM  tbl_subscription AS ts 
						LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=ts.member_id
						LEFT JOIN tbl_members AS tm ON tm.member_id=tree.member_id
						WHERE  tree.nleft BETWEEN '".$nleft."' AND '".$nright."' 
						AND DATE(ts.date_time) = '".InsertDate($cmsn_date)."'
						AND tree.member_id!='".$member_id."'
						ORDER BY ts.subcription_id ASC";
					#PrintR($QR_BSNS);
					$RS_BSNS = $this->SqlModel->runQuery($QR_BSNS);
					foreach($RS_BSNS as $AR_BSNS):
						$from_member_id = $AR_BSNS['member_id'];
						$from_nlevel = $AR_BSNS['nlevel'];
						$from_sponsor_id = $model->getSponsor($from_member_id);
						$total_ratio = ($from_sponsor_id==$member_id)? $model->getRoyaltyRatio($rank_id):$level_ratio;
						$net_amount = $AR_BSNS['prod_pv'];
						$level_no = $from_nlevel - $nlevel;
						$level_cmsn = $net_amount * ( $total_ratio / 100 );
						if( $level_cmsn > 0 && $model->checkLevelRank($member_id,$from_member_id,$rank_id)==0){
							$data_lvl = array("member_id"=>$member_id,
								"level_no"=>$level_no,
								"from_member_id"=>$from_member_id,
								"net_amount"=>getTool($net_amount,0),
								"level_ratio"=>getTool($total_ratio,0),
								"level_cmsn"=>getTool($level_cmsn,0),
								"cmsn_date"=>$cmsn_date
							);
							$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit",$data_lvl);
						}
						unset($from_member_id,$from_nlevel,$net_amount,$level_cmsn,$total_ratio);
					endforeach;
					unset($level_ratio,$level_no,$mem_level,$level_rank_id);			
				endforeach;
				$total_income = $model->getLevelProcessCmsn($member_id,$cmsn_date);
				if($total_income > 0){
					
					$tds_charge = $total_income * $tds_ratio/100;
					$admin_charge = $total_income * $admin_ratio/100;
					$net_income = $total_income - ( $tds_charge + $admin_charge );
		
					$data_mstr = array("member_id"=>$member_id,
						"trans_no"=>$trans_no,
						"total_income"=>$total_income,
						"admin_charge"=>getTool($admin_charge,0),
						"tds_charge"=>getTool($tds_charge,0),
						"net_income"=>$net_income,
						"cmsn_date"=>$cmsn_date,
						"trns_remark"=>$trns_remark
					);
					$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit_mstr",$data_mstr);
					$model->wallet_transaction($wallet_id,$member_id,"Cr",$net_income,$trns_remark,$cmsn_date,$trans_no,array("trns_for"=>"LVL","trans_ref_no"=>$trans_no));
				}
				unset($member_id, $rank_id, $nlevel, $nleft, $nright, $trans_no);
				$Ctrl++;
			}
		endforeach;
		
	}
	
	
	public function binaryincome(){ #cron 15 days
		$model = new OperationModel();
		$form_data = $this->input->get();
		$today_date = InsertDate(getLocalTime());
		$wallet_id = $model->getWallet(WALLET1);
		$process_id = $_REQUEST['process_id'];
		$AR_PRSS = $model->getPendingProcessBinary($process_id);
		$process_id = $AR_PRSS['process_id'];
		$binary_date = InsertDate("2020-05-01");
		$start_date = InsertDate($AR_PRSS['start_date']);
		$end_date = InsertDate($AR_PRSS['end_date']);
		
		$day = getDateFormat($start_date,"d");
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		$trns_remark = "BINARY [".$start_date." To ".$end_date."]";
		$this->SqlModel->deleteRecord("tbl_cmsn_binary",array("process_id"=>$process_id));
		$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
		
		
		$QR_MEM = "SELECT tm.*, tree.nlevel, tree.nleft, tree.nright
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 AND tm.block_sts='N' AND tm.member_id IN(SELECT member_id FROM tbl_mem_point)
				 GROUP BY tm.member_id 
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		
		
		$binary_rate = 8;
		$pin_base = 1000;
		$binary_ceiling = 30000;
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];	
			$AR_SELF = $model->getSumSelfCollection($member_id,"","","");
			$self_pv = $AR_SELF['total_bal_pv'];
		
			$left_direct = $model->BinaryCount($member_id,"LeftCountDirect");
			$right_direct = $model->BinaryCount($member_id,"RightCountDirect");
			
			$AR_OLD = $model->getOldBinary($process_id,$member_id);
			
			$from_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['start_date']:$binary_date;
			$end_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['end_date']:$AR_PRSS['end_date'];
			
			$preLcrf = $AR_OLD['leftCrf'];
			$preRcrf = $AR_OLD['rightCrf'];
				
			$newLft = $model->getPointCollection($member_id,"L",$from_date,$end_date,"");
			$newRgt = $model->getPointCollection($member_id,"R",$from_date,$end_date,"");
			
			
			$totalLft = $preLcrf+$newLft;
			$totalRgt = $preRcrf+$newRgt;

			$binary_narration ="You don't have any joining";
			
			if($left_direct>0 && $right_direct>0 && $self_pv>=$pin_base){
			
				$process = "active";
				$pair_match =  min($totalLft,$totalRgt);
				
				$leftCrf = ( $totalLft - $pair_match );
				$rightCrf = ( $totalRgt - $pair_match );
				$ceiling = 0;
				if($process=="active"){
					$binary_narration ="";
					$net_amount = ($pair_match*$binary_rate)/100;
					
					if($net_amount>$binary_ceiling && $net_amount>0 && $binary_ceiling>0){
						$binary_narration = "Binary Ceiling[".$binary_ceiling."]";
						$ceiling = $binary_ceiling;
						$net_amount = $ceiling;
					}else if($net_amount<=0){
						$binary_narration = "Matching not found";
						$net_amount = 0;
					}
				}elseif($newLft>0 && $newRgt==0){
					$binary_narration = "No joining found in your right";
					$net_amount = 0;
				}elseif($newRgt>0 && $newLft==0){
					$binary_narration = "No joining found in your left";
					$net_amount = 0;
				}
				
				$trans_no = UniqueId("TRNS_NO");
				
				$admin_charge = ($net_amount*$admin_ratio)/100;
				$tds_charge = ($net_amount*$tds_ratio)/100;
				$net_payout = $net_amount - ($admin_charge + $tds_charge);
				
				
				if($newLft>0 || $newRgt>0){
					$data_binary = array("process_id"=>$process_id,
							"member_id"=>$member_id,
							"trans_no"=>$trans_no,
							"preRcrf"=>getTool($preRcrf,0),
							"preLcrf"=>getTool($preLcrf,0),
							"newLft"=>getTool($newLft,0),
							"newRgt"=>getTool($newRgt,0),
							"totalLft"=>getTool($totalLft,0),
							"totalRgt"=>getTool($totalRgt,0),
							"pair_match"=>getTool($pair_match,0),
							"leftCrf"=>getTool($leftCrf,0),
							"rightCrf"=>getTool($rightCrf,0),
							"binary_rate"=>getTool($binary_rate,0),
							"binary_ceiling"=>getTool($ceiling,0),
							"binary_narration"=>getTool($binary_narration,''),
							"trns_remark"=>getTool($trns_remark,''),
							"amount"=>getTool($net_amount,0),
							"tds"=>getTool($tds_charge,0),
							"admin_charge"=>getTool($admin_charge,0),
							"net_cmsn"=>getTool($net_payout,0),
							"date_time"=>$end_date
					);
					
					$this->SqlModel->insertRecord("tbl_cmsn_binary",$data_binary);	
					#$model->wallet_transaction($wallet_id,$member_id,"Cr",$net_payout,$trns_remark,$today_date,"",array("trns_for"=>"BNY","trans_ref_no"=>$trans_no));
				}
		}
		unset($data_binary,$net_payout,$tds_charge,$admin_charge,$net_amount,$total_income,$net_payout,$binary_narration,
			  $preLcrf,$preRcrf,$leftCrf,$rightCrf,$totalLft,$totalRgt,$pair_match,$first_pair);
		unset($QR_LVL,$AR_LVL,$nlevel_lvl,$nleft_lvl,$nright_lvl,$StrNotIn);
		endforeach;		
	}
	
	
	
	
	
	public function binarylevelincome(){ #cron monthly
		$model = new OperationModel();
		
		$segment = $this->uri->uri_to_assoc(1);
		$wallet_id = $model->getWallet(WALLET1);
		
		$process_id = $_REQUEST['process_id'];
		$AR_PRSS = $model->getPendingProcessBinary($process_id);
		$process_id = $AR_PRSS['process_id'];
		

		
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		if($process_id>0){
			$trns_remark = "MATCHING LEVEL INCOME [ NO : ".$process_id."]";
			$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit_lvl",array("process_id"=>$process_id));
			$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit_mstr_lvl",array("process_id"=>$process_id));
			$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
			
			
			$QR_MEM = "SELECT tm.member_id, tree.nleft, tree.nright, tree.nlevel
					   FROM tbl_members AS tm
					   LEFT JOIN tbl_mem_tree_lvl AS tree ON tree.member_id=tm.member_id
					   WHERE tm.member_id>0  
					   ORDER BY tm.member_id ASC";
			$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
			$Ctrl=1;
			foreach($RS_MEM as $AR_MEM):
				$member_id = $AR_MEM['member_id'];
				$nleft = 	$AR_MEM['nleft'];	
				$nright = 	$AR_MEM['nright'];	
				$nlevel = 	$AR_MEM['nlevel'];		
	
				if($member_id>0){
					$Q_ALLLVL = "SELECT * FROM tbl_setup_match_cmsn_lvl WHERE level_sts>0   ORDER BY level_id ASC";
					$RS_ALLLVL = $this->SqlModel->runQuery($Q_ALLLVL);
	
					foreach($RS_ALLLVL as $AR_ALLLVL):
						$level_ratio = $AR_ALLLVL['level_ratio'];
						$level_no = $AR_ALLLVL['level_no'];
						$new_level = getTool($nlevel+$level_no,0);
						
						$QR_SEL = "SELECT tcb.member_id AS from_member_id, tcb.amount AS net_amount
								   FROM  tbl_cmsn_binary AS tcb 
								   LEFT JOIN tbl_mem_tree_lvl AS tree  ON tree.member_id=tcb.member_id
								   WHERE tcb.process_id='$process_id'
								   AND tree.nleft BETWEEN '$nleft' AND '$nright' 
								   AND tree.nlevel='$new_level'";
						$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
						foreach($RS_SEL as $AR_SEL):
						$from_member_id = $AR_SEL['from_member_id'];
						$binary_income = $AR_SEL['net_amount'];	
						$level_cmsn  = $binary_income*$level_ratio/100;
						if( $from_member_id > 0 && $level_cmsn>0 ){
							
							$data_lvl = array("member_id"=>$member_id,
								"from_member_id"=>$from_member_id,
								"process_id"=>$process_id,
								"level_no"=>$nlevel,
								"binary_income"=>getTool($binary_income,0),
								"level_ratio"=>getTool($level_ratio,0),
								"level_cmsn"=>getTool($level_cmsn,0)
								
							);
							$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit_lvl",$data_lvl);
												
						}
						unset($from_member_id,$binary_income,$level_cmsn,$data_lvl);		
						endforeach;
						
						unset($level_ratio,$level_no,$new_level);			
					endforeach;
					$Ctrl++;
				}
				unset($member_id);
			endforeach;
			$QR_MEM = "SELECT DISTINCT member_id FROM tbl_cmsn_lvl_benefit_lvl WHERE process_id='$process_id' ORDER BY member_id ASC";
			$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
			foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			$total_income = $model->getBinaryLevelProcessCmsn($member_id,$process_id);
			if($total_income > 0){
				$tds_charge = $total_income * $tds_ratio/100;
				$admin_charge = $total_income * $admin_ratio/100;
				$net_income = $total_income - ( $tds_charge + $admin_charge );
				$trans_no = UniqueId("TRNS_NO");
				$data_mstr = array("member_id"=>$member_id,
					"process_id"=>$process_id,
					"trans_no"=>$trans_no,
					"total_income"=>$total_income,
					"admin_charge"=>getTool($admin_charge,0),
					"tds_charge"=>getTool($tds_charge,0),
					"net_income"=>$net_income,
					"trns_remark"=>$trns_remark
				);
				$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit_mstr_lvl",$data_mstr);
			}
			endforeach;
			unset($member_id, $nlevel, $nleft, $nright, $trans_no, $total_income, $admin_charge,$tds_charge,$net_income);
			$this->SqlModel->updateRecord("tbl_process_binary",array("process_sts"=>"Y"),array("process_id"=>$process_id));
			if($model->checkCount("tbl_process_binary","process_id",$process_id+1)==0){
				$new_start_date = InsertDate(AddToDate($AR_PRSS['end_date'],"+1 day"));
				if($day>=16){  
					$AR_MONTH = getMonthDates($today_date);
					$new_end_date =   InsertDate(AddToDate($AR_MONTH['flddTDate']));
				}else{
					$new_end_date =   InsertDate(AddToDate($AR_PRSS['end_date'],"+15 day"));				
				}
				$this->SqlModel->insertRecord("tbl_process_binary",array("start_date"=>$new_start_date,"end_date"=>$new_end_date));
			}
		}
				
	}
	
	public function binaryincomerepurchase(){ #cron 15 days
		$model = new OperationModel();
		$form_data = $this->input->get();
		$today_date = InsertDate(getLocalTime());
		$wallet_id = $model->getWallet(WALLET1);
		$process_id = $_REQUEST['process_id'];
		$AR_PRSS = $model->getPendingProcess($process_id);
		$process_id = $AR_PRSS['process_id'];
		$binary_date = InsertDate("2020-05-01");
		$start_date = InsertDate($AR_PRSS['start_date']);
		$end_date = InsertDate($AR_PRSS['end_date']);
		
		$day = getDateFormat($start_date,"d");
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
		
		$trns_remark = "MATCHING REPURCHASE [".$start_date." To ".$end_date."]";
		$this->SqlModel->deleteRecord("tbl_cmsn_binary_repur",array("process_id"=>$process_id));
		$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
		
		
		$QR_MEM = "SELECT tm.*, tree.nlevel, tree.nleft, tree.nright
				 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
				 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
				 AND node.nleft BETWEEN tree.nleft AND tree.nright
				 AND node.member_id IN(SELECT member_id FROM tbl_orders  WHERE DATE(date_add) BETWEEN '$start_date' AND '$end_date' )
				 AND tm.block_sts='N'
				 AND tm.member_id IN(SELECT member_id FROM tbl_subscription  WHERE  type_id IN(SELECT type_id FROM tbl_pintype WHERE pin_letter IN('RF')) )
				 GROUP BY tm.member_id
				 ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		
		$binary_rate = 10;
		$pin_base = 1;
		$binary_ceiling = 160000;
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];	
			
			
			$left_direct = $model->BinaryCount($member_id,"LeftCountDirect");
			$right_direct = $model->BinaryCount($member_id,"RightCountDirect");
			
			$AR_OLD = $model->getOldBinaryRepurcase($process_id,$member_id);
			
			$from_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['start_date']:$binary_date;
			$end_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['end_date']:$AR_PRSS['end_date'];
			
			$preLcrf = $AR_OLD['leftCrf'];
			$preRcrf = $AR_OLD['rightCrf'];
				
			$newLft = $model->getOrderCollection($member_id,"L",$from_date,$end_date,"S");
			$newRgt = $model->getOrderCollection($member_id,"R",$from_date,$end_date,"S");
			
			
			
			$totalLft = $preLcrf+$newLft;
			$totalRgt = $preRcrf+$newRgt;
			
			$process = "block";
			$first_pair = 0;
			$binary_narration ="You don't have any business in downline";
			
			
			if($left_direct>0 && $right_direct>0){
			
				$process = "active";
				$pair_match =  min($totalLft,$totalRgt);
				
				$leftCrf = ( $totalLft - $pair_match );
				$rightCrf = ( $totalRgt - $pair_match );
				
				$ceiling = 0;
				if($process=="active"){
					$binary_narration ="";
					$net_amount = $pair_match*$binary_rate/100;
					
					if($net_amount>$binary_ceiling && $net_amount>0 && $binary_ceiling>0){
						$binary_narration = "Binary Ceiling[".$binary_ceiling."]";
						$ceiling = $binary_ceiling;
						$net_amount = $ceiling;
					}else if($net_amount<=0){
						$binary_narration = "Matching not found";
						$net_amount = 0;
					}
				}elseif($newLft>0 && $newRgt==0){
					$binary_narration = "No order found in your right";
					$net_amount = 0;
				}elseif($newRgt>0 && $newLft==0){
					$binary_narration = "No order found in your left";
					$net_amount = 0;
				}
				
				$trans_no = UniqueId("TRNS_NO");
				
				$admin_charge = ($net_amount*$admin_ratio)/100;
				$tds_charge = ($net_amount*$tds_ratio)/100;
				$net_payout = $net_amount - ($admin_charge + $tds_charge);
				
				
				if($newLft>0 || $newRgt>0){
					$data_binary = array("process_id"=>$process_id,
							"member_id"=>$member_id,
							"trans_no"=>$trans_no,
							"preRcrf"=>getTool($preRcrf,0),
							"preLcrf"=>getTool($preLcrf,0),
							"newLft"=>getTool($newLft,0),
							"newRgt"=>getTool($newRgt,0),
							"totalLft"=>getTool($totalLft,0),
							"totalRgt"=>getTool($totalRgt,0),
							"pair_match"=>getTool($pair_match,0),
							"leftCrf"=>getTool($leftCrf,0),
							"rightCrf"=>getTool($rightCrf,0),
							"binary_rate"=>getTool($binary_rate,0),
							"binary_ceiling"=>getTool($ceiling,0),
							"binary_narration"=>getTool($binary_narration,''),
							"trns_remark"=>getTool($trns_remark,''),
							"amount"=>getTool($net_amount,0),
							"tds"=>getTool($tds_charge,0),
							"admin_charge"=>getTool($admin_charge,0),
							"net_cmsn"=>getTool($net_payout,0),
							"date_time"=>$end_date
					);
					
					$this->SqlModel->insertRecord("tbl_cmsn_binary_repur",$data_binary);	
					#$model->wallet_transaction($wallet_id,$member_id,"Cr",$net_payout,$trns_remark,$today_date,"",array("trns_for"=>"BNY","trans_ref_no"=>$trans_no));
				}
		}
		unset($data_binary,$net_payout,$tds_charge,$admin_charge,$net_amount,$total_income,$net_payout,$binary_narration,
			  $preLcrf,$preRcrf,$leftCrf,$rightCrf,$totalLft,$totalRgt,$pair_match,$first_pair);
		unset($QR_LVL,$AR_LVL,$nlevel_lvl,$nleft_lvl,$nright_lvl,$StrNotIn);
		endforeach;		
	}
	
	
	
	
	
	
	
	public function autowithdraw(){
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		
		$wallet_id_1 = $model->getWallet(WALLET1);
		$from_member_id = $model->getFirstId();
		$min_amount = 1;
		$QR_MEM = "SELECT tm.* ,tree.nleft, tree.nright
				   FROM tbl_members AS tm 
				   LEFT JOIN tbl_mem_tree AS tree ON tree.member_id=tm.member_id
				   WHERE  tm.delete_sts>0 
				   AND tm.member_id IN(SELECT member_id FROM tbl_wallet_trns WHERE wallet_id='".$wallet_id_1."')
				   ORDER BY tm.member_id ASC";
		$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
		foreach($RS_MEM as $AR_MEM):
			$member_id = $AR_MEM['member_id'];
			$LDGR = $model->getPayoutWalletBalance($member_id,$wallet_id_1,"","");
			$net_balance = $LDGR['net_balance'];
			$trns_remark = "AUTO WITHDRAW ".$AR_MEM['user_id'];
			if($net_balance>$min_amount && $net_balance>0){
				$trans_no = UniqueId("TRNS_NO");
				
				$draw_amount = $net_balance;
				$total_charge = 0;
				$trns_amount = ($draw_amount-$total_charge);
				
				$data = array("to_member_id"=>$member_id,
					"from_member_id"=>$from_member_id,
					"trans_no"=>$trans_no,
					"wallet_id"=>$wallet_id_1,
					"initial_amount"=>$draw_amount,
					"withdraw_fee"=>getTool($WITDRAW_FEE,0),
					"deposit_fee"=>getTool($DEPOSITE_FEE,0),
					"trns_amount"=>$trns_amount,
					"trns_status"=>"P",
					"trns_type"=>"Dr",
					"trns_date"=>$today_date,
					"trns_for"=>"WTD",
					"draw_type"=>"AUTO",
					"trns_remark"=>$trns_remark
				);
				$withdraw_id = $this->SqlModel->insertRecord("tbl_fund_transfer",$data);
				$model->wallet_transaction($wallet_id_1,$member_id,"Dr",$draw_amount,$trns_remark,$today_date,$trans_no,
				array("trns_for"=>"WTD","trans_ref_no"=>$trans_no));
			}
		endforeach;
		if(count($RS_MEM)>0){
			exit("Successfully process your request");
		}
	}
	
	function resettree(){
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		
		$QR_SET = "SELECT tm.* FROM tbl_members AS tm  
				  WHERE tm.member_id>0 
				  AND tm.member_id NOT IN(SELECT member_id FROM tbl_mem_tree)
				  ORDER BY tm.member_id ASC";
		$RS_SET = $this->SqlModel->runQuery($QR_SET);
		foreach($RS_SET as $AR_SET):
			$sponsor_id = $AR_SET['sponsor_id'];
			$member_id = $AR_SET['member_id'];
			$left_right = $AR_SET['left_right'];
			$date_join = $AR_SET['date_join'];
			if($model->checkCount("tbl_mem_tree","member_id",$sponsor_id)>0){
				$AR_GET = $model->getSponsorSpill($sponsor_id,$left_right);
				$spil_id = $AR_GET['spil_id'];
				
				$tree_data = array("member_id"=>$member_id,
					"sponsor_id"=>$sponsor_id,
					"spil_id"=>$spil_id,
					"left_right"=>getTool($left_right,''),
					"nlevel"=>0,
					"nleft"=>0,
					"nright"=>0,
					"date_join"=>$date_join
				);
				$this->SqlModel->insertRecord("tbl_mem_tree",$tree_data);
				$model->updateTree($spil_id,$member_id);
				$this->SqlModel->updateRecord("tbl_members",array("spil_id"=>$spil_id),array("member_id"=>$member_id));
			}
			unset($sponsor_id,$member_id,$left_right,$date_join,$spil_id);
		endforeach;
	}
	
	function resettreelvl(){
		$model = new OperationModel();
		$today_date = InsertDate(getLocalTime());
		
		$QR_SET = "SELECT tm.* FROM tbl_members AS tm  
				  WHERE tm.member_id>0 
				  AND tm.member_id NOT IN(SELECT member_id FROM tbl_mem_tree_lvl)
				  ORDER BY tm.member_id ASC";
		$RS_SET = $this->SqlModel->runQuery($QR_SET);
		
		foreach($RS_SET as $AR_SET):
			
			$sponsor_id = $AR_SET['sponsor_id'];
			$member_id = $AR_SET['member_id'];
			$date_join = $AR_SET['date_join'];
			if($model->checkCount("tbl_mem_tree_lvl","member_id",$sponsor_id)>0){
								
				$tree_data = array("member_id"=>$member_id,
					"sponsor_id"=>$sponsor_id,
					"spil_id"=>$sponsor_id,
					"left_right"=>getTool($left_right,''),
					"nlevel"=>0,
					"nleft"=>0,
					"nright"=>0,
					"date_join"=>$date_join
				);
				$this->SqlModel->insertRecord("tbl_mem_tree_lvl",$tree_data);
				$model->updateTreeLvl($sponsor_id,$member_id);
			}
			unset($sponsor_id,$member_id,$left_right,$date_join,$spil_id);
		endforeach;
	}
	
	function binaryincomemanual(){
		$model = new OperationModel();
		$form_data = $this->input->get();
		$today_date = InsertDate(getLocalTime());
		$wallet_id = $model->getWallet(WALLET1);
		$process_id = $_REQUEST['process_id'];
		$binary_date = InsertDate("2020-05-01");
				
		$day = getDateFormat($start_date,"d");
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
			
		$QR_PRSS = "SELECT * FROM tbl_process_binary  WHERE process_id>0 AND process_sts='Y' ORDER BY process_id ASC";		
		$RS_PRSS = $this->SqlModel->runQuery($QR_PRSS);
		foreach($RS_PRSS as $AR_PRSS):
		
			$process_id = $AR_PRSS['process_id'];
			$start_date = InsertDate($AR_PRSS['start_date']);
			$end_date = InsertDate($AR_PRSS['end_date']);
			
			
			$trns_remark = "BINARY [".$start_date." To ".$end_date."]";
			$this->SqlModel->deleteRecord("tbl_cmsn_binary",array("process_id"=>$process_id));
			$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
			
			
			$QR_MEM = "SELECT tm.*, tree.nlevel, tree.nleft, tree.nright
					 FROM tbl_members AS tm , tbl_mem_tree AS tree , tbl_mem_tree AS node
					 WHERE tm.delete_sts>0  AND  tm.member_id=tree.member_id 
					 AND node.nleft BETWEEN tree.nleft AND tree.nright
					 AND tm.block_sts='N'
					 AND DATE(tm.date_join)<='".$end_date."'
					 GROUP BY tm.member_id
					 ORDER BY tm.member_id ASC";
			$RS_MEM  = $this->SqlModel->runQuery($QR_MEM);
			
			$binary_rate = 10;
			$pin_base = 2700;
			$binary_ceiling = 30000;
			foreach($RS_MEM as $AR_MEM):
				$member_id = $AR_MEM['member_id'];	
				
				
				$left_direct = $model->BinaryCount($member_id,"LeftCountDirect");
				$right_direct = $model->BinaryCount($member_id,"RightCountDirect");
				
				$AR_OLD = $model->getOldBinary($process_id,$member_id);
				
				$from_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['start_date']:$binary_date;
				$end_date = ($AR_OLD['binary_id']>0)? $AR_PRSS['end_date']:$AR_PRSS['end_date'];
				
				$preLcrf = $AR_OLD['leftCrf'];
				$preRcrf = $AR_OLD['rightCrf'];
					
				$newLft = $model->getPointCollection($member_id,"L",$from_date,$end_date,"");
				$newRgt = $model->getPointCollection($member_id,"R",$from_date,$end_date,"");
				
				
				
				$totalLft = $preLcrf+$newLft;
				$totalRgt = $preRcrf+$newRgt;
				
				
				$process = "block";
				$first_pair = 0;
				$binary_narration ="You don't have any joining";
				
				
				if($left_direct>0 && $right_direct>0){
				
				$process = "active";
				$pair_match =  min($totalLft,$totalRgt);
				
				$leftCrf = ( $totalLft - $pair_match );
				$rightCrf = ( $totalRgt - $pair_match );
				
				$ceiling = 0;
				if($process=="active"){
					$binary_narration ="";
					$net_amount = ($pair_match*$binary_rate)/100;
					
					if($net_amount>$binary_ceiling && $net_amount>0 && $binary_ceiling>0){
						$binary_narration = "Binary Ceiling[".$binary_ceiling."]";
						$ceiling = $binary_ceiling;
						$net_amount = $ceiling;
					}else if($net_amount<=0){
						$binary_narration = "Matching not found";
						$net_amount = 0;
					}
				}elseif($newLft>0 && $newRgt==0){
					$binary_narration = "No joining found in your right";
					$net_amount = 0;
				}elseif($newRgt>0 && $newLft==0){
					$binary_narration = "No joining found in your left";
					$net_amount = 0;
				}
				
				$trans_no = UniqueId("TRNS_NO");
				
				$admin_charge = ($net_amount*$admin_ratio)/100;
				$tds_charge = ($net_amount*$tds_ratio)/100;
				$net_payout = $net_amount - ($admin_charge + $tds_charge);
				
				
				if($newLft>0 || $newRgt>0){
					$data_binary = array("process_id"=>$process_id,
							"member_id"=>$member_id,
							"trans_no"=>$trans_no,
							"preRcrf"=>getTool($preRcrf,0),
							"preLcrf"=>getTool($preLcrf,0),
							"newLft"=>getTool($newLft,0),
							"newRgt"=>getTool($newRgt,0),
							"totalLft"=>getTool($totalLft,0),
							"totalRgt"=>getTool($totalRgt,0),
							"pair_match"=>getTool($pair_match,0),
							"leftCrf"=>getTool($leftCrf,0),
							"rightCrf"=>getTool($rightCrf,0),
							"binary_rate"=>getTool($binary_rate,0),
							"binary_ceiling"=>getTool($ceiling,0),
							"binary_narration"=>getTool($binary_narration,''),
							"trns_remark"=>getTool($trns_remark,''),
							"amount"=>getTool($net_amount,0),
							"tds"=>getTool($tds_charge,0),
							"admin_charge"=>getTool($admin_charge,0),
							"net_cmsn"=>getTool($net_payout,0),
							"date_time"=>$end_date
					);
					
					$this->SqlModel->insertRecord("tbl_cmsn_binary",$data_binary);	
					#$model->wallet_transaction($wallet_id,$member_id,"Cr",$net_payout,$trns_remark,$today_date,"",array("trns_for"=>"BNY","trans_ref_no"=>$trans_no));
				}
			}
			unset($data_binary,$net_payout,$tds_charge,$admin_charge,$net_amount,$total_income,$net_payout,$binary_narration,
				  $preLcrf,$preRcrf,$leftCrf,$rightCrf,$totalLft,$totalRgt,$pair_match,$first_pair);
			unset($QR_LVL,$AR_LVL,$nlevel_lvl,$nleft_lvl,$nright_lvl,$StrNotIn);
			endforeach;		
		unset($process_id);
	endforeach;
	}
	
	public function binarylevelincomemanual(){
		$model = new OperationModel();
		$form_data = $this->input->get();
		$today_date = InsertDate(getLocalTime());
		$wallet_id = $model->getWallet(WALLET1);
				
		$day = getDateFormat($start_date,"d");
		$tds_ratio = getTool($model->getValue("CONFIG_TDS"),0);
		$admin_ratio = getTool($model->getValue("CONFIG_ADMIN_CHARGE"),0);
			
		$QR_PRSS = "SELECT * FROM tbl_process_binary  WHERE process_id>=20 AND process_sts='Y' ORDER BY process_id ASC";		
		$RS_PRSS = $this->SqlModel->runQuery($QR_PRSS);
		foreach($RS_PRSS as $AR_PRSS):
		
		$process_id = $AR_PRSS['process_id'];
		$start_date = InsertDate($AR_PRSS['start_date']);
		$end_date = InsertDate($AR_PRSS['end_date']);
			if($process_id>0){
				$trns_remark = "MATCHING LEVEL INCOME [ NO : ".$process_id."]";
				$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit_lvl",array("process_id"=>$process_id));
				$this->SqlModel->deleteRecord("tbl_cmsn_lvl_benefit_mstr_lvl",array("process_id"=>$process_id));
				$this->SqlModel->deleteRecord("tbl_wallet_trns",array("trns_remark"=>$trns_remark));
				
				
				$QR_MEM = "SELECT tm.member_id, tree.nleft, tree.nright, tree.nlevel
						   FROM tbl_members AS tm
						   LEFT JOIN tbl_mem_tree_lvl AS tree ON tree.member_id=tm.member_id
						   WHERE tm.member_id>0 
						   AND DATE(tm.date_join)<='".$end_date."'
						   ORDER BY tm.member_id ASC";
				$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
				$Ctrl=1;
				foreach($RS_MEM as $AR_MEM):
					$member_id = $AR_MEM['member_id'];
					$nleft = 	$AR_MEM['nleft'];	
					$nright = 	$AR_MEM['nright'];	
					$nlevel = 	$AR_MEM['nlevel'];		
		
					if($member_id>0){
						$Q_ALLLVL = "SELECT * FROM tbl_setup_match_cmsn_lvl WHERE level_sts>0   ORDER BY level_id ASC";
						$RS_ALLLVL = $this->SqlModel->runQuery($Q_ALLLVL);
		
						foreach($RS_ALLLVL as $AR_ALLLVL):
							$level_ratio = $AR_ALLLVL['level_ratio'];
							$level_no = $AR_ALLLVL['level_no'];
							$new_level = getTool($nlevel+$level_no,0);
							
							$QR_SEL = "SELECT tcb.member_id AS from_member_id, tcb.amount AS net_amount
									   FROM  tbl_cmsn_binary AS tcb 
									   LEFT JOIN tbl_mem_tree_lvl AS tree  ON tree.member_id=tcb.member_id
									   WHERE tcb.process_id='$process_id'
									   AND tree.nleft BETWEEN '$nleft' AND '$nright' 
									   AND tree.nlevel='$new_level'";
							$RS_SEL = $this->SqlModel->runQuery($QR_SEL);
							foreach($RS_SEL as $AR_SEL):
							$from_member_id = $AR_SEL['from_member_id'];
							$binary_income = $AR_SEL['net_amount'];	
							$level_cmsn  = $binary_income*$level_ratio/100;
							if( $from_member_id > 0 && $level_cmsn>0 ){
								
								$data_lvl = array("member_id"=>$member_id,
									"from_member_id"=>$from_member_id,
									"process_id"=>$process_id,
									"level_no"=>$nlevel,
									"binary_income"=>getTool($binary_income,0),
									"level_ratio"=>getTool($level_ratio,0),
									"level_cmsn"=>getTool($level_cmsn,0)
									
								);
								$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit_lvl",$data_lvl);
													
							}
							unset($from_member_id,$binary_income,$level_cmsn,$data_lvl);		
							endforeach;
							
							unset($level_ratio,$level_no,$new_level);			
						endforeach;
						$Ctrl++;
					}
					unset($member_id);
				endforeach;
				$QR_MEM = "SELECT DISTINCT member_id FROM tbl_cmsn_lvl_benefit_lvl WHERE process_id='$process_id' ORDER BY member_id ASC";
				$RS_MEM = $this->SqlModel->runQuery($QR_MEM);
				foreach($RS_MEM as $AR_MEM):
				$member_id = $AR_MEM['member_id'];
				$total_income = $model->getBinaryLevelProcessCmsn($member_id,$process_id);
				if($total_income > 0){
					$tds_charge = $total_income * $tds_ratio/100;
					$admin_charge = $total_income * $admin_ratio/100;
					$net_income = $total_income - ( $tds_charge + $admin_charge );
					$trans_no = UniqueId("TRNS_NO");
					$data_mstr = array("member_id"=>$member_id,
						"process_id"=>$process_id,
						"trans_no"=>$trans_no,
						"total_income"=>$total_income,
						"admin_charge"=>getTool($admin_charge,0),
						"tds_charge"=>getTool($tds_charge,0),
						"net_income"=>$net_income,
						"trns_remark"=>$trns_remark
					);
					$this->SqlModel->insertRecord("tbl_cmsn_lvl_benefit_mstr_lvl",$data_mstr);
				}
				endforeach;
				unset($member_id, $nlevel, $nleft, $nright, $trans_no, $total_income, $admin_charge,$tds_charge,$net_income);
				
			}
		unset($process_id);
		endforeach;
	}
}
?>