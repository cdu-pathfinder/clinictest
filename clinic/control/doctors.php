<?php
/**
 * 前台商品
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

class doctorsControl extends BasedoctorsControl {
    public function __construct() {
        parent::__construct ();
        Language::read ( 'clic_doctors_index' );
    }

    /**
     * 单个商品信息页
     */
    public function indexOp() {
        $doctors_id = intval($_GET ['doctors_id']);
        
        // 商品详细信息
        $model_doctors = Model('doctors');
        $doctors_detail = $model_doctors->getdoctorsDetail($doctors_id, '*');
        $doctors_info = $doctors_detail['doctors_info'];
        if (empty($doctors_info)) {
            showMessage(L('doctors_index_no_doctors'), '', 'html', 'error');
        }
        
        $this->getclicInfo($doctors_info['clic_id']);

        Tpl::output('spec_list', $doctors_detail['spec_list']);
        Tpl::output('spec_image', $doctors_detail['spec_image']);
        Tpl::output('doctors_image', $doctors_detail['doctors_image']);
        Tpl::output('groupbuy_info', $doctors_detail['groupbuy_info']);
        Tpl::output('xianshi_info', $doctors_detail['xianshi_info']);
        Tpl::output('mansong_info', $doctors_detail['mansong_info']);

        // 生成缓存的键值
        $hash_key = $doctors_info['doctors_id'];
        // 先查找$hash_key缓存
        $cachekey_arr = array (
                'likenum',
                'sharenum'
        );
        if ($_cache = rcache($hash_key, 'doc')) {
            foreach ($_cache as $k => $v) {
                $doctors_info[$k] = $v;
            }
        } else {
            // 查询SNS中该商品的信息
            $snsdoctorsinfo = Model('sns_doctors')->getSNSdoctorsInfo(array('snsdoctors_doctorsid' => $doctors_info['doctors_id']), 'snsdoctors_likenum,snsdoctors_sharenum');
            $doctors_info['likenum'] = $snsdoctorsinfo['snsdoctors_likenum'];
            $doctors_info['sharenum'] = $snsdoctorsinfo['snsdoctors_sharenum'];
            
            $data = array();
            if (! empty ( $doctors_info )) {
                foreach ( $doctors_info as $k => $v ) {
                    if (in_array ( $k, $cachekey_arr )) {
                        $data [$k] = $v;
                    }
                }
            }
            // 缓存商品信息
            wcache ( $hash_key, $data, 'doc' );
        }
        
        // 检查是否为店主本人
        $clic_self = false;
        if (!empty($_SESSION['clic_id'])) {
            if ($doctors_info['clic_id'] == $_SESSION['clic_id']) {
                $clic_self = true;
            }
        }
        Tpl::output('clic_self',$clic_self );
        
        // 如果使用运费模板
        if ($doctors_info['transport_id'] > 0) {
            // 取得三种运送方式默认运费
            $model_transport = Model('transport');
            $transport = $model_transport->getExtendList(array('transport_id' => $doctors_info['transport_id'], 'is_default' => 1));
            if (!empty($transport) && is_array($transport)) {
                foreach ($transport as $v) {
                    $doctors_info[$v['type'] . "_price"] = $v['sprice'];
                }
            }
        }
        Tpl::output('doctors', $doctors_info);

        // 关联版式
        $plateid_array = array();
        if (!empty($doctors_info['plateid_top'])) {
            $plateid_array[] = $doctors_info['plateid_top'];
        }
        if (!empty($doctors_info['plateid_bottom'])) {
            $plateid_array[] = $doctors_info['plateid_bottom'];
        }
        if (!empty($plateid_array)) {
            $plate_array = Model('clic_plate')->getPlateList(array('plate_id' => array('in', $plateid_array), 'clic_id' => $doctors_info['clic_id']));
            $plate_array = array_under_reset($plate_array, 'plate_position', 2);
            Tpl::output('plate_array', $plate_array);
        }
        
        Tpl::output('clic_id', $doctors_info ['clic_id']);
        
        // 输出一级地区
        $area_list = array(1 => '北京', 2 => '天津', 3 => '河北', 4 => '山西', 5 => '内蒙古', 6 => '辽宁', 7 => '吉林', 8 => '黑龙江', 9 => '上海',
                            10 => '江苏', 11 => '浙江', 12 => '安徽', 13 => '福建', 14 => '江西', 15 => '山东', 16 => '河南', 17 => '湖北', 18 => '湖南',
                            19 => '广东', 20 => '广西', 21 => '海南', 22 => '重庆', 23 => '四川', 24 => '贵州', 25 => '云南', 26 => '西藏', 27 => '陕西',
                            28 => '甘肃', 29 => '青海', 30 => '宁夏', 31 => '新疆', 32 => '台湾', 33 => '香港', 34 => '澳门', 35 => '海外' 
                        );
        if (strtoupper(CHARSET) == 'GBK') {
            $area_list = Language::getGBK($area_list);
        }
        Tpl::output('area_list', $area_list);
        
        // 生成浏览过产品
        $cookievalue = $doctors_id . '-' . $doctors_info ['clic_id'];
        if (cookie('viewed_doctors')) {
            $string_viewed_doctors = decrypt(cookie('viewed_doctors'), MD5_KEY);
            if (get_magic_quotes_gpc()) {
                $string_viewed_doctors = stripslashes($string_viewed_doctors); // 去除斜杠
            }
            $vg_ca = @unserialize($string_viewed_doctors);
            $sign = true;
            if ( !empty($vg_ca) && is_array($vg_ca)) {
                foreach ($vg_ca as $vk => $vv) {
                    if ($vv == $cookievalue) {
                        $sign = false;
                    }
                }
            } else {
                $vg_ca = array();
            }
            
            if ($sign) {
                if (count($vg_ca) >= 6) {
                    $vg_ca[] = $cookievalue;
                    array_shift($vg_ca);
                } else {
                    $vg_ca[] = $cookievalue;
                }
            }
        } else {
            $vg_ca[] = $cookievalue;
        }
        $vg_ca = encrypt(serialize($vg_ca), MD5_KEY);
        setNcCookie('viewed_doctors', $vg_ca);
        
        //优先得到推荐商品
        $doctors_commend_list = $model_doctors->getdoctorsOnlineList(array('clic_id' => $doctors_info['clic_id'], 'doctors_commend' => 1), 'doctors_id,doctors_name,doctors_jingle,doctors_image,clic_id,doctors_price', 0, 'rand()', 5, 'doctors_commonid');
        Tpl::output('doctors_commend',$doctors_commend_list);
        
        
        // 当前位置导航
        $nav_link_list = Model('doctors_class')->getdoctorsClassNav($doctors_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $doctors_info['doctors_name']);
        Tpl::output('nav_link_list', $nav_link_list );

        //评价信息
        $doctors_evaluate_info = Model('evaluate_doctors')->getEvaluatedoctorsInfoBydoctorsID($doctors_id);
        Tpl::output('doctors_evaluate_info', $doctors_evaluate_info);
        
        $seo_param = array ();
        $seo_param['name'] = $doctors_info['doctors_name'];
        $seo_param['key'] = $doctors_info['doctors_keywords'];
        $seo_param['description'] = $doctors_info['doctors_description'];
        Model('seo')->type('doc')->param($seo_param)->show();
        Tpl::showpage('doctors');
    }

    private function get_btn_state($promotion_info) {
        $btn_state = array();
        $btn_state['btn_buynow'] = TRUE;
        $btn_state['btn_addcart'] = TRUE;

        if($promotion_info['group']) {
            $btn_state['btn_addcart'] = FALSE;
        }

        if($promotion_info['xianshi']) {
            if($promotion_info['xianshi']['start_time'] < TIMESTAMP) {
                $btn_state['btn_addcart'] = FALSE;
            }
        }
        return $btn_state;
    }

    /**
	 * 商品评论
	 */
	public function commentsOp() {
        $doctors_id = intval($_GET['doctors_id']);
        $this->_get_comments($doctors_id, $_GET['type'], 10);
		Tpl::showpage('doctors.comments','null_layout');
	}

    /**
     * 商品评价详细页
     */
    public function comments_listOp() {
        $doctors_id = intval($_GET ['doctors_id']);

        // 商品详细信息
        $model_doctors = Model('doctors');
        $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id' => intval($_GET['doctors_id'])), '*');
        // 验证商品是否存在
        if (empty($doctors_info)) {
            showMessage(L('doctors_index_no_doctors'), '', 'html', 'error');
        }
        Tpl::output('doctors', $doctors_info);

        $this->getclicInfo($doctors_info['clic_id']);

        // 当前位置导航
        $nav_link_list = Model('doctors_class')->getdoctorsClassNav($doctors_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $doctors_info['doctors_name'], 'link' => urlclinic('doctors', 'index', array('doctors_id' => $doctors_id)));
        $nav_link_list[] = array('title' => '商品评价');
        Tpl::output('nav_link_list', $nav_link_list );

        //评价信息
        $doctors_evaluate_info = Model('evaluate_doctors')->getEvaluatedoctorsInfoBydoctorsID($doctors_id);
        Tpl::output('doctors_evaluate_info', $doctors_evaluate_info);
        
        $seo_param = array ();
        $seo_param['name'] = $doctors_info['doctors_name'];
        $seo_param['key'] = $doctors_info['doctors_keywords'];
        $seo_param['description'] = $doctors_info['doctors_description'];
        Model('seo')->type('doc')->param($seo_param)->show();

        $this->_get_comments($doctors_id, $_GET['type'], 20);

		Tpl::showpage('doctors.comments_list');
    }

    private function _get_comments($doctors_id, $type, $page) {
        $condition = array();
        $condition['geval_doctorsid'] = $doctors_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                Tpl::output('type', '1');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                Tpl::output('type', '2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                Tpl::output('type', '3');
                break;
        }

        //查询商品评分信息
        $model_evaluate_doctors = Model("evaluate_doctors");
        $doctorsevallist = $model_evaluate_doctors->getEvaluatedoctorsList($condition, $page);
        Tpl::output('doctorsevallist',$doctorsevallist);
        Tpl::output('show_page',$model_evaluate_doctors->showpage('5'));
    }
    
    /**
     * 销售记录
     */
    public function salelogOp() {
        $doctors_id	 = intval($_GET['doctors_id']);
        $appointment_class = Model('appointment');
        $sales = $appointment_class->getappointmentAndappointmentdoctorsSalesRecordList(array('appointment_doctors.doctors_id'=>$doctors_id), 'appointment_doctors.*, appointment.buyer_name, appointment.add_time', 10);
        Tpl::output('show_page',$appointment_class->showpage());
        Tpl::output('sales',$sales);
        
        Tpl::output('appointment_type', array(2=>'团', 3=>'折', '4'=>'套装'));
        Tpl::showpage('doctors.salelog','null_layout');
    }

	/**
	 * 产品咨询
	 */
	public function cosultingOp() {
		$doctors_id	 = intval($_GET['doctors_id']);
		if($doctors_id <= 0){
			showMessage(Language::get('wrong_argument'),'','html','error');
		}
		// 分页信息
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
				
		//得到商品咨询信息
		$consult		= Model('consult');
		$consult_list	= $consult->getConsultList(array('doctors_id'=>$doctors_id),$page,'simple');
		Tpl::output('consult_list',$consult_list);
		Tpl::output('show_page', $page->show());		
		
		//检查是否为店主本身
		$clic_self = false;
        if(!empty($_SESSION['clic_id'])) {
            if (intval($_GET['clic_id']) == $_SESSION['clic_id']) {
                $clic_self = true;
            }
        }
        //查询会员信息
        $member_info	= array();
        $member_model = Model('member');
        if(!empty($_SESSION['member_id'])) $member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}"));
		//检查是否可以评论
        $consult_able = true;
        if((!$GLOBALS['setting_config']['guest_comment'] && !$_SESSION['member_id'] ) || $clic_self == true || ($_SESSION['member_id']>0 && $member_info['is_allowtalk'] == 0)){
        	$consult_able = false;
        }
        Tpl::output('nchash',substr(md5(clinic_SITE_URL.$_GET['act'].$_GET['op']),0,8));
        Tpl::output('consult_able',$consult_able);
		Tpl::showpage('doctors.cosulting', 'null_layout');
	}

	/**
	 * 商品咨询添加
	 */
	public function save_consultajaxOp(){
		//检查是否可以评论
        if(!C('guest_comment') && !$_SESSION['member_id']){
        	echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_doctors_noallow')));
        	die;
        }
		$doctors_id	 = intval($_GET['doctors_id']);
		if($doctors_id <= 0){
			echo json_encode(array('done'=>'false','msg'=>Language::get('wrong_argument')));
        	die;
		}
		//咨询内容的非空验证
		if(trim($_GET['doctors_content'])== ""){
			echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_input_consult')));
        	die;
		}
		$_POST = $_GET;
		//表单验证
		$result = chksubmit(true,C('captcha_status_doctorsqa'),'num');
		if (!$result){
		    echo json_encode(array('done'=>'false','msg'=>Language::get('invalid_request')));
		    die;
		}elseif ($result === -11){
	        echo json_encode(array('done'=>'false','msg'=>Language::get('invalid_request')));
	        die;
	    }elseif ($result === -12){
		   echo json_encode(array('done'=>'false','msg'=>Language::get('wrong_checkcode')));
    	   die;
	    }
        if (processClass::islock('commit')){
        	echo json_encode(array('done'=>'false','msg'=>Language::get('nc_common_op_repeat')));
        	die;
        }else{
        	processClass::addprocess('commit');
        }
        if($_SESSION['member_id']){
	        //查询会员信息
	        $member_model = Model('member');
	        $member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}"));
			if(empty($member_info) || $member_info['is_allowtalk'] == 0){
	        	echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_doctors_noallow')));
        		die;
	        }
        }
		//判断商品编号的存在性和合法性
		$doctors	= Model('doctors');
		$doctors_info	= array();
		$doctors_info	= $doctors->getdoctorsInfo(array('doctors_id'=> $doctors_id));
		if(empty($doctors_info)){
			echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_doctors_not_exists')));
        	die;
		}
        //判断是否是店主本人
        if($_SESSION['clic_id'] && $doctors_info['clic_id'] == $_SESSION['clic_id']) {
            echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_consult_clic_error')));
        	die;
        }
		//检查店铺状态
		$clic_model = Model('clic');
		$clic_info	= $clic_model->getclicInfoByID($doctors_info['clic_id']);
		if($clic_info['clic_state'] == '0' || intval($clic_info['clic_state']) == '2' || (intval($clic_info['clic_end_time']) != 0 && $clic_info['clic_end_time'] <= time())){
			echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_doctors_clic_closed')));
        	die;
		}
		//接收数据并保存
		$input	= array();
		$input['doctors_id']			= $doctors_id;
		$input['cdoctors_name']		= $doctors_info['doctors_name'];
		$input['member_id']			= intval($_SESSION['member_id']) > 0?$_SESSION['member_id']:0;
		$input['cmember_name']		= $_SESSION['member_name']?$_SESSION['member_name']:'';
		$input['clic_id']			= $clic_info['clic_id'];
		$input['email']				= $_GET['email'];
		if (strtoupper(CHARSET) == 'GBK') {
			$input['consult_content']	= Language::getGBK($_GET['doctors_content']);
		}else{
			$input['consult_content']	= $_GET['doctors_content'];
		}
		$input['isanonymous']		= $_GET['hide_name']=='hide'?1:0;
		$consult_model	= Model('consult');
		if($consult_model->addConsult($input)){
			echo json_encode(array('done'=>'true'));
        	die; 
		}else{
			echo json_encode(array('done'=>'false','msg'=>Language::get('doctors_index_consult_fail')));
        	die; 
		}
	}
    
    /**
     * 异步显示优惠套装
     */
    public function get_bundlingOp() {
        $doctors_id = intval($_GET['doctors_id']);
        $clic_id = intval($_GET['clic_id']);
        if ($doctors_id <= 0 || $clic_id <= 0) {
            exit();
        }
        $model_bundling = Model('p_bundling');
        
        // 更新优惠套装状态
        $model_bundling->editBundlingTimeout(array('clic_id' => $clic_id));
        
        // 查询店铺套餐活动是否开启
        $quota_list = $model_bundling->getBundlingQuotaOpenList(array('clic_id' => $clic_id), 0, 1);
        if (!empty($quota_list)) {
            // 根据商品id查询bl_id
            $b_g_list = $model_bundling->getBundlingdoctorsList(array('doctors_id' => $doctors_id, 'bl_appoint' => 1), 'bl_id');
            if (!empty($b_g_list) && is_array($b_g_list)) {
                $b_id_array = array();
                foreach ($b_g_list as $val) {
                    $b_id_array[] = $val['bl_id'];
                }
                
                // 查询套餐列表
                $bundling_list = $model_bundling->getBundlingOpenList(array('bl_id' => array('in', $b_id_array)));
                // 整理
                if (!empty($bundling_list) && is_array($bundling_list)) {
                    $bundling_array = array();
                    foreach ($bundling_list as $val) {
                        $bundling_array[$val['bl_id']]['id'] = $val['bl_id'];
                        $bundling_array[$val['bl_id']]['name'] = $val['bl_name'];
                        $bundling_array[$val['bl_id']]['cost_price'] = 0;
                        $bundling_array[$val['bl_id']]['price'] = $val['bl_discount_price'];
                        $bundling_array[$val['bl_id']]['freight'] = $val['bl_freight'];
                    }
                    $blid_array = array_keys($bundling_array);
                    
                    $b_doctors_list = $model_bundling->getBundlingdoctorsList(array('bl_id' => array('in', $blid_array)));
                    if (!empty($b_doctors_list)) {
                        $doctorsid_array = array();
                        foreach ($b_doctors_list as $val) {
                            $doctorsid_array[] = $val['doctors_id'];
                        }
                        $doctors_list = Model('doctors')->getdoctorsAsdoctorsShowList(array('doctors_id' => array('in', $doctorsid_array)), 'doctors_id,doctors_name,doctors_price,doctors_image');
                        $doctors_list = array_under_reset($doctors_list, 'doctors_id');
                    }
                    // 整理
                    if (! empty ( $b_doctors_list ) && is_array ( $b_doctors_list )) {
                        $b_doctors_array = array ();
                        foreach ( $b_doctors_list as $val ) {
                            if (isset($doctors_list[$val['doctors_id']])) {
                                $k = (intval($val['doctors_id']) == $doctors_id) ? 0 : $val['doctors_id'];    // 排序当前商品放到最前面
                                $b_doctors_array[$val['bl_id']][$k]['id'] = $val['doctors_id'];
                                $b_doctors_array[$val['bl_id']][$k]['image'] = thumb($doctors_list[$val['doctors_id']], 240);
                                $b_doctors_array[$val['bl_id']][$k]['name'] = $doctors_list[$val['doctors_id']]['doctors_name'];
                                $b_doctors_array[$val['bl_id']][$k]['clinic_price'] = ncPriceFormat($doctors_list[$val['doctors_id']]['doctors_price']);
                                $b_doctors_array[$val['bl_id']][$k]['price'] = ncPriceFormat($val['bl_doctors_price']);
                                $bundling_array[$val['bl_id']]['cost_price'] += ncPriceFormat($doctors_list[$val['doctors_id']]['doctors_price']);
                            }
                        }
                    }
                    Tpl::output('bundling_array', $bundling_array);
                    Tpl::output('b_doctors_array', $b_doctors_array);
                }
            }
        }
        Tpl::showpage('doctors_bundling', 'null_layout');
    }

	/**
	 * 商品详细页运费显示
	 *
	 * @return unknown
	 */
	function calcOp(){
		if (!is_numeric($_GET['id']) || !is_numeric($_GET['tid'])) return false;

		$model_transport = Model('transport');
		$extend = $model_transport->getExtendList(array('transport_id'=>array(intval($_GET['tid']))));
		if (!empty($extend) && is_array($extend)){
			$calc = array();
			$calc_default = array();
			foreach ($extend as $v) {
				if (strpos($v['top_area_id'],",".intval($_GET['id']).",") !== false){
					$calc = $v['sprice'];
				}
				if ($v['is_default']==1){
					$calc_default = $v['sprice'];
				}
			}
			//如果运费模板中没有指定该地区，取默认运费
			if (empty($calc) && !empty($calc_default)){
				$calc = $calc_default;
			}
		}
		echo json_encode($calc);
	}
}
