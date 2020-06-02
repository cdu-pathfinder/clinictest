<?php
/**
 * 店铺费用模型
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
class clic_costModel extends Model{

    public function __construct(){
        parent::__construct('clic_cost');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getclicCostList($condition, $page='', $appointment='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getclicCostInfo($condition, $fields = '*') {
        $result = $this->where($condition)->field($fields)->find();
        return $result;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addclicCost($param){
        return $this->insert($param);	
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delclicCost($condition){
        return $this->where($condition)->delete();
    }
    
    /**
     * 更新
     * @param array $data
     * @param array $condition
     */
    public function editclicCost($data,$condition) {
        return $this->where($condition)->update($data);
    }

}
