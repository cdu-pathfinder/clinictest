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

class clic_callcenterControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_clic_index');
	}
	public function indexOp(){
		$clic_info = Model('clic')->field('clic_presales,clic_aftersales,clic_workingtime')->find($_SESSION['clic_id']);
		if(!empty($clic_info['clic_presales'])){
			$clic_info['clic_presales']	= unserialize($clic_info['clic_presales']);
		}
		if(!empty($clic_info['clic_aftersales'])){
			$clic_info['clic_aftersales']	= unserialize($clic_info['clic_aftersales']);
		}
		Tpl::output('clicinfo', $clic_info);
		
		Tpl::output('menu_sign','clic_setting');
		$this->profile_menu('clic_callcenter');
		Tpl::showpage('clic_callcenter');
	}
	/**
	 * 保存
	 */
	public function saveOp(){
		if(chksubmit()){
			$update = array();
			$i=0;
			if(is_array($_POST['pre']) && !empty($_POST['pre'])){
				foreach($_POST['pre'] as $val){
					if(empty($val['name']) || empty($val['type']) || empty($val['num'])) continue;
					$update['clic_presales'][$i]['name']	= $val['name'];
					$update['clic_presales'][$i]['type']	= intval($val['type']);
					$update['clic_presales'][$i]['num']	= $val['num'];
					$i++;
				}
				$update['clic_presales'] = serialize($update['clic_presales']);
			}else{
				$update['clic_presales'] = serialize(null);
			}
			
			$i=0;
			if(is_array($_POST['after']) && !empty($_POST['after'])){
				foreach($_POST['after'] as $val){
					if(empty($val['name']) || empty($val['type']) || empty($val['num'])) continue;
					$update['clic_aftersales'][$i]['name']	= $val['name'];
					$update['clic_aftersales'][$i]['type']	= intval($val['type']);
					$update['clic_aftersales'][$i]['num']	= $val['num'];
					$i++;
				}
				$update['clic_aftersales'] = serialize($update['clic_aftersales']);
			}else{
				$update['clic_aftersales'] = serialize(null);
			}
			
			$update['clic_workingtime'] = $_POST['working_time'];
			$update['clic_id']	= $_SESSION['clic_id'];
			Model()->table('clic')->update($update);
			showDialog(Language::get('nc_common_save_succ'), 'index.php?act=clic_callcenter', 'succ');
		}
	}
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key) {
		$menu_array	= array(
			1=>array('menu_key'=>'clic_callcenter','menu_name'=>Language::get('nc_member_path_clic_callcenter'),'menu_url'=>'index.php?act=clic_callcenter'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
