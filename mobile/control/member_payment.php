<?php
/**
 * 支付
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

class member_paymentControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 支付
     */
    public function payOp() {
	    $pay_sn = $_GET['pay_sn'];
        $payment_code = 'alipay';

        $model_payment = Model('payment');
        $result = $model_payment->docBuy($pay_sn, $payment_code, $this->member_info['member_id']);

        if(!empty($result['error'])) {
            output_error($result['error']);
        }

        //第三方API支付
        $this->_api_pay($result['appointment_pay_info'], $result['payment_info']);
    }

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($appointment_pay_info, $payment_info) {
    	$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
    	if(!file_exists($inc_file)){
            output_error('支付接口不存在');
    	}
    	require_once($inc_file);
        $param = array();
    	$param = unserialize($payment_info['payment_config']);
        $param['appointment_sn'] = $appointment_pay_info['pay_sn'];
        $param['appointment_amount'] = $appointment_pay_info['pay_amount'];
        $param['sign_type'] = 'MD5';
    	$payment_api = new $payment_info['payment_code']($param);
        $return = $payment_api->submit();
        echo $return;
    	exit;
	}
}
