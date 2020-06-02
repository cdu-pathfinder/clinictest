<?php
/**
 * 意见反馈
 *
 * 
 *
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */

defined('InclinicNC') or exit('Access Invalid!');

class mb_feedbackModel{
	/**
	 * 列表
	 *
	 * @param array $condition 检索条件
	 * @param obj $page 分页
	 * @return array 数组结构的返回结果
	 */
	public function getList($condition,$page=''){
		$param = array();
		$param['table'] = 'mb_feedback';
		$param['appointment'] = 'ftime desc';
		$result = Db::select($param,$page);
		return $result;
	}

	/**
	 * 删除
	 *
	 * @param int $id 记录ID
	 * @return bool 布尔类型的返回结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('mb_feedback',$where);
			return $result;
		}else {
			return false;
		}
	}
}
