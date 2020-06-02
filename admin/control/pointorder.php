<?php
/**
 * 积分兑换订单管理
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class pointappointmentControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('pointappointment');
		/**
		 * 判断系统是否开启积分功能和积分兑换功能
		 */
		if ($GLOBALS['setting_config']['points_isuse'] != 1 || $GLOBALS['setting_config']['pointprod_isuse'] != 1){
			showMessage(Language::get('admin_pointappointment_unavailable'),'index.php?act=dashboard&op=welcome','','error');
		}
	}
	/**
	 * 积分兑换列表
	 */
	public function pointappointment_listOp(){
		//条件
		$condition_arr = array();
		//兑换单号
		if (trim($_GET['pappointmentsn'])){
			$condition_arr['point_appointmentsn_like'] = trim($_GET['pappointmentsn']);
		}
		//兑换会员名称
		if (trim($_GET['pbuyname'])){
			$condition_arr['point_buyername_like'] = trim($_GET['pbuyname']);
		}
		if (trim($_GET['pappointmentstate'])){
			$condition_arr['point_appointmentstatetxt'] = trim($_GET['pappointmentstate']);
		}
		//分页
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		//查询直通车列表
		$pointprod_model = Model('pointappointment');
		$appointment_list = $pointprod_model->getPointappointmentList($condition_arr,$page,'simple');
		if (is_array($appointment_list) && count($appointment_list)>0){
			foreach ($appointment_list as $k => $v){
				$appointment_list[$k]['point_appointmentstatetext'] = $this->pointappointment_state($v['point_appointmentstate']);
			}
		}

		//信息输出
		Tpl::output('appointment_list',$appointment_list);
		Tpl::output('show_page',$page->show());
		Tpl::showpage('pointappointment.list');
	}
	/**
	 * 删除积分礼品兑换信息
	 */
	public function appointment_dropOp(){
		$appointment_id = intval($_GET['appointment_id']);
		if (!$appointment_id){
			showMessage(Language::get('admin_pointappointment_parameter_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		$pointappointment_model = Model('pointappointment');
		//删除操作
		$condition_arr = array();
		$condition_arr['point_appointmentid_del'] = $appointment_id;
		$condition_arr['point_appointmentstate_in'] = '2';//只有取消的订单才能删除
		$result = $pointappointment_model->dropPointappointment($condition_arr);
		if($result) {
			//删除兑换礼品信息
			$pointappointment_model->dropPointappointmentProd(array('prod_appointmentid_del'=>$appointment_id));
			//删除兑换地址信息
			$pointappointment_model->dropPointappointmentAddress(array('address_appointmentid_del'=>$appointment_id));
			showMessage(Language::get('admin_pointappointment_del_success'),'index.php?act=pointappointment&op=pointappointment_list');
		} else {
			showMessage(Language::get('admin_pointappointment_del_fail'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
	}
	/**
	 * 取消兑换
	 */
	public function appointment_cancelOp(){
		$appointment_id = intval($_GET['id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('admin_pointappointment_parameter_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		$pointappointment_model = Model('pointappointment');
		$condition_arr = array();
		$condition_arr['point_appointmentid'] = "$appointment_id";
		$condition_arr['point_appointment_enablecancel'] = '1';//可取消
		//查询兑换信息
		$appointment_info = $pointappointment_model->getPointappointmentInfo($condition_arr,'simple','point_appointmentsn,point_buyerid,point_buyername,point_allpoint');
		if (!is_array($appointment_info) || count($appointment_info)<=0){
			showMessage(Language::get('admin_pointappointmentd_record_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		//更新运费
		$state = $pointappointment_model->updatePointappointment($condition_arr,array('point_appointmentstate'=>'2'));
		if ($state){
			//退还会员积分
			$points_model =Model('points');
			$insert_arr['pl_memberid'] 		= $appointment_info['point_buyerid'];
			$insert_arr['pl_membername'] 	= $appointment_info['point_buyername'];
			$insert_arr['pl_points'] 		= $appointment_info['point_allpoint'];
			$insert_arr['point_appointmentsn'] 	= $appointment_info['point_appointmentsn'];
			$insert_arr['pl_desc'] 			= Language::get('admin_pointappointment_cancel_tip1').$appointment_info['point_appointmentsn'].Language::get('admin_pointappointment_cancel_tip2');
			$points_model->savePointsLog('pointappointment',$insert_arr,true);
			//更改兑换礼品库存
			$prod_list = $pointappointment_model->getPointappointmentProdList(array('prod_appointmentid'=>$appointment_id),'','point_doctorsid,point_doctorsnum');
			if (is_array($prod_list) && count($prod_list)>0){
				$pointprod_model = Model('pointprod');
				foreach ($prod_list as $v){
					$update_arr = array();
					$update_arr['pdoctors_storage'] = array('sign'=>'increase','value'=>$v['point_doctorsnum']);
					$update_arr['pdoctors_salenum'] = array('sign'=>'decrease','value'=>$v['point_doctorsnum']);
					$pointprod_model->updatePointProd($update_arr,array('pdoctors_id'=>$v['point_doctorsid']));
					unset($update_arr);
				}
			}
			showMessage(Language::get('admin_pointappointment_cancel_success'),'index.php?act=pointappointment&op=pointappointment_list');
		}else {
			showMessage(Language::get('admin_pointappointment_cancel_fail'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
	}
	/**
	 * 发货
	 */
	public function appointment_shipOp(){
		$appointment_id = intval($_GET['id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('admin_pointappointment_parameter_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		$pointappointment_model = Model('pointappointment');
		$condition_arr = array();
		$condition_arr['point_appointmentid'] = "$appointment_id";
		$condition_arr['point_appointmentstate_in'] = '20,30';//确认付款状态和已经发货状态
		if (chksubmit()){
			$obj_validate = new Validate();
			$validate_arr[] = array("input"=>$_POST["shippingcode"],"require"=>"true","message"=>Language::get('admin_pointappointment_ship_code_nullerror'));
			$obj_validate->validateparam = $validate_arr;
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage(Language::get('error').$error,'index.php?act=pointappointment&op=pointappointment_list','','error');
			}
			//更新发货信息
			$update_arr = array();
			$shippingtime = strtotime(trim($_POST['shippingtime']));
			if ($shippingtime > 0){
				$update_arr['point_shippingtime'] = $shippingtime;
			}else {
				$update_arr['point_shippingtime'] = time();
			}
			$update_arr['point_shippingcode'] = trim($_POST['shippingcode']);
			$update_arr['point_shippingdesc'] = trim($_POST['shippingdesc']);
			$update_arr['point_appointmentstate']   = '30'; //已经发货
			$state = $pointappointment_model->updatePointappointment($condition_arr,$update_arr);
			if ($state){
				showMessage(Language::get('admin_pointappointment_ship_success'),'index.php?act=pointappointment&op=pointappointment_list');
			}else {
				showMessage(Language::get('admin_pointappointment_ship_fail'),'index.php?act=pointappointment&op=pointappointment_list','','error');
			}
		}else {
			//查询订单信息
			$appointment_info = $pointappointment_model->getPointappointmentInfo($condition_arr,'simple','point_appointmentsn,point_buyername,point_shippingtime,point_shippingcode,point_shippingdesc,point_appointmentstate');
			if (is_array($appointment_info) && count($appointment_info)>0){
				Tpl::output('appointment_info',$appointment_info);
				Tpl::showpage('pointappointment.ship');
			}else {
				Tpl::output('errormsg',Language::get('admin_pointappointmentd_record_error'));
				Tpl::showpage('pointappointment.ship');
			}
		}
	}
	/**
	 * 兑换信息详细
	 */
	public function appointment_infoOp(){
		$appointment_id = intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('admin_pointappointment_parameter_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		//查询订单信息
		$pointappointment_model = Model('pointappointment');
		$condition_arr['point_appointmentid'] = $appointment_id;
		$appointment_info = $pointappointment_model->getPointappointmentInfo($condition_arr,'all','*');
		if (!is_array($appointment_info) || count($appointment_info) <= 0){
			showMessage(Language::get('admin_pointappointmentd_record_error'),'index.php?act=pointappointment&op=pointappointment_list','','error');
		}
		$appointment_info['point_appointmentstatetext'] = $this->pointappointment_state($appointment_info['point_appointmentstate']);
		//兑换商品信息
		$prod_list = $pointappointment_model->getPointappointmentProdList(array('prod_appointmentid'=>"{$appointment_id}"),$page);
		Tpl::output('prod_list',$prod_list);
		Tpl::output('appointment_info',$appointment_info);
		Tpl::showpage('pointappointment.info');
	}
	/**
	 * 获得订单状态描述
	 *
	 */
	public function pointappointment_state($appointment_step){
		$log_array	= array();
		switch ($appointment_step) {
			case 2:
				$log_array['appointment_state']	= Language::get('admin_pointappointment_state_canceled');
				$log_array['change_state'] = '';
				break;
			case 20:
				$log_array['appointment_state']	= Language::get('admin_pointappointment_state_waitship');
				$log_array['change_state']	= '';
				break;
			case 30:
				$log_array['appointment_state']	= Language::get('admin_pointappointment_state_shipped');
				$log_array['change_state']	= Language::get('admin_pointappointment_state_waitreceiving');
				break;
			case 40:
				$log_array['appointment_state']	= Language::get('admin_pointappointment_state_finished');
				$log_array['change_state']	= '';
				break;
			default:
				$log_array['appointment_state']	= Language::get('admin_pointappointment_state_unknown');
				$log_array['change_state']	= Language::get('admin_pointappointment_state_unknown');
		}
		return $log_array;
	}
}