<?php
/**
 * 店铺商品分类管理
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
class clic_doctors_classModel {
	/**
	 * 根据编号获取一条数据
	 *
	 * @param int $id
	 * @return bool|array
	 */
	public function getOneById($id){
		if(intval($id)<=0)return false;
		$param	= array();
		$param['table']	= 'clic_doctors_class';
		$param['field']	= 'stc_id';
		$param['value']	= intval($id);
		return Db::getRow($param);
	}
	/**
	 * 查询店铺商品分类列表
	 *
	 * @return array
	 */
	public function getStcTreeList($clic_id = 0){
		$param	= array();
		$param['table']	= 'clic_doctors_class';
		if (intval($clic_id) > 0){
			$param['where'] = "clic_id = '{$clic_id}'";
		}
		$list	= Db::select($param);
		return	$this->getStcTree($list);
	}
	private function getStcTree($list,$pid='0',$deep=1){
		$return	= array();
		if(is_array($list)){
			foreach($list as $k=>$stc){
				if($stc['stc_parent_id'] == $pid){
					for($i=0;$i<$deep;$i++){
						$stc['stc_name']	= '&nbsp;&nbsp;'.$stc['stc_name'];
					}
					$return[]	= $stc;
					$temp	= $this->getStcTree($list,$stc['stc_id'],$deep+1);
					if(!empty($temp)){
						$return	= array_merge($return,$temp);
					}
					unset($temp);
				}
			}
		}
		return $return;
	}
}