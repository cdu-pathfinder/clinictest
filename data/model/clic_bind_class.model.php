<?php
/**
 * 店铺分类分佣比例
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
class clic_bind_classModel extends Model{

    public function __construct(){
        parent::__construct('clic_bind_class');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getclicBindClassList($condition,$page='',$appointment='',$field='*'){
        $result = $this->table('clic_bind_class')->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getclicBindClassInfo($condition){
        $result = $this->where($condition)->find();
        return $result;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addclicBindClass($param){
        return $this->insert($param);	
    }
	
	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addclicBindClassAll($param){
        return $this->insertAll($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editclicBindClass($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delclicBindClass($condition){
        return $this->where($condition)->delete();
    }
	
}
