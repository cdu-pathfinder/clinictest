<?php
/**
 * 店铺导航模型
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
class clic_navigationModel extends Model{

    public function __construct(){
        parent::__construct('clic_navigation');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getclicNavigationList($condition, $page='', $appointment='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getclicNavigationInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addclicNavigation($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editclicNavigation($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delclicNavigation($condition){
        return $this->where($condition)->delete();
    }
	
}
