<?php
/**
 * 商品评价
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class evaluateControl extends SystemControl{
	public function __construct() {
		parent::__construct();
		Language::read('evaluate');
	}

	public function indexOp() {
		$this->evaldoctors_listOp();
	}

	/**
	 * 商品来自买家的评价列表
	 */
	public function evaldoctors_listOp() {
		$model_evaluate_doctors = Model('evaluate_doctors');

		$condition = array();
		//商品名称
		if (!empty($_GET['doctors_name'])) {
			$condition['geval_doctorsname'] = array('like', '%'.$_GET['doctors_name'].'%');
		}
		//店铺名称
		if (!empty($_GET['clic_name'])) {
			$condition['geval_clicname'] = array('like', '%'.$_GET['clic_name'].'%');
		}
        $condition['geval_addtime'] = array('time', array(strtotime($_GET['stime']), strtotime($_GET['etime'])));
		$evaldoctors_list	= $model_evaluate_doctors->getEvaluatedoctorsList($condition, 10);

		Tpl::output('show_page',$model_evaluate_doctors->showpage());
		Tpl::output('evaldoctors_list',$evaldoctors_list);
		Tpl::showpage('evaldoctors.index');
	}

	/**
	 * 删除商品评价
	 */
	public function evaldoctors_delOp() {
		$geval_id = intval($_POST['geval_id']);
		if ($geval_id <= 0) {
			showMessage(Language::get('param_error'),'','','error');
		}

		$model_evaluate_doctors = Model('evaluate_doctors');

		$result = $model_evaluate_doctors->delEvaluatedoctors(array('geval_id'=>$geval_id));

		if ($result) {
            $this->log('删除商品评价，评价编号'.$geval_id);
			showMessage(Language::get('nc_common_del_succ'),'','','error');
		} else {
			showMessage(Language::get('nc_common_del_fail'),'','','error');
		}
	}

	/**
	 * 店铺动态评价列表
	 */
	public function evalclic_listOp() {
        $model_evaluate_clic = Model('evaluate_clic');

		$condition = array();
		//商品名称
		if (!empty($_GET['doctors_name'])) {
			$condition['geval_doctorsname'] = array('like', '%'.$_GET['doctors_name'].'%');
		}
		//店铺名称
		if (!empty($_GET['clic_name'])) {
			$condition['geval_clicname'] = array('like', '%'.$_GET['clic_name'].'%');
		}
        $condition['seval_addtime_gt'] = array('time', array(strtotime($_GET['stime']), strtotime($_GET['etime'])));

		$evalclic_list	= $model_evaluate_clic->getEvaluateclicList($condition, 10);
		Tpl::output('show_page',$model_evaluate_clic->showpage());
		Tpl::output('evalclic_list',$evalclic_list);
		Tpl::showpage('evalclic.index');
	}

	/**
	 * 删除店铺评价
	 */
	public function evalclic_delOp() {
		$seval_id = intval($_POST['seval_id']);
		if ($seval_id <= 0) {
			showMessage(Language::get('param_error'),'','','error');
		}

		$model_evaluate_clic = Model('evaluate_clic');

		$result = $model_evaluate_clic->delEvaluateclic(array('seval_id'=>$seval_id));

		if ($result) {
            $this->log('删除店铺评价，评价编号'.$geval_id);
			showMessage(Language::get('nc_common_del_succ'),'','','error');
		} else {
			showMessage(Language::get('nc_common_del_fail'),'','','error');
		}
	}
}
