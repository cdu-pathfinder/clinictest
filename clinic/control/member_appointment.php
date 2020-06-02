<?php
/**
 * 买家 我的订单
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_appointmentControl extends BaseMemberControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_member_index');
    }

    /**
     * 买家我的订单，以总订单pay_sn来分组显示
     *
     */
    public function indexOp() {
        $model_appointment = Model('appointment');

        //搜索
        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        if ($_GET['appointment_sn'] != '') {
            $condition['appointment_sn'] = $_GET['appointment_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['state_type'] != '') {
            $condition['appointment_state'] = str_replace(
                    array('state_new','state_pay','state_send','state_success','state_noeval','state_cancel'),
                    array(appointment_STATE_NEW,appointment_STATE_PAY,appointment_STATE_SEND,appointment_STATE_SUCCESS,appointment_STATE_SUCCESS,appointment_STATE_CANCEL), $_GET['state_type']);
        }
        if ($_GET['state_type'] == 'state_noeval') {
            $condition['evaluation_state'] = 0;
            $condition['appointment_state'] = appointment_STATE_SUCCESS;
            $condition['finnshed_time'] = array('gt',TIMESTAMP - appointment_EVALUATE_TIME);
        }
        $appointment_list = $model_appointment->getappointmentList($condition, 20, '*', 'appointment_id desc','', array('appointment_common','appointment_doctors','clic'));

        $model_refund_return = Model('refund_return');
        $appointment_list = $model_refund_return->getdoctorsRefundList($appointment_list);

        //订单列表以支付单pay_sn分组显示
        $appointment_group_list = array();
        $appointment_pay_sn_array = array();
        foreach ($appointment_list as $appointment_id => $appointment) {

            //显示取消订单
            $appointment['if_cancel'] = $model_appointment->getappointmentOperateState('buyer_cancel',$appointment);

            //显示退款取消订单
            $appointment['if_refund_cancel'] = $model_appointment->getappointmentOperateState('refund_cancel',$appointment);

            //显示投诉
            $appointment['if_complain'] = $model_appointment->getappointmentOperateState('complain',$appointment);

            //显示收货
            $appointment['if_receive'] = $model_appointment->getappointmentOperateState('receive',$appointment);

            //显示锁定中
            $appointment['if_lock'] = $model_appointment->getappointmentOperateState('lock',$appointment);

            //显示物流跟踪
            $appointment['if_deliver'] = $model_appointment->getappointmentOperateState('deliver',$appointment);

            //显示评价
            $appointment['if_evaluation'] = $model_appointment->getappointmentOperateState('evaluation',$appointment);

            //显示分享
            $appointment['if_share'] = $model_appointment->getappointmentOperateState('share',$appointment);

            $appointment_group_list[$appointment['pay_sn']]['appointment_list'][] = $appointment;

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($appointment['appointment_state'] == appointment_STATE_NEW) {
                $appointment_group_list[$appointment['pay_sn']]['pay_amount'] += $appointment['appointment_amount'];
            }
            $appointment_group_list[$appointment['pay_sn']]['add_time'] = $appointment['add_time'];

            //记录一下pay_sn，后面需要查询支付单表
            $appointment_pay_sn_array[] = $appointment['pay_sn'];
        }

        //取得这些订单下的支付单列表
        $condition = array('pay_sn'=>array('in',array_unique($appointment_pay_sn_array)));
        $appointment_pay_list = $model_appointment->getappointmentPayList($condition,'','*','','pay_sn');
        foreach ($appointment_group_list as $pay_sn => $pay_info) {
        	$appointment_group_list[$pay_sn]['pay_info'] = $appointment_pay_list[$pay_sn];
        }
		$this->get_member_info();
        Tpl::output('appointment_group_list',$appointment_group_list);
        Tpl::output('appointment_pay_list',$appointment_pay_list);
		Tpl::output('show_page',$model_appointment->showpage());

		self::profile_menu('member_appointment');
        Tpl::showpage('member_appointment.index');
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        Language::read('member_member_index');
        $lang	= Language::getLangContent();
        $appointment_id	= intval($_GET['appointment_id']);
        if ($appointment_id <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        $model_appointment	= Model('appointment');
        $condition['appointment_id'] = $appointment_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $appointment_info = $model_appointment->getappointmentInfo($condition,array('appointment_common','appointment_doctors'));
        if (empty($appointment_info) || !in_array($appointment_info['appointment_state'],array(appointment_STATE_SEND,appointment_STATE_SUCCESS))) {
            showMessage('未找到信息','','html','error');
        }
        Tpl::output('appointment_info',$appointment_info);
        //卖家信息
        $model_clic	= Model('clic');
        $clic_info		= $model_clic->getclicInfoByID($appointment_info['clic_id']);
        Tpl::output('clic_info',$clic_info);

        //卖家发货信息
        $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$appointment_info['extend_appointment_common']['daddress_id']));
        Tpl::output('daddress_info',$daddress_info);

		$this->get_member_info();
        //取得配送公司代码
        $express = ($express = H('express'))? $express :H('express',true);
        Tpl::output('e_code',$express[$appointment_info['extend_appointment_common']['shipping_express_id']]['e_code']);
        Tpl::output('e_name',$express[$appointment_info['extend_appointment_common']['shipping_express_id']]['e_name']);
        Tpl::output('e_url',$express[$appointment_info['extend_appointment_common']['shipping_express_id']]['e_url']);
        Tpl::output('shipping_code',$appointment_info['shipping_code']);
        self::profile_menu('search','search');
        Tpl::output('left_show','appointment_view');
        Tpl::showpage('member_appointment_deliver.detail');
    }

    /**
     * 从第三方取快递信息
     *
     */
    public function get_expressOp(){

        $url = 'http://www.kuaidi100.com/query?type='.$_GET['e_code'].'&postid='.$_GET['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
        import('function.ftp');
        $content = dfsockopen($url);
        $content = json_decode($content,true);

        if ($content['status'] != 200) exit(json_encode(false));
        $content['data'] = array_reverse($content['data']);
        $output = '';
        if (is_array($content['data'])){
            foreach ($content['data'] as $k=>$v) {
                if ($v['time'] == '') continue;
                $output .= '<li>'.$v['time'].'&nbsp;&nbsp;'.$v['context'].'</li>';
            }
        }
        if ($output == '') exit(json_encode(false));
        if (strtoupper(CHARSET) == 'GBK'){
            $output = Language::getUTF8($output);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($output);
    }

    /**
     * 订单详细
     *
     */
    public function show_appointmentOp() {
        $appointment_id = intval($_GET['appointment_id']);
        if ($appointment_id <= 0) {
            showMessage(Language::get('member_appointment_none_exist'),'','html','error');
        }
        $model_appointment = Model('appointment');
        $condition = array();
        $condition['appointment_id'] = $appointment_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $appointment_info = $model_appointment->getappointmentInfo($condition,array('appointment_doctors','appointment_common','clic'));
        if (empty($appointment_info)) {
            showMessage(Language::get('member_appointment_none_exist'),'','html','error');
        }
        Tpl::output('appointment_info',$appointment_info);
        Tpl::output('left_show','appointment_view');

        //卖家发货信息
        if (!empty($appointment_info['extend_appointment_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$appointment_info['extend_appointment_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }

        //订单变更日志
		$log_list	= $model_appointment->getappointmentLogList(array('appointment_id'=>$appointment_info['appointment_id']));
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
		Tpl::showpage('member_appointment.show');
    }

	/**
	 * 买家订单状态操作
	 *
	 */
	public function change_stateOp() {
		$state_type	= $_GET['state_type'];
		$appointment_id	= intval($_GET['appointment_id']);

        $model_appointment = Model('appointment');

		$condition = array();
		$condition['appointment_id'] = $appointment_id;
		$condition['buyer_id'] = $_SESSION['member_id'];
		$appointment_info	= $model_appointment->getappointmentInfo($condition);

        if (!chksubmit()) {
            Tpl::output('appointment_info', $appointment_info);
            if($state_type == 'appointment_cancel') {
                Tpl::showpage('member_appointment.cancel','null_layout');exit();
            } elseif ($state_type == 'appointment_receive') {
                Tpl::showpage('member_appointment.receive','null_layout');exit();
            }
        }

        $extend_msg = $_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info'];

        $result = $model_appointment->memberChangeState($state_type, $appointment_info, $_SESSION['member_id'], $_SESSION['member_name'], $extend_msg);

        if(empty($result['error'])) {
            showDialog($result['success'],'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        } else {
            showDialog($result['error'],'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='') {
	    Language::read('member_layout');
	    $menu_array = array(
	            1=>array('menu_key'=>'member_appointment','menu_name'=>Language::get('nc_member_path_appointment_list'),	    'menu_url'=>'index.php?act=member_appointment'),
	            2=>array('menu_key'=>'buyer_refund','menu_name'=>Language::get('nc_member_path_buyer_refund'),	'menu_url'=>'index.php?act=member_refund'),
	            3=>array('menu_key'=>'buyer_return','menu_name'=>Language::get('nc_member_path_buyer_return'),	'menu_url'=>'index.php?act=member_return')
	    );
	    Tpl::output('member_menu',$menu_array);
	    Tpl::output('menu_key',$menu_key);
	}
}
