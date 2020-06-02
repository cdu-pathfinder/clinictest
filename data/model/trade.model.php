<?php
/**
 * 交易新模型
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
class tradeModel extends Model{
	public function __construct() {
		parent::__construct();
	}
	/**
	 * 订单处理天数
	 *
	 */
	public function getMaxDay($day_type = 'all') {
		$max_data = array(
			'appointment_cancel' => 7,//未选择支付方式时取消订单
			'appointment_confirm' => 10,//买家不收货也没退款时自动完成订单
			'appointment_refund' => 15,//收货完成后可以申请退款退货
			'refund_confirm' => 7,//卖家不处理退款退货申请时按同意处理
			'return_confirm' => 7,//卖家不处理收货时按弃货处理
			'return_delay' => 5//退货的商品发货多少天以后才可以选择没收到
			);
		if ($day_type == 'all') return $max_data;//返回所有
		if (intval($max_data[$day_type]) < 1) $max_data[$day_type] = 1;//最小的值设置为1
		return $max_data[$day_type];
	}
	/**
	 * 订单状态
	 *
	 */
	public function getappointmentState($type = 'all') {
		$state_data = array(
			'appointment_cancel' => appointment_STATE_CANCEL,//0:已取消
			'appointment_default' => appointment_STATE_NEW,//10:未付款
			'appointment_paid' => appointment_STATE_PAY,//20:已付款
			'appointment_shipped' => appointment_STATE_SEND,//30:已发货
			'appointment_completed' => appointment_STATE_SUCCESS //40:已收货
			);
		if ($type == 'all') return $state_data;//返回所有
		return $state_data[$type];
	}
	/**
	 * 更新订单
	 * @param int $member_id 会员编号
	 * @param int $clic_id 店铺编号
	 */
	public function editappointmentPay($member_id=0, $clic_id=0) {
		$appointment_cancel = $this->getMaxDay('appointment_cancel');//未选择支付方式时取消订单的天数
		$day = time()-$appointment_cancel*60*60*24;
		$appointment_confirm = $this->getMaxDay('appointment_confirm');//买家不收货也没锁定订单时自动完成订单的天数
		$shipping_day = time()-$appointment_confirm*60*60*24;
		$appointment_default = $this->getappointmentState('appointment_default');//订单状态10:未付款
		$appointment_shipped = $this->getappointmentState('appointment_shipped');//订单状态30:已发货
		$condition = " ((appointment_state='".$appointment_default."' and add_time<".$day.") or (appointment_state='".$appointment_shipped."' and lock_state=0 and delay_time<".$shipping_day."))";//待支付(10)和待收货(30)
		$condition_sql = "";
		if ($member_id > 0) {
			$condition_sql = " buyer_id = '".$member_id."' and ";
		}
		if ($clic_id > 0) {
			$condition_sql = " clic_id = '".$clic_id."' and ";
		}
		$condition_sql = $condition_sql.$condition;
		$field = 'appointment_id,buyer_id,clic_id,add_time,payment_time,delay_time,appointment_state';
		$appointment_list = $this->table('appointment')->field($field)->where($condition_sql)->select();
		Language::read('model_lang_index');
		Language::read('refund');
		if (!empty($appointment_list) && is_array($appointment_list)) {
			foreach($appointment_list as $k => $v) {
				$appointment_id = $v['appointment_id'];//订单编号
				$appointment_state = $v['appointment_state'];//订单状态
				$log_array = array();
				$log_array['log_role'] = 'system';
				$log_array['log_time'] = time();
				$log_array['appointment_id'] = $appointment_id;
				switch ($appointment_state) {
    			    case $appointment_default:
    			    	$appointment_time = $v['add_time'];//订单生成时间
    					if (intval($appointment_time) < $day) {//超期时取消订单
    						$state_info = Language::get('appointment_max_day').$appointment_cancel.Language::get('appointment_max_day_cancel');
    						$log_array['log_msg'] = $state_info;
    						$this->editappointmentCancel($appointment_id, $log_array);
    					}
    			    	break;
    			    case $appointment_shipped:
    			    	$appointment_time = $v['delay_time'];
    					if (intval($appointment_time) < $shipping_day) {//超期时自动完成订单
    						$state_info = Language::get('appointment_max_day').$appointment_confirm.Language::get('appointment_max_day_confirm');
    						$log_array['log_msg'] = $state_info;
    						$this->editappointmentFinnsh($appointment_id, $log_array);
    					}
    			    	break;
				}
			}
			return true;
		}
		return false;
	}
	/**
	 * 取消订单并退回库存
	 * @param int $appointment_id 订单编号
	 * @param	array	$log_array	订单记录信息
	 */
	public function editappointmentCancel($appointment_id, $log_array) {
		$doctors_list = $this->table('appointment_doctors')->field('appointment_id,doctors_num,doctors_id')->where(array('appointment_id'=> $appointment_id))->select();//订单商品
		if (!empty($doctors_list) && is_array($doctors_list)) {
			foreach($doctors_list as $k => $v) {
				$doctors_id = $v['doctors_id'];
				$doctors_num = $v['doctors_num'];
        	    $condition = array();
        	    $condition['doctors_id'] = $doctors_id;
        	    $condition['doctors_salenum'] = array('egt',$doctors_num);
				$data = array();
				$data['doctors_storage'] = array('exp','doctors_storage+'.$doctors_num);//库存
				$data['doctors_salenum'] = array('exp','doctors_salenum-'.$doctors_num);//销售记录
				$state = $this->table('doctors')->where($condition)->update($data);
			}
			$appointment_cancel = $this->getappointmentState('appointment_cancel');//订单状态0:已取消
        	$appointment_array = array();
        	$appointment_array['appointment_state'] = $appointment_cancel;
        	$model_appointment = Model('appointment');
        	$state = $model_appointment->editappointment($appointment_array, array('appointment_id'=> $appointment_id));//更新订单
			if ($state) {
			    $log_array['log_appointmentstate'] = $appointment_array['appointment_state'];
			    $state = $model_appointment->addappointmentLog($log_array);
        	}
			return $state;
		}
		return false;
	}
	/**
	 * 更新退款申请
	 * @param int $member_id 会员编号
	 * @param int $clic_id 店铺编号
	 */
	public function editRefundConfirm($member_id=0, $clic_id=0) {
		Language::read('refund');
		$refund_confirm = $this->getMaxDay('refund_confirm');//卖家不处理退款申请时按同意并弃货处理
		$day = time()-$refund_confirm*60*60*24;
		$condition = " seller_state=1 and add_time<".$day;//状态:1为待审核,2为同意,3为不同意
		$condition_sql = "";
		if ($member_id > 0) {
			$condition_sql = " buyer_id = '".$member_id."'  and ";
		}
		if ($clic_id > 0) {
			$condition_sql = " clic_id = '".$clic_id."' and ";
		}
		$condition_sql = $condition_sql.$condition;
		$refund_array = array();
		$refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成
		$refund_array['seller_state'] = '2';//卖家处理状态:1为待审核,2为同意,3为不同意
		$refund_array['return_type'] = '1';//退货类型:1为不用退货,2为需要退货
		$refund_array['seller_time'] = time();
		$refund_array['seller_message'] = Language::get('appointment_max_day').$refund_confirm.Language::get('appointment_day_refund');
		$this->table('refund_return')->where($condition_sql)->update($refund_array);

		$return_confirm = $this->getMaxDay('return_confirm');//卖家不处理收货时按弃货处理
		$day = time()-$return_confirm*60*60*24;
		$condition = " seller_state=2 and doctors_state=2 and return_type=2 and delay_time<".$day;//物流状态:1为待发货,2为待收货,3为未收到,4为已收货
		$condition_sql = "";
		if ($member_id > 0) {
			$condition_sql = " buyer_id = '".$member_id."'  and ";
		}
		if ($clic_id > 0) {
			$condition_sql = " clic_id = '".$clic_id."' and ";
		}
		$condition_sql = $condition_sql.$condition;
		$refund_array = array();
		$refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成
		$refund_array['return_type'] = '1';//退货类型:1为不用退货,2为需要退货
		$refund_array['seller_message'] = Language::get('appointment_max_day').$return_confirm.'天未处理收货，按弃货处理';
		$this->table('refund_return')->where($condition_sql)->update($refund_array);
	}
	/**
	 * 自动收货完成订单
	 * @param int $appointment_id 订单编号
	 * @param	array	$log_array	订单记录信息
	 */
	public function editappointmentFinnsh($appointment_id, $log_array = array()) {
		$field = 'appointment_id,buyer_id,buyer_name,clic_id,appointment_sn,appointment_amount,payment_code,appointment_state';
		$appointment = $this->table('appointment')->field($field)->where(array('appointment_id'=> $appointment_id))->find();
		$appointment_shipped = $this->getappointmentState('appointment_shipped');//订单状态30:已发货
		$appointment_completed = $this->getappointmentState('appointment_completed');//订单状态40:已收货
		if ($appointment['appointment_state'] == $appointment_shipped) {//确认已经完成发货
			if (empty($log_array)) {
				$log_array['appointment_id'] = $appointment_id;
				$log_array['log_role'] = 'system';
				$log_array['log_msg'] = Language::get('appointment_completed');
				$log_array['log_time'] = time();
			}
			$state = true;
			$appointment_array = array();
			$appointment_array['finnshed_time'] = time();
			$appointment_array['appointment_state'] = $appointment_completed;
			$model_appointment = Model('appointment');
			$state = $model_appointment->editappointment($appointment_array, array('appointment_id'=> $appointment_id));//更新订单状态为已收货
			$log_array['log_appointmentstate'] = $appointment_array['appointment_state'];
			if ($state) $state = $model_appointment->addappointmentLog($log_array);//订单处理记录信息
			return $state;
		} else {
			return false;
		}
	}

}
?>