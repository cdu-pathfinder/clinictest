<?php
/**
 * 二级域名
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
class domainControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('clic');
	}

	/**
	 * 二级域名设置
	 *
	 * @param
	 * @return
	 */
	public function clic_domain_settingOp(){
		/**
		 * 读取语言包
		 */
		$lang	= Language::getLangContent();

		/**
		 * 实例化模型
		 */
		$model_setting = Model('setting');
		/**
		 * 保存信息
		 */
		if (chksubmit()){
			$update_array = array();
			$update_array['enabled_subdomain'] = intval($_POST['enabled_subdomain']);
			$update_array['subdomain_reserved'] = trim($_POST['subdomain_reserved']);
			$update_array['subdomain_length'] = trim($_POST['subdomain_length']);
			$update_array['subdomain_edit'] = intval($_POST['subdomain_edit']);
			$update_array['subdomain_times'] = intval($_POST['subdomain_times']);
			$subdomain_length = explode('-',$update_array['subdomain_length']);
			$subdomain_length[0] = intval($subdomain_length[0]);
			$subdomain_length[1] = intval($subdomain_length[1]);
			if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]){//域名长度
				$update_array['subdomain_length'] = '3-12';
			}
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,nc_domain_manage'),1);
				showMessage($lang['nc_common_save_succ']);
			}else {
				showMessage($lang['nc_common_save_fail']);
			}
		}

		$list_setting = $model_setting->getListSetting();

		Tpl::output('list_setting',$list_setting);
		Tpl::showpage('clic_domain.setting');
	}

	/**
	 * 店铺二级域名列表
	 */
	public function clic_domain_listOp(){

		$lang = Language::getLangContent();

		$condition = array();
		$condition['clic_state']	= array('neq', 2);
		if(trim($_GET['clic_name']) != ''){
			$condition['clic_name']	= array('like', '%'.trim($_GET['clic_name']).'%');
		}
		if(trim($_GET['clic_domain']) != ''){
			$condition['clic_domain']	= array(array('neq', ''), array('like', '%'.trim($_GET['clic_domain']).'%'), 'and');
		}else{
			$condition['clic_domain']	= array('neq', '');
		}
		$model_clic = Model('clic');
		$clic_list = $model_clic->where($condition)->appointment('clic_sort asc')->page(10)->select();

		if(!empty($clic_list)){
			foreach ($clic_list as $k => $v){
				$clic_list[$k]['state'] = ($v['clic_state'] == 1)?$lang['open']:$lang['close'];
			}
		}
		Tpl::output('clic_list',$clic_list);
		Tpl::output('page',$model_clic->showpage('2'));
		Tpl::showpage('clic_domain.index');
	}

	/**
	 * 二级域名编辑
	 */
	public function clic_domain_editOp(){

		/**
		 * 取店铺信息
		 */
		$model_clic = Model('clic');
		$clic_array = $model_clic->getclicInfoByID(intval($_GET['clic_id']));

		$setting_config = $GLOBALS['setting_config'];
		$subdomain_times = intval($setting_config['subdomain_times']);//系统设置二级域名可修改次数
		$subdomain_length = explode('-',$setting_config['subdomain_length']);
		$subdomain_length[0] = intval($subdomain_length[0]);
		$subdomain_length[1] = intval($subdomain_length[1]);
		if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]){//域名长度
			$subdomain_length[0] = 3;
			$subdomain_length[1] = 12;
		}
		Tpl::output('subdomain_length',$subdomain_length);

		if (chksubmit()){
			$clic_domain_times = intval($_POST['clic_domain_times']);//店铺已修改次数
			$clic_domain = trim($_POST['clic_domain']);
			$clic_id = intval($_POST['clic_id']);
			$clic_domain = strtolower($clic_domain);
			$param = array();
			$param['clic_domain_times'] = $clic_domain_times;
			$param['clic_domain'] = '';
			if (!empty($clic_domain)){
				$clic_domain_count = strlen($clic_domain);
				if ($clic_domain_count < $subdomain_length[0] || $clic_domain_count > $subdomain_length[1]){
					showMessage(Language::get('clic_domain_length_error').': '.$setting_config['subdomain_length']);
				}
				if (!preg_match('/^[\w-]+$/i',$clic_domain)){//判断域名是否正确
					showMessage(Language::get('clic_domain_invalid'));
				}
				$clic_info = $model_clic->getclicInfo(array(
					'clic_domain'=>$clic_domain
				));
				//二级域名存在,则提示错误
				if (!empty($clic_info) && ($clic_id != $clic_info['clic_id'])){
					showMessage(Language::get('clic_domain_exists'));
				}
				//判断二级域名是否为系统禁止
				$subdomain_reserved = @explode(',',$setting_config['subdomain_reserved']);
				if(!empty($subdomain_reserved) && is_array($subdomain_reserved)){
						if (in_array($clic_domain,$subdomain_reserved)){
							showMessage(Language::get('clic_domain_sys'));
						}
				}
				$param['clic_domain'] = $clic_domain;//所有验证通过后更新
			}
			$model_clic->editclic($param, array('clic_id'=> $clic_id));
			$this->log(L('nc_edit,nc_domain_manage').'['.$clic_domain.']',1);
			showMessage(Language::get('nc_common_save_succ'),'index.php?act=domain&op=clic_domain_list');//保存成功
		}
		Tpl::output('clic_array',$clic_array);
		Tpl::showpage('clic_domain.edit');
	}
}
