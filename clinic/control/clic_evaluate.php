<?php
/**
 * 会员中心——卖家评价
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class clic_evaluateControl extends BaseclinicerControl{
	public function __construct(){
        parent::__construct() ;
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act','clic_evaluate');
    }

    /**
	 * 评价列表
	 */
	public function listOp(){
		$model_evaluate_doctors = Model('evaluate_doctors');

		$condition = array();
        if(!empty($_GET['doctors_name'])) {
            $condition['geval_doctorsname'] = array('like', '%'.$_GET['doctors_name'].'%');
        }
        if(!empty($_GET['member_name'])) {
            $condition['geval_frommembername'] = array('like', '%'.$_GET['member_name'].'%');
        }
        $condition['geval_clicid'] = $_SESSION['clic_id'];
        $doctorsevallist = $model_evaluate_doctors->getEvaluatedoctorsList($condition, 10, 'geval_id desc');

		Tpl::output('doctorsevallist',$doctorsevallist);
		Tpl::output('show_page',$model_evaluate_doctors->showpage());
		Tpl::showpage('evaluation.index');
	}	
	/**
	 * 解释来自买家的评价
	 */
	public function explain_saveOp(){
        $geval_id = intval($_POST['geval_id']);
        $geval_explain = $_POST['geval_explain'];

        $data = array();
        $data['result'] = true;

        $model_evaluate_doctors = Model('evaluate_doctors');

        $evaluate_info = $model_evaluate_doctors->getEvaluatedoctorsInfoByID($geval_id);
        if(empty($evaluate_info)) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $update = array('geval_explain' => $geval_explain);
        $condition = array('geval_id' => $geval_id);
        $result = $model_evaluate_doctors->editEvaluatedoctors($update, $condition);

        if($result) {
            $data['message'] = '解释成功';
        } else {
            $data['result'] = false;
            $data['message'] = '解释保存失败';
        }
        echo json_encode($data);die;
	}
}
