<?php
/**
 * 微商城首页
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class indexControl extends MircroclinicControl{

	public function __construct() {
		parent::__construct();
        Tpl::output('index_sign','index');
    }
	public function indexOp(){

        //首页幻灯
        self::get_microclinic_adv('index');

        //用户信息
        $model_member = Model('member');
        $member_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));
        if(!empty($member_info)) {
            $member_info = self::get_member_detail_info($member_info);
        }
        $model_micro_member_info = Model('micro_member_info');
        $micro_member_info = $model_micro_member_info->getOneById($_SESSION['member_id']);
        if(empty($micro_member_info)) {
            $member_info['personal_count'] = 0;
            $member_info['doctors_count'] = 0;
        } else {
            $member_info['personal_count'] = $micro_member_info['personal_count'];
            $member_info['doctors_count'] = $micro_member_info['doctors_count'];
        }
        Tpl::output('member_info',$member_info);

        //首页购物达人
        $model_member_info = Model('micro_member_info');
        $member_array = $model_member_info->getListWithUserInfo(TRUE,null,'personal_count desc','*',3);
        $member_list = array();
        if(!empty($member_array)) {
            foreach ($member_array as $value) {
                $member_info = self::get_member_detail_info($value);
                if(!empty($_SESSION['member_id']) && $value['member_id'] != $_SESSION['member_id']) {
                    $model = Model();
                    $gz_array	= $model->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id'], 'friend_tomid'=>array('in', $value['member_id'])))->select();
                    if(empty($gz_array)) {
                        $member_info['follow_flag'] = TRUE;
                    } else {
                        $member_info['follow_flag'] = FALSE;
                    }
                }
                $member_list[] = $member_info;
            }
        }
        Tpl::output('member_list',$member_list);

        //首页推荐个人秀
        $condition_personal = array();
        $condition_personal['microclinic_commend'] = 1;
        $model_micro_personal = Model('micro_personal');
        $personal_list = $model_micro_personal->getListWithUserInfo($condition_personal,null,'','*',8);
        Tpl::output('personal_list',$personal_list);

        //首页推荐随心看
        $model_micro_doctors = Model('micro_doctors');
        $model_doctors_class = Model('micro_doctors_class');
        //取分类
        $doctors_class_list = $model_doctors_class->getList(TRUE,NULL,'class_sort asc');
        $doctors_class_root = array();
        $doctors_class_menu = array();
        $doctors_class_root_children = array();
        $doctors_list = array();
        if(!empty($doctors_class_list)) {
            foreach($doctors_class_list as $val) {
                if($val['class_parent_id'] == 0 && $val['class_commend'] == 1) {
                    $doctors_class_root[] = $val;
                } else {
                    $doctors_class_menu[$val['class_parent_id']][] = $val;
                    $doctors_class_root_children[$val['class_parent_id']] .= $val['class_id'].',';
                }
            }
        }
        //取分类下推荐商品
        foreach ($doctors_class_root as $value) {
            $condition_doctors = array();
            $condition_doctors['microclinic_commend'] = 1;
            $condition_doctors['class_id'] = array('in',rtrim($doctors_class_root_children[$value['class_id']],','));
            $doctors_list[$value['class_id']] = $model_micro_doctors->getListWithUserInfo($condition_doctors,null,'','*',6);
        }
        Tpl::output('doctors_class_root',$doctors_class_root);
        Tpl::output('doctors_class_menu',$doctors_class_menu);
        Tpl::output('doctors_list',$doctors_list);

        //首页推荐店铺
        $condition_clic = array();
        $condition_clic['microclinic_commend'] = 1;
        $model_micro_clic = Model('micro_clic');
        $model_clic = Model('clic');
        $clic_list = $model_micro_clic->getListWithclicInfo($condition_personal,null,'like_count desc,click_count desc','*',15);
        Tpl::output('clic_list',$clic_list);

		Tpl::showpage('index');
	}
}
