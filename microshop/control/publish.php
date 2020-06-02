<?php
/**
 * 微商城发布 
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class publishControl extends MircroclinicControl{

	public function __construct() {
		parent::__construct();
        self::check_login();
        Tpl::output('index_sign','');
    }

	public function indexOp() {
        $this->doctors_buyOp();
	}

    public function doctors_buyOp() {
        $model_appointment = Model('appointment');

        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        $doctors_list = $model_appointment->getappointmentdoctorsList($condition, '*', null, 20);
        Tpl::output('list',$doctors_list);
        Tpl::output('doctors_type','buy');
        Tpl::output('show_page',$model_appointment->showpage());	
        $this->get_commend_doctors_list();
        //获得分享app列表
        self::get_share_app_list();
		Tpl::showpage('publish_doctors');
    }

    public function doctors_favoritesOp() {
		$model_favorites = Model('favorites');
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
		$favorites_list = $model_favorites->getdoctorsFavoritesList($condition, '*', 20);
        $doctors_list = array();
        if (!empty($favorites_list) && is_array($favorites_list)){
            $doctors_id_string = '';
            foreach ($favorites_list as $key=>$value){
                $doctors_id_string .= $value['fav_id'].',';
            }
            $doctors_id_string = rtrim($doctors_id_string,',');
            $model_doctors = Model('doctors');
            $doctors_list = $model_doctors->getdoctorsList(array('doctors_id'=>array('in', $doctors_id_string)));
        }
        Tpl::output('list',$doctors_list);
        Tpl::output('doctors_type','favorites');
        Tpl::output('show_page',$model_favorites->showpage());	
        $this->get_commend_doctors_list();

        //获得分享app列表
        self::get_share_app_list();
		Tpl::showpage('publish_doctors');
    }

    //获取已经推荐的列表
    private function get_commend_doctors_list() {
        $model_microclinic_doctors = Model('micro_doctors');
        $commend_doctors_list = $model_microclinic_doctors->getList(array('commend_member_id'=>$_SESSION['member_id']));
        $commend_doctors_array = array();
        if(!empty($commend_doctors_list)) {
            foreach ($commend_doctors_list as $value) {
                $commend_doctors_array[] = $value['commend_doctors_id'];
            }
        }
        Tpl::output('commend_doctors_array',$commend_doctors_array);
    }

    public function doctors_saveOp() {
        $model_doctors = Model('doctors');
        $model_microclinic_doctors = Model('micro_doctors');
        $doctors_id = intval($_POST['commend_doctors_id']);

        if(empty($doctors_id)) {
            showDialog(Language::get('wrong_argument'),'','error','');
        }
        $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id'=>$doctors_id));

        $model_doctors_relation = Model('micro_doctors_relation');
        $doctors_relation = $model_doctors_relation->getOne(array('clinic_class_id'=>$doctors_info['gc_id']));

        $commend_doctors_info = array();
        $commend_doctors_info['commend_member_id'] = $_SESSION['member_id'];
        $commend_doctors_info['commend_doctors_id'] = $doctors_info['doctors_id'];
        $commend_doctors_info['commend_doctors_commonid'] = $doctors_info['doctors_commonid'];
        $commend_doctors_info['commend_doctors_clic_id'] = $doctors_info['clic_id'];
        $commend_doctors_info['commend_doctors_name'] = $doctors_info['doctors_name'];
        $commend_doctors_info['commend_doctors_price'] = $doctors_info['doctors_price'];
        $commend_doctors_info['commend_doctors_image'] = $doctors_info['doctors_image'];
        if(empty($_POST['commend_message'])) { 
            $commend_doctors_info['commend_message'] = Language::get('microclinic_doctors_default_commend_message');
        } else {
            $commend_doctors_info['commend_message'] = trim($_POST['commend_message']);
        }
        $commend_doctors_info['commend_time'] = time();
        $commend_doctors_info['microclinic_commend'] = 0;
        $commend_doctors_info['microclinic_sort'] = 255;
        //没有建立分类绑定关系的，使用默认分类，没有设定默认分类的默认到第一个二级分类下
        if(empty($doctors_relation)) {
            $model_doctors_class = Model('micro_doctors_class');
            $default_class = $model_doctors_class->getOne(array('class_default'=>1));
            if(!empty($default_class)) {
                //默认分类
                $commend_doctors_info['class_id'] = $default_class['class_id'] ;
            } else {
                $condition = array();
                $condition['class_parent_id'] = array('gt',0);
                $doctors_class = $model_doctors_class->getOne($condition,'class_id asc'); 
                if(empty($doctors_class)) {
                    showDialog(Language::get('microclinic_doctors_class_none'),'reload','error','');
                } else {
                    $commend_doctors_info['class_id'] = $doctors_class['class_id'] ;
                }
            }
        } else {
            $commend_doctors_info['class_id'] = $doctors_relation['class_id'];
        }
        $result = $model_microclinic_doctors->save($commend_doctors_info);
        $message = Language::get('nc_common_save_fail');
            //分享内容
            if($result) {
                $message = Language::get('nc_common_save_succ');
                //计数
                $model_micro_member_info = Model('micro_member_info');
                $model_micro_member_info->updateMemberdoctorsCount($_SESSION['member_id'],'+');

                if(isset($_POST['share_app_items'])) {
                    $commend_doctors_info['type'] = 'doctors';
                    $commend_doctors_info['url'] = MICROclinic_SITE_URL.DS."index.php?act=doctors&op=detail&doctors_id=".$result;
                    self::share_app_publish('publish',$commend_doctors_info);
                }
            }
        showDialog($message,'reload',$result? 'succ' : 'error','');
    }

    /**
     * 个人秀图片上传
     **/
    public function personal_image_uploadOp() {
        $data = array();
        $data['status'] = 'success';
        if(isset($_SESSION['member_id'])) {
            if(!empty($_FILES['personal_image_ajax']['name'])) {
                $upload	= new UploadFile();
                $upload->set('default_dir',ATTACH_MICROclinic.DS.$_SESSION['member_id']);
                $upload->set('thumb_width','60,240');
                $upload->set('thumb_height', '5000,50000');
                $upload->set('thumb_ext',	'_tiny,_list');	

                $result = $upload->upfile('personal_image_ajax');
                if(!$result) {
                    $data['status'] = 'fail';
                    $data['error'] = $upload->error;
                }
                $data['file'] = $upload->file_name;
            }
        } else {
            $data['status'] = 'fail';
            $data['error'] = Language::get('no_login');
        }
        self::echo_json($data);
    }

    /**
     * 个人秀图片删除
     **/
    public function personal_image_deleteOp() {
        $data = array();
        $data['status'] = 'success';
        self::drop_personal_image($_GET['image_name']);
        self::echo_json($data);
    }

    /**
     * 个人秀数量限制检查
     **/
    public function personal_limitOp() {
        $result = $this->check_personal_limit();
        if($result) {
            self::return_json('','true');
        } else {
            self::return_json(Language::get('micro_personal_limit_error'),'false');
        }
    }

    //检查个人秀数量限制
    private function check_personal_limit() {
        $personal_limit = C('microclinic_personal_limit');
        if(empty($personal_limit)) {
            return TRUE;
        }
        $model = Model('micro_member_info');
        $micro_member_info = $model->getOneById($_SESSION['member_id']);
        if(empty($micro_member_info)) {
            return TRUE;
        }
        if($micro_member_info['personal_count'] < $personal_limit) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 个人秀购买链接添加
     */
    public function personal_link_addOp() {
        $link = urldecode($_GET['link']);
        if(empty($link)) {
            self::return_json(Language::get('wrong_argument'),'false');
        }
        $model_doctors_info = Model('doctors_info_by_url');
        $result = $model_doctors_info->get_doctors_info_by_url($link);
        if($result) {
            self::echo_json($result);
        } else {
            self::return_json(Language::get('microclinic_wrong_url'),'false');
        }
    }

    /**
     * 个人秀保存
     **/
    public function personal_saveOp() {
        $personal_limit = $this->check_personal_limit();
        if(!$personal_limit) {
            self::drop_personal_image($_POST['personal_image']);
            showDialog(Language::get('micro_personal_limit_error'),'','error','');
        }
        if(empty($_POST['personal_image'])) {
            showDialog(Language::get('wrong_argument'),'','error','');
        }
        $personal_info = array();
        $personal_info['class_id'] = intval($_POST['class_id']);
        if(empty($_POST['commend_message'])) { 
            $personal_info['commend_message'] = Language::get('microclinic_personal_default_commend_message');
        } else {
            $personal_info['commend_message'] = trim($_POST['commend_message']);
        }
        $personal_info['commend_member_id'] =  $_SESSION['member_id'];
        $personal_info['commend_image'] = trim($_POST['personal_image']);
        $personal_info['commend_time'] = time(); 
        $personal_info['class_id'] =  intval($_POST['personal_class']);
        $personal_link_array = array();
        if(!empty($_POST['personal_buy_link'])) {
            $model_doctors_info = Model('doctors_info_by_url');
            for ($i = 0,$count = count($_POST['personal_buy_link']); $i < $count; $i++) {
                $check_link = $model_doctors_info->check_personal_buy_link($_POST['personal_buy_link'][$i]);
                if($check_link) {
                    $personal_link_array[$i]['link'] = $_POST['personal_buy_link'][$i];
                    $personal_link_array[$i]['image'] = $_POST['personal_buy_image'][$i];
                    $personal_link_array[$i]['price'] = $_POST['personal_buy_price'][$i];
                    $personal_link_array[$i]['title'] = $_POST['personal_buy_title'][$i];
                }
            }
        }
        $personal_info['commend_buy'] = serialize($personal_link_array);
        $personal_info['microclinic_commend'] = 0;
        $personal_info['microclinic_sort'] = 255;

        $model_personal = Model('micro_personal');
        $result = $model_personal->save($personal_info);
        $message = Language::get('nc_common_save_fail');
        //分享内容
        if($result) {
            $message = Language::get('nc_common_save_succ');
            //计数
            $model_micro_member_info = Model('micro_member_info');
            $model_micro_member_info->updateMemberPersonalCount($_SESSION['member_id'],'+');
            if(isset($_POST['share_app_items'])) {
                $personal_info['type'] = 'personal';
                $personal_info['url'] = MICROclinic_SITE_URL.DS."index.php?act=personal&op=detail&personal_id=".$result;
                self::share_app_publish('publish',$personal_info);
            }
        }
        showDialog($message,MICROclinic_SITE_URL.DS.'index.php?act=home&op=personal',$result? 'succ' : 'error','');
    }



    public function albumOp() {
		Tpl::showpage('publish_album');
    }
}
