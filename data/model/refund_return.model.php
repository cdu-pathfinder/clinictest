<?php
/**
 * 退款退货
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
class refund_returnModel extends Model{

    /**
     * 取得退单数量
     * @param unknown $condition
     */
    public function getRefundReturn($condition) {
        return $this->table('refund_return')->where($condition)->count();
    }

	/**
	 * 增加退款退货
	 *
	 * @param
	 * @return int
	 */
	public function addRefundReturn($refund_array, $appointment = array(), $doctors = array()) {
	    if (!empty($appointment) && is_array($appointment)) {
			$refund_array['appointment_id'] = $appointment['appointment_id'];
			$refund_array['appointment_sn'] = $appointment['appointment_sn'];
			$refund_array['clic_id'] = $appointment['clic_id'];
			$refund_array['clic_name'] = $appointment['clic_name'];
			$refund_array['buyer_id'] = $appointment['buyer_id'];
			$refund_array['buyer_name'] = $appointment['buyer_name'];
	    }
	    if (!empty($doctors) && is_array($doctors)) {
			$refund_array['doctors_id'] = $doctors['doctors_id'];
			$refund_array['appointment_doctors_id'] = $doctors['rec_id'];
			$refund_array['appointment_doctors_type'] = $doctors['doctors_type'];
			$refund_array['doctors_name'] = $doctors['doctors_name'];
			$refund_array['commis_rate'] = $doctors['commis_rate'];
			$refund_array['doctors_image'] = $doctors['doctors_image'];
	    }
	    $refund_array['refund_sn'] = $this->getRefundsn($refund_array['clic_id']);
		$refund_id = $this->table('refund_return')->insert($refund_array);
		return $refund_id;
	}

	/**
	 * 订单锁定
	 *
	 * @param
	 * @return bool
	 */
	public function editappointmentLock($appointment_id) {
	    $appointment_id = intval($appointment_id);
		if ($appointment_id > 0) {
    	    $condition = array();
    	    $condition['appointment_id'] = $appointment_id;
    		$data = array();
    		$data['lock_state'] = array('exp','lock_state+1');
    		$result = $this->table('appointment')->where($condition)->update($data);
    		return $result;
		}
		return false;
	}

	/**
	 * 订单解锁
	 *
	 * @param
	 * @return bool
	 */
	public function editappointmentUnlock($appointment_id) {
	    $appointment_id = intval($appointment_id);
		if ($appointment_id > 0) {
    	    $condition = array();
    	    $condition['appointment_id'] = $appointment_id;
    	    $condition['lock_state'] = array('egt','1');
    		$data = array();
    		$data['lock_state'] = array('exp','lock_state-1');
    		$data['delay_time'] = time();
    		$result = $this->table('appointment')->where($condition)->update($data);
    		return $result;
		}
		return false;
	}

	/**
	 * 修改记录
	 *
	 * @param
	 * @return bool
	 */
	public function editRefundReturn($condition, $data) {
		if (empty($condition)) {
			return false;
		}
		if (is_array($data)) {
			$result = $this->table('refund_return')->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * 平台确认退款处理
	 *
	 * @param
	 * @return bool
	 */
	public function editappointmentRefund($refund) {
	    $refund_id = intval($refund['refund_id']);
		if ($refund_id > 0) {
		    Language::read('model_lang_index');
			$appointment_id = $refund['appointment_id'];//订单编号
			$field = 'appointment_id,buyer_id,buyer_name,clic_id,appointment_sn,appointment_amount,payment_code,appointment_state,refund_amount';
			$appointment = $this->table('appointment')->field($field)->where(array('appointment_id'=> $appointment_id))->find();

			$predeposit_model = Model('predeposit');
            $log_array = array();
            $log_array['member_id'] = $appointment['buyer_id'];
            $log_array['member_name'] = $appointment['buyer_name'];
            $log_array['amount'] = $refund['refund_amount'];
            $log_array['appointment_sn'] = $appointment['appointment_sn'];
            $state = $predeposit_model->changePd('refund', $log_array);//增加买家可用金额

			$appointment_state = $appointment['appointment_state'];
			$model_trade = Model('trade');
			$appointment_paid = $model_trade->getappointmentState('appointment_paid');//订单状态20:已付款
			if ($state && $appointment_state == $appointment_paid) {
				$log_array = array();
				$log_array['appointment_id'] = $appointment_id;
				$log_array['log_role'] = 'system';
				$log_array['log_time'] = time();
        	    $log_array['log_msg'] = '商品全部退款完成取消订单。';
        	    $state = $model_trade->editappointmentCancel($appointment_id, $log_array);//已付款未发货时取消订单
        	}
			if ($state) {
			    $appointment_array = array();
			    $appointment_amount = $appointment['appointment_amount'];//订单金额
			    $refund_amount = $appointment['refund_amount']+$refund['refund_amount'];//退款金额
			    $appointment_array['refund_state'] = ($appointment_amount-$refund_amount) > 0 ? 1:2;
			    $appointment_array['refund_amount'] = ncPriceFormat($refund_amount);
			    $appointment_array['delay_time'] = time();
			    $state = $this->table('appointment')->where(array('appointment_id'=> $appointment_id))->update($appointment_array);//更新订单退款
        	}
			if ($state && $refund['appointment_lock'] == '2') {
			    $state = $this->editappointmentUnlock($appointment_id);//订单解锁
			}
			return $state;
		}
		return false;
	}

	/**
	 * 取退款退货记录
	 *
	 * @param
	 * @return array
	 */
	public function getRefundReturnList($condition = array(), $page = '', $fields = '*', $limit = '') {
		$result = $this->table('refund_return')->field($fields)->where($condition)->page($page)->limit($limit)->appointment('refund_id desc')->select();
		return $result;
	}

	/**
	 * 取退款记录
	 *
	 * @param
	 * @return array
	 */
	public function getRefundList($condition = array(), $page = '') {
	    $condition['refund_type'] = '1';//类型:1为退款,2为退货
		$result = $this->getRefundReturnList($condition, $page);
		return $result;
	}

	/**
	 * 取退货记录
	 *
	 * @param
	 * @return array
	 */
	public function getReturnList($condition = array(), $page = '') {
	    $condition['refund_type'] = '2';//类型:1为退款,2为退货
		$result = $this->getRefundReturnList($condition, $page);
		return $result;
	}

	/**
	 * 退款退货申请编号
	 *
	 * @param
	 * @return array
	 */
	public function getRefundsn($clic_id) {
		$result = mt_rand(100,999).substr(100+$clic_id,-3).date('ymdHis');
		return $result;
	}

	/**
	 * 取一条记录
	 *
	 * @param
	 * @return array
	 */
	public function getRefundReturnInfo($condition = array(), $fields = '*') {
        return $this->table('refund_return')->where($condition)->field($fields)->find();
	}

	/**
	 * 根据订单取商品的退款退货状态
	 *
	 * @param
	 * @return array
	 */
	public function getdoctorsRefundList($appointment_list = array()) {
	    $appointment_ids = array();//订单编号数组
	    $appointment_ids = array_keys($appointment_list);
	    $model_trade = Model('trade');
	    $condition = array();
	    $condition['appointment_id'] = array('in', $appointment_ids);
	    $refund_list = $this->table('refund_return')->where($condition)->appointment('refund_id desc')->select();
	    $refund_doctors = array();//已经提交的退款退货商品
	    if (!empty($refund_list) && is_array($refund_list)) {
    	    foreach ($refund_list as $key => $value) {
    	        $appointment_id = $value['appointment_id'];//订单编号
    	        $doctors_id = $value['appointment_doctors_id'];//订单商品表编号
    	        if (empty($refund_doctors[$appointment_id][$doctors_id])) {
    	            $refund_doctors[$appointment_id][$doctors_id] = $value;
    	        }
    	    }
	    }
	    if (!empty($appointment_list) && is_array($appointment_list)) {
    	    foreach ($appointment_list as $key => $value) {
    	        $appointment_id = $key;
    	        $doctors_list = $value['extend_appointment_doctors'];//订单商品
    	        $appointment_state = $value['appointment_state'];//订单状态
        	    $appointment_paid = $model_trade->getappointmentState('appointment_paid');//订单状态20:已付款
        	    $payment_code = $value['payment_code'];//支付方式
        	    if ($appointment_state == $appointment_paid && $payment_code != 'offline') {//已付款未发货的非货到付款订单可以申请取消
        	        $appointment_list[$appointment_id]['refund'] = '1';
        	    } elseif ($appointment_state > $appointment_paid && !empty($doctors_list) && is_array($doctors_list)) {//已发货后对商品操作
        	        $refund = $this->getRefundState($value);//根据订单状态判断是否可以退款退货
            	    foreach ($doctors_list as $k => $v) {
            	        $doctors_id = $v['rec_id'];//订单商品表编号
            	        if ($v['doctors_pay_price'] > 0) {//实际支付额大于0的可以退款
            	            $v['refund'] = $refund;
            	        }
            	        if (!empty($refund_doctors[$appointment_id][$doctors_id])) {
            	            $seller_state = $refund_doctors[$appointment_id][$doctors_id]['seller_state'];//卖家处理状态:1为待审核,2为同意,3为不同意
            	            if ($seller_state == 3) {
            	                $appointment_list[$appointment_id]['complain'] = '1';//不同意可以发起投诉
            	            } else {
            	                $v['refund'] = '0';//已经存在处理中或同意的商品不能再操作
            	            }
            	            $v['extend_refund'] = $refund_doctors[$appointment_id][$doctors_id];
            	        }
            	        $doctors_list[$k] = $v;
            	    }
        	    }
    	        $appointment_list[$appointment_id]['extend_appointment_doctors'] = $doctors_list;
    	    }
	    }
		return $appointment_list;
	}

	/**
	 * 根据订单判断投诉订单商品是否可退款
	 *
	 * @param
	 * @return array
	 */
	public function getComplainRefundList($appointment) {
	    $list = array();
	    $refund_list = array();//已退或处理中商品
	    $refund_doctors = array();//可退商品
	    if (!empty($appointment) && is_array($appointment)) {
            $appointment_id = $appointment['appointment_id'];
            $appointment_list[$appointment_id] = $appointment;
            $appointment_list = $this->getdoctorsRefundList($appointment_list);
            $appointment = $appointment_list[$appointment_id];
            $doctors_list = $appointment['extend_appointment_doctors'];
            $appointment_amount = $appointment['appointment_amount'];//订单金额
		    $appointment_refund_amount = $appointment['refund_amount'];//订单退款金额
            foreach ($doctors_list as $k => $v) {
                $doctors_id = $v['rec_id'];//订单商品表编号
        		$v['refund_state'] = 3;
                if (!empty($v['extend_refund'])) {
                    $v['refund_state'] = $v['extend_refund']['seller_state'];//卖家处理状态为3,不同意时能退款
                }
                if ($v['refund_state'] > 2) {//可退商品
                    $doctors_pay_price = $v['doctors_pay_price'];//商品实际成交价
            		if ($appointment_amount < ($doctors_pay_price + $appointment_refund_amount)) {
            		    $doctors_pay_price = $appointment_amount - $appointment_refund_amount;
            		    $v['doctors_pay_price'] = $doctors_pay_price;
            		}
            		$v['doctors_refund'] = $v['doctors_pay_price'];
                    $refund_doctors[$doctors_id] = $v;
                } else {//已经存在处理中或同意的商品不能再退款
                    $refund_list[$doctors_id] = $v;
                }
            }
		}
		$list = array(
			'refund' => $refund_list,
			'doctors' => $refund_doctors
			);
		return $list;
	}

	/**
	 * 根据订单状态判断是否可以退款退货
	 *
	 * @param
	 * @return array
	 */
	public function getRefundState($appointment) {
	    $refund = '0';//默认不允许退款退货
	    $appointment_state = $appointment['appointment_state'];//订单状态
	    $model_trade = Model('trade');
	    $appointment_shipped = $model_trade->getappointmentState('appointment_shipped');//30:已发货
	    $appointment_completed = $model_trade->getappointmentState('appointment_completed');//40:已收货
	    switch ($appointment_state) {
            case $appointment_shipped:
                $payment_code = $appointment['payment_code'];//支付方式
                if ($payment_code != 'offline') {//货到付款订单在没确认收货前不能退款退货
                    $refund = '1';
                }
                break;
            case $appointment_completed:
        	    $appointment_refund = $model_trade->getMaxDay('appointment_refund');//15:收货完成后可以申请退款退货
        	    $delay_time = $appointment['delay_time']+60*60*24*$appointment_refund;
                if ($delay_time > time()) {
                    $refund = '1';
                }
                break;
            default:
                $refund = '0';
                break;
	    }

	    return $refund;
	}

	/**
	 * 向模板页面输出退款退货状态
	 *
	 * @param
	 * @return array
	 */
	public function getRefundStateArray($type = 'all') {
		Language::read('refund');
		$state_array = array(
			'1' => Language::get('refund_state_confirm'),
			'2' => Language::get('refund_state_yes'),
			'3' => Language::get('refund_state_no')
			);//卖家处理状态:1为待审核,2为同意,3为不同意
		Tpl::output('state_array', $state_array);

		$admin_array = array(
			'1' => '处理中',
			'2' => '待处理',
			'3' => '已完成'
			);//确认状态:1为买家或卖家处理中,2为待平台管理员处理,3为退款退货已完成
		Tpl::output('admin_array', $admin_array);

		$state_data = array(
			'seller' => $state_array,
			'admin' => $admin_array
			);
		if ($type == 'all') return $state_data;//返回所有
		return $state_data[$type];
	}

    /**
     * 退货退款数量
     *
     * @param array $condition
     * @return int
     */
    public function getRefundReturnCount($condition) {
        return $this->table('refund_return')->where($condition)->count();
    }

}