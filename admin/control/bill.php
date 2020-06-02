<?php
/**
 * 结算管理
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
class billControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    private $links = array(
    	array('url'=>'act=bill&op=index','lang'=>'nc_manage'),
    );
    
    public function __construct(){
    	parent::__construct();
    }

    /**
     * 所有月份销量账单
     *
     */
    public function indexOp(){

        //检查是否需要生成上月及更早结算单的程序不再执行，执行量较大，放到任务计划中触发

        $condition = array();
    	if (preg_match('/^\d{4}$/',$_GET['query_year'],$match)) {
	        $condition['os_year'] = $_GET['query_year'];
	    }
        $model_bill = Model('bill');
        $bill_list = $model_bill->getappointmentStatisList($condition,'*',12,'os_month desc');
        Tpl::output('bill_list',$bill_list);
        Tpl::output('show_page',$model_bill->showpage());

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'index'));

        Tpl::showpage('bill_appointment_statis.index');
    }

	/**
	 * 某月所有店铺销量账单
	 *
	 */
	public function show_statisOp(){
		//原版
	    // if (!empty($_GET['os_month']) && !preg_match('/^20\d{4}$/',$_GET['os_month'],$match)) {
	    if (!empty($_GET['os_month']) && !preg_match('/^20\d{6}$/',$_GET['os_month'],$match)) {
	        showMessage('参数错误','','html','error');
	    }
	    $model_bill = Model('bill');
		$condtion = array();
		if (!empty($_GET['os_month'])) {
			//原版
		    // $condition['os_month'] = intval($_GET['os_month']);
		    $condition['ob_start_date'] = strtotime(intval($_GET['os_month']));
		}
		if (is_numeric($_GET['bill_state'])) {
		    $condition['ob_state'] = intval($_GET['bill_state']);
		}
		if (preg_match('/^\d{1,8}$/',$_GET['query_clic'])) {
			$condition['ob_clic_id'] = $_GET['query_clic'];
		}elseif ($_GET['query_clic'] != ''){
			$condition['ob_clic_name'] = $_GET['query_clic'];
		}
		$bill_list = $model_bill->getappointmentBillList($condition,'*',30,'ob_no desc');
		Tpl::output('bill_list',$bill_list);
		Tpl::output('show_page',$model_bill->showpage());

		//输出子菜单
		Tpl::output('top_link',$this->sublink($this->links,'index'));

		Tpl::showpage('bill_appointment_statis.show');
	}

	/**
	 * 某店铺某月订单列表
	 *
	 */
	public function show_billOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'],$match)) {
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
		$start_unixtime = $if_end_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
		if ($if_end_date || $if_end_date) {
		    $appointment_condition['finnshed_time'] = array('time',array($start_unixtime,$end_unixtime));
		} else {
		    $appointment_condition['finnshed_time'] = array('time',array($bill_info['ob_start_date'],$bill_info['ob_end_date']));
		}

		if ($_GET['query_type'] == 'refund') {
			//退款订单列表
		    $model_refund = Model('refund_return');
			$refund_condition = array();
			$refund_condition['clinicer_state'] = 2;
			$refund_condition['clic_id'] = $bill_info['ob_clic_id'];
			$refund_condition['doctors_id'] = array('gt',0);
			$refund_condition['admin_time'] = $appointment_condition['finnshed_time'];
			$refund_list = $model_refund->getRefundReturnList($refund_condition,20,'*,refund_amount*commis_rate/100 as commis_amount');
			if (is_array($refund_list) && count($refund_list) == 1 && $refund_list[0]['refund_id'] == '') {
			    $refund_list = array();
			}
			//取返还佣金
			Tpl::output('refund_list',$refund_list);
			Tpl::output('show_page',$model_refund->showpage());
			$sub_tpl_name = 'bill_appointment_bill.show.refund_list';
		} elseif ($_GET['query_type'] == 'cost') {

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
		    $sub_tpl_name = 'bill_appointment_bill.show.cost_list';
		    
		} else {

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
		    $sub_tpl_name = 'bill_appointment_bill.show.appointment_list';
		    
		}

        Tpl::output('tpl_name',$sub_tpl_name);
		Tpl::output('bill_info',$bill_info);
		Tpl::showpage('bill_appointment_bill.show');
	}

	public function bill_checkOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showMessage('参数错误','','html','error');
		}
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_no'] = $_GET['ob_no'];
		$condition['ob_state'] = BILL_STATE_clic_COFIRM;
		$update = $model_bill->editappointmentBill(array('ob_state'=>BILL_STATE_SYSTEM_CHECK),$condition);
		if ($update){
		    $this->log('审核账单,账单号：'.$_GET['ob_no'],1);
			showMessage('审核成功，账单进入付款环节');
		}else{
		    $this->log('审核账单，账单号：'.$_GET['ob_no'],0);
			showMessage('审核失败','','html','error');
		}
	}

	/**
	 * 账单付款
	 *
	 */
	public function bill_payOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
			showMessage('参数错误','','html','error');
		}
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_no'] = $_GET['ob_no'];
		$condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
		$bill_info = $model_bill->getappointmentBillInfo($condition);
		if (!$bill_info){
			showMessage('参数错误','','html','error');
		}
		if (chksubmit()){
			if (!preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['pay_date'])) {
				showMessage('参数错误','','html','error');
			}
			$input = array();
			$input['ob_pay_content'] = $_POST['pay_content'];
			$input['ob_pay_date'] = strtotime($_POST['pay_date']);
			$input['ob_state'] = BILL_STATE_SUCCESS;
			$update = $model_bill->editappointmentBill($input,$condition);
			if ($update){
			    $model_clic_cost = Model('clic_cost');
			    $cost_condition = array();
			    $cost_condition['cost_clic_id'] = $bill_info['ob_clic_id'];
			    $cost_condition['cost_state'] = 0;
			    $cost_condition['cost_time'] = array('time',array($bill_info['ob_start_date'],$bill_info['ob_end_date']));
			    $model_clic_cost->editclicCost(array('cost_state'=>1),$cost_condition);
			    $this->log('账单付款,账单号：'.$_GET['ob_no'],1);
				showMessage('保存成功','index.php?act=bill&op=show_statis&os_month='.$bill_info['os_month']);
			}else{
			    $this->log('账单付款,账单号：'.$_GET['ob_no'],1);
				showMessage('保存失败','','html','error');
			}
		}else{
			Tpl::showpage('bill.pay');
		}
	}

	/**
	 * 打印结算单
	 *
	 */
	public function bill_printOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
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

		Tpl::showpage('bill.print','null_layout');
	}	


	/**
	 * 导出平台月出账单表
	 *
	 */
	public function export_billOp(){
	    if (!empty($_GET['os_month']) && !preg_match('/^20\d{4}$/',$_GET['os_month'],$match)) {
	        showMessage('参数错误','','html','error');
	    }
	    $model_bill = Model('bill');
		$condition = array();
		if (!empty($_GET['os_month'])) {
		    $condition['os_month'] = intval($_GET['os_month']);
		}
		if (is_numeric($_GET['bill_state'])) {
		    $condition['ob_state'] = intval($_GET['bill_state']);
		}
		if (preg_match('/^\d{1,8}$/',$_GET['query_clic'])) {
			$condition['ob_clic_id'] = $_GET['query_clic'];
		}elseif ($_GET['query_clic'] != ''){
			$condition['ob_clic_name'] = $_GET['query_clic'];
		}
		if (!is_numeric($_GET['curpage'])){
		    $count = $model_bill->getappointmentBillCount($condition);
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
    		    Tpl::output('murl','index.php?act=bill&op=index');
    		    Tpl::showpage('export.excel');
    		    exit();
    		}else{
    		    //如果数量小，直接下载
    		    $data = $model_bill->getappointmentBillList($condition,'*','','ob_no desc',self::EXPORT_SIZE);
    		}
		}else{
		    //下载
		    $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
		    $limit2 = self::EXPORT_SIZE;
		    $data = $model_bill->getappointmentBillList($condition,'*','','ob_no desc',"{$limit1},{$limit2}");
		}

		$export_data = array();
		$export_data[0] = array('账单编号','开始日期','结束日期','订单金额','运费','佣金金额','退款金额','退还佣金','店铺费用','本期应结','出账日期','账单状态','店铺','店铺ID');
		$ob_appointment_totals = 0;
		$ob_shipping_totals = 0;
		$ob_commis_totals = 0;
		$ob_appointment_return_totals = 0;
		$ob_commis_return_totals = 0;
		$ob_clic_cost_totals = 0;
		$ob_result_totals = 0;
		foreach ($data as $k => $v) {
		    $export_data[$k+1][] = $v['ob_no'];
		    $export_data[$k+1][] = date('Y-m-d',$v['ob_start_date']);
		    $export_data[$k+1][] = date('Y-m-d',$v['ob_end_date']);
		    $ob_appointment_totals += $export_data[$k+1][] = $v['ob_appointment_totals'];
		    $ob_shipping_totals += $export_data[$k+1][] = $v['ob_shipping_totals'];
		    $ob_commis_totals += $export_data[$k+1][] = $v['ob_commis_totals'];
		    $ob_appointment_return_totals += $export_data[$k+1][] = $v['ob_appointment_return_totals'];
		    $ob_commis_return_totals += $export_data[$k+1][] = $v['ob_commis_return_totals'];
		    $ob_clic_cost_totals += $export_data[$k+1][] = $v['ob_clic_cost_totals'];
		    $ob_result_totals += $export_data[$k+1][] = $v['ob_result_totals'];
		    $export_data[$k+1][] = date('Y-m-d',$v['ob_create_date']);
		    $export_data[$k+1][] = billState($v['ob_state']);
		    $export_data[$k+1][] = $v['ob_clic_name'];
		    $export_data[$k+1][] = $v['ob_clic_id'];
		}
		$count = count($export_data);
		$export_data[$count][] = '';
		$export_data[$count][] = '';
		$export_data[$count][] = '合计';
		$export_data[$count][] = $ob_appointment_totals;
		$export_data[$count][] = $ob_commis_totals;
		$export_data[$count][] = $ob_appointment_return_totals;
		$export_data[$count][] = $ob_commis_return_totals;
		$export_data[$count][] = $ob_clic_cost_totals;
		$export_data[$count][] = $ob_result_totals;
		$csv = new Csv();
		$export_data = $csv->charset($export_data,CHARSET,'gbk');
		$csv->filename = $csv->charset('账单-',CHARSET).$_GET['os_month'];
		$csv->export($export_data);
	}

	/**
	 * 导出结算订单明细CSV
	 *
	 */
	public function export_appointmentOp(){
		if (!preg_match('/^20\d{5,12}$/',$_GET['ob_no'])) {
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
		$condition['clic_id'] = $bill_info['ob_clic_id'];
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
    		    Tpl::output('murl','index.php?act=bill&op=show_bill&ob_no='.$_GET['ob_no']);
    		    Tpl::showpage('export.excel');
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
		$export_data[0] = array('订单编号','订单金额','运费','佣金','下单日期','成交日期','商家','商家编号','买家','买家编号','商品');
		$appointment_totals = 0;
		$shipping_totals = 0;
		$commis_totals = 0;
		$k = 0;
		foreach ($data as $v) {
		    //该订单算佣金
		    $field = 'SUM(doctors_pay_price*commis_rate/100) as commis_amount,appointment_id';
		    $commis_list = $model_appointment->getappointmentdoctorsList($appointment_doctors_condition,$field,null,null,'','appointment_id','appointment_id');
		    $export_data[$k+1][] = 'NC'.$v['appointment_sn'];
		    $appointment_totals += $export_data[$k+1][] = $v['appointment_amount'];
		    $shipping_totals += $export_data[$k+1][] = $v['shipping_fee'];
		    $commis_totals += $export_data[$k+1][] = floatval($commis_list[$v['appointment_id']]['commis_amount']);
		    $export_data[$k+1][] = date('Y-m-d',$v['add_time']);
		    $export_data[$k+1][] = date('Y-m-d',$v['finnshed_time']);
		    $export_data[$k+1][] = $v['clic_name'];
		    $export_data[$k+1][] = $v['clic_id'];
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
		$export_data[$count][] = $shipping_totals;
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
	    $model_bill = Model('bill');
	    $bill_info = $model_bill->getappointmentBillInfo(array('ob_no'=>$_GET['ob_no']));
	    if (!$bill_info){
	        showMessage('参数错误','','html','error');
	    }

	    $model_refund = Model('refund_return');
		$condition = array();
		$condition['clinicer_state'] = 2;
		$condition['clic_id'] = $bill_info['ob_clic_id'];
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
	            Tpl::output('murl','index.php?act=bill&op=show_bill&query_type=refund&ob_no='.$_GET['ob_no']);
	            Tpl::showpage('export.excel');
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
	    $export_data[0] = array('退单编号','订单编号','退单金额','退单佣金','类型','退款日期','商家','商家编号','买家','买家编号');
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
	        $export_data[$k+1][] = $v['clic_name'];
	        $export_data[$k+1][] = $v['clic_id'];
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
}