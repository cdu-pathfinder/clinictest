<?php
/**
 * 微商城
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
class microclinicControl extends SystemControl{

    const MICROclinic_CLASS_LIST = 'index.php?act=microclinic&op=doctorsclass_list';
    const doctorS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const clic_FLAG = 4;

	public function __construct(){
		parent::__construct();
		Language::read('clic');
		Language::read('microclinic');
	}

//	public function indexOp() {
//        $this->manageOp();
//	}

	/**
	 * 微商城管理
	 */
	public function manageOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        $this->show_menu('manage');
        Tpl::showpage('microclinic_manage');
	}

	/**
	 * 微商城管理保存
	 */
	public function manage_saveOp() {
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['microclinic_isuse'] = intval($_POST['microclinic_isuse']);
        $update_array['microclinic_style'] = trim($_POST['microclinic_style']);
        $update_array['microclinic_personal_limit'] = intval($_POST['microclinic_personal_limit']);
        $old_image = array();
        if(!empty($_FILES['microclinic_logo']['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_MICROclinic);
            $result = $upload->upfile('microclinic_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['microclinic_logo'] = $upload->file_name;
            $old_image[] = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.C('microclinic_logo');
        }
        if(!empty($_FILES['microclinic_header_pic']['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_MICROclinic);
            $result = $upload->upfile('microclinic_header_pic');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['microclinic_header_pic'] = $upload->file_name;
            $old_image[] = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.C('microclinic_header_pic');
        }
        $update_array['taobao_api_isuse'] = intval($_POST['taobao_api_isuse']);
        $update_array['taobao_app_key'] = $_POST['taobao_app_key'];
        $update_array['taobao_secret_key'] = $_POST['taobao_secret_key'];
        $update_array['microclinic_seo_keywords'] = $_POST['microclinic_seo_keywords'];
        $update_array['microclinic_seo_description'] = $_POST['microclinic_seo_description'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            if(!empty($old_image)) {
                foreach ($old_image as $value) {
                    if(is_file($value)) {
                        unlink($value);
                    }
                }
            }
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            showMessage(Language::get('nc_common_save_fail'));
        }
	}

    /**
     * 微商城商品(随心看)分类管理
     **/
    public function doctorsclass_listOp() {
        $this->class_list('doctors');
    }

    /**
     * 微商城商品(随心看)分类管理
     **/
    public function personalclass_listOp() {
        $this->class_list('personal');
    }

    private function class_list($type) {
        $model_class = Model("micro_{$type}_class");
        $list = $model_class->getList(TRUE);
        Tpl::output('list',$list);
        $menu_function = "show_menu_{$type}_class";
        $this->{$menu_function}("{$type}_class_list");
        Tpl::showpage("microclinic_{$type}_class.list");
    }

    /**
     * 微商城商品(随心看)分类添加
     **/
    public function doctorsclass_addOp() {
        //取得一级分类列表
        $model_microclinic_doctors_class = Model('micro_doctors_class');
        $condition = array();
        $condition['class_parent_id'] = 0;
        $doctors_class_list = $model_microclinic_doctors_class->getList($condition);
        Tpl::output('list',$doctors_class_list);

        $class_parent_id = intval($_GET['class_parent_id']);
        if(!empty($class_parent_id)) {
            Tpl::output('class_parent_id',$class_parent_id);
        }

        $this->show_menu_doctors_class('doctors_class_add');
        Tpl::showpage('microclinic_doctors_class.add');
    }

    /**
     * 微商城个人秀分类添加
     **/
    public function personalclass_addOp() {
        $this->show_menu_personal_class('personal_class_add');
        Tpl::showpage('microclinic_personal_class.add');
    }


    /**
     * 微商城商品(随心看)分类编辑
     **/
    public function doctorsclass_editOp() {
        $this->class_edit('doctors');
    }

    /**
     * 微商城商品(随心看)分类编辑
     **/
    public function personalclass_editOp() {
        $this->class_edit('personal');
    }

    private function class_edit($type) {
        $class_id = intval($_GET['class_id']);
        if(empty($class_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_class = Model("micro_{$type}_class");
        $condition = array();
        $condition['class_id'] = $class_id;
        $class_info = $model_class->getOne($condition);
        Tpl::output('class_info',$class_info);

        $menu_function = "show_menu_{$type}_class";
        $this->{$menu_function}("{$type}_class_edit");
        Tpl::showpage("microclinic_{$type}_class.add");
    }

    /**
     * 微商城商品(随心看)分类保存
     **/
    public function doctorsclass_saveOp() {
        $this->class_save('doctors');
    }

    /**
     * 微商城个人秀分类保存
     **/
    public function personalclass_saveOp() {
        $this->class_save('personal');
    }

    /**
     * 微商城商品(随心看)分类保存
     **/
    private function class_save($type) {

        $obj_validate = new Validate();
        $validate_array = array( 
            array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
            array('input'=>$_POST['class_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        if($type == 'doctors') {
            $validate_array[] = array('input'=>$_POST['class_parent_id'],'require'=>'true','validator'=>'Number','message'=>Language::get('parent_id_error'));
        }
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();			
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['class_name'] = trim($_POST['class_name']);
        if(isset($_POST['class_parent_id']) && intval($_POST['class_parent_id']) > 0) {
            $param['class_parent_id'] = $_POST['class_parent_id'];
        }
        if(isset($_POST['class_keyword'])) {
            $param['class_keyword'] = $_POST['class_keyword'];
        }
        $param['class_sort'] = intval($_POST['class_sort']);
        if(!empty($_FILES['class_image']['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_MICROclinic);
            $result = $upload->upfile('class_image');
            if(!$result) {
                showMessage($upload->error);
            }
            $param['class_image'] = $upload->file_name;
            //删除老图片
            if(!empty($_POST['old_class_image'])) {
                $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$_POST['old_class_image'];
                if(is_file($old_image)) {
                    unlink($old_image);
                }
            }
        }

        $model_class = Model("micro_{$type}_class");
        if(isset($_POST['class_id']) && intval($_POST['class_id']) > 0) {
            $result = $model_class->modify($param,array('class_id'=>$_POST['class_id']));
        } else {
            $result = $model_class->save($param);
        }
        if($result) {
            showMessage(Language::get('class_add_success'),"index.php?act=microclinic&op={$type}class_list");
        } else {
            showMessage(Language::get('class_add_fail'),"index.php?act=microclinic&op={$type}class_list",'','error');
        }

    }

    /* 
     * ajax修改分类排序
     */
    public function doctorsclass_sort_updateOp() {
        $this->update_class_sort('doctors');
    }
    public function personalclass_sort_updateOp() {
        $this->update_class_sort('personal');
    }
    private function update_class_sort($type) {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("micro_{$type}_class");
            $result = $model_class->modify(array('class_sort'=>$new_sort),array('class_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /* 
     * ajax修改分类名称
     */
    public function doctorsclass_name_updateOp() {
        $this->update_class_name('doctors');
    }
    public function personalclass_name_updateOp() {
        $this->update_class_name('personal');
    }
    private function update_class_name($type) {
        $class_id = intval($_GET['id']);
        if($class_id <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }

        $new_name = trim($_GET['value']);
        $obj_validate = new Validate();
        $obj_validate->validateparam = array( 
            array('input'=>$new_name,'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
        );
        $error = $obj_validate->validate();			
        if ($error != ''){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_name_error')));
            die;
		} else {
            $model_class = Model("micro_{$type}_class");
            $result = $model_class->modify(array('class_name'=>$new_name),array('class_id'=>$class_id));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }

    }

    /**
     * 随心看分类删除
     **/
     public function doctorsclass_dropOp() {

        $class_id = trim($_POST['class_id']);
        $model_microclinic_doctors_class = Model('micro_doctors_class');
        $condition = array();
        $condition['class_parent_id'] = array('in',$class_id);
        $doctors_class_list = $model_microclinic_doctors_class->getList($condition,'','','class_id');
        if(!empty($doctors_class_list) && is_array($doctors_class_list)) {
            foreach($doctors_class_list as $val) {
                $class_id .= ','.$val['class_id']; 
            }
        }
        $class_id = rtrim($class_id,',');
        $condition = array();
        $condition['class_id'] = array('in',$class_id);
        //删除分类图片
        $list = $model_microclinic_doctors_class->getList($condition);
        if(!empty($list)) {
            foreach ($list as $value) {
                if(!empty($value['class_image'])) {
                    //删除老图片
                    $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$value['class_image'];
                    if(is_file($old_image)) {
                        unlink($old_image);
                    }
                }
            }
        }

        //删除绑定关系
        $model_microclinic_doctors_relation = Model('micro_doctors_relation');
        $model_microclinic_doctors_relation->drop($condition);

        //删除分类
        $result = $model_microclinic_doctors_class->drop($condition);
        if($result) {
            showMessage(Language::get('class_drop_success'),'');
        } else {
            showMessage(Language::get('class_drop_fail'),'','','error');
        }

     }

    /**
     * 个人秀分类删除
     **/
     public function personalclass_dropOp() {

        $class_id = trim($_POST['class_id']);
        $model_class = Model('micro_personal_class');
        $condition = array();
        $condition['class_id'] = array('in',$class_id);
        //删除分类图片
        $list = $model_class->getList($condition);
        if(!empty($list)) {
            foreach ($list as $value) {
                //删除老图片
                if(!empty($value['class_image'])) {
                    $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$value['class_image'];
                    if(is_file($old_image)) {
                        unlink($old_image);
                    }
                }
            }
        }

        $result = $model_class->drop($condition);
        if($result) {
            showMessage(Language::get('class_drop_success'),'');
        } else {
            showMessage(Language::get('class_drop_fail'),'','','error');
        }

     }

    /**
     * 分类关键字和商品分类的绑定
     **/
    public function doctorsclass_bindingOp() {

        $class_id = intval($_GET['class_id']);
        if($class_id <= 0) {
            showMessage(Language::get('param_error'),'','','error');
        }
        Tpl::output('class_id',$class_id);

		$model_class = Model('doctors_class');
        $doctors_class_list = ($g = H('doctors_class')) ? $g : H('doctors_class',true);
        $doctors_class_root = array();
        foreach($doctors_class_list as $val) {
            if($val['gc_parent_id'] == '0') {
                $doctors_class_root[] = $val;
            }
        }
        Tpl::output('doctors_class_root',$doctors_class_root);
        Tpl::output('doctors_class',$doctors_class_list);

        $model_doctors_relation = Model('micro_doctors_relation');
        $class_binding_list = $model_doctors_relation->getList(array('class_id'=>$class_id));
        Tpl::output('class_binding_list',$class_binding_list);
        $class_binding_string = '';
        if(!empty($class_binding_list)) {
            foreach ($class_binding_list as $val) {
                $class_binding_string .= $val['clinic_class_id'].',';
            }
        }
        Tpl::output('class_binding_string',rtrim($class_binding_string,','));

        $this->show_menu_doctors_class('doctors_class_binding');
        Tpl::showpage('microclinic_doctors_class.binding');

    }

    /**
     * 分类关键字和商品分类的绑定保存
     **/
    public function doctorsclass_binding_saveOp() {
        $class_id = intval($_POST['class_id']);
        $clinic_class_id = trim($_POST['clinic_class_id']);
        $clinic_class_array = explode(',',$clinic_class_id);
        $param = array();
        foreach($clinic_class_array as $val) {
            if(!empty($val)) {
                $param[] = array('class_id'=>$class_id,'clinic_class_id'=>$val);
            }
        }
        $model_doctors_relation = Model('micro_doctors_relation');
        $model_doctors_relation->drop(array('class_id'=>$class_id));
        $result = $model_doctors_relation->saveAll($param);
        if($result) {
            showMessage(Language::get('doctors_relation_save_success'),self::MICROclinic_CLASS_LIST);
        } else {
            showMessage(Language::get('doctors_relation_save_fail'),self::MICROclinic_CLASS_LIST,'','error');
        }
    }

    /**
     * 设为默认分类
     **/
    public function doctorsclass_defaultOp() {
        $class_id = intval($_GET['class_id']);
        if($class_id <= 0) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_doctors_class = Model('micro_doctors_class');
        $model_doctors_class->modify(array('class_default'=>0),TRUE);
        $result = $model_doctors_class->modify(array('class_default'=>1),array('class_id'=>$class_id));
        if($result) {
            showMessage(Language::get('nc_common_op_succ'),'');
        } else {
            showMessage(Language::get('nc_common_op_fail'),'','','error');
        }
    }

    public function doctorsclass_getOp() {

        $doctors_class_id = intval($_GET['class_id']);
        $doctors_class_list = ($g = H('doctors_class')) ? $g : H('doctors_class',true);
        if(empty($doctors_class_list[$doctors_class_id]['childchild'])) {
            if(empty($doctors_class_list[$doctors_class_id]['child'])) {
                $doctors_class_child = $doctors_class_id;
            } else {
                $doctors_class_child = $doctors_class_list[$doctors_class_id]['child'];
            }
        } else {
            $doctors_class_child = $doctors_class_list[$doctors_class_id]['childchild'];
        }
        $doctors_class_child = explode(',',$doctors_class_child);

        $model_doctors_relation = Model('micro_doctors_relation');
        $doctors_relation_list = $model_doctors_relation->getList(TRUE);
        $doctors_id_list = array();
        $doctors_class_selected_list = array();
        if(!empty($doctors_relation_list) && is_array($doctors_relation_list)) {
            foreach($doctors_relation_list as $val) {
                $doctors_class_selected_list[] = $val['clinic_class_id'];
            }
        }

        $doctors_class_child_array = array();
        if(!empty($doctors_class_child) && is_array($doctors_class_child)) {
            foreach($doctors_class_child as $val) {
                if(in_array($val,$doctors_class_selected_list)) {
                    $doctors_class_list[$val]['selected'] = TRUE;
                }
                $doctors_class_child_array[] = $doctors_class_list[$val];
            }
        }
        echo json_encode($doctors_class_child_array);
        die;
        
    }

	/**
	 * 随心看管理
	 */
	public function doctors_manageOp() {
        $model_microclinic_doctors = Model('micro_doctors');
        $condition = array();
        if(!empty($_GET['commend_id']) && intval($_GET['commend_id']) > 0 ) {
            $condition['commend_id'] = $_GET['commend_id'];
        }
        if(!empty($_GET['member_name'])) {
            $condition['member_name'] = array('like','%'.trim($_GET['member_name']).'%');
        }
        if(!empty($_GET['commend_doctors_name'])) {
            $condition['commend_doctors_name'] = array('like','%'.trim($_GET['commend_doctors_name']).'%');
        }
        if(!empty($_GET['commend_time_from']) && !empty($_GET['commend_time_to'])) {
            $condition['commend_time'] = array('between',strtotime($_GET['commend_time_from']).','.strtotime($_GET['commend_time_to']));
        }
        $field = 'micro_doctors.*,member.member_name,member.member_avatar';
        $list = $model_microclinic_doctors->getListWithUserInfo($condition,10,'commend_time desc',$field);
        Tpl::output('show_page',$model_microclinic_doctors->showpage(2));	
        Tpl::output('list',$list);
        $this->show_menu('doctors_manage');
        Tpl::showpage('microclinic_doctors.manage');
    }

    /**
     * 随心看删除 
     */
    public function doctors_dropOp() {
        $model = Model('micro_doctors');
        $condition = array();
        $condition['commend_id'] = array('in',trim($_POST['commend_id']));
        //删除随心看图片
        $list = $model->getList($condition);
        if(!empty($list)) {
            foreach ($list as $info) {
                //计数
                $model_micro_member_info = Model('micro_member_info');
                $model_micro_member_info->updateMemberdoctorsCount($info['commend_member_id'],'-');
            }
        }
        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

	/**
	 * 个人秀管理
	 */
	public function personal_manageOp() {
        $model_personal = Model('micro_personal');
        $condition = array();
        if(!empty($_GET['personal_id']) && intval($_GET['personal_id']) > 0 ) {
            $condition['personal_id'] = $_GET['personal_id'];
        }
        if(!empty($_GET['member_name'])) {
            $condition['member_name'] = array('like','%'.trim($_GET['member_name']).'%');
        }
        if(!empty($_GET['commend_time_from']) && !empty($_GET['commend_time_to'])) {
            $condition['commend_time'] = array('between',strtotime($_GET['commend_time_from']).','.strtotime($_GET['commend_time_to']));
        }
        $field = 'micro_personal.*,member.member_name,member.member_avatar';
        $list = $model_personal->getListWithUserInfo($condition,10,'commend_time desc',$field);
        Tpl::output('show_page',$model_personal->showpage(2));	
        Tpl::output('list',$list);
        $this->show_menu('personal_manage');
        Tpl::showpage('microclinic_personal.manage');
    }

    /**
     * 随心看删除 
     */
    public function personal_dropOp() {
        $model = Model('micro_personal');
        $condition = array();
        $condition['personal_id'] = array('in',trim($_POST['personal_id']));

        //删除随心看图片
        $list = $model->getList($condition);
        if(!empty($list)) {
            foreach ($list as $personal_info) {
                //计数
                $model_micro_member_info = Model('micro_member_info');
                $model_micro_member_info->updateMemberPersonalCount($personal_info['commend_member_id'],'-');

                $image_array = explode(',',$personal_info['commend_image']);
                foreach ($image_array as $value) {
                    //删除原始图片
                    $image_name = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$personal_info['commend_member_id'].DS.$value;
                    if(is_file($image_name)) {
                        unlink($image_name);
                    }
                    //删除列表图片
                    $ext = explode('.', $value);
                    $ext = $ext[count($ext) - 1];
                    $image_name = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$personal_info['commend_member_id'].DS.$value.'_list.'.$ext;
                    if(is_file($image_name)) {
                        unlink($image_name);
                    }
                    $image_name = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.$personal_info['commend_member_id'].DS.$value.'_tiny.'.$ext;
                    if(is_file($image_name)) {
                        unlink($image_name);
                    }
                }
            }
        }
 
        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

	/**
	 * 店铺管理
	 */
	public function clic_manageOp(){
		$model_clic = Model('micro_clic');

        $condition = array();
        if(!empty($_GET['owner_and_name'])) {
            $condition['member_name'] = array('like','%'.trim($_GET['owner_and_name']).'%');
        }
        if(!empty($_GET['clic_name'])) {
            $condition['clic_name'] = array('like','%'.trim($_GET['clic_name']).'%');
        }
		$clic_list = $model_clic->getListWithclicInfo($condition,10);
		Tpl::output('clic_list',$clic_list);

        $this->show_menu_clic('clic_manage');
        Tpl::output('show_page',$model_clic->showpage(2));
        Tpl::showpage('microclinic_clic.manage');
    }
	

	/**
	 * 店铺街添加列表
	 */
	public function clic_addOp(){
		$model_clic = Model('clic');
		$model_microclinic_clic = Model('micro_clic');

        $microclinic_clic_list = $model_microclinic_clic->getList(TRUE);
        $microclinic_clic_array = array();
        if(!empty($microclinic_clic_list)) {
            foreach($microclinic_clic_list as $value) {
                $microclinic_clic_array[] = $value['clinic_clic_id'];
            }
        }
		Tpl::output('microclinic_clic_array',$microclinic_clic_array);

		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');

        $condition = array();
		$condition['member_name'] = array('like', '%'.$_GET['owner_and_name'].'%');
		$condition['clic_name'] = array('like', '%'.$_GET['clic_name'].'%');
		$clic_list = $model_clic->getclicOnlineList($condition,$page);
		Tpl::output('clic_list',$clic_list);

		Tpl::output('page',$page->show());
        $this->show_menu_clic('clic_add');
        Tpl::showpage('microclinic_clic.add');
	}

    /**
     * 店铺街添加保存
     */
    public function clic_add_saveOp() {
        $clic_id_array = explode(',',$_POST['clic_id']);
        $param = array();
        if(!empty($clic_id_array)) {
            foreach ($clic_id_array as $value) {
                if(intval($value) > 0) {
                    $microclinic_clic['clinic_clic_id'] = $value;
                    $microclinic_clic['microclinic_sort'] = 255;
                    $microclinic_clic['microclinic_commend'] = 0;
                    $param[] = $microclinic_clic;
                }
            }
        }
        $model_clic = Model('micro_clic');
        $result = $model_clic->saveAll($param);
        if($result) {
            showMessage(Language::get('nc_common_op_succ'),'');
        } else {
            showMessage(Language::get('nc_common_op_fail'),'','','error');
        }
    }

    /**
     * 店铺街删除保存 
     */
    public function clic_drop_saveOp() {
        $model_clic = Model('micro_clic');
        $condition = array();
        $condition['clinic_clic_id'] = array('in',trim($_POST['clic_id']));
        $result = $model_clic->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

    /**
     * 更新微商城店铺排序
     */
    public function clic_sort_updateOp() {
        $this->update_microclinic_sort('micro_clic','clinic_clic_id');
    }

    /**
     * 更新微商城随心看排序
     */
    public function doctors_sort_updateOp() {
        $this->update_microclinic_sort('micro_doctors','commend_id');
    }

    /**
     * 更新微商城个人秀排序
     */
    public function personal_sort_updateOp() {
        $this->update_microclinic_sort('micro_personal','personal_id');
    }

    private function update_microclinic_sort($model_name,$key_name) {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('microclinic_sort_error')));
            die;
        } else {
            $model_class = Model($model_name);
            $result = $model_class->modify(array('microclinic_sort'=>$new_sort),array($key_name=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'nc_common_op_succ'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('nc_common_op_fail')));
                die;
            }
        }
    }

    /**
     * 评论管理
     */
	public function comment_manageOp() {
        $condition = array();
        if(!empty($_GET['comment_id'])) {
            $condition['comment_id'] = intval($_GET['comment_id']);
        }
        if(!empty($_GET['member_name'])) {
            $condition['member_name'] = array('like','%'.trim($_GET['member_name']).'%');
        }
        if(!empty($_GET['comment_type'])) {
            $condition['comment_type'] = intval($_GET['comment_type']);
        }
        if(!empty($_GET['comment_object_id'])) {
            $condition['comment_object_id'] = intval($_GET['comment_object_id']);
        }
        if(!empty($_GET['comment_message'])) {
            $condition['comment_message'] = array('like','%'.trim($_GET['comment_message']).'%');
        }
        $model_comment = Model("micro_comment");
        $comment_list = $model_comment->getListWithUserInfo($condition,10,'comment_time desc');
        Tpl::output('list',$comment_list);
        Tpl::output('show_page',$model_comment->showpage(2));	
        $this->get_channel_array();
        $this->show_menu('comment_manage');
        Tpl::showpage('microclinic_comment.manage');
    }

    /**
     * 评论删除 
     */
    public function comment_dropOp() {
        $model = Model('micro_comment');
        $condition = array();
        $condition['comment_id'] = array('in',trim($_POST['comment_id']));
        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

	/**
	 * 广告管理
	 */
	public function adv_manageOp() {
        $model_personal = Model('micro_adv');
        $condition = array();
        if(!empty($_GET['adv_type'])) {
            $condition['adv_type'] = array('like','%'.trim($_GET['adv_type']).'%');
        }
        if(!empty($_GET['adv_name'])) {
            $condition['adv_name'] = array('like','%'.trim($_GET['adv_name']).'%');
        }
        $list = $model_personal->getList($condition,10,'','*');
        Tpl::output('show_page',$model_personal->showpage(2));	
        Tpl::output('list',$list);
        $this->get_adv_type_list();
        $this->show_menu_adv('adv_manage');
        Tpl::showpage('microclinic_adv.manage');
    }

    /**
     * 微商城广告添加
     **/
    public function adv_addOp() {
        $this->get_adv_type_list();
        $this->show_menu_adv('adv_add');
        Tpl::showpage('microclinic_adv.add');
    }

    public function adv_editOp() {
        $adv_id = intval($_GET['adv_id']);
        if(empty($adv_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_adv = Model("micro_adv");
        $condition = array();
        $condition['adv_id'] = $adv_id;
        $adv_info = $model_adv->getOne($condition);
        Tpl::output('adv_info',$adv_info);

        $this->get_adv_type_list();
        $this->show_menu_adv('adv_add');
        Tpl::showpage("microclinic_adv.add");
    }

    public function adv_saveOp() {
        $obj_validate = new Validate();
        $validate_array = array( 
            array('input'=>$_POST['adv_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();			
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['adv_type'] = trim($_POST['adv_type']);
        $param['adv_name'] = trim($_POST['adv_name']);
        $param['adv_url'] = trim($_POST['adv_url']);
        $param['adv_sort'] = intval($_POST['adv_sort']);
        if(!empty($_FILES['adv_image']['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_MICROclinic.DS.'adv');
            $result = $upload->upfile('adv_image');
            if(!$result) {
                showMessage($upload->error);
            }
            $param['adv_image'] = $upload->file_name;
            //删除老图片
            if(!empty($_POST['old_adv_image'])) {
                $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.'adv'.DS.$_POST['old_adv_image'];
                if(is_file($old_image)) {
                    unlink($old_image);
                }
            }
        } else {
            if(empty($_POST['adv_id'])) {
                showMessage(Language::get('microclinic_adv_image_error'),'','','error');
            }
        }

        $model_adv = Model("micro_adv");
        if(isset($_POST['adv_id']) && intval($_POST['adv_id']) > 0) {
            $result = $model_adv->modify($param,array('adv_id'=>$_POST['adv_id']));
        } else {
            $result = $model_adv->save($param);
        }
        if($result) {
            showMessage(Language::get('nc_common_save_succ'),"index.php?act=microclinic&op=adv_manage");
        } else {
            showMessage(Language::get('nc_common_save_fail'),"index.php?act=microclinic&op=adv_manage",'','error');
        }
    }

    /**
     * 广告删除 
     */
    public function adv_dropOp() {
        $model = Model('micro_adv');
        $condition = array();
        $condition['adv_id'] = array('in',trim($_POST['adv_id']));

        //删除图片
        $list = $model->getList($condition);
        if(!empty($list)) {
            foreach ($list as $adv_info) {
                //删除原始图片
                $image_name = BASE_UPLOAD_PATH.DS.ATTACH_MICROclinic.DS.'adv'.DS.$adv_info['adv_image'];
                if(is_file($image_name)) {
                    unlink($image_name);
                }
            }
        }

        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

    /**
     * 广告排序
     */
    public function adv_sort_updateOp() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("micro_adv");
            $result = $model_class->modify(array('adv_sort'=>$new_sort),array('adv_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>''));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>''));
                die;
            }
        }
    }


    //微商城广告类型列表
    private function get_adv_type_list() {
        $adv_type_list = array();
        $adv_type_list['index'] = Language::get('microclinic_adv_type_index');
        $adv_type_list['clic_list'] = Language::get('microclinic_adv_type_clic_list');
        Tpl::output('adv_type_list',$adv_type_list);
    }

	/**
	 * ajax操作
	 */
	public function ajaxOp(){

		switch ($_GET['branch']){
            //随心看推荐
			case 'doctors_commend':
                if(intval($_GET['id']) > 0) {
                    $model= Model('micro_doctors');
                    $condition['commend_id'] = intval($_GET['id']);
                    $update[$_GET['column']] = trim($_GET['value']);
                    $model->modify($update,$condition);
                    echo 'true';die;
                } else {
                    echo 'false';die;
                }
                break;
            //个人秀推荐
			case 'personal_commend':
                if(intval($_GET['id']) > 0) {
                    $model= Model('micro_personal');
                    $condition['personal_id'] = intval($_GET['id']);
                    $update[$_GET['column']] = trim($_GET['value']);
                    $model->modify($update,$condition);
                    echo 'true';die;
                } else {
                    echo 'false';die;
                }
                break;
            //店铺街推荐
			case 'clic_commend':
                if(intval($_GET['id']) > 0) {
                    $model= Model('micro_clic');
                    $condition['clinic_clic_id'] = intval($_GET['id']);
                    $update[$_GET['column']] = trim($_GET['value']);
                    $model->modify($update,$condition);
                    echo 'true';die;
                } else {
                    echo 'false';die;
                }
                break;
            //随心看分类推荐
			case 'class_commend':
                if(intval($_GET['id']) > 0) {
                    $model= Model('micro_doctors_class');
                    $condition['class_id'] = intval($_GET['id']);
                    $update[$_GET['column']] = trim($_GET['value']);
                    $model->modify($update,$condition);
                    echo 'true';die;
                } else {
                    echo 'false';die;
                }
                break;
		}
	}

    /**
     * 获取频道数组
     */
    private function get_channel_array() {
        $channel_array = array();
        $channel_array[self::doctorS_FLAG] = array('name'=>Language::get('nc_microclinic_doctors'),'key'=>'doctors');
        $channel_array[self::PERSONAL_FLAG] = array('name'=>Language::get('nc_microclinic_personal'),'key'=>'personal');
        $channel_array[self::clic_FLAG] = array('name'=>Language::get('nc_microclinic_clic'),'key'=>'clic');
        Tpl::output('channel_array',$channel_array);
    }


    /**
     * 微商城菜单
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            "{$menu_key}"=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=microclinic&op='.$menu_key),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

    private function show_menu_doctors_class($menu_key) {
        $menu_array = array(
            'doctors_class_list'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=microclinic&op=doctorsclass_list'),
            'doctors_class_add'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_new'),'menu_url'=>'index.php?act=microclinic&op=doctorsclass_add'),
        );
        if($menu_key == 'doctors_class_edit') {
            $menu_array['doctors_class_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('nc_edit'),'menu_url'=>'###');
        }
        if($menu_key == 'doctors_class_binding') {
            $menu_array['doctors_class_binding'] = array('menu_type'=>'link','menu_name'=>Language::get('microclinic_doctors_class_binding'),'menu_url'=>'###');
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

    private function show_menu_personal_class($menu_key) {
        $menu_array = array(
            'personal_class_list'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=microclinic&op=personalclass_list'),
            'personal_class_add'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_new'),'menu_url'=>'index.php?act=microclinic&op=personalclass_add'),
        );
        if($menu_key == 'personal_class_edit') {
            $menu_array['personal_class_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('nc_edit'),'menu_url'=>'###');
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

    private function show_menu_clic($menu_key) {
        $menu_array = array(
            'clic_manage'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=microclinic&op=clic_manage'),
            'clic_add'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_new'),'menu_url'=>'index.php?act=microclinic&op=clic_add'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

    private function show_menu_adv($menu_key) {
        $menu_array = array(
            'adv_manage'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=microclinic&op=adv_manage'),
            'adv_add'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_new'),'menu_url'=>'index.php?act=microclinic&op=adv_add'),
        );

        if($menu_key == 'adv_edit') {
            $menu_array['adv_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('nc_edit'),'menu_url'=>'###');
            unset($menu_array['adv_add']);
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

}

