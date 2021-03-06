<?php
/**
 * 支付入口
 *
 * 
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class paymentControl extends BaseHomeControl{

    public function indexOp(){
        //购买商品和预存款充值分流
        if ($_POST['appointment_type'] == 'doc_buy') {
            $this->_doc_buy();
        } elseif ($_POST['appointment_type'] == 'pd_rechange') {
            $this->_pd_rechange();
        }
    }

	/**
	 * 商品购买
	 */
	private function _doc_buy(){
	    $pay_sn = $_POST['pay_sn'];
		$payment_code = $_POST['payment_code'];
        $url = 'index.php?act=member_appointment';

        $valid = !preg_match('/^\d{18}$/',$pay_sn) || !preg_match('/^[a-z]{1,20}$/',$payment_code) || in_array($payment_code,array('offline','predeposit'));
        if($valid){
            showMessage(Language::get('para_error'),'','html','error');
        }

        $model_payment = Model('payment');
        $result = $model_payment->docBuy($pay_sn, $payment_code, $_SESSION['member_id']);

        if(!empty($result['error'])) {
            showMessage($result['error'], $url, 'html', 'error');
        }

        //第三方API支付
        $this->_api_pay($result['appointment_pay_info'], $result['payment_info']);
	}

	/**
	 * 预存款充值
	 */
	private function _pd_rechange(){
	    Language::read('home_payment_index');
	    $url = 'index.php?act=predeposit';
	    //pay_sn:充值单号
	    $pay_sn = $_POST['pdr_sn'];
	    $payment_code = $_POST['payment_code'];
	    if(!preg_match('/^\d{18}$/',$pay_sn) || !preg_match('/^[a-z]{1,20}$/',$payment_code)){
	        showMessage(Language::get('para_error'),$url,'html','error');
	    }
	
	    //取支付方式信息
	    $model_payment = Model('payment');
	    $condition = array();
	    $condition['payment_code'] = $payment_code;
	    $payment_info = $model_payment->getPaymentOpenInfo($condition);
	    if(!$payment_info || in_array($payment_info['payment_code'],array('offline','predeposit'))) {
	        showMessage(L('payment_index_sys_not_support'),$url,'html','error');
	    }
	    $model_pd = Model('predeposit');
	    $appointment_info = $model_pd->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
	    $appointment_info['subject'] = '预存款充值_'.$appointment_info['pdr_sn'];
	    $appointment_info['appointment_type'] = 'predeposit';
	    $appointment_info['pay_sn'] = $appointment_info['pdr_sn'];
	    $appointment_info['pay_amount'] = $appointment_info['pdr_amount'];
	    if(empty($appointment_info) || $appointment_info['pdr_payment_state'] == 1){
	        showMessage(Language::get('cart_appointment_pay_not_exists'),$url,'html','error');
	    }

	    //其它第三方在线通用支付入口
	    $this->_api_pay($appointment_info,$payment_info);
	    break;
	}

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($appointment_info, $payment_info) {
    	$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
    	if(!file_exists($inc_file)){
    		showMessage(Language::get('payment_index_lose_file'),'','html','error');
    	}
    	require_once($inc_file);
    	$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
    	$payment_api = new $payment_info['payment_code']($payment_info,$appointment_info);
    	if($payment_info['payment_code'] == 'chinabank') {
    		$payment_api->submit();
    	} else {
    		@header("Location: ".$payment_api->get_payurl());
    	}
    	exit;
	}

	/**
	 * 通知处理(支付宝异步通知和网银在线自动对账)
	 *
	 */
	public function notifyOp(){
		$success	= str_replace(array('alipay','chinabank'), array('success','ok'), $_GET['payment_code']);
		$fail		= str_replace(array('alipay','chinabank'), array('fail','error'), $_GET['payment_code']);

		//参数判断
		if(empty($_POST['out_trade_no'])) exit($fail);
		if(!preg_match('/^\d{18}$/',$_POST['out_trade_no'])) exit($fail);
		if (!in_array($_POST['extra_common_param'],array('predeposit','doc_buy'))) {
		    exit($fail);
		}
		$out_trade_no = $_POST['out_trade_no'];
		$model_pd = Model('predeposit');
		if ($_POST['extra_common_param'] == 'doc_buy') {

    		//商品购买
    		$model_appointment = Model('appointment');
    		$appointment_pay_info	= $model_appointment->getappointmentPayInfo(array('pay_sn'=>$out_trade_no));
    		if(!is_array($appointment_pay_info) || empty($appointment_pay_info)) exit($fail);
    		if (intval($appointment_pay_info['api_pay_state'])) exit($success);

    		//取得订单列表和API支付总金额
    		$appointment_list = $model_appointment->getappointmentList(array('pay_sn'=>$out_trade_no,'appointment_state'=>appointment_STATE_NEW));
    		if (empty($appointment_list)) exit($success);
    		$pay_amount = 0;
    		foreach($appointment_list as $appointment_info) {
    		    $pay_amount += ncPriceFormat(floatval($appointment_info['appointment_amount']) - floatval($appointment_info['pd_amount']));
    		}
    		$appointment_pay_info['pay_amount'] = $pay_amount;

		} elseif ($_POST['extra_common_param'] == 'predeposit') {

		    //预存款充值
		    $appointment_pay_info = $model_pd->getPdRechargeInfo(array('pdr_sn'=>$out_trade_no));
		    if(!is_array($appointment_pay_info) || empty($appointment_pay_info)) exit($fail);
		    if (intval($appointment_pay_info['pdr_payment_state'])) exit($success);
		}

		//取得支付方式信息
		$payment_info = Model('payment')->getPaymentOpenInfo(array('payment_code'=>$_GET['payment_code']));
		if(!is_array($payment_info) or empty($payment_info)) exit($fail);
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
		if(!file_exists($inc_file)) exit($fail);
		require_once($inc_file);

		//创建支付接口对象
		$payment_api	= new $payment_info['payment_code']($payment_info,$appointment_pay_info);

		//对进入的参数进行远程数据判断
		$verify = $payment_api->notify_verify();
		if (!$verify) {
		    exit($fail);
		}
		$appointment_type = $payment_api->appointment_type;
		if (!in_array($appointment_type,array('doc_buy','predeposit'))) exit($fail);

        //购买商品
		if ($appointment_type == 'doc_buy') {
            $model_payment = Model('payment');
            $result = $model_payment->updatedocBuy($out_trade_no, $payment_info['payment_code'], $appointment_list, $_POST['trade_no']);
            if(!empty($result['error'])) {
		        exit($fail);
            }
		    exit($success);
		} else {

		    //预存款充值
		    $condition = array();
		    $condition['pdr_sn'] = $_POST['out_trade_no'];
		    $condition['pdr_payment_state'] = 0;
		    $recharge_info = $model_pd->getPdRechargeInfo($condition);
		    if (!$recharge_info) {
		        exit($fail);
		    }
            $condition = array();
            $condition['pdr_sn'] = $recharge_info['pdr_sn'];
            $condition['pdr_payment_state'] = 0;
            $update = array();
            $update['pdr_payment_state'] = 1;
            $update['pdr_payment_time'] = TIMESTAMP;
            $update['pdr_payment_code'] = $payment_info['payment_code'];
            $update['pdr_payment_name'] = $payment_info['payment_name'];
            $update['pdr_trade_sn'] = $_POST['trade_no'];

            try {
                $model_pd->beginTransaction();
                //更改充值状态
                $state = $model_pd->editPdRecharge($update,$condition);
                if (!$state) {
                    exit($fail);
                }
                //变更会员预存款
                $data = array();
                $data['member_id'] = $recharge_info['pdr_member_id'];
                $data['member_name'] = $recharge_info['pdr_member_name'];
                $data['amount'] = $recharge_info['pdr_amount'];
                $data['pdr_sn'] = $recharge_info['pdr_sn'];
                $model_pd->changePd('recharge',$data);
                $model_pd->commit();
                exit($success);
            } catch (Exception $e) {
                $model_pd->rollback();
                exit($fail);
            }
		}
	}

	/**
	 * 支付接口返回
	 *
	 */
	public function returnOp(){
		Language::read('home_payment_index');
		if ($_GET['extra_common_param'] == 'doc_buy') {
		    $url = clinic_SITE_URL."/index.php?act=member_appointment";
		} else {
		    $url = clinic_SITE_URL."/index.php?act=predeposit";
		}

		$out_trade_no = $_GET['out_trade_no'];
		//对外部交易编号进行非空判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) {
		    showMessage(Language::get('para_error'),$url,'','html','error');
		}
		if (!in_array($_GET['extra_common_param'],array('predeposit','doc_buy'))) {
		    showMessage(Language::get('para_error'),$url,'','html','error');
		}

        $condition = array();
		if ($_GET['extra_common_param'] == 'doc_buy') {

		    //取得订单信息
		    $model_appointment = Model('appointment');
		    $condition['pay_sn'] = $out_trade_no;
		    $appointment_pay_info	= $model_appointment->getappointmentPayInfo($condition);

		    //对订单信息进行非空判断
		    if(empty($appointment_pay_info)) {
		        showMessage('返回的交易号不存',$url,'html','error');
		    }
		    if (intval($appointment_pay_info['api_pay_state'])) {
		        showMessage(Language::get('payment_index_deal_appointment_success'),$url);
		    }

		    //取得订单列表和API支付总金额
		    $appointment_list = $model_appointment->getappointmentList(array('pay_sn'=>$out_trade_no,'appointment_state'=>appointment_STATE_NEW));
		    if (empty($appointment_list)) {
		        showMessage(Language::get('payment_index_deal_appointment_success'),$url);
		    }
		    $pay_amount = $api_pay_amount = 0;
		    foreach($appointment_list as $appointment_info) {
		        $api_pay_amount += ncPriceFormat(floatval($appointment_info['appointment_amount']) - floatval($appointment_info['pd_amount']));
		        $pay_amount += floatval($appointment_info['appointment_amount']);
		    }
		    $appointment_pay_info['pay_amount'] = $api_pay_amount;

		} elseif ($_GET['extra_common_param'] == 'predeposit') {
		    $model_pd = Model('predeposit');
		    $condition['pdr_sn'] = $out_trade_no;
		    $appointment_pay_info = $model_pd->getPdRechargeInfo($condition);
		    //对订单信息进行非空判断
		    if(empty($appointment_pay_info)) {
		        showMessage('返回的交易号不存',$url,'html','error');
		    }
		    if (intval($appointment_pay_info['pdr_payment_state'])) {
		        showMessage(Language::get('payment_index_deal_pdr_success'),$url);
		    }
		}

		//取得支付接口信息
		$payment_code = $_GET['payment_code'];
		unset($_GET['payment_code']);
		$model_payment = Model('payment');
		$condition = array();
		$condition['payment_code'] = $payment_code;
		$payment_info = $model_payment->getPaymentOpenInfo($condition);
		if(!is_array($payment_info) || empty($payment_info)) {
		    showMessage(Language::get('payment_index_miss_pay_method_data'),$url,'html','error');
		}
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
		if(!file_exists($inc_file)) {
		    showMessage(Language::get('payment_index_lose_file'),$url,'html','error');
		}
		require_once($inc_file);
		$payment_api = new $payment_info['payment_code']($payment_info,$appointment_pay_info);

		//返回参数判断
		$verify = $payment_api->return_verify();
		if(!$verify) {
		    showMessage(Language::get('payment_index_identify_fail'),$url,'html','error');
		}
		$appointment_type = $payment_api->appointment_type;
		if (!in_array($appointment_type,array('doc_buy','predeposit'))) {
		    showMessage(Language::get('payment_index_identify_fail'),$url,'html','error');
		}

		//取得支付结果
		$pay_result	= $payment_api->getPayResult($_GET);
		if (!$pay_result) {
		    showMessage('非常抱歉，您的订单支付没有成功，请您后尝试',$url,'html','error');
		}

		//支付成功后处理
		if ($appointment_type == 'predeposit') {
		    $this->_updatePredeposit($payment_info['payment_code']);
		} elseif ($appointment_type == 'doc_buy') {
		    $this->_updatedoc_buy($payment_info['payment_code'],$appointment_list,$pay_amount);
		}

	}

	/**
	 * 预存款充值在线支付成功后，更新数据表
	 *
	 */
	private function _updatePredeposit($payment_code) {
	    $url	= clinic_SITE_URL."/index.php?act=predeposit&op=index";

        //取得记录信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $_GET['out_trade_no'];
        $condition['pdr_payment_state'] = 0;
        $recharge_info = $model_pd->getPdRechargeInfo($condition);
        if (!is_array($recharge_info) || empty($recharge_info)){
            showMessage(Language::get('predeposit_payment_pay_fail'),$url,'html','error');
        }

        //取支付方式信息
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(!$payment_info || $payment_info['payment_code'] == 'offline') {
            showMessage(L('payment_index_sys_not_support'),'','html','error');
        }

        $condition = array();
        $condition['pdr_sn'] = $recharge_info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = TIMESTAMP;
        $update['pdr_payment_code'] = $payment_code;
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $_GET['trade_no'];

        try {
        	$model_pd->beginTransaction();
        	//更改充值状态
        	$state = $model_pd->editPdRecharge($update,$condition);
        	if (!$state) {
        	    throw Exception(Language::get('predeposit_payment_pay_fail'));
        	}
        	//变更会员预存款
    	    $data = array();
    	    $data['member_id'] = $recharge_info['pdr_member_id'];
    	    $data['member_name'] = $recharge_info['pdr_member_name'];
    	    $data['amount'] = $recharge_info['pdr_amount'];
    	    $data['pdr_sn'] = $recharge_info['pdr_sn'];
    	    $model_pd->changePd('recharge',$data);
    	    $model_pd->commit();
        } catch (Exception $e) {
            $model_pd->rollback();
            showMessage($e->getMessage(),$url,'html','error');
        }

        //财付通需要输出反馈
        if ($payment_code == 'tenpay'){
            $url = clinic_SITE_URL."/index.php?act=payment&op=payment_success&predeposit=1";
            showMessage(Language::get('payment_index_deal_pdr_success'),$url,'tenpay');
        } else {
            showMessage(Language::get('payment_index_deal_pdr_success'),$url);
        }
	}

	/**
	 * 购买商品在线支付成功后，更新数据表(财付通异步也使用return,不能使用SESSION)
	 */
	private function _updatedoc_buy($payment_code,$appointment_list,$pay_amount) {
	    $url = clinic_SITE_URL."/index.php?act=member_appointment";
	    $out_trade_no = $_GET['out_trade_no'];

        if ($_GET['trade_no'] != '') {
            $trade_no = $_GET['trade_no'];
        }

        $model_payment = Model('payment');
        $result = $model_payment->updatedocBuy($out_trade_no, $payment_code, $appointment_list, $trade_no);
        if(!empty($result['error'])) {
	        showMessage($result['error'], $url, 'html', 'error');
        }

	    //财付通需要输出反馈
	    if ($payment_code == 'tenpay'){
	        $url = clinic_SITE_URL."/index.php?act=payment&op=payment_success";
	        showMessage(Language::get('payment_index_deal_appointment_success'),$url,'tenpay');
	    } else {
	        redirect(clinic_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.ncPriceFormat($pay_amount));
	    }
	}

	/**
	 * 支付成功
	 * 
	 */
	public function payment_successOp(){
		Language::read('home_payment_index');
		if ($_GET['predeposit']) {
		    $url = clinic_SITE_URL."/index.php?act=predeposit";
		    $lang = Language::get('payment_index_deal_pdr_success');
		} else {
		    $url = clinic_SITE_URL."/index.php?act=member_appointment";
		    $lang = Language::get('payment_index_deal_appointment_success');
		}
		showMessage($lang,$url);
	}
}
