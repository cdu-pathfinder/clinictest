<?php
/**
 * 结算管理
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class clic_billControl extends BaseclinicerControl {
    /**
     * 每次导出多少条记录
     * @var unknown
     */
    const EXPORT_SIZE = 1000;

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
    }

	/**
	 * 结算列表
	 *
	 */
    public function indexOp() {
        $model_bill = Model('bill');
        $condition = array();
        $condition['ob_clic_id'] = $_SESSION['clic_id'];
        if (preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
            $condition['ob_no'] = $_GET['ob_no'];
        }
        if (is_numeric($_GET['bill_state'])) {
            $condition['ob_state'] = intval($_GET['bill_state']);
        }
        $bill_list = $model_bill->getappointmentBillList($condition,'*',12,'ob_state asc,ob_no asc');
        Tpl::output('bill_list',$bill_list);
        Tpl::output('show_page',$model_bill->showpage());

        $this->profile_menu('list','list');
        Tpl::showpage('clic_bill.index');
    }

	/**
	 * 查看结算单详细
	 *
	 */
	public function show_billOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showMessage('参数错误','','html','error');
		}
		//原版
		// if (substr($_GET['ob_no'],6) != $_SESSION['clic_id']){
		if (substr($_GET['ob_no'],8) != $_SESSION['clic_id']){
			showMessage('参数错误','','html','error');
		}
        $model_bill = Model('bill');
		$bill_info = $model_bill->getappointmentBillInfo(array('ob_no'=>$_GET['ob_no']));
		if (!$bill_info){
			showMessage('参数错误','','html','error');
		}

		$appointment_condition = array();
		$appointment_condition['appointment_state'] = appointment_STATE_SUCCESS;
		$appointment_condition['clic_id'] = $bill_info['ob_clic_id'];
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
		$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
		$start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
		if ($if_end_date || $if_end_date) {
		    $appointment_condition['finnshed_time'] = array('time',array($start_unixtime,$end_unixtime));
		} else {
		    $appointment_condition['finnshed_time'] = array('time',array($bill_info['ob_start_date'],$bill_info['ob_end_date']));
		}
		if ($_GET['type'] =='refund'){
		    if (preg_match('/^\d{8,20}$/',$_GET['query_appointment_no'])) {
		        $appointment_condition['refund_sn'] = $_GET['query_appointment_no'];
		    }
			//退款订单列表
		    $model_refund = Model('refund_return');
			$refund_condition = array();
			$refund_condition['clinicer_state'] = 2;
			$refund_condition['clic_id'] = $bill_info['ob_clic_id'];
			$refund_condition['doctors_id'] = array('gt',0);
			$refund_condition['admin_time'] = $appointment_condition['finnshed_time'];
			if (preg_match('/^\d{8,20}$/',$_GET['query_appointment_no'])) {
			    $refund_condition['refund_sn'] = $_GET['query_appointment_no'];
			}
			$refund_list = $model_refund->getRefundReturnList($refund_condition,20,'*,refund_amount*commis_rate/100 as commis_amount');
			if (is_array($refund_list) && count($refund_list) == 1 && $refund_list[0]['refund_id'] == '') {
			    $refund_list = array();
			}
			//取返还佣金
			Tpl::output('refund_list',$refund_list);
			Tpl::output('show_page',$model_refund->showpage());
			$sub_tpl_name = 'clic_bill.show.refund_list';
			$this->profile_menu('show','refund_list');
		} elseif ($_GET['type'] == 'cost') {
		    //店铺费用
		    $model_clic_cost = Model('clic_cost');
		    $cost_condition = array();
		    $cost_condition['cost_clic_id'] = $bill_info['ob_clic_id'];
		    $cost_condition['cost_time'] = $appointment_condition['finnshed_time'];
		    $clic_cost_list = $model_clic_cost->getclicCostList($cost_condition,20);
		    
		    //取得店铺名字
		    $clic_info = Model('clic')->getclicInfoByID($bill_info['ob_clic_id']);
		    Tpl::output('cost_list',$clic_cost_list);
		    Tpl::output('clic_info',$clic_info);
		    Tpl::output('show_page',$model_clic_cost->showpage());
		    $sub_tpl_name = 'clic_bill.show.cost_list';
		    $this->profile_menu('show','cost_list');

		} else {

		    if (preg_match('/^\d{8,20}$/',$_GET['query_appointment_no'])) {
		        $appointment_condition['appointment_sn'] = $_GET['query_appointment_no'];
		    }
		    //订单列表
		    $model_appointment = Model('appointment');
		    $appointment_list = $model_appointment->getappointmentList($appointment_condition,20);

		    //然后取订单商品佣金
		    $appointment_id_array = array();
		    if (is_array($appointment_list)) {
		        foreach ($appointment_list as $appointment_info) {
		            $appointment_id_array[] = $appointment_info['appointment_id'];
		        }
		    }
		    $appointment_doctors_condition = array();
		    $appointment_doctors_condition['appointment_id'] = array('in',$appointment_id_array);
		    $field = 'SUM(doctors_pay_price*commis_rate/100) as commis_amount,appointment_id';
		    $commis_list = $model_appointment->getappointmentdoctorsList($appointment_doctors_condition,$field,null,null,'','appointment_id','appointment_id');
		    Tpl::output('commis_list',$commis_list);
		    Tpl::output('appointment_list',$appointment_list);
		    Tpl::output('show_page',$model_appointment->showpage());
		    $sub_tpl_name = 'clic_bill.show.appointment_list';
		    $this->profile_menu('show','appointment_list');
		}

		Tpl::output('sub_tpl_name',$sub_tpl_name);
		Tpl::output('bill_info',$bill_info);
		Tpl::showpage('clic_bill.show');		
	}

	/**
	 * 打印结算单
	 *
	 */
	public function bill_printOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showMessage('参数错误','','html','error');
		}
		if (substr($_GET['ob_no'],6) != $_SESSION['clic_id']){
			showMessage('参数错误','','html','error');
		}
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_no'] = $_GET['ob_no'];
		$condition['ob_state'] = BILL_STATE_SUCCESS;
		$bill_info = $model_bill->getappointmentBillInfo($condition);
		if (!$bill_info){
			showMessage('参数错误','','html','error');
		}

		Tpl::output('bill_info',$bill_info);
		Tpl::showpage('clic_bill.print','null_layout');
	}

	/**
	 * 店铺确认出账单
	 *
	 */
	public function confirm_billOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showDialog(L('nc_common_op_fail'),'','error');
		}
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_no'] = $_GET['ob_no'];
		$condition['ob_clic_id'] = $_SESSION['clic_id'];
		$condition['ob_state'] = BILL_STATE_CREATE;
		$update = $model_bill->editappointmentBill(array('ob_state'=>BILL_STATE_clic_COFIRM),$condition);
		if ($update){
			showDialog('确认成功','reload','succ');
		}else{
			showDialog(L('nc_common_op_fail'),'reload','error');
		}
	}

	/**
	 * 导出结算订单明细CSV
	 *
	 */
	public function export_appointmentOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showMessage('参数错误','','html','error');
		}
		if (substr($_GET['ob_no'],6) != $_SESSION['clic_id']){
		    showMessage('参数错误','','html','error');
		}

		$model_bill = Model('bill');
		$bill_info = $model_bill->getappointmentBillInfo(array('ob_no'=>$_GET['ob_no']));
		if (!$bill_info){
			showMessage('参数错误','','html','error');
		}

		$model_appointment = Model('appointment');
		$condition = array();
		$condition['appointment_state'] = appointment_STATE_SUCCESS;
		$condition['clic_id'] = $_SESSION['clic_id'];
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
		$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
		$start_unixtime = $if_end_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
		if ($if_end_date || $if_end_date) {
		    $condition['finnshed_time'] = array('time',array($start_unixtime,$end_unixtime));
		} else {
		    $condition['finnshed_time'] = array('time',array($bill_info['ob_start_date'],$bill_info['ob_end_date']));
		}
		if (!is_numeric($_GET['curpage'])){
		    $count = $model_appointment->getappointmentCount($condition);
    		$array = array();
    		if ($count > self::EXPORT_SIZE ){
    		    //显示下载链接
    		    $page = ceil($count/self::EXPORT_SIZE);
    		    for ($i=1;$i<=$page;$i++){
    		        $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
    		        $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
    		        $array[$i] = $limit1.' ~ '.$limit2 ;
    		    }
    		    Tpl::output('list',$array);
    		    Tpl::output('murl','index.php?act=clic_bill&op=show_bill&ob_no='.$_GET['ob_no']);
    		    Tpl::showpage('clic_export.excel');
    		    exit();
    		}else{
    		    //如果数量小，直接下载
    		    $data = $model_appointment->getappointmentList($condition,'','*','appointment_id desc',self::EXPORT_SIZE,array('appointment_doctors'));
    		}
		}else{
		    //下载
		    $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
		    $limit2 = self::EXPORT_SIZE;
		    $data = $model_appointment->getappointmentList($condition,'','*','appointment_id desc',"{$limit1},{$limit2}",array('appointment_doctors'));
		}

		$export_data = array();
		$export_data[0] = array('订单编号','订单金额','运费','佣金','下单日期','成交日期','买家','买家编号','商品');
		$appointment_totals = 0;
		$shipping_fee_totals = 0;
		$commis_totals = 0;
		$k = 0;
		foreach ($data as $v) {
		    //该订单算佣金
		    $field = 'SUM(doctors_pay_price*commis_rate/100) as commis_amount,appointment_id';
		    $commis_list = $model_appointment->getappointmentdoctorsList($appointment_doctors_condition,$field,null,null,'','appointment_id','appointment_id');
		    $export_data[$k+1][] = 'NC'.$v['appointment_sn'];
		    $appointment_totals += $export_data[$k+1][] = $v['appointment_amount'];
		    $shipping_fee_totals += $export_data[$k+1][] = $v['shipping_fee'];
		    $commis_totals += $export_data[$k+1][] = $commis_list[$v['appointment_id']]['commis_amount'];
		    $export_data[$k+1][] = date('Y-m-d',$v['add_time']);
		    $export_data[$k+1][] = date('Y-m-d',$v['finnshed_time']);
		    $export_data[$k+1][] = $v['buyer_name'];
		    $export_data[$k+1][] = $v['buyer_id'];
		    $doctors_string = '';
		    if (is_array($v['extend_appointment_doctors'])) {
                foreach ($v['extend_appointment_doctors'] as $v) {
                    $doctors_string .= $v['doctors_name'].'|单价:'.$v['doctors_price'].'|数量:'.$v['doctors_num'].'|实际支付:'.$v['doctors_pay_price'].'|佣金比例:'.$v['commis_rate'].'%';
                }
		    }
		    $export_data[$k+1][] = $doctors_string;
		    $k++;
		}
		$count = count($export_data);
		$export_data[$count][] = '合计';
		$export_data[$count][] = $appointment_totals;
		$export_data[$count][] = $shipping_fee_totals;
		$export_data[$count][] = $commis_totals;
		$csv = new Csv();
		$export_data = $csv->charset($export_data,CHARSET,'gbk');
		$csv->filename = $csv->charset('订单明细-',CHARSET).$_GET['ob_no'];
		$csv->export($export_data);
	}

	/**
	 * 导出结算退单明细CSV
	 *
	 */
	public function export_refund_appointmentOp(){
	    if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
	        showMessage('参数错误','','html','error');
	    }
	    if (substr($_GET['ob_no'],6) != $_SESSION['clic_id']){
	        showMessage('参数错误','','html','error');
	    }
	    $model_bill = Model('bill');
	    $bill_info = $model_bill->getappointmentBillInfo(array('ob_no'=>$_GET['ob_no']));
	    if (!$bill_info){
	        showMessage('参数错误','','html','error');
	    }

	    $model_refund = Model('refund_return');
		$condition = array();
		$condition['clinicer_state'] = 2;
		$condition['clic_id'] = $_SESSION['clic_id'];
		$condition['doctors_id'] = array('gt',0);
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
		$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
		$start_unixtime = $if_end_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
		if ($if_end_date || $if_end_date) {
		    $condition['admin_time'] = array('time',array($start_unixtime,$end_unixtime));
		} else {
		    $condition['admin_time'] = array('time',array($bill_info['ob_start_date'],$bill_info['ob_end_date']));
		}

	    if (!is_numeric($_GET['curpage'])){
	        $count = $model_refund->getRefundReturn($condition);
	        $array = array();
	        if ($count > self::EXPORT_SIZE ){	//显示下载链接
	            $page = ceil($count/self::EXPORT_SIZE);
	            for ($i=1;$i<=$page;$i++){
	                $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
	                $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
	                $array[$i] = $limit1.' ~ '.$limit2 ;
	            }
	            Tpl::output('list',$array);
	            Tpl::output('murl','index.php?act=clic_bill&op=show_bill&query_type=refund&ob_no='.$_GET['ob_no']);
	            Tpl::showpage('clic_export.excel');
	            exit();
	        }else{
	            //如果数量小，直接下载
	            $data = $model_refund->getRefundReturnList($condition,'','*,refund_amount*commis_rate/100 as commis_amount',self::EXPORT_SIZE);
	        }
	    }else{
	        //下载
	        $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
	        $limit2 = self::EXPORT_SIZE;
	        $data = $model_refund->getRefundReturnList(condition,'','*,refund_amount*commis_rate/100 as commis_amount',"{$limit1},{$limit2}");
	    }
	    if (is_array($data) && count($data) == 1 && $data[0]['refund_id'] == '') {
	        $refund_list = array();
	    }
	    $export_data = array();
	    $export_data[0] = array('退单编号','订单编号','退单金额','退单佣金','类型','退款日期','买家','买家编号');
	    $refund_amount = 0;
	    $commis_totals = 0;
	    $k = 0;
	    foreach ($data as $v) {
	        $export_data[$k+1][] = 'NC'.$v['refund_sn'];
	        $export_data[$k+1][] = 'NC'.$v['appointment_sn'];
	        $refund_amount += $export_data[$k+1][] = $v['refund_amount'];
	        $commis_totals += $export_data[$k+1][] = ncPriceFormat($v['commis_amount']);
	        $export_data[$k+1][] = str_replace(array(1,2),array('退款','退货'),$v['refund_type']);
	        $export_data[$k+1][] = date('Y-m-d',$v['admin_time']);
	        $export_data[$k+1][] = $v['buyer_name'];
	        $export_data[$k+1][] = $v['buyer_id'];
	        $k++;
	    }
	    $count = count($export_data);
	    $export_data[$count][] = '';
	    $export_data[$count][] = '合计';
	    $export_data[$count][] = $refund_amount;
	    $export_data[$count][] = $commis_totals;
	    $csv = new Csv();
	    $export_data = $csv->charset($export_data,CHARSET,'gbk');
	    $csv->filename = $csv->charset('退单明细-',CHARSET).$_GET['ob_no'];
	    $csv->export($export_data);
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'list':
				$menu_array = array(
				1=>array('menu_key'=>'list','menu_name'=>Language::get('nc_member_path_bill_manage'), 'menu_url'=>'index.php?act=bill&op=list'),
				);
				break;
			case 'show':
				$menu_array = array(
				array('menu_key'=>'appointment_list','menu_name'=>'订单列表', 'menu_url'=>'index.php?act=clic_bill&op=show_bill&ob_no='.$_GET['ob_no']),
				array('menu_key'=>'refund_list','menu_name'=>'退款订单',	'menu_url'=>'index.php?act=clic_bill&op=show_bill&type=refund&ob_no='.$_GET['ob_no']),
				array('menu_key'=>'cost_list','menu_name'=>'促销费用',	'menu_url'=>'index.php?act=clic_bill&op=show_bill&type=cost&ob_no='.$_GET['ob_no'])
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
