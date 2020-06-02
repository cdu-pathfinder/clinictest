<?php
/**
 * 交易管理
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
class appointmentControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('trade');		
	}

	public function indexOp(){
	    $model_appointment = Model('appointment');
        $condition	= array();
        if($_GET['appointment_sn']) {
        	$condition['appointment_sn'] = $_GET['appointment_sn'];
        }
        if($_GET['clic_name']) {
            $condition['clic_name'] = $_GET['clic_name'];
        }
        if(in_array($_GET['appointment_state'],array('0','10','20','30','40','50'))){
        	$condition['appointment_state'] = $_GET['appointment_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $appointment_list	= $model_appointment->getappointmentList($condition,30);

        foreach ($appointment_list as $appointment_id => $appointment_info) {
            //显示取消订单
            $appointment_list[$appointment_id]['if_cancel'] = $model_appointment->getappointmentOperateState('system_cancel',$appointment_info);
            //显示收到货款
            $appointment_list[$appointment_id]['if_system_receive_pay'] = $model_appointment->getappointmentOperateState('system_receive_pay',$appointment_info);            
        }
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);

        Tpl::output('appointment_list',$appointment_list);
        Tpl::output('show_page',$model_appointment->showpage());
        Tpl::showpage('appointment.index');
	}

	/**
	 * 平台订单状态操作
	 *
	 */
	public function change_stateOp() {
        $appointment_id = intval($_GET['appointment_id']);
        if($appointment_id <= 0){
            showMessage(L('miss_appointment_number'),$_POST['ref_url'],'html','error');
        }
        $model_appointment = Model('appointment');

        //获取订单详细
        $condition = array();
        $condition['appointment_id'] = $appointment_id;
        $appointment_info	= $model_appointment->getappointmentInfo($condition);
        try {

            $model_appointment->beginTransaction();
            $state_type	= $_GET['state_type'];
            if ($state_type == 'cancel') {
                $this->_change_state_appointment_cancel($appointment_info);
            } elseif ($state_type == 'receive_pay') {
                $this->_change_state_appointment_receive_pay($appointment_info);
            }

            $model_appointment->commit();
            showMessage(L('nc_common_op_succ'),$_POST['ref_url']);

        } catch (Exception $e) {
            $model_appointment->rollback();
            showMessage($e->getMessage(),$_POST['ref_url'],'html','error');
        }
	}

	/**
	 * 系统取消订单
	 * @throws Exception
	 */
	private function _change_state_appointment_cancel($appointment_info) {
	    $appointment_id = $appointment_info['appointment_id'];
	    $model_appointment = Model('appointment');
	    $if_allow = $model_appointment->getappointmentOperateState('system_cancel',$appointment_info);
	    if (!$if_allow) {
	        throw new Exception(L('invalid_request'));
	    }
	    $doctors_list = $model_appointment->getappointmentdoctorsList(array('appointment_id'=>$appointment_id));
	    $model_doctors= Model('doctors');
	    if(is_array($doctors_list) and !empty($doctors_list)) {
	        $data = array();
	        foreach ($doctors_list as $doctors) {
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

	    //更新订单状态
	    $update_appointment = array('appointment_state' => appointment_STATE_CANCEL);
	    $update = $model_appointment->editappointment($update_appointment,array('appointment_id'=>$appointment_id));
	    if (!$update) {
	        throw new Exception(L('nc_common_save_fail'));
	    }

	    //添加订单日志
	    $data = array();
	    $data['appointment_id'] = $appointment_id;
	    $data['log_role'] = 'system';
	    $data['log_user'] = $this->admin_info['name'];
	    $data['log_msg'] = L('appointment_log_cancel');
	    $data['log_appointmentstate'] = appointment_STATE_CANCEL;
	    $model_appointment->addappointmentLog($data);
	    
	    $this->log(L('appointment_log_cancel').','.L('appointment_number').':'.$appointment_info['appointment_sn'],1);
	}
	
	/**
	 * 系统收到货款
	 * @throws Exception
	 */
	private function _change_state_appointment_receive_pay($appointment_info) {
	    $appointment_id = $appointment_info['appointment_id'];
	    $model_appointment = Model('appointment');
	    $if_allow = $model_appointment->getappointmentOperateState('system_receive_pay',$appointment_info);
	    if (!$if_allow) {
	        throw new Exception(L('invalid_request'));
	    }

	    if (!chksubmit()) {
	        Tpl::output('appointment_info',$appointment_info);

	        //显示支付接口列表
	        $payment_list = Model('payment')->getPaymentOpenList();
	        //去掉预存款和货到付款
	        foreach ($payment_list as $key => $value){
	            if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
	               unset($payment_list[$key]); 
	            }
	        }
	        Tpl::output('payment_list',$payment_list);

	        Tpl::showpage('appointment.receive_pay');exit();
	    }

	    //下单，支付被冻结的预存款
	    $pd_amount = floatval($appointment_info['pd_amount']);
	    if ($pd_amount > 0) {
	        $model_pd = Model('predeposit');
	        $data_pd = array();
	        $data_pd['member_id'] = $appointment_info['buyer_id'];
	        $data_pd['member_name'] = $appointment_info['buyer_name'];
	        $data_pd['amount'] = $pd_amount;
	        $data_pd['appointment_sn'] = $appointment_info['appointment_sn'];
	        $model_pd->changePd('appointment_comb_pay',$data_pd);
	    }

	    //更新订单状态
	    $update_appointment = array();
	    $update_appointment['appointment_state'] = appointment_STATE_PAY;
	    $update_appointment['payment_time'] = strtotime($_POST['payment_time']);
	    $update_appointment['payment_code'] = $_POST['payment_code'];
	    $update = $model_appointment->editappointment($update_appointment,array('appointment_id'=>$appointment_id));
	    if (!$update) {
	        throw new Exception(L('nc_common_save_fail'));
	    }
	
	    //添加订单日志
	    $data = array();
	    $data['appointment_id'] = $appointment_id;
	    $data['log_role'] = 'system';
	    $data['log_user'] = $this->admin_info['name'];
	    $data['log_msg'] = L('appointment_log_receive_paye').' ( 支付平台交易号 : '.$_POST['trade_no'].' )';
	    $data['log_appointmentstate'] = appointment_STATE_PAY;
	    $model_appointment->addappointmentLog($data);

	    $this->log(L('appointment_change_received').','.L('appointment_number').':'.$appointment_info['appointment_sn'],1);
	}

	/**
	 * 查看订单
	 *
	 */
	public function show_appointmentOp(){
	    $appointment_id = intval($_GET['appointment_id']);
	    if($appointment_id <= 0 ){
	        showMessage(L('miss_appointment_number'));
	    }
        $model_appointment	= Model('appointment');
        $appointment_info	= $model_appointment->getappointmentInfo(array('appointment_id'=>$appointment_id),array('appointment_doctors','appointment_common','clic'));

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

		//卖家发货信息
		if (!empty($appointment_info['extend_appointment_common']['daddress_id'])) {
		    $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$appointment_info['extend_appointment_common']['daddress_id']));
		    Tpl::output('daddress_info',$daddress_info);
		}

		Tpl::output('appointment_info',$appointment_info);
        Tpl::showpage('appointment.view');
	}

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$lang	= Language::getLangContent();

	    $model_appointment = Model('appointment');
        $condition	= array();
        if($_GET['appointment_sn']) {
        	$condition['appointment_sn'] = $_GET['appointment_sn'];
        }
        if($_GET['clic_name']) {
            $condition['clic_name'] = $_GET['clic_name'];
        }
        if(in_array($_GET['appointment_state'],array('0','10','20','30','40','50'))){
        	$condition['appointment_state'] = $_GET['appointment_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

		if (!is_numeric($_GET['curpage'])){		
			$count = $model_appointment->getappointmentCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?act=appointment&op=index');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model_appointment->getappointmentList($condition,'','*','appointment_id desc',self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_appointment->getappointmentList($condition,'','*','appointment_id desc',"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_clic'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_clicid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'NC'.$v['appointment_sn']);
			$tmp[] = array('data'=>$v['clic_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['appointment_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('data'=>appointmentPaymentName($v['payment_code']));
			$tmp[] = array('data'=>appointmentState($v));
			$tmp[] = array('data'=>$v['clic_id']);
			$tmp[] = array('data'=>$v['buyer_id']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_appointment'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_appointment'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}