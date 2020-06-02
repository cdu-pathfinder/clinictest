<?php
/**
 * 我的订单
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

class member_appointmentControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 订单列表
     */
    public function appointment_listOp() {
		$model_appointment = Model('appointment');

        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];

        $appointment_list_array = $model_appointment->getappointmentList($condition, $this->page, '*', 'appointment_id desc','', array('appointment_doctors'));

        $appointment_group_list = array();
        $appointment_pay_sn_array = array();
        foreach ($appointment_list_array as $value) {
            //显示取消订单
            $value['if_cancel'] = $model_appointment->getappointmentOperateState('buyer_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_appointment->getappointmentOperateState('receive',$value);
            //显示锁定中
            $value['if_lock'] = $model_appointment->getappointmentOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_appointment->getappointmentOperateState('deliver',$value);

            $appointment_group_list[$value['pay_sn']]['appointment_list'][] = $value;

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['appointment_state'] == appointment_STATE_NEW) {
                $appointment_group_list[$value['pay_sn']]['pay_amount'] += $value['appointment_amount'];
            }
            $appointment_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];

            //记录一下pay_sn，后面需要查询支付单表
            $appointment_pay_sn_array[] = $value['pay_sn'];
        }

        $new_appointment_group_list = array();
        foreach ($appointment_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            $new_appointment_group_list[] = $value;
        }

        $page_count = $model_appointment->gettotalpage();

        output_data(array('appointment_group_list' => $new_appointment_group_list), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    public function appointment_cancelOp() {
        $extend_msg = '其它原因';
        $this->change_appointment_state('appointment_cancel', $extend_msg);
    }

    /**
     * 订单确认收货
     */
    public function appointment_receiveOp() {
        $this->change_appointment_state('appointment_receive');
    }

    /**
     * 修改订单状态
     */
	private function change_appointment_state($state_type, $extend_msg = '') {
        $appointment_id = intval($_POST['appointment_id']);

        $model_appointment = Model('appointment');

		$condition = array();
		$condition['appointment_id'] = $appointment_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
		$appointment_info	= $model_appointment->getappointmentInfo($condition);

        $result = $model_appointment->memberChangeState($state_type, $appointment_info, $this->member_info['member_id'], $this->member_info['member_name'], $extend_msg);

        if(empty($result['error'])) {
            output_data('1');
        } else {
            output_error($result['error']);
        }
    }


}
