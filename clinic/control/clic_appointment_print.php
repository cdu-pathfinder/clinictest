<?php
/**
 * 订单打印
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class clic_appointment_printControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_printappointment');
	}

	/**
	 * 查看订单
	 */
	public function indexOp() {
		$appointment_id	= intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('wrong_argument'),'','html','error');
		}
		$appointment_model = Model('appointment');
		$condition['appointment_id'] = $appointment_id;
		$condition['clic_id'] = $_SESSION['clic_id'];
		$appointment_info = $appointment_model->getappointmentInfo($condition,array('appointment_common','appointment_doctors'));
		if (empty($appointment_info)){
			showMessage(Language::get('member_printappointment_appointmenterror'),'','html','error');
		}
		Tpl::output('appointment_info',$appointment_info);

		//卖家信息
		$model_clic	= Model('clic');
		$clic_info		= $model_clic->getclicInfoByID($appointment_info['clic_id']);
		if (!empty($clic_info['clic_label'])){
			if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_label'])){
				$clic_info['clic_label'] = UPLOAD_SITE_URL.DS.ATTACH_clic.DS.$clic_info['clic_label'];
			}else {
				$clic_info['clic_label'] = '';
			}
		}
		if (!empty($clic_info['clic_stamp'])){
			if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_stamp'])){
				$clic_info['clic_stamp'] = UPLOAD_SITE_URL.DS.ATTACH_clic.DS.$clic_info['clic_stamp'];
			}else {
				$clic_info['clic_stamp'] = '';
			}
		}	
		Tpl::output('clic_info',$clic_info);

		//订单商品
		$model_appointment = Model('appointment');
		$condition = array();
		$condition['appointment_id'] = $appointment_id;
		$condition['clic_id'] = $_SESSION['clic_id'];		
		$doctors_new_list = array();
		$doctors_all_num = 0;
		$doctors_total_price = 0;
		if (!empty($appointment_info['extend_appointment_doctors'])){
			$doctors_count = count($appointment_doctors_list);
			$i = 1;
			foreach ($appointment_info['extend_appointment_doctors'] as $k => $v){
				$v['doctors_name'] = str_cut($v['doctors_name'],100);
				$doctors_all_num += $v['doctors_num'];				
				$v['doctors_all_price'] = ncPriceFormat($v['doctors_num'] * $v['doctors_price']);
				$doctors_total_price += $v['doctors_all_price'];
				$doctors_new_list[ceil($i/4)][$i] = $v;
				$i++;
			}
		}
		//优惠金额
		$promotion_amount = $doctors_total_price - $appointment_info['doctors_amount'];
		//运费
		$appointment_info['shipping_fee'] = $appointment_info['shipping_fee'];
		Tpl::output('promotion_amount',$promotion_amount);
		Tpl::output('doctors_all_num',$doctors_all_num);
		Tpl::output('doctors_total_price',ncPriceFormat($doctors_total_price));
		Tpl::output('doctors_list',$doctors_new_list);
		Tpl::showpage('clic_appointment.print',"null_layout");
	}
}
