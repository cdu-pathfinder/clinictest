<?php
/**
 * 卖家订单管理
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
class clic_appointmentControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_clic_index');
    }

	/**
	 * 订单列表
	 *
	 */
	public function indexOp() {
        $model_appointment = Model('appointment');
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        if ($_GET['appointment_sn'] != '') {
            $condition['appointment_sn'] = $_GET['appointment_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($_GET['state_type'],$allow_state_array)) {
            $condition['appointment_state'] = str_replace($allow_state_array,
                    array(appointment_STATE_NEW,appointment_STATE_PAY,appointment_STATE_SEND,appointment_STATE_SUCCESS,appointment_STATE_CANCEL), $_GET['state_type']);
        } else {
            $_GET['state_type'] = 'clic_appointment';
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $appointment_list = $model_appointment->getappointmentList($condition, 20, '*', 'appointment_id desc','', array('appointment_doctors','appointment_common','member'));
        
        //页面中显示那些操作
        foreach ($appointment_list as $key => $appointment_info) {

        	//显示取消订单
        	$appointment_list[$key]['if_cancel'] = $model_appointment->getappointmentOperateState('clic_cancel',$appointment_info);

        	//显示调整费用
        	$appointment_list[$key]['if_modify_price'] = $model_appointment->getappointmentOperateState('modify_price',$appointment_info);
        	
        	//显示发货
        	$appointment_list[$key]['if_send'] = $model_appointment->getappointmentOperateState('send',$appointment_info);
        	
        	//显示锁定中
        	$appointment_list[$key]['if_lock'] = $model_appointment->getappointmentOperateState('lock',$appointment_info);

        	//显示物流跟踪
        	$appointment_list[$key]['if_deliver'] = $model_appointment->getappointmentOperateState('deliver',$appointment_info);

        }

        Tpl::output('appointment_list',$appointment_list);
        Tpl::output('show_page',$model_appointment->showpage());
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('clic_appointment.index');
	}

	/**
	 * 卖家订单详情
	 *
	 */
	public function show_appointmentOp() {
	    $appointment_id = intval($_GET['appointment_id']);
	    if ($appointment_id <= 0) {
	        showMessage(Language::get('wrong_argument'),'','html','error');
	    }
	    $model_appointment = Model('appointment');
	    $condition = array();
        $condition['appointment_id'] = $appointment_id;
        $condition['clic_id'] = $_SESSION['clic_id'];	    
	    $appointment_info = $model_appointment->getappointmentInfo($condition,array('appointment_common','appointment_doctors','member'));
	    if (empty($appointment_info)) {
	        showMessage(Language::get('clic_appointment_none_exist'),'','html','error');
	    }
	    Tpl::output('appointment_info',$appointment_info);

		//订单处理历史
		$log_list	= $model_appointment->getappointmentLogList(array('appointment_id'=>$appointment_id));
		Tpl::output('appointment_log',$log_list);

		//退款退货信息
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['appointment_id'] = $appointment_info['appointment_id'];
		$condition['clinicer_state'] = 2;
		$condition['admin_time'] = array('gt',0);
		$return_list = $model_refund->getReturnList($condition);
		Tpl::output('return_list',$return_list);

		//退款信息
		$refund_list = $model_refund->getRefundList($condition);
		Tpl::output('refund_list',$refund_list);

		self::profile_menu('show','show_appointment');
		Tpl::output('menu_sign','show_appointment');
		Tpl::output('left_show','appointment_view');
		Tpl::showpage('clic_appointment.show');	    
	}

	/**
	 * 卖家订单状态操作
	 *
	 */
	public function change_stateOp() {
		$state_type	= $_GET['state_type'];
		$appointment_id	= intval($_GET['appointment_id']);

		$model_appointment = Model('appointment');
		$condition = array();
		$condition['appointment_id'] = $appointment_id;
		$condition['clic_id'] = $_SESSION['clic_id'];
		$appointment_info	= $model_appointment->getappointmentInfo($condition);
		Tpl::output('appointment_info',$appointment_info);
		try {
		
		    $model_appointment->beginTransaction();

    		if ($state_type == 'appointment_cancel') {
    		    $this->_change_state_appointment_cancel($appointment_info);
    		    $message = Language::get('clic_appointment_cancel_success');
    		} elseif ($state_type == 'modify_price') {
    		    $this->_change_state_modify_price($appointment_info);
    		    $message = Language::get('clic_appointment_edit_ship_success');
    		}

    		$model_appointment->commit();
    		showDialog($message,'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');

		} catch (Exception $e) {
		    $model_appointment->rollback();
		    showDialog($e->getMessage(),'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}

	/**
	 * 取消订单
	 * @param unknown $appointment_info
	 */
	private function _change_state_appointment_cancel($appointment_info) {
	    $appointment_id = $appointment_info['appointment_id'];
	    $model_appointment = Model('appointment');
	    if(chksubmit()) {
	        $if_allow = $model_appointment->getappointmentOperateState('clic_cancel',$appointment_info);
	        if (!$if_allow) {
	            throw new Exception(L('invalid_request'));
	        }
	        $doctors_list = $model_appointment->getappointmentdoctorsList(array('appointment_id'=>$appointment_id));
	        $model_doctors = Model('doctors');
	        if(is_array($doctors_list) and !empty($doctors_list)) {
	            foreach ($doctors_list as $doctors) {
	                $data = array();
	                $data['doctors_storage'] = array('exp','doctors_storage+'.$doctors['doctors_num']);
	                $data['doctors_salenum'] = array('exp','doctors_salenum-'.$doctors['doctors_num']);
	                $update = $model_doctors->editdoctors($data,array('doctors_id'=>$doctors['doctors_id']));
	                if (!$update) {
	                    throw new Exception(L('nc_common_save_fail'));
	                }
	            }
	        }

	        //解冻预存款
            $pd_amount = floatval($appointment_info['pd_amount']);
            if ($pd_amount > 0) {
                $model_pd = Model('predeposit');
                $data_pd = array();
                $data_pd['member_id'] = $appointment_info['buyer_id'];
                $data_pd['member_name'] = $appointment_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['appointment_sn'] = $appointment_info['appointment_sn'];
                $model_pd->changePd('appointment_cancel',$data_pd);
            }

	        //更新订单信息
	        $data = array('appointment_state'=>appointment_STATE_CANCEL);
	        $update = $model_appointment->editappointment($data,array('appointment_id'=>$appointment_id));
	        if (!$update) {
	            throw new Exception(L('nc_common_save_fail'));
	        }

	        //记录订单日志
	        $data = array();
	        $data['appointment_id'] = $appointment_id;
	        $data['log_role'] = 'clinicer';
			$data['log_user'] = $_SESSION['member_name'];
	        $data['log_msg'] = L('appointment_log_cancel');
	        $extend_msg = $_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info'];
	        if ($extend_msg) {
	            $data['log_msg'] .= ' ( '.$extend_msg.' )';
	        }
	        $data['log_appointmentstate'] = appointment_STATE_CANCEL;
	        $model_appointment->addappointmentLog($data);
	    } else {
	        Tpl::output('appointment_id',$appointment_id);
	        Tpl::showpage('clic_appointment.cancel','null_layout');
	        exit();
	    }
	}

	/**
	 * 修改运费
	 * @param unknown $appointment_info
	 */
	private function _change_state_modify_price($appointment_info) {
	    $appointment_id = $appointment_info['appointment_id'];
	    $model_appointment = Model('appointment');
	    if(chksubmit()) {
	        $if_allow = $model_appointment->getappointmentOperateState('modify_price',$appointment_info);
	        if (!$if_allow) {
	            throw new Exception(L('invalid_request'));
	        }
	        $data = array();
	        $data['shipping_fee'] = abs(floatval($_POST['shipping_fee']));
	        $data['appointment_amount'] = array('exp','doctors_amount+'.$data['shipping_fee']);
	        $update = $model_appointment->editappointment($data,array('appointment_id'=>$appointment_id));
	        if (!$update) {
	            throw new Exception(L('nc_common_save_fail'));
	        }
	        //记录订单日志
	        $data = array();
	        $data['appointment_id'] = $appointment_id;
	        $data['log_role'] = 'clinicer';
			$data['log_user'] = $_SESSION['member_name'];
	        $data['log_msg'] = L('appointment_log_edit_ship');
	        $model_appointment->addappointmentLog($data);
	    } else {
	        Tpl::output('appointment_id',$appointment_id);
	        Tpl::showpage('clic_appointment.edit_price','null_layout');
	        exit();
	    }
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
     */
    private function profile_menu($menu_type='',$menu_key='') {
        Language::read('member_layout');
        switch ($menu_type) {
        	case 'list':
            $menu_array = array(
            array('menu_key'=>'clic_appointment',		'menu_name'=>Language::get('nc_member_path_all_appointment'),	'menu_url'=>'index.php?act=clic_appointment'),
            array('menu_key'=>'state_new',			'menu_name'=>Language::get('nc_member_path_wait_pay'),	'menu_url'=>'index.php?act=clic_appointment&op=index&state_type=state_new'),
            array('menu_key'=>'state_pay',	        'menu_name'=>Language::get('nc_member_path_wait_send'),	'menu_url'=>'index.php?act=clic_appointment&op=clic_appointment&state_type=state_pay'),
            array('menu_key'=>'state_send',		    'menu_name'=>Language::get('nc_member_path_sent'),	    'menu_url'=>'index.php?act=clic_appointment&op=index&state_type=state_send'),
            array('menu_key'=>'state_success',		'menu_name'=>Language::get('nc_member_path_finished'),	'menu_url'=>'index.php?act=clic_appointment&op=index&state_type=state_success'),
            array('menu_key'=>'state_cancel',		'menu_name'=>Language::get('nc_member_path_canceled'),	'menu_url'=>'index.php?act=clic_appointment&op=index&state_type=state_cancel'),
            );
            break;
            case 'show':
            $menu_array = array(
            array('menu_key'=>'all_appointment',			'menu_name'=>Language::get('nc_member_path_all_appointment'),	'menu_url'=>'index.php?act=clic_appointment&op=index'),
            array('menu_key'=>'show_appointment',				'menu_name'=>Language::get('nc_member_path_show_appointment'),	'menu_url'=>'')
            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}   
