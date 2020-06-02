<?php
/**
 * 会员中心——我是卖家
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

class clic_doctors_classControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_clic_index');
	}
	/**
	 * 卖家商品分类
	 *
	 * @param 
	 * @return 
	 */
	public function indexOp() {
		$model_class	= Model('my_doctors_class');

		if($_GET['type'] == 'ok') {
			if(intval($_GET['class_id']) != 0) {
				$class_info	= $model_class->getClassInfo(array('stc_id'=>intval($_GET['class_id'])));
				Tpl::output('class_info',$class_info);
			}
			if(intval($_GET['top_class_id']) != 0) {
				Tpl::output('class_info',array('stc_parent_id'=>intval($_GET['top_class_id'])));
			}
			$doctors_class		= $model_class->getClassList(array('clic_id'=>$_SESSION['clic_id'],'stc_top'=>1));
			Tpl::output('doctors_class',$doctors_class);
			Tpl::showpage('clic_doctors_class.add','null_layout');
		} else {
			$doctors_class		= $model_class->getTreeClassList(array('clic_id'=>$_SESSION['clic_id']),2);
			$str	= '';
			if(is_array($doctors_class) and count($doctors_class)>0) {
				foreach ($doctors_class as $key => $val) {
					$row[$val['stc_id']]	= $key + 1;
					$str .= intval($row[$val['stc_parent_id']]).",";
				}
				$str = substr($str,0,-1);
			} else {
				$str = '0';
			}
			Tpl::output('map',$str);
			Tpl::output('class_num',count($doctors_class)-1);
			Tpl::output('doctors_class',$doctors_class);

			self::profile_menu('clic_doctors_class','clic_doctors_class');
			Tpl::output('menu_sign','clic_doctors_class');
			Tpl::output('menu_sign_url','index.php?act=clic_doctors_class&op=clic_doctors_class');
			Tpl::output('menu_sign1','doctors_class');
			Tpl::showpage('clic_doctors_class.list');
		}
	}
	/**
	 * 卖家商品分类保存
	 *
	 * @param 
	 * @return 
	 */
	public function doctors_class_saveOp() {
		$model_class	= Model('my_doctors_class');
		if($_POST['stc_id'] != '') {
			$choeck_class	= $model_class->getClassInfo(array('stc_id'=>intval($_POST['stc_id']),'clic_id'=>$_SESSION['clic_id']));
			if(empty($choeck_class)) {
				showDialog(Language::get('clic_doctors_class_wrong'));
			}
			$state = $model_class->editdoctorsClass($_POST,intval($_POST['stc_id']));
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=clic_doctors_class&op=index','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'));
			}
		} else {
			$state = $model_class->adddoctorsClass($_POST);
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=clic_doctors_class&op=index','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'));
			}
		}
	}
	/**
	 * 卖家商品分类删除
	 *
	 * @param 
	 * @return 
	 */
	public function drop_doctors_classOp() {
		$model_class	= Model('my_doctors_class');
		$drop_state	= $model_class->dropdoctorsClass(trim($_GET['class_id']));
		if ($drop_state){
			showDialog(Language::get('nc_common_del_succ'),'index.php?act=clic_doctors_class&op=clic_doctors_class','succ');
		}else{
			showDialog(Language::get('nc_common_del_fail'));
		}
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'clic_doctors_class':
				$menu_array = array(
				1=>array('menu_key'=>'clic_doctors_class','menu_name'=>Language::get('nc_member_path_doctors_class'),	'menu_url'=>'index.php?act=clic_doctors_class&op=clic_doctors_class'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
