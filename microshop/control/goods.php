<?php
/**
 * 推荐商品(随心看)
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class doctorsControl extends MircroclinicControl{

	public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','doctors');
    }

	public function indexOp() {
        $this->listOp();
	}

	public function listOp() {

        $model_doctors_class = Model('micro_doctors_class');
        $doctors_class_list = $model_doctors_class->getList(TRUE,NULL,'class_sort asc');

        $doctors_class_root = array();
        $doctors_class_menu = array();
        if(!empty($doctors_class_list)) {
            foreach($doctors_class_list as $val) {
                if($val['class_parent_id'] == 0) {
                    $doctors_class_root[] = $val;
                } else {
                    $doctors_class_menu[$val['class_parent_id']][] = $val;
                }
            }
        }

        //处理一级菜单选中
        $doctors_class_root_id = $doctors_class_root[0]['class_id'];
        if(isset($_GET['doctors_class_root_id'])) {
            if(intval($_GET['doctors_class_root_id']) > 0) {
                $doctors_class_root_id = $_GET['doctors_class_root_id'];
            }
        }
        Tpl::output('doctors_class_root',$doctors_class_root);
        Tpl::output('doctors_class_root_id',$doctors_class_root_id);

        //处理二级菜单选中
        $doctors_class_menu_id = 0;
        if(isset($_GET['doctors_class_menu_id'])) {
            if(intval($_GET['doctors_class_menu_id']) > 0) {
                $doctors_class_menu_id = $_GET['doctors_class_menu_id'];
            }
        }
        Tpl::output('doctors_class_menu',$doctors_class_menu[$doctors_class_root_id]);
        Tpl::output('doctors_class_menu_id',$doctors_class_menu_id);

        /**
         * 查询条件处理
         **/
        $condition = array();
        if(isset($_GET['keyword'])) {
            $condition['commend_doctors_name'] = array('like','%'.$_GET['keyword'].'%'); 
        }
        //分类条件 
        if($doctors_class_menu_id > 0) {
            //选中二级菜单
            $condition['class_id'] = $doctors_class_menu_id; 
        } else {
            //只选中一级菜单
            $class_array = $doctors_class_menu[$doctors_class_root_id];
            $class_id_string = '';
            if(!empty($class_array)) {
                foreach ($class_array as $val) {
                    $class_id_string .= $val['class_id'].',';
                }
            }
            $class_id_string = rtrim($class_id_string,',');
            if(!empty($class_id_string)) {
                $condition['class_id'] = array('in',$class_id_string);
            }
        }

        $appointment = 'microclinic_sort asc,commend_time desc';
        if($_GET['appointment'] == 'hot') {
            $appointment = 'microclinic_sort asc,click_count desc';
        }
        self::get_doctors_list($condition,$appointment);

        Tpl::output('html_title',Language::get('nc_microclinic_doctors').'-'.Language::get('nc_microclinic').'-'.C('site_name'));
		Tpl::showpage('doctors_list');

	}

    public function detailOp() {

        $doctors_id = intval($_GET['doctors_id']);
        if($doctors_id <= 0) {
            header('location: '.MICROclinic_SITE_URL);die;
        }
        $model_microclinic_doctors = Model('micro_doctors');
        $condition = array();
        $condition['commend_id'] = $doctors_id;
        $detail = $model_microclinic_doctors->getOneWithUserInfo($condition);
        if(empty($detail)) {
            header('location: '.MICROclinic_SITE_URL);die;
        }
        Tpl::output('detail',$detail);

        //商品多图
        $model_doctors = Model('doctors');
        $doctors_image_list = $model_doctors->getdoctorsImageList(array('doctors_commonid' => $detail['commend_doctors_commonid']));
        Tpl::output('doctors_image_list', $doctors_image_list);


        //点击数加1
        $update = array();
        $update['click_count'] = array('exp','click_count+1');
        $model_microclinic_doctors->modify($update,$condition);

        //侧栏
        self::get_sidebar_list($detail['commend_member_id']);

        //店铺信息
		$model_clic = Model('clic');
        $clic_info = $model_clic->getclicInfoByID($detail['commend_doctors_clic_id']);
        $clic_info['hot_sales_list'] = $model_clic->getHotSalesList($detail['commend_doctors_clic_id'], 5);
        Tpl::output('clic_info',$clic_info);

        //获得分享app列表
        self::get_share_app_list();
        Tpl::output('comment_id',$detail['commend_id']);
        Tpl::output('comment_type','doctors');
        Tpl::output('html_title',$detail['commend_doctors_name'].'-'.Language::get('nc_microclinic_doctors').'-'.Language::get('nc_microclinic').'-'.C('site_name'));
		Tpl::showpage('doctors_detail');

    }
}
