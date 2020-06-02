<?php
/**
 * 支付方式
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
class paymentModel extends Model {
    /**
     * 开启状态标识
     * @var unknown
     */
    const STATE_OPEN = 1;
    
    public function __construct() {
        parent::__construct('payment');
    }

	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 读开启中的取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentOpenInfo($condition = array()) {
	    $condition['payment_state'] = self::STATE_OPEN;
	    return $this->where($condition)->find();
	}
	
	/**
	 * 读取多行
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentList($condition = array()){
        return $this->where($condition)->select();
	}
	
	/**
	 * 读取开启中的支付方式
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentOpenList($condition = array()){
	    $condition['payment_state'] = self::STATE_OPEN;
	    return $this->where($condition)->key('payment_code')->select();
	}
	
	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function editPayment($data, $condition){
		return $this->where($condition)->update($data);
	}

	/**
	 * 读取支付方式信息by Condition
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getRowByCondition($conditionfield,$conditionvalue){
	    $param	= array();
	    $param['table']	= 'payment';
	    $param['field']	= $conditionfield;
	    $param['value']	= $conditionvalue;
	    $result	= Db::getRow($param);
	    return $result;
	}

    /**
     * 购买商品
     */
    public function docBuy($pay_sn, $payment_code, $member_id) {
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $this->getPaymentOpenInfo($condition);
        if(!$payment_info) {
            return array('error' => '系统不支持选定的支付方式');
        }

        //验证订单信息
	    $model_appointment = Model('appointment');
	    $appointment_pay_info = $model_appointment->getappointmentPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$member_id));
	    if(empty($appointment_pay_info)){
            return array('error' => '该订单不存在');
	    }
	    $appointment_pay_info['subject'] = '商品购买_'.$appointment_pay_info['pay_sn'];
	    $appointment_pay_info['appointment_type'] = 'doc_buy';

	    //重新计算在线支付且处于待支付状态的订单总额
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['appointment_state'] = appointment_STATE_NEW;
        $appointment_list = $model_appointment->getappointmentList($condition,'','appointment_id,appointment_sn,appointment_amount,pd_amount');
        if (empty($appointment_list)) {
            return array('error' => '该订单不存在');
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        foreach ($appointment_list as $appointment_info) {
                $pay_amount += ncPriceFormat(floatval($appointment_info['appointment_amount']) - floatval($appointment_info['pd_amount']));
        }

        //如果为空，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount)) {
            return array('error' => '订单金额为0，不需要支付');
        }
        $appointment_pay_info['pay_amount'] = $pay_amount;

        return(array('appointment_pay_info' => $appointment_pay_info, 'payment_info' => $payment_info));

    }

    /**
     * 购买订单支付成功后修改订单状态
     */
    public function updatedocBuy($out_trade_no, $payment_code, $appointment_list, $trade_no) {
	    try {
	        $model_appointment = Model('appointment');
	        $model_pd = Model('predeposit');
	        $model_appointment->beginTransaction();

	        $data = array();
	        $data['api_pay_state'] = 1;
	        $update = $model_appointment->editappointmentPay($data,array('pay_sn'=>$out_trade_no));
	        if (!$update) {
	            throw new Exception('更新订单状态失败');
	        }

	        $data = array();
	        $data['appointment_state']	= appointment_STATE_PAY;
	        $data['payment_time']	= TIMESTAMP;
	        $data['payment_code']   = $payment_code;
	        $update = $model_appointment->editappointment($data,array('pay_sn'=>$out_trade_no,'appointment_state'=>appointment_STATE_NEW));
	        if (!$update) {
	            throw new Exception('更新订单状态失败');
	        }

            foreach($appointment_list as $appointment_info) {
                //如果有预存款支付的，彻底扣除冻结的预存款
                $pd_amount = floatval($appointment_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $appointment_info['buyer_id'];
                    $data_pd['member_name'] = $appointment_info['buyer_name'];
                    $data_pd['amount'] = $appointment_info['pd_amount'];
                    $data_pd['appointment_sn'] = $appointment_info['appointment_sn'];
                    $model_pd->changePd('appointment_comb_pay',$data_pd);
                }
                //记录订单日志
                $data = array();
                $data['appointment_id'] = $appointment_info['appointment_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = L('appointment_log_pay').' ( 支付平台交易号 : '.$trade_no.' )';
                $data['log_appointmentstate'] = appointment_STATE_PAY;
                $insert = $model_appointment->addappointmentLog($data);
                if (!$insert) {
                    throw new Exception('记录订单日志出现错误');
                }
            }
	        $model_appointment->commit();
            return array('success' => true);
	    } catch (Exception $e) {
	        $model_appointment->rollback();
            return array('error' => $e->getMessage());
	    }

    }
}
