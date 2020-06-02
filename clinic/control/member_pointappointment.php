<?php
/**
 * 会员中心——积分兑换信息
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_pointappointmentControl extends BaseMemberControl{
	public function __construct() {
		parent::__construct();
		/**
		 * 读取语言包
		 */
		Language::read('member_pointappointment');
		/**
		 * 判断系统是否开启积分和积分兑换功能
		 */
		if ($GLOBALS['setting_config']['points_isuse'] != 1 || $GLOBALS['setting_config']['pointprod_isuse'] != 1){
			showMessage(Language::get('member_pointappointment_unavailable'),'index.php?act=member_snsindex','html','error');
		}
	}
	public function indexOp() {
		$this->appointmentlistOp();
	}
	/**
	 * 兑换信息列表
	 */
	public function appointmentlistOp() {
		//条件
		$condition_arr = array();
		$condition_arr['point_buyerid'] = $_SESSION['member_id'];
		//分页
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		//兑换信息列表
		$pointappointment_model = Model('pointappointment');
		$appointment_list = $pointappointment_model->getPointappointmentList($condition_arr,$page,'simple');
		$appointment_idarr = array();
		$appointment_listnew = array();
		if (is_array($appointment_list) && count($appointment_list)>0){
			foreach ($appointment_list as $k => $v){
				$v['point_appointmentstatetext'] = $this->pointappointment_state($v['point_appointmentstate']);
				$appointment_idarr[] = $v['point_appointmentid'];
				$appointment_listnew[$v['point_appointmentid']] = $v;
			}
		}
		//查询兑换商品
		if (is_array($appointment_idarr) && count($appointment_idarr)>0){
			$appointment_idstr = implode(',',$appointment_idarr);
			$prod_list = $pointappointment_model->getPointappointmentProdList(array('prod_appointmentid_in'=>$appointment_idstr),'');
			if (is_array($prod_list) && count($prod_list)>0){
				foreach ($prod_list as $v){
					if (isset($appointment_listnew[$v['point_appointmentid']])){
						$v['point_doctorsimage'] = ATTACH_POINTPROD.DS.str_ireplace('.', '_small.', $v['point_doctorsimage']);
						$appointment_listnew[$v['point_appointmentid']]['prodlist'][] = $v;
					}
				}
			}
		}
		//信息输出
		Tpl::output('payment_list',$payment_list);
		Tpl::output('appointment_list',$appointment_listnew);
		Tpl::output('page',$page->show());
		//查询会员信息
		$this->get_member_info();
		self::profile_menu('pointappointment','appointmentlist');
		Tpl::output('menu_sign','pointappointment');
		Tpl::output('menu_sign_url','index.php?act=member_pointappointment&op=appointmentlist');
		Tpl::output('menu_sign1','pointappointment_list');
		Tpl::showpage('member_pointappointment');
	}
	/**
	 * 	取消兑换
	 */
	public function cancel_appointmentOp(){
		$appointment_id = intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('member_pointappointment_parameter_error'),'index.php?act=member_pointappointment','html','error');
		}
		$pointappointment_model = Model('pointappointment');
		$condition_arr = array();		
		$condition_arr['point_appointmentid'] = "$appointment_id";
		$condition_arr['point_buyerid'] = $_SESSION['member_id'];
		$condition_arr['point_appointment_enablecancel'] = '1';//可取消
		//查询兑换信息
		$appointment_info = $pointappointment_model->getPointappointmentInfo($condition_arr,'simple','point_appointmentsn,point_buyerid,point_buyername,point_allpoint');
		if (!is_array($appointment_info) || count($appointment_info)<=0){
			showMessage(Language::get('member_pointappointment_record_error'),'index.php?act=member_pointappointment','html','error');
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
			$insert_arr['pl_desc'] 			= Language::get('member_pointappointment_cancel_tip1').$appointment_info['point_appointmentsn'].Language::get('member_pointappointment_cancel_tip2');
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
			showMessage(Language::get('member_pointappointment_cancel_success'),'index.php?act=member_pointappointment');
		}else {
			showMessage(Language::get('member_pointappointment_cancel_fail'),'index.php?act=member_pointappointment','html','error');
		}
	}
	/**
	 * 确认收货
	 */
	public function receiving_appointmentOp(){
		$appointment_id = intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('member_pointappointment_parameter_error'),'index.php?act=member_pointappointment','html','error');
		}
		$pointappointment_model = Model('pointappointment');
		$condition_arr = array();		
		$condition_arr['point_appointmentid'] = "$appointment_id";
		$condition_arr['point_buyerid'] = $_SESSION['member_id'];
		$condition_arr['point_appointmentstate'] = '30';//待收货
		//更新运费
		$state = $pointappointment_model->updatePointappointment($condition_arr,array('point_appointmentstate'=>'40','point_finnshedtime'=>time()));
		if ($state){
			showMessage(Language::get('member_pointappointment_confirmreceiving_success'),'index.php?act=member_pointappointment');
		}else {
			showMessage(Language::get('member_pointappointment_confirmreceiving_fail'),'index.php?act=member_pointappointment','html','error');
		}
	}
	/**
	 * 兑换信息详细
	 */
	public function appointment_infoOp(){
		$appointment_id = intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('member_pointappointment_parameter_error'),'index.php?act=member_pointappointment','html','error');
		}
		//查询订单信息
		$pointappointment_model = Model('pointappointment');
		$condition_arr['point_appointmentid'] = $appointment_id;
		$condition_arr['point_buyerid'] = $_SESSION['member_id'];
		$appointment_info = $pointappointment_model->getPointappointmentInfo($condition_arr,'all','*');
		if (!is_array($appointment_info) || count($appointment_info) <= 0){
			showMessage(Language::get('member_pointappointment_record_error'),'index.php?act=member_pointappointment','html','error');
		}
		$appointment_info['point_appointmentstatetext'] = $this->pointappointment_state($appointment_info['point_appointmentstate']);
		//兑换商品信息
		$prod_list = $pointappointment_model->getPointappointmentProdList(array('prod_appointmentid'=>"{$appointment_id}"),$page);
		Tpl::output('prod_list',$prod_list);
		Tpl::output('appointment_info',$appointment_info);
		//查询会员信息
		$this->get_member_info();
		//信息输出
//		self::profile_menu('pointappointmentinfo','appointmentinfo');
//		Tpl::output('menu_sign','pointappointment');
//		Tpl::output('menu_sign_url','index.php?act=member_pointappointment&op=appointmentlist');
//		Tpl::output('menu_sign1','pointappointment_info');
		Tpl::output('left_show','appointment_view');		
		Tpl::showpage('member_pointappointment_info');
	}
	/**
	 * 获得订单状态描述
	 *
	 */
	public function pointappointment_state($appointment_step){
		$log_array	= array();
		switch ($appointment_step) {
			case 2:
				$log_array['appointment_state']	= Language::get('member_pointappointment_state_canceled');
				$log_array['change_state'] = '';
				break;
			case 20:
				$log_array['appointment_state']	= Language::get('member_pointappointment_state_waitship');
				$log_array['change_state']	= '';
				break;
			case 30:
				$log_array['appointment_state']	= Language::get('member_pointappointment_state_shipped');
				$log_array['change_state']	= Language::get('member_pointappointment_state_waitreceiving');
				break;
			case 40:
				$log_array['appointment_state']	= Language::get('member_pointappointment_state_finished');
				$log_array['change_state']	= '';
				break;
			default:
				$log_array['appointment_state']	= Language::get('member_pointappointment_state_unknown');
				$log_array['change_state']	= Language::get('member_pointappointment_state_unknown');
		}
		return $log_array;
	}
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'pointappointment':
				$menu_array	= array(
					1=>array('menu_key'=>'appointmentlist','menu_name'=>Language::get('member_pointappointment_list_title'),	'menu_url'=>'index.php?act=member_pointappointment&op=appointmentlist')
				);
				break;
			case 'pointappointmentinfo':
				$menu_array	= array(
					1=>array('menu_key'=>'appointmentlist','menu_name'=>Language::get('nc_member_path_pointappointment_list'),	'menu_url'=>'index.php?act=member_pointappointment&op=appointmentlist'),
					2=>array('menu_key'=>'appointmentinfo','menu_name'=>Language::get('nc_member_path_pointappointment_info'),	'')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}