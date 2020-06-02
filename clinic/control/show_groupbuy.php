<?php
/**
 * 前台团购
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

class show_groupbuyControl extends BaseHomeControl {

    public function __construct() {
        parent::__construct();

        //读取语言包
        Language::read('member_groupbuy,home_cart_index');

        //检查团购功能是否开启
        if (intval($GLOBALS['setting_config']['groupbuy_allow']) !== 1){
            showMessage(Language::get('groupbuy_unavailable'),'index.php','','error');
        }

		//分类导航
		$nav_link = array(
			0=>array(
				'title'=>Language::get('homepage'),
				'link'=>'index.php'
			),
			1=>array(
				'title'=>Language::get('nc_groupbuy')
			)
		);
		Tpl::output('nav_link_list',$nav_link);
	
    }

	/**
	 * 默认跳转到进行中的团购列表
	 */
    public function indexOp() {
        $this->groupbuy_listOp();
	}

    /**
     * 进行中的团购团购
     **/
    public function groupbuy_listOp() {
        Tpl::output('current', 'online');
        Tpl::output('buy_button', L('groupbuy_buy'));
        $this->_show_groupbuy_list('getGroupbuyOnlineList');
    }

    /**
     * 即将开始的团购
     **/
    public function groupbuy_soonOp() {
        Tpl::output('current', 'soon');
        Tpl::output('buy_button', '未开始');
        $this->_show_groupbuy_list('getGroupbuySoonList');
    }

    /**
     * 往期团购
     **/
    public function groupbuy_historyOp() {
        Tpl::output('current', 'history');
        Tpl::output('buy_button', '已结束');
        $this->_show_groupbuy_list('getGroupbuyHistoryList');
    }

    /**
     * 获取团购列表
     **/
    private function _show_groupbuy_list($function_name) {
        $model_groupbuy = Model('groupbuy');
		$g_cache = ($cache = H('groupbuy'))? $cache : H('groupbuy',true);

        $condition = array();
        $appointment = '';

        // 地区筛选条件
        $area_id = intval($_GET['groupbuy_area']);
        if(empty($condition['area_id'])) {
            if(cookie('groupbuy_area')) {
                $area_array = explode(',',cookie('groupbuy_area'));
                $area_id = intval($area_array[0]);
            }
        }
        if(!empty($area_id)) {
            $condition['area_id'] = array('in', array(0, $area_id));
        }

        // 分类筛选条件
        $class_id = intval($_GET['groupbuy_class']);
        if($class_id > 0) {
            $condition['class_id'] = $class_id;
        }

        // 价格区间筛选条件
        if(intval($_GET['groupbuy_price']) > 0) {
            $price_range_list = $g_cache['price'];
            foreach($price_range_list as $price_range) {
                if($price_range['range_id'] == $_GET['groupbuy_price']) {
                    $condition['groupbuy_price'] = array('between', array($price_range['range_start'], $price_range['range_end']));
                } 
            }
        }

        // 排序
        $groupbuy_appointment_key = trim($_GET['groupbuy_appointment_key']);
        $groupbuy_appointment = $_GET['groupbuy_appointment'] == '2'?'desc':'asc';
        if(!empty($groupbuy_appointment_key)) {
            switch ($groupbuy_appointment_key) {
                case '1':
                    $appointment = 'groupbuy_price '.$groupbuy_appointment;
                    break;
                case '2':
                    $appointment = 'groupbuy_rebate '.$groupbuy_appointment;
                    break;
                case '3':
                    $appointment = 'buyer_count '.$groupbuy_appointment;
                    break;
            }
        }

        $groupbuy_list = $model_groupbuy->$function_name($condition, 20, $appointment);
        Tpl::output('groupbuy_list', $groupbuy_list);
        Tpl::output('show_page', $model_groupbuy->showpage(5));

        Tpl::output('class_list',$g_cache['category']);
        Tpl::output('area_list',$g_cache['area']);
        Tpl::output('price_list',$g_cache['price']);
		Tpl::output('index_sign','groupbuy');
		Tpl::output('html_title', Language::get('text_groupbuy_list'));

		Model('seo')->type('group')->show();

       loadfunc('search');
		Tpl::showpage('groupbuy_list');
    }

    /**
     * 团购详细信息
     **/
    public function groupbuy_detailOp() {
        $group_id = intval($_GET['group_id']);

        $model_groupbuy = Model('groupbuy');
        $model_clic = Model('clic');

		$g_cache = ($cache = H('groupbuy'))? $cache : H('groupbuy',true);
        Tpl::output('area_list',$g_cache['area']);

        //获取团购详细信息
        $groupbuy_info = $model_groupbuy->getGroupbuyInfoByID($group_id);
        if(empty($groupbuy_info)) {
            showMessage(Language::get('param_error'),'index.php?act=show_groupbuy','','error');
        }
        Tpl::output('groupbuy_info',$groupbuy_info);

        // 输出店铺信息 
        $clic_info = $model_clic->getclicInfoByID($groupbuy_info['clic_id']);
        Tpl::output('clic_info', $clic_info);

        // 浏览数加1
        $update_array = array();
        $update_array['views'] = array('exp', 'views+1');
        $model_groupbuy->editGroupbuy($update_array, array('groupbuy_id'=>$group_id));

        
        //获取店铺推荐商品
        $commended_groupbuy_list = $model_groupbuy->getGroupbuyCommendedList(8);
        Tpl::output('commended_groupbuy_list', $commended_groupbuy_list);

		Tpl::output('index_sign','groupbuy');

		Model('seo')->type('group_content')->param(array('name'=>$groupbuy_info['groupbuy_name']))->show();

		loadfunc('search');
		Tpl::showpage('groupbuy_detail');
    }

    public function groupbuy_appointmentOp() {
        $group_id = intval($_GET['group_id']);

        $model_groupbuy = Model('groupbuy');

        $groupbuy_info = $model_groupbuy->getGroupbuyInfoByID($group_id);

        if(!empty($groupbuy_info)) {
            //获取购买记录
            $model_appointment = Model('appointment');
            $condition = array();
            $condition['doctors_id'] = $groupbuy_info['doctors_id'];
            $condition['doctors_type'] = 2;
            $appointment_doctors_list = $model_appointment->getappointmentdoctorsList($condition, '*', 0 , 10);
            Tpl::output('appointment_doctors_list', $appointment_doctors_list);
            Tpl::output('show_page', $model_appointment->showpage());
            if (!empty($appointment_doctors_list)) {
                $appointmentid_array = array();
                foreach ($appointment_doctors_list as $value) {
                    $appointmentid_array[] = $value['appointment_id'];
                }
                $appointment_list = $model_appointment->getappointmentList(array('appointment_id' => array('in', $appointmentid_array)), '', 'appointment_id,buyer_name,add_time');
                $appointment_list = array_under_reset($appointment_list, 'appointment_id');
                Tpl::output('appointment_list', $appointment_list);
            }
        }
		Tpl::showpage('groupbuy_appointment', 'null_layout');
    }

}
