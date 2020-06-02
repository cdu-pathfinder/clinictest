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

class clic_printsetupControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_clic_index');
	}

	/**
	 * 店铺打印设置
	 */
	public function indexOp(){
		$model = Model();
		$clic_info = $model->table('clic')->where(array('clic_id'=>$_SESSION['clic_id']))->find();
		if(empty($clic_info)){
			showDialog(Language::get('clic_clicinfo_error'),'index.php?act=clic_printsetup','error');
		}
		if(chksubmit()){
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST['clic_printdesc'], "require"=>"true","validator"=>"Length","min"=>1,"max"=>200,"message"=>Language::get('clic_printsetup_desc_error'))
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showDialog($error);
			}
			$update_arr = array();
			//上传认证文件
			if($_FILES['clic_stamp']['name'] != '') {
				$upload = new UploadFile();
				$upload->set('default_dir',ATTACH_clic);
				if($_FILES['clic_stamp']['name'] != '') {
					$result = $upload->upfile('clic_stamp');
					if ($result){
						$update_arr['clic_stamp'] = $upload->file_name;
						//删除旧认证图片
						if (!empty($clic_info['clic_stamp'])){
							@unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_stamp']);
						}
					}
				}
			}
			$update_arr['clic_printdesc'] = $_POST['clic_printdesc'];
			$rs = $model->table('clic')->where(array('clic_id'=>$_SESSION['clic_id']))->update($update_arr);
			if ($rs){
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=clic_printsetup','succ');
			}else {
				showDialog(Language::get('nc_common_save_fail'),'index.php?act=clic_printsetup','error');
			}
		}else{
			Tpl::output('clic_info',$clic_info);
			self::profile_menu('clic_printsetup');
			Tpl::showpage('clic_printsetup');
		}
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_key='') {
		Language::read('member_layout');
        $menu_array = array(
            1=>array('menu_key'=>'clic_printsetup','menu_name'=>'打印设置','menu_url'=>'index.php?act=clic_printsetup&op=index'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
