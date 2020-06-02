<?php
/**
 * 商品与属性对应
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

class doctors_attr_indexModel extends Model {
    public function __construct() {
        parent::__construct('doctors_attr_index');
    }
    
    /**
     * 对应列表
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsAttrIndexList($condition, $field = '*') {
        return $this->where($condition)->field($field)->select();
    }
}