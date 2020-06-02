<?php
/**
 * 店铺分类管理
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

class clic_classControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('clic_class');
	}

	/**
	 * 店铺分类
	 */
	public function clic_classOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('clic_class');
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_sc_id'])){
				if (is_array($_POST['check_sc_id'])){
					$del_array = $model_class->getChildClass($_POST['check_sc_id']);
					if (is_array($del_array)){
						foreach ($del_array as $k => $v){
							$model_class->del($v['sc_id']);
						}
					}
				}
				$this->log(L('nc_del,clic_class').'[ID:'.implode(',',$_POST['check_sc_id']).']',1);
				showMessage($lang['nc_common_del_succ']);
			}else {
				showMessage($lang['nc_common_del_fail']);
			}
		}

		$parent_id = $_GET['sc_parent_id']?intval($_GET['sc_parent_id']):0;

		$tmp_list = $model_class->getTreeClassList(2);
		if (is_array($tmp_list)){
			foreach ($tmp_list as $k => $v){
				if ($v['sc_parent_id'] == $parent_id){
					//判断是否有子类
					if ($tmp_list[$k+1]['deep'] > $v['deep']){
						$v['have_child'] = 1;
					}
					$class_list[] = $v;
				}
			}
		}
		if ($_GET['ajax'] == '1'){
			//转码
			if (strtoupper(CHARSET) == 'GBK'){
				$class_list = Language::getUTF8($class_list);
			}
			$output = json_encode($class_list);
			print_r($output);
			exit;
		}else {
			Tpl::output('class_list',$class_list);
			Tpl::showpage('clic_class.index');
		}
	}

	/**
	 * 商品分类添加
	 */
	public function clic_class_addOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('clic_class');
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['clic_class_name_no_null']),
			array("input"=>$_POST["sc_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['clic_class_sort_only_number']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['sc_name'] = $_POST['sc_name'];
				$insert_array['sc_parent_id'] = intval($_POST['sc_parent_id']);
				$insert_array['sc_sort'] = intval($_POST['sc_sort']);
				$result = $model_class->add($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=clic_class&op=clic_class_add&sc_parent_id='.intval($_POST['sc_parent_id']),
					'msg'=>$lang['continue_add_clic_class'],
					),
					array(
					'url'=>'index.php?act=clic_class&op=clic_class',
					'msg'=>$lang['back_clic_class_list'],
					)
					);
					$this->log(L('nc_add,clic_class').'['.$_POST['sc_name'].']',1);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}
		//父类列表，只取到第三级
		$parent_list = $model_class->getTreeClassList(1);
		if (is_array($parent_list)){
			foreach ($parent_list as $k => $v){
				$parent_list[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
			}
		}

		Tpl::output('sc_parent_id',intval($_GET['sc_parent_id']));
		Tpl::output('parent_list',$parent_list);
		Tpl::showpage('clic_class.add');
	}

	/**
	 * 编辑
	 */
	public function clic_class_editOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('clic_class');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['clic_class_name_no_null']),
			array("input"=>$_POST["sc_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['clic_class_sort_only_number']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {

				$update_array = array();
				$update_array['sc_id'] = intval($_POST['sc_id']);
				$update_array['sc_name'] = $_POST['sc_name'];
				$update_array['sc_sort'] = $_POST['sc_sort'];
				$result = $model_class->update($update_array);
				if ($result){
					$this->log(L('nc_edit,clic_class').'['.$_POST['sc_name'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?act=clic_class&op=clic_class');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$class_array = $model_class->getOneClass(intval($_GET['sc_id']));
		if (empty($class_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('class_array',$class_array);
		Tpl::showpage('clic_class.edit');
	}

	/**
	 * 删除分类
	 */
	public function clic_class_delOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('clic_class');
		if (intval($_GET['sc_id']) > 0){
			$array = array(intval($_GET['sc_id']));

			$del_array = $model_class->getChildClass($array);
			if (is_array($del_array)){
				foreach ($del_array as $k => $v){
					$model_class->del($v['sc_id']);
				}
			}
			$this->log(L('nc_del,clic_class').'[ID:'.$_GET['sc_id'].']',1);
			showMessage($lang['nc_common_del_succ'],'index.php?act=clic_class&op=clic_class');
		}else {
			showMessage($lang['nc_common_del_fail'],'index.php?act=clic_class&op=clic_class');
		}
	}

	/**
	 * 分类导入
	 */
	public function clic_class_importOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('clic_class');

		if (chksubmit()){
			//得到导入文件后缀名
			$file_type = end(explode('.',$_FILES['csv']['name']));
			if (!empty($_FILES['csv']) && !empty($_FILES['csv']['name']) && $file_type == 'csv'){
				$fp = @fopen($_FILES['csv']['tmp_name'],'rb');
				//父ID
				$parent_id_1 = 0;

				while (!feof($fp)) {
					$data = fgets($fp, 4096);
					switch (strtoupper($_POST['charset'])){
						case 'UTF-8':
							if (strtoupper(CHARSET) !== 'UTF-8'){
								$data = iconv('UTF-8',strtoupper(CHARSET),$data);
							}
							break;
						case 'GBK':
							if (strtoupper(CHARSET) !== 'GBK'){
								$data = iconv('GBK',strtoupper(CHARSET),$data);
							}
							break;
					}

					if (!empty($data)){
						$data	= str_replace('"','',$data);
						//逗号去除
						$tmp_array = array();
						$tmp_array = explode(',',$data);
						if($tmp_array[0] == 'sort_appointment')continue;
						//第一位是序号，后面的是内容，最后一位名称
						$tmp_deep = 'parent_id_'.(count($tmp_array)-1);

						$insert_array = array();
						$insert_array['sc_sort'] = $tmp_array[0];
						$insert_array['sc_parent_id'] = $$tmp_deep;
						$insert_array['sc_name'] = $tmp_array[count($tmp_array)-1];
						$sc_id = $model_class->add($insert_array);
						//赋值这个深度父ID
						$tmp = 'parent_id_'.count($tmp_array);
						$$tmp = $sc_id;
					}
				}
				// 重新生成缓存
				$this->log(L('import,clic_class'),1);
				showMessage($lang['import_ok'],'index.php?act=clic_class&op=clic_class');
			}else {
				showMessage($lang['import_csv_no_null']);
			}
		}
		Tpl::showpage('clic_class.import');
	}

	/**
	 * 分类导出
	 */
	public function clic_class_exportOp(){

		$lang	= Language::getLangContent();
		if (chksubmit()){
			$model_class = Model('clic_class');
			//分类信息
			$class_list = $model_class->getTreeClassList();

			@header("Content-type: application/unknown");
			@header("Content-Disposition: attachment; filename=clic_class.csv");
			if (is_array($class_list)){
				foreach ($class_list as $k => $v){
					$tmp = array();
					//序号
					$tmp['sc_sort'] = $v['sc_sort'];
					//深度
					for ($i=1; $i<=($v['deep']-1); $i++){
						$tmp[] = '';
					}
					//名称
					$tmp['sc_name'] = replaceSpecialChar($v['sc_name']);
					//转码 utf-gbk
					if (strtoupper(CHARSET) == 'UTF-8'){
						switch ($_POST['if_convert']){
							case '1':
								$tmp_line = iconv('UTF-8','GB2312//IGNORE',join(',',$tmp));
								break;
							case '0':
								$tmp_line = join(',',$tmp);
								break;
						}
					}else {
						$tmp_line = join(',',$tmp);
					}
					$tmp_line = str_replace("\r\n",'',$tmp_line);
					echo $tmp_line."\r\n";
				}
			}
			exit;
		}
		Tpl::showpage('clic_class.export');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			//分类：验证是否有重复的名称
			case 'clic_class_name':
				$model_class = Model('clic_class');
				$class_array = $model_class->getOneClass(intval($_GET['id']));

				$condition['sc_name'] = trim($_GET['value']);
				$condition['sc_parent_id'] = $class_array['sc_parent_id'];
				$condition['no_sc_id'] = intval($_GET['id']);
				$class_list = $model_class->getClassList($condition);
				if (empty($class_list)){
					$update_array = array();
					$update_array['sc_id'] = intval($_GET['id']);
					$update_array['sc_name'] = trim($_GET['value']);
					$model_class->update($update_array);
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
			//分类： 排序 显示 设置
			case 'clic_class_sort':
				$model_class = Model('clic_class');
				$update_array = array();
				$update_array['sc_id'] = intval($_GET['id']);
				$update_array[$_GET['column']] = trim($_GET['value']);
				$result = $model_class->update($update_array);
				echo 'true';exit;
				break;
				/**
			 * 分类：添加、修改操作中 检测类别名称是否有重复
			 */
			case 'check_class_name':
				$model_class = Model('clic_class');
				$condition['sc_name'] = trim($_GET['sc_name']);
				$condition['sc_parent_id'] = intval($_GET['sc_parent_id']);
				$condition['no_sc_id'] = intval($_GET['sc_id']);
				$class_list = $model_class->getClassList($condition);
				if (empty($class_list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}
}