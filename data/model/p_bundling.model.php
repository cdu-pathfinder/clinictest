<?php
/**
 * 优惠套装
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

class p_bundlingModel extends Model {
    const STATE1 = 1;       // 开启
    const STATE0 = 0;       // 关闭
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 组合活动数量
     * 
     * @param array $condition
     * @return array
     */
    public function getBundlingCount($condition) {
        return $this->table('p_bundling')->where($condition)->count();
    }
    
    /**
     * 活动列表
     * 
     * @param array $condition
     * @param string $field
     * @param string $appointment
     * @param int $page
     * @param int $limit
     * @param int $count
     * @return array
     */
    public function getBundlingList($condition, $field = '*', $appointment = 'bl_id desc', $page = 10, $limit = 0, $count = 0) {
        return $this->table('p_bundling')->where($condition)->appointment($appointment)->limit($limit)->page($page, $count)->select();
    }
    
    /**
     * 开启的活动列表
     * @param array $condition
     * @param string $field
     * @param string $appointment
     * @param int $limit
     * @return array
     */
    public function getBundlingOpenList($condition, $field = '*', $appointment = 'bl_id desc', $limit = 0) {
        $condition['bl_state'] = self::STATE1;
        return $this->getBundlingList($condition, $field, $appointment, 0, $limit);
    }
    
    /**
     * 获得获得详细信息
     */
    public function getBundlingInfo($condition) {
        return $this->table('p_bundling')->where($condition)->find();
    }
    
    /**
     * 保存活动
     * 
     * @param array $insert
     * @param string $replace
     * @return boolean
     */
    public function addBundling($insert, $replace = false) {
        return $this->table('p_bundling')->insert($insert, $replace);
    }
    
    /**
     * 更新活动
     * 
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editBundling($update, $condition) {
        return $this->table('p_bundling')->where($condition)->update($update);
    }
    
    /**
     * 更新活动关闭
     * 
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editBundlingCloseBydoctorsIds($condition) {
        $bundlingdoctors_list = $this->getBundlingdoctorsList($condition, 'bl_id');
        if (!empty($bundlingdoctors_list)) {
            $blid_array = array();
            foreach ($bundlingdoctors_list as $val) {
                $blid_array[] = $val['bl_id'];
            }
            $update = array('bl_state' => self::STATE0);
            return $this->table('p_bundling')->where(array('bl_id' => array('in', $blid_array)))->update($update);
        }
        return true;
    }
    
    /**
     * 删除套餐活动
     * @param array $blids
     * @param int $clic_id
     * @return boolean
     */
    public function delBundling($blids, $clic_id) {
        $blid_array = explode(',', $blids);
        foreach ($blid_array as $val) {
            if (!is_numeric($val)) {
                return false;
            }
        }
        $where = array();
        $where['bl_id'] = array('in', $blid_array);
        $where['clic_id'] = $clic_id;
        $bl_list = $this->getBundlingList($where, 'bl_id');
        $bl_list = array_under_reset($bl_list, 'bl_id');
        $blid_array = array_keys($bl_list);
        
        $where = array();
        $where['bl_id'] = array('in', $blid_array);
        $rs = $this->table('p_bundling')->where($where)->delete();
        if ($rs) {
            return $this->delBundlingdoctors($where);
        } else {
            return false;
        }
    }
    
    /**
     * 删除套餐活动（平台后台使用）
     * @param array $condition
     * @return boolean
     */
    public function delBundlingForAdmin($condition) {
        $rs = $this->table('p_bundling')->where($condition)->delete();
        if ($rs) {
            return $this->delBundlingdoctors($condition);
        } else {
            return false;
        }
    }
    
    /**
     * 单条组合套餐
     * 
     * @param array $condition
     * @return array
     */
    public function getBundlingQuotaInfo($condition) {
        return $this->table('p_bundling_quota')->where($condition)->find();
    }
    
    /**
     * 单条组合套餐
     * 
     * @param array $condition
     * @return array
     */
    public function getBundlingQuotaInfoCurrent($condition) {
        $condition['bl_quota_endtime'] = array('gt', TIMESTAMP);
        $condition['bl_state'] = 1;
        return $this->getBundlingQuotaInfo($condition);
    }
    
    /**
     * 组合套餐列表
     * 
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getBundlingQuotaList($condition, $page = 10, $limit = 0) {
        return $this->table('p_bundling_quota')->where($condition)->appointment('bl_quota_id desc')->limit($limit)->page($page)->select();
    }
    
    /**
     * 开启的组合套餐列表
     * 
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getBundlingQuotaOpenList($condition, $page = 10, $limit = 0) {
        $condition['bl_state'] = self::STATE1;
        return $this->getBundlingQuotaList($condition, $page, $limit);
    }
    
    /**
     * 保存组合套餐
     * 
     * @param array $insert
     * @return boolean
     */
    public function addBundlingQuota($insert) {
        return $this->table('p_bundling_quota')->insert($insert);
    }
    
    /**
     * 更新组合套餐
     * 
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editBundlingQuota($update, $condition) {
        return $this->table('p_bundling_quota')->where($condition)->update($update);
    }
    
    /**
     * 更新组合套餐
     * 
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editBundlingQuotaOpen($update, $condition) {
        $update['bl_state'] = self::STATE1;
        return $this->table('p_bundling_quota')->where($condition)->update($update);
    }
    
    /**
     * 更新套餐为关闭状态
     * @param array $condition
     * @return boolean
     */
    public function editBundlingQuotaClose($condition) {
        $quota_list = $this->getBundlingQuotaList($condition);
        if (empty($quota_list)) {
            return true;
        }
        $clicid_array = array();
        foreach ($quota_list as $val) {
            $clicid_array[] = $val['clic_id'];
        }
        $where = array('clic_id' => array('in', $clicid_array));
        $update = array('bl_state' => self::STATE0);
        $this->editBundlingQuota($update, $where);
        $this->editBundling($update, $where);
        return true;
    }
    
    /**
     * 更新超时的套餐为关闭状态
     * @param array $condition
     * @return boolean
     */
    public function editBundlingTimeout($condition) {
        $condition['bl_quota_endtime'] = array('lt', TIMESTAMP);
        $quota_list = $this->getBundlingQuotaList($condition);
        if (!empty($quota_list)) {
            $quotaid_array = array();
            foreach ($quota_list as $val) {
                $quotaid_array[] = $val['bl_quota_id'];
            }
            return $this->editBundlingQuotaClose(array('bl_quota_id' => array('in', $quotaid_array)));
        } else {
            return true;
        }
    }

    /**
     * 套餐商品列表
     * 
     * @param array $condition
     * @param string $field
     * @param string $appointment
     * @param string $group
     * @return array
     */
    public function getBundlingdoctorsList($condition, $field = '*', $appointment = 'bl_doctors_id asc', $group = '') {
        return $this->table('p_bundling_doctors')->field($field)->where($condition)->group($group)->appointment($appointment)->select();
    }

    /**
     * 保存套餐商品
     * 
     * @param unknown $insert
     * @return boolean
     */
    public function addBundlingdoctorsAll($insert) {
        return $this->table('p_bundling_doctors')->insertAll($insert);
    }
    
    /**
     * 删除套餐商品
     * 
     * @param array $condition
     * @return boolean
     */
    public function delBundlingdoctors($condition) {
        return $this->table('p_bundling_doctors')->where($condition)->delete();
    }
    
}