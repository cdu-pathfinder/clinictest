<?php
/**
 * 限时折扣套餐模型 
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
class p_xianshi_quotaModel extends Model{

    public function __construct(){
        parent::__construct('p_xianshi_quota');
    }

	/**
     * 读取限时折扣套餐列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 所需字段
     * @return array 限时折扣套餐列表
	 *
	 */
	public function getXianshiQuotaList($condition, $page=null, $appointment='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getXianshiQuotaInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

    /**
     * 获取当前可用套餐
	 * @param int $clic_id
     * @return array
     *
     */
    public function getXianshiQuotaCurrent($clic_id) {
        $condition = array();
        $condition['clic_id'] = $clic_id;
        $condition['end_time'] = array('gt', TIMESTAMP);
        $xianshi_quota_list = $this->getXianshiQuotaList($condition, null, 'end_time desc');
        $xianshi_quota_info = $xianshi_quota_list[0];
        return $xianshi_quota_info;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
     *
	 */
    public function addXianshiQuota($param){
        return $this->insert($param);	
    }

    /*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
     *
	 */
    public function editXianshiQuota($update, $condition){
        return $this->where($condition)->update($update);
    }

	/*
	 * 删除
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delXianshiQuota($condition){
        return $this->where($condition)->delete();
    }
}
