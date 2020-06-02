<?php
/**
 * 买家退款
 *
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_refundControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_member_index');
		$model_refund = Model('refund_return');
		$model_refund->getRefundStateArray();
	}
	/**
	 * 添加订单商品部分退款
	 *
	 */
	public function add_refundOp(){
		$model_appointment = Model('appointment');
		$model_refund = Model('refund_return');
		$appointment_id = intval($_GET['appointment_id']);
		$doctors_id = intval($_GET['doctors_id']);
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['appointment_id'] = $appointment_id;
		$appointment_list = $model_appointment->getappointmentList($condition);
		$appointment = $appointment_list[0];
		Tpl::output('appointment',$appointment);
		$appointment_id = $appointment['appointment_id'];

		$condition = array();
		$condition['appointment_id'] = $appointment_id;
		$condition['rec_id'] = $doctors_id;//订单商品表编号
		$doctors_list = $model_appointment->getappointmentdoctorsList($condition);
		$doctors = $doctors_list[0];
		$doctors_pay_price = $doctors['doctors_pay_price'];//商品实际成交价
		$appointment_amount = $appointment['appointment_amount'];//订单金额
		$appointment_refund_amount = $appointment['refund_amount'];//订单退款金额
		if ($appointment_amount < ($doctors_pay_price + $appointment_refund_amount)) {
		    $doctors_pay_price = $appointment_amount - $appointment_refund_amount;
		    $doctors['doctors_pay_price'] = $doctors_pay_price;
		}
		Tpl::output('doctors',$doctors);

		$doctors_id = $doctors['rec_id'];
		$condition = array();
		$condition['buyer_id'] = $appointment['buyer_id'];
		$condition['appointment_id'] = $appointment['appointment_id'];
		$condition['appointment_doctors_id'] = $doctors_id;
		$condition['clinicer_state'] = array('lt','3');
		$refund_list = $model_refund->getRefundReturnList($condition);
		$refund = array();
		if (!empty($refund_list) && is_array($refund_list)) {
			$refund = $refund_list[0];
		}
		if (chksubmit() && $doctors_id > 0){
		    $refund_state = $model_refund->getRefundState($appointment);//根据订单状态判断是否可以退款退货
			if ($refund['refund_id'] > 0 || $refund_state != 1) {//检查订单状态,防止页面刷新不及时造成数据错误
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$refund_array = array();
			$refund_amount = floatval($_POST['refund_amount']);//退款金额
			if (($refund_amount < 0) || ($refund_amount > $doctors_pay_price)) {
			    $refund_amount = $doctors_pay_price;
			}
			$doctors_num = intval($_POST['doctors_num']);//退货数量
			if (($doctors_num < 0) || ($doctors_num > $doctors['doctors_num'])) {
			    $doctors_num = 1;
			}
			$model_trade = Model('trade');
			$appointment_shipped = $model_trade->getappointmentState('appointment_shipped');//订单状态30:已发货
			if ($appointment['appointment_state'] == $appointment_shipped) {
			    $refund_array['appointment_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
			}
			$refund_array['refund_type'] = $_POST['refund_type'];//类型:1为退款,2为退货
			$refund_array['return_type'] = '2';//退货类型:1为不用退货,2为需要退货
			if ($refund_array['refund_type'] != '2') {
			    $refund_array['refund_type'] = '1';
			    $refund_array['return_type'] = '1';
			}
			$refund_array['clinicer_state'] = '1';//状态:1为待审核,2为同意,3为不同意
			$refund_array['refund_amount'] = ncPriceFormat($refund_amount);
			$refund_array['doctors_num'] = $doctors_num;
			$refund_array['buyer_message'] = $_POST['buyer_message'];
			$refund_array['add_time'] = time();
			$state = $model_refund->addRefundReturn($refund_array,$appointment,$doctors);

			if ($state) {
    			if ($appointment['appointment_state'] == $appointment_shipped) {
    			    $model_refund->editappointmentLock($appointment_id);
    			}
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		Tpl::showpage('member_refund_add','null_layout');
	}
	/**
	 * 添加全部退款即取消订单
	 *
	 */
	public function add_refund_allOp(){
		$model_appointment = Model('appointment');
		$model_trade = Model('trade');
		$model_refund = Model('refund_return');
		$appointment_id = intval($_GET['appointment_id']);
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['appointment_id'] = $appointment_id;
		$appointment_list = $model_appointment->getappointmentList($condition);
		$appointment = $appointment_list[0];
		Tpl::output('appointment',$appointment);
		$appointment_amount = $appointment['appointment_amount'];//订单金额
		$condition = array();
		$condition['buyer_id'] = $appointment['buyer_id'];
		$condition['appointment_id'] = $appointment['appointment_id'];
		$condition['doctors_id'] = '0';
		$condition['clinicer_state'] = array('lt','3');
		$refund_list = $model_refund->getRefundReturnList($condition);
		$refund = array();
		if (!empty($refund_list) && is_array($refund_list)) {
			$refund = $refund_list[0];
		}
		if (chksubmit()) {
		    $appointment_paid = $model_trade->getappointmentState('appointment_paid');//订单状态20:已付款
		    $payment_code = $appointment['payment_code'];//支付方式
			if ($refund['refund_id'] > 0 || $appointment['appointment_state'] != $appointment_paid || $payment_code == 'offline') {//检查订单状态,防止页面刷新不及时造成数据错误
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$refund_array = array();
			$refund_array['refund_type'] = '1';//类型:1为退款,2为退货
			$refund_array['clinicer_state'] = '1';//状态:1为待审核,2为同意,3为不同意
			$refund_array['appointment_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
			$refund_array['doctors_id'] = '0';
			$refund_array['appointment_doctors_id'] = '0';
			$refund_array['doctors_name'] = '订单商品全部退款';
			$refund_array['refund_amount'] = ncPriceFormat($appointment_amount);
			$refund_array['buyer_message'] = $_POST['buyer_message'];
			$refund_array['add_time'] = time();
			$state = $model_refund->addRefundReturn($refund_array,$appointment);

			if ($state) {
			    $model_refund->editappointmentLock($appointment_id);
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
	    Tpl::showpage('member_refund_all','null_layout');
	}
	/**
	 * 退款记录列表页
	 *
	 */
	public function indexOp(){
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];

		$keyword_type = array('appointment_sn','refund_sn','doctors_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)){
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != ''){
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false){
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}
		$refund_list = $model_refund->getRefundList($condition,10);
		//查询会员信息
		$this->get_member_info();
		Tpl::output('refund_list',$refund_list);
		Tpl::output('show_page',$model_refund->showpage());
		self::profile_menu('member_appointment','buyer_refund');
		Tpl::output('menu_sign','myappointment');
		Tpl::output('menu_sign_url','index.php?act=member_appointment');
		Tpl::output('menu_sign1','buyer_refund');
		Tpl::showpage('member_refund');
	}
	/**
	 * 退款记录查看
	 *
	 */
	public function viewOp(){
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_id'] = intval($_GET['refund_id']);
		$refund_list = $model_refund->getRefundList($condition);
		$refund = $refund_list[0];
		Tpl::output('refund',$refund);
		Tpl::showpage('member_refund_view','null_layout');
	}
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array = array();
		switch ($menu_type) {
			case 'member_appointment':
				$menu_array = array(
				array('menu_key'=>'member_appointment','menu_name'=>Language::get('nc_member_path_appointment_list'),	'menu_url'=>'index.php?act=member_appointment'),
				array('menu_key'=>'buyer_refund','menu_name'=>Language::get('nc_member_path_buyer_refund'),	'menu_url'=>'index.php?act=member_refund'),
				array('menu_key'=>'buyer_return','menu_name'=>Language::get('nc_member_path_buyer_return'),	'menu_url'=>'index.php?act=member_return'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
