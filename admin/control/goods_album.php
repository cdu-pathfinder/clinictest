<?php
/**
 * 相册管理
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

class doctors_albumControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('doctors_album');
	}
	/**
	 * 相册列表
	 */
	public function listOp(){
		$model = Model();
		if (chksubmit()){
			if (is_array($_POST['aclass_id'])){
				foreach ($_POST['aclass_id'] as $k=>$v) {
					if (!is_numeric($v)){
						unset($_POST['aclass_id'][$k]);
					}
				}
			}
			if (!empty($_POST['aclass_id'])){
				$pic = $model->table('album_pic')->field('apic_cover')->where(array('aclass_id'=>array('in',$_POST['aclass_id'])))->select();
				if (is_array($pic)){
					foreach ($pic as $v) {
						$this->del_file($v['apic_cover']);
					}
				}
				$model->table('album_pic')->where(array('aclass_id'=>array('in',$_POST['aclass_id'])))->delete();
				$model->table('album_class')->where(array('aclass_id'=>array('in',$_POST['aclass_id'])))->delete();
				$this->log(L('nc_delete,g_album_one').'[ID:'.implode(',',$_POST['aclass_id']).']',1);
				showMessage(Language::get('nc_common_del_succ'));				
			}
		}
		$condiiton = array();
		if (is_numeric($_GET['keyword'])){
			$condiiton['clic.clic_id'] = $_GET['keyword'];
			$clic_name = $model->table('clic')->getfby_clic_id($_GET['keyword'],'clic_name');
		}elseif (!empty($_GET['keyword'])){
			$clic_name = $_GET['keyword'];
			$clic_id = $model->table('clic')->getfby_clic_name($_GET['keyword'],'clic_id');
			if (is_numeric($clic_id)){
				$condiiton['clic.clic_id'] = $clic_id;
			}else{
				$condiiton['clic.clic_id'] = 0;
			}
		}

		$model->table('album_class,clic')->where($condiiton)->join('inner')->on('album_class.clic_id=clic.clic_id');
		$list = $model->field('album_class.*,clic.clic_name')->page(10)->select();
		Tpl::output('page',$model->showpage());

		$model->cls()->table('album_pic')->field('aclass_id,count(*) as pcount')->group('aclass_id');
		if (is_array($list) && !empty($list)){
			foreach ($list as $v) {
				$class[] = $v['aclass_id'];
			}
			$model->where(array('aclass_id'=>array('in',implode(',',$class))));
		}
		$count = $model->select();
		if (is_array($count)){
			foreach ($count as $v) {
				$pic_count[$v['aclass_id']] = $v['pcount'];
			}
		}
		Tpl::output('pic_count',$pic_count);
		Tpl::output('list',$list);
		Tpl::output('clic_name',$clic_name);
		Tpl::showpage('doctors_album.index');
	}

	/**
	 * 图片列表
	 */
	public function pic_listOp(){
		$model = Model();
		$condiiton = array();
		if (is_numeric($_GET['keyword'])){
			$condiiton['clic_id'] = $_GET['keyword'];
			$clic_name = $model->table('clic')->getfby_clic_id($_GET['keyword'],'clic_name');
		}elseif (!empty($_GET['keyword'])){
			$clic_name = $_GET['keyword'];
			$clic_id = $model->table('clic')->getfby_clic_name($_GET['keyword'],'clic_id');
			if (is_numeric($clic_id)){
				$condiiton['clic_id'] = $clic_id;
			}else{
				$condiiton['clic_id'] = 0;
			}
		}elseif (is_numeric($_GET['aclass_id'])){
			$condiiton['aclass_id'] = $_GET['aclass_id'];
		}
		$list = $model->table('album_pic')->where($condiiton)->appointment('apic_id desc')->page(40)->select();
		$show_page = $model->showpage();
		Tpl::output('page',$show_page);
		Tpl::output('list',$list);
		Tpl::output('clic_name',$clic_name);
		Tpl::showpage('doctors_album.pic_list');
	}

	/**
	 * 删除相册
	 */
	public function aclass_delOp(){
		$aclass_id = intval($_GET['aclass_id']);
		if (!is_numeric($aclass_id)){
			showMessage(Language::get('param_error'));
		}
		$model = Model();
		$pic = $model->table('album_pic')->field('apic_cover')->where(array('aclass_id'=>$aclass_id))->select();
		if (is_array($pic)){
			foreach ($pic as $v) {
				$this->del_file($v['apic_cover']);
			}
		}
		$model->table('album_pic')->where(array('aclass_id'=>$aclass_id))->delete();
		$model->table('album_class')->where(array('aclass_id'=>$aclass_id))->delete();
		$this->log(L('nc_delete,g_album_one').'[ID:'.intval($_GET['aclass_id']).']',1);
		showMessage(Language::get('nc_common_del_succ'));
	}

	/**
	 * 删除一张图片及其对应记录
	 *
	 */
	public function del_album_picOp(){
		list($apic_id,$filename) = @explode('|',$_GET['key']);
		if (!is_numeric($apic_id) || empty($filename)) exit('0');
		$this->del_file($filename);
		Model('album_pic')->where(array('apic_id'=>$apic_id))->delete();
		$this->log(L('nc_delete,g_album_pic_one').'[ID:'.$apic_id.']',1);
		exit('1');
	}

	/**
	 * 删除多张图片
	 *
	 */
	public function del_more_picOp(){
		$model= Model('album_pic');
		$list = $model->where(array('apic_id'=>array('in',$_POST['delbox'])))->select();
		if (is_array($list)){
			foreach ($list as $v) {
				$this->del_file($v['apic_cover']);
			}
		}
		$model->where(array('apic_id'=>array('in',$_POST['delbox'])))->delete();
		$this->log(L('nc_delete,g_album_pic_one').'[ID:'.implode(',',$_POST['delbox']).']',1);
		redirect();
	}

	/**
	 * 删除图片文件
	 * 
	 */
	private function del_file($filename){
		//取店铺ID
		if (preg_match('/^(\d+_)/',$filename)){
			$clic_id = substr($filename,0,strpos($filename,'_'));
		}else{
			$clic_id = Model()->cls()->table('album_pic')->getfby_apic_cover($filename,'clic_id');
		}
		$path = BASE_UPLOAD_PATH.'/'.ATTACH_doctorS.'/'.$clic_id.'/'.$filename;
		
		$ext = strrchr($path, '.');
		$type = array('_tiny','_small','_mid','_max','_240x240');
		foreach ($type as $v) {
			if (is_file($fpath = $path.$v.$ext)){
				unlink($fpath);
			}
		}
		if (is_file($path)) unlink($path);
	}
}
