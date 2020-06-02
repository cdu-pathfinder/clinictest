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

class clic_storage_alarmControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * 卖家商品分类
	 *
	 * @param 
	 * @return 
	 */
	public function indexOp() {
        $model_clic = Model('clic');
        if (chksubmit()) {
            $clic_storage_alarm = intval(abs($_POST['clic_storage_alarm']));
            $model_clic->editclic(array('clic_storage_alarm'=>$clic_storage_alarm),array('clic_id'=>$_SESSION['clic_id']));
            showDialog(L('nc_common_save_succ'),'reload','succ');
        }
        Tpl::output('clic_storage_alarm',$this->clic_info['clic_storage_alarm']);
        $this->profile_menu('clic_storage_alarm', 'clic_storage_alarm');
		Tpl::output('menu_sign','clic_storage_alarm');
		Tpl::showpage('clic_storage_alarm.index');
	}


	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array		= array();
		switch ($menu_type) {
			case 'clic_storage_alarm':
				$menu_array = array(
				1=>array('menu_key'=>'clic_storage_alarm','menu_name'=>'库存警报',	'menu_url'=>urlclinic('clic_storage_alarm', 'index')));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
