<?php
/**
 * 投诉
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
class member_complainControl extends BaseMemberControl{
    //定义投诉状态常量
    const STATE_NEW = 10;
    const STATE_APPEAL = 20;
    const STATE_TALK = 30;
    const STATE_HANDLE = 40;
    const STATE_FINISH = 99;
    const STATE_UNACTIVE = 1;
    const STATE_ACTIVE = 2;

    public function __construct() {
        parent::__construct() ;
        Language::read('member_layout,member_complain');
        //定义状态常量
    }

    /*
     * 我的投诉页面
     */
    public function indexOp() {
        $page = new Page() ;
        $page->setEachNum(10);
        $page->setStyle('admin') ;
        /*
         * 得到当前用户的投诉列表
         */
        $model_complain = Model('complain') ;
        $condition = array();
        $condition['appointment']        = 'complain_state asc,complain_id desc';
        $condition['accuser_id'] = $_SESSION['member_id'];
        switch(intval($_GET['select_complain_state'])) {
            case 1:
                $condition['progressing'] = 'true';
                break;
            case 2:
                $condition['finish'] = 'true';
                break;
            default :
                $condition['state'] = '';
        }
        $list = $model_complain->getComplain($condition, $page) ;
		//查询会员信息
		$this->get_member_info();
        $this->profile_menu('complain_accuser_list');
        Tpl::output('list', $list) ;
        Tpl::output('show_page', $page->show()) ;
        Tpl::output('menu_sign','complain');
        Tpl::showpage('complain.list');
    }

    /*
     * 新投诉
     */
    public function complain_newOp() {
        $appointment_id = intval($_GET['appointment_id']);
        //获取订单详细信息，并检查权限
        $appointment_info = $this->get_appointment_info($appointment_id);
        //检查是不是正在进行投诉
        if($this->check_complain_exist($appointment_id)) {
            showMessage(Language::get('complain_repeat'),'','html','error');//'您已经投诉了该订单请等待处理'
        }
        //检查订单状态是否可以投诉
        $complain_time_limit = intval($GLOBALS['setting_config']['complain_time_limit']);
        if(!empty($appointment_info['finnshed_time'])) {
            if((intval($appointment_info['finnshed_time'])+$complain_time_limit) < time()) {
                showMessage(Language::get('complain_time_limit'),'','html','error');//'您的订单已经超出投诉时限'
            }
        }
        //列出订单商品列表
        $appointment_doctors_list = $appointment_info['extend_appointment_doctors'];
        //买家未付款不能投诉
        if(intval($appointment_info['appointment_state']) < appointment_STATE_PAY) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        //获取投诉类型
        $model_complain_subject = Model('complain_subject');
        $param = array();
        $complain_subject_list = $model_complain_subject->getActiveComplainSubject($param);
        if(empty($complain_subject_list)) {
            showMessage(Language::get('complain_subject_error'),'','html','error');
        }
        $model_refund = Model('refund_return');
        $appointment_list[$appointment_id] = $appointment_info;
        $appointment_list = $model_refund->getdoctorsRefundList($appointment_list);
        if(intval($appointment_list[$appointment_id]['complain']) == 1) {//退款投诉
            $complain_subject = Model()->table('complain_subject')->where(array('complain_subject_id'=> 1))->select();//投诉主题
            $complain_subject_list = array_merge($complain_subject, $complain_subject_list);
        }

		//查询会员信息
		$this->get_member_info();
        Tpl::output('appointment_info',$appointment_info);
        Tpl::output('appointment_doctors_list',$appointment_doctors_list);
        Tpl::output('complain_info',$complain_info);
        Tpl::output('subject_list',$complain_subject_list);
		Tpl::output('left_show','appointment_view');
        Tpl::showpage('complain.submit');
    }

    /*
     * 处理投诉请求
     */
    public function complain_showOp() {
        $complain_id = intval($_GET['complain_id']);
        //获取投诉详细信息
        $complain_info = $this->get_complain_info($complain_id);
        //获取订单详细信息
        $appointment_info = $this->get_appointment_info($complain_info['appointment_id']);
        //获取投诉的商品列表
        $model_complain_doctors = Model('complain_doctors');
        $param = array();
        $param['complain_id'] = $complain_id;
        $complain_doctors_list = $model_complain_doctors->getComplaindoctors($param);
        $page_name = '';
        switch(intval($complain_info['complain_state'])) {
            case self::STATE_NEW:
                $page_name = 'complain.info';
                break;
            case self::STATE_APPEAL:
                $page_name = 'complain.info';
                break;
            case self::STATE_TALK:
                $page_name = 'complain.talk';
                break;
            case self::STATE_HANDLE:
                $page_name = 'complain.talk';
                break;
            case self::STATE_FINISH:
                $page_name = 'complain.info';
                break;
            default:
                showMessage(Language::get('para_error'),'','html','error');
        }
        Tpl::output('appointment_info',$appointment_info);
        Tpl::output('complain_info',$complain_info);
        Tpl::output('complain_doctors_list',$complain_doctors_list);
        Tpl::output('left_show','appointment_view');
        Tpl::showpage($page_name);
    }

    /*
     * 保存用户提交的投诉
     */
    public function complain_saveOp() {
        //获取输入的投诉信息
        $input = array();
        $input['appointment_id'] = intval($_POST['input_appointment_id']);
        //检查是不是正在进行投诉
        if($this->check_complain_exist($input['appointment_id'])) {
            showDialog(Language::get('complain_repeat'),'','error');
        }
        list($input['complain_subject_id'],$input['complain_subject_content']) = explode(',',trim($_POST['input_complain_subject']));
        $input['complain_content'] = trim($_POST['input_complain_content']);
        //验证输入的信息
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['complain_content'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('complain_content_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
        	showValidateError($error);
        }
        //获取有问题的商品
        $checked_doctors = $_POST['input_doctors_check'];
        $doctors_problem = $_POST['input_doctors_problem'];
        if(empty($checked_doctors)) {
            showDialog(Language::get('para_error'),'','error');
        }
        $appointment_info = $this->get_appointment_info($input['appointment_id']);
        $input['accuser_id'] = $appointment_info['buyer_id'];
        $input['accuser_name'] = $appointment_info['buyer_name'];
        $input['accused_id'] = $appointment_info['clic_id'];
        $input['accused_name'] = $appointment_info['clic_name'];
        //上传图片
        $complain_pic = array();
        $complain_pic[1] = 'input_complain_pic1';
        $complain_pic[2] = 'input_complain_pic2';
        $complain_pic[3] = 'input_complain_pic3';
        $pic_name = $this->upload_pic($complain_pic);
        $input['complain_pic1'] = $pic_name[1];
        $input['complain_pic2'] = $pic_name[2];
        $input['complain_pic3'] = $pic_name[3];
        $input['complain_datetime'] = time();
        $input['complain_state'] = self::STATE_NEW;
        $input['complain_active'] = self::STATE_UNACTIVE;
        //保存投诉信息
        $model_complain = Model('complain');
        $complain_id = $model_complain->saveComplain($input);
        //保存被投诉的商品详细信息
        $model_complain_doctors = Model('complain_doctors');
        $appointment_doctors_list = $appointment_info['extend_appointment_doctors'];
        foreach($appointment_doctors_list as $doctors) {
            $appointment_doctors_id = $doctors['rec_id'];
            if (array_key_exists($appointment_doctors_id,$checked_doctors)) {//验证提交的商品属于订单
                $input_checked_doctors['complain_id'] = $complain_id;
                $input_checked_doctors['appointment_doctors_id'] = $appointment_doctors_id;
                $input_checked_doctors['appointment_doctors_type'] = $doctors['doctors_type'];
                $input_checked_doctors['doctors_id'] = $doctors['doctors_id'];
                $input_checked_doctors['doctors_name'] = $doctors['doctors_name'];
                $input_checked_doctors['doctors_price'] = $doctors['doctors_price'];
                $input_checked_doctors['doctors_num'] = $doctors['doctors_num'];
                $input_checked_doctors['doctors_image'] = $doctors['doctors_image'];
                $input_checked_doctors['complain_message'] = $doctors_problem[$appointment_doctors_id];
                $model_complain_doctors->saveComplaindoctors($input_checked_doctors);
            }
        }
		showDialog(Language::get('complain_submit_success'),'index.php?act=member_complain','succ');
    }

    /*
     * 保存用户提交的补充证据
     */
    public function complain_add_picOp() {
    	$complain_id = intval($_GET['complain_id']);
    	//获取投诉详细信息
        $complain_info = $this->get_complain_info($complain_id);
    	if (chksubmit()){
            $where_array = array();
            $where_array['complain_id'] = $complain_id;
            //获取输入的投诉信息
            $input = array();
            $complain_pic = array();
            $complain_pic[1] = 'input_complain_pic1';
            $complain_pic[2] = 'input_complain_pic2';
            $complain_pic[3] = 'input_complain_pic3';
            $pic_name = $this->upload_pic($complain_pic);
            $input['complain_pic1'] = $pic_name[1];
            $input['complain_pic2'] = $pic_name[2];
            $input['complain_pic3'] = $pic_name[3];
            //保存投诉信息
            $model_complain = Model('complain');
            $model_complain->updateComplain($input,$where_array);
            showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
    	}
    	Tpl::output('complain_info',$complain_info);
        Tpl::showpage('complain_add_pic','null_layout');
    }

    /*
     * 取消用户提交的投诉
     */
    public function complain_cancelOp() {
        $complain_id = intval($_GET['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        if(intval($complain_info['complain_state']) === 10) {
            $pics = array();
            if(!empty($complain_info['complain_pic1'])) $pics[] = $complain_info['complain_pic1'];
            if(!empty($complain_info['complain_pic2'])) $pics[] = $complain_info['complain_pic2'];
            if(!empty($complain_info['complain_pic3'])) $pics[] = $complain_info['complain_pic3'];
            if(!empty($pics)) {//删除图片
                foreach($pics as $pic) {
                    $pic = BASE_UPLOAD_PATH.DS.ATTACH_PATH.DS.'complain'.DS.$pic;
                    if(file_exists($pic)) {
                        @unlink($pic);
                    }
                }
            }
            $model_complain = Model('complain');
            $model_complain->dropComplain(array('complain_id' => $complain_id));
            $model_complain_doctors = Model('complain_doctors');
            $model_complain_doctors->dropComplaindoctors(array('complain_id' => $complain_id));
            showDialog(Language::get('complain_cancel_success'),'reload','succ');
        } else {
        	showDialog(Language::get('complain_cancel_fail'),'','error');
        }
    }
    /*
     * 处理用户申请仲裁
     */
    public function apply_handleOp() {
        $complain_id = intval($_POST['input_complain_id']);
        //获取投诉详细信息
        $complain_info = $this->get_complain_info($complain_id);
        $complain_state = intval($complain_info['complain_state']);
        //检查当前是不是投诉状态
        if($complain_state < self::STATE_TALK || $complain_state === 99) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $update_array = array();
        $update_array['complain_state'] = self::STATE_HANDLE;
        $where_array = array();
        $where_array['complain_id'] = $complain_id;
        //保存投诉信息
        $model_complain = Model('complain');
        $complain_id = $model_complain->updateComplain($update_array,$where_array);
        showMessage(Language::get('handle_submit_success'),'index.php?act=member_complain');
    }

    /*
     * 根据投诉id获取投诉对话
     */
    public function get_complain_talkOp() {
        $complain_id = intval($_POST['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        $model_complain_talk = Model('complain_talk');
        $param = array();
        $param['complain_id'] = $complain_id;
        $complain_talk_list = $model_complain_talk->getComplainTalk($param);
        $talk_list = array();
        $i=0;
        foreach($complain_talk_list as $talk) {
            $talk_list[$i]['css'] = $talk['talk_member_type'];
            $talk_list[$i]['talk'] = date("Y-m-d H:i:s",$talk['talk_datetime']);
            switch($talk['talk_member_type']){
            case 'accuser':
                $talk_list[$i]['talk'] .= Language::get('complain_accuser');
                break;
            case 'accused':
                $talk_list[$i]['talk'] .= Language::get('complain_accused');
                break;
            case 'admin':
                $talk_list[$i]['talk'] .= Language::get('complain_admin');
                break;
            default:
                $talk_list[$i]['talk'] .= Language::get('complain_unknow');
            }
            if(intval($talk['talk_state']) === 2) {
                $talk['talk_content'] = Language::get('talk_forbit_message');
            }
            $talk_list[$i]['talk'].= '('.$talk['talk_member_name'].')'.Language::get('complain_text_say').':'.$talk['talk_content'];
            $i++;
        }
        if (strtoupper(CHARSET) == 'GBK') {
            $talk_list = Language::getUTF8($talk_list);
        }
        echo json_encode($talk_list);
    }

    /*
     * 根据发布投诉对话
     */
    public function publish_complain_talkOp() {
        $complain_id = intval($_POST['complain_id']);
        $complain_talk = trim($_POST['complain_talk']);
        $talk_len = strlen($complain_talk);
        if($talk_len > 0 && $talk_len < 255) {
            $complain_info = $this->get_complain_info($complain_id);
            $complain_state = intval($complain_info['complain_state']);
            //检查投诉是否是可发布对话状态
            if($complain_state > self::STATE_APPEAL && $complain_state < self::STATE_FINISH) {
                $model_complain_talk = Model('complain_talk');
                $param = array();
                $param['complain_id'] = $complain_id;
                $param['talk_member_id'] = $complain_info['accuser_id'];
                $param['talk_member_name'] = $complain_info['accuser_name'];
                $param['talk_member_type'] = $complain_info['member_status'];
                if (strtoupper(CHARSET) == 'GBK') {
                    $complain_talk = Language::getGBK($complain_talk);
                }
                $param['talk_content'] = $complain_talk;
                $param['talk_state'] =1;
                $param['talk_admin'] = 0;
                $param['talk_datetime'] = time();
                if($model_complain_talk->saveComplainTalk($param)) {
                    echo json_encode('success');
                } else {
                    echo json_encode('error2');
                }
            } else {
                echo json_encode('error');
            }
        } else {
            echo json_encode('error1');
        }
    }

    /*
     * 获取订单信息
     */
    private function get_appointment_info($appointment_id) {
        if(empty($appointment_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $model_appointment = Model('appointment');
        $appointment_info = $model_appointment->getappointmentInfo(array('appointment_id' => $appointment_id),array('appointment_doctors'));
        if($appointment_info['buyer_id'] != $_SESSION['member_id']) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $appointment_info['appointment_state_text'] = appointmentState($appointment_info);
        return $appointment_info;
    }

    /*
     * 获取投诉信息
     */
    private function get_complain_info($complain_id) {
        $model_complain = Model('complain');
        $complain_info = $model_complain->getoneComplain($complain_id);
        if($complain_info['accuser_id'] != $_SESSION['member_id']) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $complain_info['member_status'] = 'accuser';
        $complain_info['complain_state_text'] = $this->get_complain_state_text($complain_info['complain_state']);
        return $complain_info;
    }

    /*
     * 检查投诉是否已经存在
     */
    private function check_complain_exist($appointment_id) {
        $model_complain = Model('complain');
        $param = array();
        $param['appointment_id'] = $appointment_id;
        $param['accuser_id'] = $_SESSION['member_id'];
        $param['progressing'] = 'ture';
        return $model_complain->isExist($param);
    }

    /*
     * 获得投诉状态文本
     */
    private function get_complain_state_text($complain_state) {
        switch(intval($complain_state)) {
        case self::STATE_NEW:
            return Language::get('complain_state_new');
            break;
        case self::STATE_APPEAL:
            return Language::get('complain_state_appeal');
            break;
        case self::STATE_TALK:
            return Language::get('complain_state_talk');
            break;
        case self::STATE_HANDLE:
            return Language::get('complain_state_handle');
            break;
        case self::STATE_FINISH:
            return Language::get('complain_state_finish');
            break;
        default:
            showMessage(Language::get('para_error'),'','html','error');
        }
    }

    private function upload_pic($complain_pic) {
        $pic_name = array();
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'complain'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        $count = 1;
        foreach($complain_pic as $pic) {
            if (!empty($_FILES[$pic]['name'])){
                $result = $upload->upfile($pic);
                if ($result){
                    $pic_name[$count] = $upload->file_name;
                    $upload->file_name = '';
                } else {
                    $pic_name[$count] = '';
                }
            }
            $count++;
        }
        return $pic_name;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @param array 	$array		附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            array('menu_key'=>'complain_accuser_list','menu_name'=>Language::get('complain_manage_title'),'menu_url'=>'index.php?act=member_complain')
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
