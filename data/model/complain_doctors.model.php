<?php 
/**
 * 投诉商品模型 
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
class complain_doctorsModel{

	/*
	 * 构造条件
	 */
	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['complain_id'])) {
            $condition_str.= " and  complain_id = '{$condition['complain_id']}'";
        }
		return $condition_str;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
	public function saveComplaindoctors($param){
	
		return Db::insert('complain_doctors',$param) ;
	
	}
	
	/*
	 * 更新
	 * @param array $update_array
	 * @param array $where_array
	 * @return bool
	 */
	public function updateComplaindoctors($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('complain_doctors',$update_array,$where) ;
    
    }
	
	/*
	 * 删除
	 * @param array $param
	 * @return bool
	 */
	public function dropComplaindoctors($param){

		$where = $this->getCondition($param) ;
		return Db::delete('complain_doctors', $where) ;
	
	}

	/*
	 *  获得列表
	 *  @param array $condition
	 *  @param obj $page 	//分页对象
	 *  @return array
	 */
	public function getComplaindoctors($condition='',$page='') {

        $param = array() ;
        $param['table'] = 'complain_doctors' ;
        $param['where'] = $this->getCondition($condition);
        $param['appointment'] = $condition['appointment'] ? $condition['appointment']: ' complain_doctors_id desc ';
        return Db::select($param,$page);
	}

    /*
     *   根据id获取投诉商品详细信息
     */
    public function getoneComplaindoctors($complain_doctors_id) {
        
        $param = array() ;
    	$param['table'] = 'complain_doctors';
    	$param['field'] = 'complain_doctors_id' ;
    	$param['value'] = intval($complain_doctors_id);
    	return Db::getRow($param) ;

    }

}
