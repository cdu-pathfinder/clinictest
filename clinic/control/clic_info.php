<?php
/**
 * 客服中心
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

class clic_infoControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_clic_index');
	}
	public function indexOp(){
        $model_clic = Model('clic');
        $model_clic_bind_class = Model('clic_bind_class');
        $model_clic_class = Model('clic_class');
        $model_clic_grade = Model('clic_grade');

        // 店铺信息
        $clic_info = $model_clic->getclicInfoByID($_SESSION['clic_id']);
        Tpl::output('clic_info', $clic_info);

        // 店铺分类信息
        $clic_class_info = $model_clic_class->getOneClass($clic_info['sc_id']);
        Tpl::output('clic_class_name', $clic_class_info['sc_name']);

        // 店铺等级信息
        $clic_grade_info = $model_clic_grade->getOneGrade($clic_info['grade_id']);
        Tpl::output('clic_grade_name', $clic_grade_info['sg_name']);

        $model_clic_joinin = Model('clic_joinin');
        $joinin_detail = $model_clic_joinin->getOne(array('member_id'=>$clic_info['member_id']));
        Tpl::output('joinin_detail', $joinin_detail);

        $clic_bind_class_list = $model_clic_bind_class->getclicBindClassList(array('clic_id'=>$_SESSION['clic_id']), null);
        $doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        for($i = 0, $j = count($clic_bind_class_list); $i < $j; $i++) {
            $clic_bind_class_list[$i]['class_1_name'] = $doctors_class[$clic_bind_class_list[$i]['class_1']]['gc_name'];
            $clic_bind_class_list[$i]['class_2_name'] = $doctors_class[$clic_bind_class_list[$i]['class_2']]['gc_name'];
            $clic_bind_class_list[$i]['class_3_name'] = $doctors_class[$clic_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('clic_bind_class_list', $clic_bind_class_list);

        Tpl::showpage('clic_info');
	}
}
