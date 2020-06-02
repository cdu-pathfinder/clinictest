<?php
/**
 * 水印管理
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
class clic_watermarkModel {
	/**
	 * 根据店铺id获取水印
	 *
	 * @param array $param 参数内容
	 * @return array $param 水印数组
	 */
	public function getOneclicWMByclicId($clic_id){
		$wm_arr = array();
		$clic_id = intval($clic_id);
		if ($clic_id > 0){
			$param = array(
				'table'=>'clic_watermark',
				'field'=>'clic_id',
				'value'=>$clic_id
			);
			$wm_arr = Db::getRow($param);
		}
		return $wm_arr;
	}
	/**
	 * 新增水印
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function addclicWM($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('clic_watermark',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 更新水印
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function updateclicWM($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " wm_id = '". $param['wm_id'] ."'";
			$result = Db::update('clic_watermark',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除水印
	 *
	 * @param int $id 记录ID
	 * @return bool 布尔类型的返回结果
	 */
	public function delclicWM($id){
		if (intval($id) > 0){
			$where = " wm_id = '". intval($id) ."'";
			$result = Db::delete('clic_watermark',$where);
			return $result;
		}else {
			return false;
		}
	}
}