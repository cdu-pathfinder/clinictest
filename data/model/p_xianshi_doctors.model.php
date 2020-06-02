<?php
/**
 * 限时折扣活动商品模型 
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
class p_xianshi_doctorsModel extends Model{

    const XIANSHI_doctorS_STATE_CANCEL = 0;
    const XIANSHI_doctorS_STATE_NORMAL = 1;

    public function __construct(){
        parent::__construct('p_xianshi_doctors');
    }

	/**
	 * 读取限时折扣商品列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 所需字段
     * @param int $limit 个数限制
     * @return array 限时折扣商品列表
	 *
	 */
	public function getXianshidoctorsList($condition, $page=null, $appointment='', $field='*', $limit = 0) {
        $xianshi_doctors_list = $this->field($field)->where($condition)->page($page)->appointment($appointment)->limit($limit)->select();
        if(!empty($xianshi_doctors_list)) {
            for($i=0, $j=count($xianshi_doctors_list); $i < $j; $i++) {
                $xianshi_doctors_list[$i] = $this->getXianshidoctorsExtendInfo($xianshi_doctors_list[$i]);
            }
        }
        return $xianshi_doctors_list;
	}

    /**
	 * 根据条件读取限制折扣商品信息
	 * @param array $condition 查询条件
     * @return array 限时折扣商品信息
	 *
	 */
    public function getXianshidoctorsInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

    /**
	 * 根据限时折扣商品编号读取限制折扣商品信息
	 * @param int $xianshi_doctors_id
     * @return array 限时折扣商品信息
	 *
	 */
    public function getXianshidoctorsInfoByID($xianshi_doctors_id, $clic_id = 0) {
        if(intval($xianshi_doctors_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['xianshi_doctors_id'] = $xianshi_doctors_id;
        $xianshi_doctors_info = $this->getXianshidoctorsInfo($condition);

        if($clic_id > 0 && $xianshi_doctors_info['clic_id'] != $clic_id) {
            return null;
        } else {
            return $xianshi_doctors_info;
        }
    }

	/*
	 * 增加限时折扣商品 
	 * @param array $xianshi_doctors_info
	 * @return bool
     *
	 */
    public function addXianshidoctors($xianshi_doctors_info){
        $xianshi_doctors_info['state'] = self::XIANSHI_doctorS_STATE_NORMAL;
        $xianshi_doctors_id = $this->insert($xianshi_doctors_info);	
        $xianshi_doctors_info['xianshi_doctors_id'] = $xianshi_doctors_id;
        $xianshi_doctors_info = $this->getXianshidoctorsExtendInfo($xianshi_doctors_info);
        return $xianshi_doctors_info;
    }

    /*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
     *
	 */
    public function editXianshidoctors($update, $condition){
        return $this->where($condition)->update($update);
    }

	/*
	 * 删除
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delXianshidoctors($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 获取限时折扣商品扩展信息
     * @param array $xianshi_info
     * @return array 扩展限时折扣信息
     *
     */
    public function getXianshidoctorsExtendInfo($xianshi_info) {
        $xianshi_info['doctors_url'] = urlclinic('doctors', 'index', array('doctors_id' => $xianshi_info['doctors_id']));
        $xianshi_info['image_url'] = cthumb($xianshi_info['doctors_image'], 60, $xianshi_info['clic_id']);
        $xianshi_info['xianshi_price'] = ncPriceFormat($xianshi_info['xianshi_price']);
        $xianshi_info['xianshi_discount'] = number_format($xianshi_info['xianshi_price'] / $xianshi_info['doctors_price'] * 10, 1).'折';
        return $xianshi_info;
    }

    /**
     * 获取推荐限时折扣商品
     * @param int $count 推荐数量
     * @return array 推荐限时活动列表
     *
     */
    public function getXianshidoctorsCommendList($count = 4) {
        $condition = array();
        $condition['state'] = self::XIANSHI_doctorS_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $xianshi_list = $this->getXianshidoctorsList($condition, null, 'xianshi_recommend desc', '*', $count);
        return $xianshi_list;
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param int $doctors_id
     * @return array $xianshi_info
     *
     */
    public function getXianshidoctorsInfoBydoctorsID($doctors_id) {
        $xianshi_doctors_list = $this->_getXianshidoctorsListBydoctors($doctors_id);
        return $xianshi_doctors_list[0];
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param string $doctors_string 商品编号字符串，例：'1,22,33'
     * @return array $xianshi_doctors_list
     *
     */
    public function getXianshidoctorsListBydoctorsString($doctors_string) {
        $xianshi_doctors_list = $this->_getXianshidoctorsListBydoctors($doctors_string);
        $xianshi_doctors_list = array_under_reset($xianshi_doctors_list, 'doctors_id');
        return $xianshi_doctors_list;
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param string $doctors_id_string
     * @return array $xianshi_info
     *
     */
    private function _getXianshidoctorsListBydoctors($doctors_id_string) {
        $condition = array();
        $condition['state'] = self::XIANSHI_doctorS_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $condition['doctors_id'] = array('in', $doctors_id_string);
        $xianshi_doctors_list = $this->getXianshidoctorsList($condition, null, 'xianshi_doctors_id desc', '*');
        return $xianshi_doctors_list;
    }
}
