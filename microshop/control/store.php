<?php
/**
 * 微商城店铺街
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class clicControl extends MircroclinicControl{

	public function __construct() {
		parent::__construct();
        Tpl::output('index_sign','clic');
    }

	public function indexOp(){
        $this->clic_listOp();
	}

    /**
     * 店铺列表
     */
    public function clic_listOp() {
		$model_clic = Model('clic');
		$model_microclinic_clic = Model('micro_clic');
        $condition = array();
        if(!empty($_GET['keyword'])) {
            $condition['clic_name'] = array('like','%'.trim($_GET['keyword']).'%');
        }
		$clic_list = $model_microclinic_clic->getListWithclicInfo($condition,30,'microclinic_sort asc');
		Tpl::output('list',$clic_list);
        Tpl::output('show_page',$model_clic->showpage(2));	
        //广告位
        self::get_microclinic_adv('clic_list');
        Tpl::output('html_title',Language::get('nc_microclinic_clic').'-'.Language::get('nc_microclinic').'-'.C('site_name'));
		Tpl::showpage('clic_list');
    }

    /**
     * 店铺详细页
     */
    public function detailOp() {
        $clic_id = intval($_GET['clic_id']);
        if($clic_id <= 0) {
            header('location: '.MICROclinic_SITE_URL);die;
        }
		$model_clic = Model('clic');
		$model_doctors = Model('doctors');
		$model_microclinic_clic = Model('micro_clic');

        $clic_info = $model_microclinic_clic->getOneWithclicInfo(array('microclinic_clic_id'=>$clic_id));
        if(empty($clic_info)) {
            header('location: '.MICROclinic_SITE_URL);
        }

        //点击数加1
        $update = array();
        $update['click_count'] = array('exp','click_count+1');
        $model_microclinic_clic->modify($update,array('microclinic_clic_id'=>$clic_id));

        Tpl::output('detail',$clic_info);

        $condition = array();
        $condition['clic_id'] = $clic_info['clinic_clic_id'];
        $doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, 'doctors_id,clic_id,doctors_name,doctors_image,doctors_price,doctors_salenum', 'doctors_id asc', 39);
        Tpl::output('comment_type','clic');
        Tpl::output('comment_id',$clic_id);
		Tpl::output('list',$doctors_list);
        Tpl::output('show_page',$model_doctors->showpage());	
        //获得分享app列表
        self::get_share_app_list();
        Tpl::output('html_title',$clic_info['clic_name'].'-'.Language::get('nc_microclinic_clic').'-'.Language::get('nc_microclinic').'-'.C('site_name'));
		Tpl::showpage('clic_detail');
    }

}
