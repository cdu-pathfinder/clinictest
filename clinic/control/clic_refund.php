<?php
/**
 * 卖家退款
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

class clic_refundControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		$model_refund = Model('refund_return');
		$model_refund->getRefundStateArray();
	}
	/**
	 * 退款记录列表页
	 *
	 */
	public function indexOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['clic_id'] = $_SESSION['clic_id'];

		$keyword_type = array('appointment_sn','refund_sn','buyer_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false) {
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}
		$clinicer_state = intval($_GET['state']);
		if ($clinicer_state > 0) {
		    $condition['clinicer_state'] = $clinicer_state;
		}
		$appointment_lock = intval($_GET['lock']);
		if ($appointment_lock != 1) {
		    $appointment_lock = 2;
		}
		$_GET['lock'] = $appointment_lock;
		$condition['appointment_lock'] = $appointment_lock;

		$refund_list = $model_refund->getRefundList($condition,10);
		Tpl::output('refund_list',$refund_list);
		Tpl::output('show_page',$model_refund->showpage());
		self::profile_menu('refund',$appointment_lock);
		Tpl::showpage('clic_refund');
	}
	/**
	 * 退款审核页
	 *
	 */
	public function editOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['clic_id'] = $_SESSION['clic_id'];
		$condition['refund_id'] = intval($_GET['refund_id']);
		$refund_list = $model_refund->getRefundList($condition);
		$refund = $refund_list[0];
		if (chksubmit()) {
			if ($refund['clinicer_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$appointment_id = $refund['appointment_id'];
			$refund_array = array();
			$refund_array['clinicer_time'] = time();
			$refund_array['clinicer_state'] = $_POST['clinicer_state'];//卖家处理状态:1为待审核,2为同意,3为不同意
			$refund_array['clinicer_message'] = $_POST['clinicer_message'];
			if ($refund_array['clinicer_state'] == '3') {
			    $refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成
			} else {
			    $refund_array['clinicer_state'] = '2';
			    $refund_array['refund_state'] = '2';
			}
			$state = $model_refund->editRefundReturn($condition, $refund_array);
			if ($state) {
    			if ($refund_array['clinicer_state'] == '3' && $refund['appointment_lock'] == '2') {
    			    $model_refund->editappointmentUnlock($appointment_id);//订单解锁
    			}
    			$this->recordclinicerLog('退款处理，退款编号：'.$refund['refund_sn']);
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		Tpl::output('refund',$refund);
		Tpl::showpage('clic_refund_edit','null_layout');
	}
	/**
	 * 退款记录查看页
	 *
	 */
	public function viewOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['clic_id'] = $_SESSION['clic_id'];
		$condition['refund_id'] = intval($_GET['refund_id']);
		$refund_list = $model_refund->getRefundList($condition);
		$refund = $refund_list[0];
		Tpl::output('refund',$refund);
		Tpl::showpage('clic_refund_view','null_layout');
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
			case 'refund':
				$menu_array = array(
					array('menu_key'=>'2','menu_name'=>'售前退款',	'menu_url'=>'index.php?act=clic_refund&lock=2'),
					array('menu_key'=>'1','menu_name'=>'售后退款','menu_url'=>'index.php?act=clic_refund&lock=1')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
