<?php
/**
 * 积分礼品购物车操作
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class pointcartControl extends BaseHomeControl {
	public function __construct() {
		parent::__construct();
		/**
		 * 读取语言包
		 */
		Language::read('home_pointcart');
		/**
		 * 判断系统是否开启积分和积分兑换功能
		 */
		if ($GLOBALS['setting_config']['points_isuse'] != 1 || $GLOBALS['setting_config']['pointprod_isuse'] != 1){
			showMessage(Language::get('pointcart_unavailable'),'index.php','html','error');
		}
		//验证是否登录
		if ($_SESSION['is_login'] != '1'){
			showMessage(Language::get('pointcart_unlogin_error'),'index.php?act=login','html','error');
		}
	}
	/**
	 * 积分礼品购物车首页
	 *
	 * @param
	 * @return
	 */
	public function indexOp() {
		$cart_doctors	= array();
		$pointcart_model	= Model('pointcart');
		$cart_doctors	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		$cart_array	= array();
		if(is_array($cart_doctors) and !empty($cart_doctors)) {
			$pdoctors_pointall = 0;
			foreach ($cart_doctors as $val) {
				$val['pdoctors_pointone']			= intval($val['pdoctors_points']) * intval($val['pdoctors_choosenum']);
				$cart_array[] = $val;
				$pdoctors_pointall = $pdoctors_pointall + $val['pdoctors_pointone'];
			}
			Tpl::output('pdoctors_pointall',$pdoctors_pointall);
			Tpl::output('cart_array',$cart_array);
		}
		Tpl::showpage('pointcart_list');
	}
	/**
	 * 购物车添加礼品
	 *
	 * @param
	 * @return
	 */
	public function addOp() {
		$pgid	= intval($_GET['pgid']);
		$quantity	= intval($_GET['quantity']);
		if($pgid <= 0 || $quantity <= 0) {
			showMessage(Language::get('pointcart_cart_addcart_fail'),'index.php?act=pointprod','html','error');
		}
		//验证积分礼品是否存在购物车中
		$pointcart_model	= Model('pointcart');
		$check_cart	= $pointcart_model->getPointCartInfo(array('pdoctors_id'=>$pgid,'pmember_id'=>$_SESSION['member_id']));
		if(!empty($check_cart)) {
			@header("Location:index.php?act=pointcart");exit;
		}

		$pointprod_model = Model('pointprod');
		//验证积分礼品是否存在
		$prod_info	= $pointprod_model->getPointProdInfo(array('pdoctors_id'=>$pgid,'pdoctors_show'=>'1','pdoctors_state'=>'0'));
		if (!is_array($prod_info) || count($prod_info)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		//验证积分礼品兑换状态
		$ex_state = $pointprod_model->getPointProdExstate($prod_info);
		switch ($ex_state){
			case 'willbe':
				showMessage(Language::get('pointcart_cart_addcart_willbe'),getReferer(),'html','error');
				break;
			case 'end':
				showMessage(Language::get('pointcart_cart_addcart_end'),getReferer(),'html','error');
				break;
		}
		//验证兑换数量是否合法
		$quantity = $pointprod_model->getPointProdExnum($prod_info,$quantity);
		if ($quantity <= 0){
			showMessage(Language::get('pointcart_cart_addcart_end'),getReferer(),'html','error');
		}
		//计算消耗积分总数
		$points_all = intval($prod_info['pdoctors_points'])*intval($quantity);
		//验证积分数是否足够
		$member_model = Model('member');
		$member_info = $member_model->getMemberInfo(array('member_id'=>$_SESSION['member_id']),'member_points');
		if (intval($member_info['member_points']) < $points_all){
			showMessage(Language::get('pointcart_cart_addcart_pointshort'),getReferer(),'html','error');
		}
		$array						= array();
		$array['pmember_id']		= $_SESSION['member_id'];
		$array['pdoctors_id']			= $prod_info['pdoctors_id'];
		$array['pdoctors_name']		= $prod_info['pdoctors_name'];
		$array['pdoctors_points']		= $prod_info['pdoctors_points'];
		$array['pdoctors_choosenum']	= $quantity;
		$array['pdoctors_image']		= $prod_info['pdoctors_image'];
		$cart_state = $pointcart_model->addPointCart($array);
		@header("Location:index.php?act=pointcart");
		exit;
	}
	/**
	 * 积分礼品购物车更新礼品数量
	 *
	 * @param
	 * @return
	 */
	public function updateOp() {
		$pcart_id	= intval($_GET['pc_id']);
		$quantity	= intval($_GET['quantity']);
		//兑换失败提示
		$msg = Language::get('pointcart_cart_modcart_fail');
		//转码
		if (strtoupper(CHARSET) == 'GBK'){
			$msg = Language::getUTF8($msg);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		}
		if($pcart_id <= 0 || $quantity <= 0) {
			echo json_encode(array('msg'=>$msg));
			die;
		}
		//验证礼品购物车信息是否存在
		$pointcart_model	= Model('pointcart');
		$cart_info	= $pointcart_model->getPointCartInfo(array('pcart_id'=>$pcart_id,'pmember_id'=>$_SESSION['member_id']));
		if (!is_array($cart_info) || count($cart_info)<=0){
			echo json_encode(array('msg'=>$msg)); die;
		}
		$pointprod_model = Model('pointprod');
		//验证积分礼品是否存在
		$prod_info	= $pointprod_model->getPointProdInfo(array('pdoctors_id'=>$cart_info['pdoctors_id'],'pdoctors_show'=>'1','pdoctors_state'=>'0'));
		if (!is_array($prod_info) || count($prod_info)<=0){
			//删除积分礼品兑换信息
			$pointcart_model->dropPointCartById($pcart_id);
			echo json_encode(array('msg'=>$msg)); die;
		}
		//验证积分礼品兑换状态
		$ex_state = $pointprod_model->getPointProdExstate($prod_info);
		switch ($ex_state){
			case 'going':
				//验证兑换数量是否合法
				$quantity = $pointprod_model->getPointProdExnum($prod_info,$quantity);
				if ($quantity <= 0){
					//删除积分礼品兑换信息
					$pointcart_model->dropPointCartById($pcart_id);
					echo json_encode(array('msg'=>$msg)); die;
				}
				break;
			default:
				//删除积分礼品兑换信息
				$pointcart_model->dropPointCartById($pcart_id);
				echo json_encode(array('msg'=>$msg)); die;
				break;
		}
		/**
		 * 更新礼品购物车内单个礼品数量
		 */
		$cart_state = $pointcart_model->updatePointCart(array('pdoctors_choosenum'=>$quantity),array('pcart_id'=>$pcart_id,'pmember_id'=>$_SESSION['member_id']));
		if ($cart_state) {
			//计算总金额
			$all_price	= $this->amountOp();
			echo json_encode(array('done'=>'true','subtotal'=>$prod_info['pdoctors_points']*$quantity,'amount'=>$all_price,'quantity'=>$quantity));
			die;
		}
	}
	/**
	 * 积分礼品购物车删除单个礼品
	 *
	 * @param
	 * @return
	 */
	public function dropOp() {
		$pcart_id	= intval($_GET['pc_id']);
		if($pcart_id==0) {
			die;
		}
		$pointcart_model	= Model('pointcart');
		$drop_state	= $pointcart_model->dropPointCartById($pcart_id);
		die;
	}
	/**
	 * 已选择兑换礼品总积分
	 * @return 积分值
	 */
	private function amountOp() {
		$pointcart_model	= Model('pointcart');
		$cart_doctors	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		$all_points	= 0;
		if(is_array($cart_doctors) and !empty($cart_doctors)) {
			foreach ($cart_doctors as $val) {
				$all_points	= $val['pdoctors_points'] * $val['pdoctors_choosenum'] + $all_points;
			}
		}
		return $all_points;
	}
	/**
	 * 兑换订单流程第一步
	 */
	public function step1Op(){
		//获取符合条件的兑换礼品和总积分及运费
		$pointprod_arr = $this->getLegalPointdoctors();
		Tpl::output('pointprod_arr',$pointprod_arr);

		//实例化收货地址模型
		$mode_address	= Model('address');
		$address_list	= $mode_address->getAddressList(array('member_id'=>$_SESSION['member_id']), 'address_id desc');
		Tpl::output('address_list',$address_list);

		Tpl::showpage('pointcart_step1');
	}
	/**
	 * 兑换订单流程第二步
	 */
	public function step2Op() {
		//获取符合条件的兑换礼品和总积分及运费
		$pointprod_arr = $this->getLegalPointdoctors();
		//验证积分数是否足够
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_points');
		if (intval($member_info['member_points']) < $pointprod_arr['pdoctors_pointall']){
			showMessage(Language::get('pointcart_cart_addcart_pointshort'),'index.php?act=member_points','html','error');
		}
		//实例化兑换订单模型
		$pointappointment_model= Model('pointappointment');
		//实例化店铺模型
		$appointment_array		= array();
		$appointment_array['point_appointmentsn']		= $pointappointment_model->point_snappointment();
		$appointment_array['point_buyerid']		= $_SESSION['member_id'];
		$appointment_array['point_buyername']		= $_SESSION['member_name'];
		$appointment_array['point_buyeremail']	= $_SESSION['member_email'];
		$appointment_array['point_addtime']		= time();
		$appointment_array['point_outsn']			= $pointappointment_model->point_outSnappointment();
		$appointment_array['point_allpoint']		= $pointprod_arr['pdoctors_pointall'];
		$appointment_array['point_appointmentamount']	= $pointprod_arr['pdoctors_freightall'];
		$appointment_array['point_shippingcharge']= $pointprod_arr['pdoctors_freightcharge'];
		$appointment_array['point_shippingfee']	= $pointprod_arr['pdoctors_freightall'];
		$appointment_array['point_appointmentmessage']	= trim($_POST['pcart_message']);
		$appointment_array['point_appointmentstate']	= 20;//状态为已经确认收款
		$appointment_id	= $pointappointment_model->addPointappointment($appointment_array);
		if (!$appointment_id){
			showMessage(Language::get('pointcart_step2_fail'),'index.php?act=pointcart','html','error');
		}
		//扣除会员积分
		$points_model = Model('points');
		$insert_arr['pl_memberid'] = $_SESSION['member_id'];
		$insert_arr['pl_membername'] = $_SESSION['member_name'];
		$insert_arr['pl_points'] = -$pointprod_arr['pdoctors_pointall'];
		$insert_arr['point_appointmentsn'] = $appointment_array['point_appointmentsn'];
		$points_model->savePointsLog('pointappointment',$insert_arr,true);

		//添加订单中的礼品信息
		$pointprod_model = Model('pointprod');
		if(is_array($pointprod_arr['pointprod_list']) && count($pointprod_arr['pointprod_list'])>0) {
			$output_doctors_name = array();
			foreach ($pointprod_arr['pointprod_list'] as $val) {
				$appointment_doctors_array	= array();
				$appointment_doctors_array['point_appointmentid']		= $appointment_id;
				$appointment_doctors_array['point_doctorsid']		= $val['pdoctors_id'];
				$appointment_doctors_array['point_doctorsname']	= $val['pdoctors_name'];
				$appointment_doctors_array['point_doctorspoints']	= $val['pdoctors_points'];
				$appointment_doctors_array['point_doctorsnum']	= $val['quantity'];
				$appointment_doctors_array['point_doctorsimage']	= $val['pdoctors_image'];
				$pointappointment_model->addPointappointmentProd($appointment_doctors_array);

				if (count($output_doctors_name)<3) $output_doctors_name[] = $val['pdoctors_name'];

				//更新积分礼品库存
				$pointprod_uparr = array();
				$pointprod_uparr['pdoctors_salenum'] = array('value'=>$val['quantity'],'sign'=>'increase');
				$pointprod_uparr['pdoctors_storage'] = array('value'=>$val['quantity'],'sign'=>'decrease');
				$pointprod_model->updatePointProd($pointprod_uparr,array('pdoctors_id'=>$val['pdoctors_id']));
				unset($pointprod_uparr);
				unset($appointment_doctors_array);
			}
		}
		//清除购物车信息
		$pointcart_model = Model('pointcart');

		//保存买家收货地址
		$address_model		= Model('address');
		if(intval($_POST['address_options']) > 0) {
			$address_info = $address_model->getOneAddress(intval($_POST['address_options']));
			//sql注入过滤转义
			if (!empty($address_info) && !get_magic_quotes_gpc()){
				foreach ($address_info as $k=>$v){
					$address_info[$k] = addslashes(trim($v));
				}
			}
		}
		//添加订单收货地址
		if (is_array($address_info) && count($address_info)>0){
			$address_array		= array();
			$address_array['point_appointmentid']		= $appointment_id;
			$address_array['point_truename']	= $address_info['true_name'];
			$address_array['point_areaid']		= $address_info['area_id'];
			$address_array['point_areainfo']	= $address_info['area_info'];
			$address_array['point_address']		= $address_info['address'];
			$address_array['point_zipcode']		= $address_info['zip_code'];
			$address_array['point_telphone']	= $address_info['tel_phone'];
			$address_array['point_mobphone']	= $address_info['mob_phone'];
			$pointappointment_model->addPointappointmentAddress($address_array);
		}
		@header("Location:index.php?act=pointcart&op=step3&appointment_id=".$appointment_id);
	}
	/**
	 * 流程第三步
	 */
	public function step3Op($appointment_arr=array()) {
	    $pointappointment_model = Model('pointappointment');
		$appointment_id = intval($_GET['appointment_id']);
		if ($appointment_id <= 0){
			showMessage(Language::get('pointcart_record_error'),'index.php','html','error');
		}
		$condition = array();
		$condition['point_appointmentid'] = "$appointment_id";
		$condition['point_buyerid'] = "{$_SESSION['member_id']}";
		$appointment_info = $pointappointment_model->getPointappointmentInfo($condition,'simple');
		$appointment_arr['appointment_id'] = $appointment_info['point_appointmentid'];
		$appointment_arr['appointment_sn'] = $appointment_info['point_appointmentsn'];
		$appointment_arr['pdoctors_pointall'] = $appointment_info['point_allpoint'];
		$appointment_arr['pdoctors_freightcharge'] = $appointment_info['point_shippingcharge'];
		$appointment_arr['pdoctors_freightall'] = $appointment_info['point_shippingfee'];

		Tpl::output('appointment_arr',$appointment_arr);
		Tpl::showpage('pointcart_step2');
	}
	/**
	 * 验证购物车商品是否符合兑换条件，并返回符合条件的积分礼品和对应的总积分总运费及其他信息
	 * @return array
	 */
	private function getLegalPointdoctors(){
		$return_array = array();
		//获取礼品购物车内信息
		$pointcart_model	= Model('pointcart');
		$cart_doctors	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		if(!is_array($cart_doctors) || count($cart_doctors)<=0) {
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_doctors_new = array();
		foreach ($cart_doctors as $val) {
			$cart_doctors_new[$val['pdoctors_id']] = $val;
		}
		$cart_doctorsid_arr = array_keys($cart_doctors_new);
		if(!is_array($cart_doctorsid_arr) || count($cart_doctorsid_arr)<=0) {
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_doctorsid_str = implode(',',$cart_doctorsid_arr);
		unset($cart_doctorsid_arr);
		unset($cart_doctors);

		//查询积分礼品信息
		$pointprod_model = Model('pointprod');
		$pointprod_list = $pointprod_model->getPointProdList(array('pdoctors_id_in'=>$cart_doctorsid_str,'pdoctors_show'=>'1','pdoctors_state'=>'0'));
		if (!is_array($pointprod_list) || count($pointprod_list)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_delid_arr = array();
		$pdoctors_pointall = 0;//积分总数
		$pdoctors_freightall = 0;//运费总数
		$pdoctors_freightcharge = false;//是否需要支付运费
		foreach ($pointprod_list as $k=>$v){
			$pointprod_list[$k] = $v;
			//验证积分礼品兑换状态
			$ex_state = $pointprod_model->getPointProdExstate($v);
			switch ($ex_state){
				case 'going':
					//验证兑换数量是否合法
					$quantity = $pointprod_model->getPointProdExnum($v,$cart_doctors_new[$v['pdoctors_id']]['pdoctors_choosenum']);
					if ($quantity <= 0){
						//删除积分礼品兑换信息
						$cart_delid_arr[] = $cart_doctors_new[$v['pdoctors_id']]['pcart_id'];
						unset($pointprod_list[$k]);
					}else {
						$pointprod_list[$k]['quantity'] = $quantity;
						//计算单件礼品积分数
						$pointprod_list[$k]['onepoints'] = intval($quantity)*intval($v['pdoctors_points']);
						$pdoctors_pointall = $pdoctors_pointall + $pointprod_list[$k]['onepoints'];
						//计算运费
						if ($v['pdoctors_freightcharge'] == 1){
							$pdoctors_freightcharge = true;
							$pdoctors_freightall = $pdoctors_freightall + $v['pdoctors_freightprice'];
						}
					}
					break;
				default:
					//删除积分礼品兑换信息
					$cart_delid_arr[] = $cart_doctors_new[$v['pdoctors_id']]['pcart_id'];
					unset($pointprod_list[$k]);
					break;
			}
		}
		//删除不符合条件的礼品购物车信息
		if (is_array($cart_delid_arr) && count($cart_delid_arr)>0){
			$pointcart_model->dropPointCartById($cart_delid_arr);
		}
		if (!is_array($pointprod_list) || count($pointprod_list)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$pdoctors_freightall = ncPriceFormat($pdoctors_freightall);
		$return_array = array('pointprod_list'=>$pointprod_list,'pdoctors_freightcharge'=>$pdoctors_freightcharge,'pdoctors_pointall'=>$pdoctors_pointall,'pdoctors_freightall'=>$pdoctors_freightall);

		return $return_array;
	}
	/**
	 * 递归去除转义
	 *
	 * @param array/string $value
	 * @return array/string
	 */
	public function stripslashes_deep($value){
	    $value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
	    return $value;
	}
}
