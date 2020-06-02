<?php
/**
 * 会员中心——买家评价
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class member_evaluateControl extends BaseMemberControl{
    public function __construct(){
        parent::__construct() ;
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act','member_evaluate');		
    }
    /**
     * 订单添加评价
     */
    public function addOp(){
        $appointment_id = intval($_GET['appointment_id']);
        if (!$appointment_id){
            showMessage(Language::get('wrong_argument'),'index.php?act=member_appointment','html','error');
        }

        $model_appointment = Model('appointment');
        $model_clic = Model('clic');
        $model_evaluate_doctors = Model('evaluate_doctors');
        $model_evaluate_clic = Model('evaluate_clic');

        //获取订单信息
        //订单为'已收货'状态，并且未评论
        $appointment_info = $model_appointment->getappointmentInfo(array('appointment_id' => $appointment_id));
        $appointment_info['evaluate_able'] = $model_appointment->getappointmentOperateState('evaluation',$appointment_info);
        if (empty($appointment_info) || !$appointment_info['evaluate_able']){
            showMessage(Language::get('member_evaluation_appointment_notexists'),'index.php?act=member_appointment','html','error');
        }

        //查询店铺信息
        $clic_info = $model_clic->getclicInfoByID($appointment_info['clic_id']);
        if(empty($clic_info)){
            showMessage(Language::get('member_evaluation_clic_notexists'),'index.php?act=member_appointment','html','error');
        }

        //获取订单商品
        $appointment_doctors = $model_appointment->getappointmentdoctorsList(array('appointment_id'=>$appointment_id));
        if(empty($appointment_doctors)){
            showMessage(Language::get('member_evaluation_appointment_notexists'),'index.php?act=member_appointment','html','error');
        }

        //判断是否为页面
        if (!$_POST){
            for ($i = 0, $j = count($appointment_doctors); $i < $j; $i++) {
                $appointment_doctors[$i]['doctors_image_url'] = cthumb($appointment_doctors[$i]['doctors_image'], 60, $clic_info['clic_id']);
            }
            //不显示左菜单
            Tpl::output('left_show','appointment_view');
            Tpl::output('appointment_info',$appointment_info);
            Tpl::output('appointment_doctors',$appointment_doctors);
            Tpl::output('clic_info',$clic_info);
            Tpl::output('menu_sign','evaluateadd');
            Tpl::showpage('evaluation.add');
        }else {
            $evaluate_doctors_array = array();
            foreach ($appointment_doctors as $value){
                //如果未评分，默认为5分
                $evaluate_score = intval($_POST['doctors'][$value['doctors_id']]['score']);
                if($evaluate_score <= 0 || $evaluate_score > 5) {
                    $evaluate_score = 5;
                }
                //默认评语
                $evaluate_comment = $_POST['doctors'][$value['doctors_id']]['comment'];
                if(empty($evaluate_comment)) {
                    $evaluate_comment = '不错哦';
                }

                $evaluate_doctors_info = array();
                $evaluate_doctors_info['geval_appointmentid'] = $appointment_id;
                $evaluate_doctors_info['geval_appointmentno'] = $appointment_info['appointment_sn'];
                $evaluate_doctors_info['geval_appointmentdoctorsid'] = $value['rec_id'];
                $evaluate_doctors_info['geval_doctorsid'] = $value['doctors_id'];
                $evaluate_doctors_info['geval_doctorsname'] = $value['doctors_name'];
                $evaluate_doctors_info['geval_doctorsprice'] = $value['doctors_price'];
                $evaluate_doctors_info['geval_scores'] = $evaluate_score;
                $evaluate_doctors_info['geval_content'] = $evaluate_comment;
                $evaluate_doctors_info['geval_isanonymous'] = $_POST['anony']?1:0;
                $evaluate_doctors_info['geval_addtime'] = TIMESTAMP;
                $evaluate_doctors_info['geval_clicid'] = $clic_info['clic_id'];
                $evaluate_doctors_info['geval_clicname'] = $clic_info['clic_name'];
                $evaluate_doctors_info['geval_frommemberid'] = $_SESSION['member_id'];
                $evaluate_doctors_info['geval_frommembername'] = $_SESSION['member_name'];

                $evaluate_doctors_array[] = $evaluate_doctors_info;
            }
            $model_evaluate_doctors->addEvaluatedoctorsArray($evaluate_doctors_array);

            $clic_desccredit = intval($_POST['clic_desccredit']);
            if($clic_desccredit <= 0 || $clic_desccredit > 5) {
                $clic_desccredit= 5;
            }
            $clic_servicecredit = intval($_POST['clic_servicecredit']);
            if($clic_servicecredit <= 0 || $clic_servicecredit > 5) {
                $clic_servicecredit = 5;
            }
            $clic_deliverycredit = intval($_POST['clic_deliverycredit']);
            if($clic_deliverycredit <= 0 || $clic_deliverycredit > 5) {
                $clic_deliverycredit = 5;
            }
            //添加店铺评价
            $evaluate_clic_info = array();
            $evaluate_clic_info['seval_appointmentid'] = $appointment_id;
            $evaluate_clic_info['seval_appointmentno'] = $appointment_info['appointment_sn'];
            $evaluate_clic_info['seval_addtime'] = time();
            $evaluate_clic_info['seval_clicid'] = $clic_info['clic_id'];
            $evaluate_clic_info['seval_clicname'] = $clic_info['clic_name'];
            $evaluate_clic_info['seval_memberid'] = $_SESSION['member_id'];
            $evaluate_clic_info['seval_membername'] = $_SESSION['member_name'];
            $evaluate_clic_info['seval_desccredit'] = $clic_desccredit;
            $evaluate_clic_info['seval_servicecredit'] = $clic_servicecredit;
            $evaluate_clic_info['seval_deliverycredit'] = $clic_deliverycredit;
            $model_evaluate_clic->addEvaluateclic($evaluate_clic_info);

            //更新订单信息并记录订单日志
            $state = $model_appointment->editappointment(array('evaluation_state'=>1), array('appointment_id' => $appointment_id));
            $model_appointment->editappointmentCommon(array('evaluation_time'=>TIMESTAMP), array('appointment_id' => $appointment_id));
            if ($state){
                $data = array();
                $data['appointment_id'] = $appointment_id;
                $data['log_role'] = 'buyer';
                $data['log_msg'] = L('appointment_log_eval');
                $model_appointment->addappointmentLog($data);
            }

            //添加会员积分
            if ($GLOBALS['setting_config']['points_isuse'] == 1){
                $points_model = Model('points');
                $points_model->savePointsLog('comments',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name']));
            }

            showDialog(Language::get('member_evaluation_evaluat_success'),'index.php?act=member_appointment', 'succ');
        }
    }

    /**
     * 评价列表
     */
    public function listOp(){
        $model_evaluate_doctors = Model('evaluate_doctors');

        $condition = array();
        $condition['geval_frommemberid'] = $_SESSION['member_id'];
        $doctorsevallist = $model_evaluate_doctors->getEvaluatedoctorsList($condition, 10, 'geval_id desc');
        Tpl::output('doctorsevallist',$doctorsevallist);
        Tpl::output('show_page',$model_evaluate_doctors->showpage());

		$this->get_member_info();
		Tpl::output('menu_sign','evaluatemanage');
		Tpl::output('menu_sign_url','index.php?act=member_evaluate');
        Tpl::showpage('evaluation.index');
    }

    public function add_imageOp() {
        $geval_id = intval($_GET['geval_id']);

        $model_evaluate_doctors = Model('evaluate_doctors');
        $model_doctors = Model('doctors');
        $model_sns_alumb = Model('sns_album');

        $geval_info = $model_evaluate_doctors->getEvaluatedoctorsInfoByID($geval_id);

        if(!empty($geval_info['geval_image'])) {
            showMessage('该商品已经发表过晒单', '', '', 'error');
        }

        if($geval_info['geval_frommemberid'] != $_SESSION['member_id']) {
            showMessage(L('param_error'), '', '', 'error');
        }
        Tpl::output('geval_info', $geval_info);

        $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id' => $geval_info['geval_doctorsid']));
        Tpl::output('doctors_info', $doctors_info);

        $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($_SESSION['member_id']);
        Tpl::output('ac_id', $ac_id);

        //不显示左菜单
        Tpl::output('left_show','appointment_view');
        Tpl::showpage('evaluation.add_image');
    }

    public function add_image_saveOp() {
        $geval_id = intval($_POST['geval_id']);
        $geval_image = '';
        foreach ($_POST['evaluate_image'] as $value) {
            if(!empty($value)) {
                $geval_image .= $value . ',';
            } 
        }
        $geval_image = rtrim($geval_image, ',');

        $model_evaluate_doctors = Model('evaluate_doctors');

        $geval_info = $model_evaluate_doctors->getEvaluatedoctorsInfoByID($geval_id);
        if(empty($geval_info)) {
            showDialog(L('param_error'));
        }

        $update = array();
        $update['geval_image'] = $geval_image;
        $condition = array();
        $condition['geval_id'] = $geval_id;
        $result = $model_evaluate_doctors->editEvaluatedoctors($update, $condition);

        list($sns_image) = explode(',', $geval_image);
        $doctors_url = urlclinic('doctors', 'index', array('doctors_id' => $geval_info['geval_doctorsid']));
        //同步到sns
        $content = "
            <div class='fd-media'>
            <div class='doctorsimg'><a target=\"_blank\" href=\"{$doctors_url}\"><img src=\"".snsThumb($sns_image, 240)."\" title=\"{$geval_info['geval_doctorsname']}\" alt=\"{$geval_info['geval_doctorsname']}\"></a></div>
            <div class='doctorsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$doctors_url}\">{$geval_info['geval_doctorsname']}</a></dt>
            <dd>价格".Language::get('nc_colon').Language::get('currency').$geval_info['geval_doctorsprice']."</dd>
            <dd><a target=\"_blank\" href=\"{$doctors_url}\">去看看</a></dd>
            </dl>
            </div>
            </div>
            ";

        $tracelog_model = Model('sns_tracelog');
        $insert_arr = array();
        $insert_arr['trace_originalid'] = '0';
        $insert_arr['trace_originalmemberid'] = '0';
        $insert_arr['trace_memberid'] = $_SESSION['member_id'];
        $insert_arr['trace_membername'] = $_SESSION['member_name'];
        $insert_arr['trace_memberavatar'] = $_SESSION['member_avatar'];
        $insert_arr['trace_title'] = '发表了商品晒单'; 
        $insert_arr['trace_content'] = $content;
        $insert_arr['trace_addtime'] = TIMESTAMP;
        $insert_arr['trace_state'] = '0';
        $insert_arr['trace_privacy'] = 0; 
        $insert_arr['trace_commentcount'] = 0;
        $insert_arr['trace_copycount'] = 0;
        $insert_arr['trace_from'] = '1';
        $result = $tracelog_model->tracelogAdd($insert_arr);

        if($result) {
            showDialog(L('nc_common_save_succ'), urlclinic('member_evaluate', 'list'), 'succ');
        } else {
            showDialog(L('nc_common_save_succ'), urlclinic('member_evaluate', 'list'));
        }
    }
}
