<?php
/**
 * 商品管理
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

class doctorsModel extends Model{
    public function __construct(){
        parent::__construct('doctors');
    }
    
    const STATE1 = 1;       // 出售中
    const STATE0 = 0;       // 下架
    const STATE10 = 10;     // 违规
    const VERIFY1 = 1;      // 审核通过
    const VERIFY0 = 0;      // 审核失败
    const VERIFY10 = 10;    // 等待审核
    
    /**
     * 新增商品数据
     * 
     * @param array $insert 数据
     * @param string $table 表名
     */
    public function adddoctors($insert, $table = "doctors") {
        return $this->table($table)->insert($insert);
    }

    /**
     * 新增多条商品数据
     * 
     * @param unknown $insert
     */
    public function adddoctorsAll($insert, $table = 'doctors') {
        return $this->table($table)->insertAll($insert);
    }
    
    /**
     * 商品SKU列表
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @param boolean $lock 是否锁定
     * @return array 二维数组
     */
    public function getdoctorsList($condition, $field = '*', $group = '',$appointment = '', $limit = 0, $page = 0, $lock = false, $count = 0) {
        $condition = $this->_getRecursiveClass($condition);
        return $this->table('doctors')->field($field)->where($condition)->group($group)->appointment($appointment)->limit($limit)->page($page, $count)->lock($lock)->select();
    }

    /**
     * 出售中的商品SKU列表（只显示不同颜色的商品，前台商品索引，店铺也商品列表等使用）
     * @param array $condition
     * @param string $field
     * @param string $appointment
     * @param number $page
     * @return array
     */
    public function getdoctorsListByColorDistinct($condition, $field = '*', $appointment = 'doctors_id asc', $page = 0) {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        $condition = $this->_getRecursiveClass($condition);
        $field = "CONCAT(doctors_commonid,',',color_id) as nc_distinct ," . $field;
        $count = $this->getdoctorsOnlineCount($condition,"distinct CONCAT(doctors_commonid,',',color_id)");
        $doctors_list = array();
        if ($count != 0) {
            $doctors_list = $this->getdoctorsOnlineList($condition, $field, $page, $appointment, 0, 'nc_distinct', false, $count);
        }
        return $doctors_list;
    }

    /**
     * 在售商品SKU列表
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @param boolean $lock 是否锁定
     * @return array
     */
    public function getdoctorsOnlineList($condition, $field = '*', $page = 0, $appointment = 'doctors_id desc', $limit = 0, $group = '', $lock = false, $count = 0) {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsList($condition, $field, $group, $appointment, $limit, $page, $lock, $count);
    }
    
    /**
     * 商品SUK列表 doctors_show = 1 为出售中，doctors_show = 0为未出售（仓库中，违规，等待审核）
     * 
     * @param unknown $condition
     * @param string $field
     * @return multitype:
     */
    public function getdoctorsAsdoctorsShowList($condition, $field = '*') {
        $field = $this->_asdoctorsShow($field);
        return $this->getdoctorsList($condition, $field);
    }

    /**
     * 商品列表 卖家中心使用
     * 
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $appointment 排序
     * @return array
     */
    public function getdoctorsCommonList($condition, $field = '*', $page = 10, $appointment = 'doctors_commonid desc') {
        $condition = $this->_getRecursiveClass($condition);
        return $this->table('doctors_common')->field($field)->where($condition)->appointment($appointment)->page($page)->select();
    }
    
    /**
     * 出售中的商品列表 卖家中心使用
     * 
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $appointment 排序
     * @return array
     */
    public function getdoctorsCommonOnlineList($condition, $field = '*', $page = 10, $appointment = "doctors_commonid desc") {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonList($condition, $field, $page, $appointment);
    }
    
    /**
     * 仓库中的商品列表 卖家中心使用
     * 
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $appointment 排序
     * @return array
     */
    public function getdoctorsCommonOfflineList($condition, $field = '*', $page = 10, $appointment = "doctors_commonid desc") {
        $condition['doctors_state']   = self::STATE0;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonList($condition, $field, $page, $appointment);
    }
    
    /**
     * 违规的商品列表 卖家中心使用
     * 
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $appointment 排序
     * @return array
     */
    public function getdoctorsCommonLockUpList($condition, $field = '*', $page = 10, $appointment = "doctors_commonid desc") {
        $condition['doctors_state']   = self::STATE10;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonList($condition, $field, $page, $appointment);
    }
    
    /**
     * 等待审核或审核失败的商品列表 卖家中心使用
     * 
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $appointment 排序
     * @return array
     */
    public function getdoctorsCommonWaitVerifyList($condition, $field = '*', $page = 10, $appointment = "doctors_commonid desc") {
        if (!isset($condition['doctors_verify'])) {
            $condition['doctors_verify']  = array('neq', self::VERIFY1);
        }
        return $this->getdoctorsCommonList($condition, $field, $page, $appointment);
    }
    
    /**
     * 公共商品列表，doctors_show = 1 为出售中，doctors_show = 0为未出售（仓库中，违规，等待审核）
     */
    public function getdoctorsCommonAsdoctorsShowList($condition, $field = '*') {
        return $this->getdoctorsCommonList($condition, $field);
    }
    
    /**
     * 查询商品SUK及其店铺信息
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsclicList($condition, $field = '*') {
        $condition = $this->_getRecursiveClass($condition);
        return $this->table('doctors,clic')->field($field)->join('inner')->on('doctors.clic_id = clic.clic_id')->where($condition)->select();
    }
    
    /**
     * 计算商品库存
     * 
     * @param array $doctors_list
     * @return array|boolean
     */
    public function calculateStorage($doctors_list, $storage_alarm = 0) {
        // 计算库存
        if (!empty($doctors_list)) {
            $doctorsid_array = array();
            foreach ($doctors_list as $value) {
                $doctorscommonid_array[] = $value['doctors_commonid'];
            }
            $doctors_storage = $this->getdoctorsList(array('doctors_commonid' => array('in', $doctorscommonid_array)), 'doctors_storage,doctors_commonid,doctors_id');
            $storage_array = array();
            foreach ($doctors_storage as $val) {
                if ($storage_alarm != 0 && $val['doctors_storage'] <= $storage_alarm) {
                    $storage_array[$val['doctors_commonid']]['alarm'] = true;
                }
                $storage_array[$val['doctors_commonid']]['sum'] += $val['doctors_storage'];
                $storage_array[$val['doctors_commonid']]['doctors_id'] = $val['doctors_id'];
            }
            return $storage_array;
        } else {
            return false;
        }
    }
    
    /**
     * 更新商品SUK数据
     * 
     * @param array $update 更新数据
     * @param array $condition 条件
     * @return boolean
     */
    public function editdoctors($update, $condition) {
        return $this->table('doctors')->where($condition)->update($update);
    }

    
    /**
     * 更新商品数据
     * @param array $update 更新数据
     * @param array $condition 条件
     * @return boolean
     */
    public function editdoctorsCommon($update, $condition) {
        return $this->table('doctors_common')->where($condition)->update($update);
    }
    
    /**
     * 更新商品数据
     * @param array $update 更新数据
     * @param array $condition 条件
     * @return boolean
     */
    public function editdoctorsCommonNoLock($update, $condition) {
        $condition['doctors_lock'] = 0;
        return $this->table('doctors_common')->where($condition)->update($update);
    }
    
    /**
     * 锁定商品
     * @param unknown $condition
     * @return boolean
     */
    public function editdoctorsCommonLock($condition) {
        $update = array('doctors_lock' => 1);
        return $this->table('doctors_common')->where($condition)->update($update);
    }

     /**
     * 解锁商品
     * @param unknown $condition
     * @return boolean
     */
    public function editdoctorsCommonUnlock($condition) {
        $update = array('doctors_lock' => 0);
        return $this->table('doctors_common')->where($condition)->update($update);
    }
   
    /**
     * 更新商品信息
     * 
     * @param array $condition
     * @param array $update1
     * @param array $update2
     * @return boolean
     */
    public function editProduces($condition, $update1, $update2 = array()) {
        $update2 = empty($update2) ? $update1 : $update2;
        $return1 = $this->editdoctorsCommon($update1, $condition);
        $return2 = $this->editdoctors($update2, $condition);
        if ($return1 && $return2) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 更新未锁定商品信息
     * 
     * @param array $condition
     * @param array $update1
     * @param array $update2
     * @return boolean
     */
    public function editProducesNoLock($condition, $update1, $update2 = array()) {
        $update2 = empty($update2) ? $update1 : $update2;
        $condition['doctors_lock'] = 0;
        $common_array = $this->getdoctorsCommonList($condition);
        $common_array = array_under_reset($common_array, 'doctors_commonid');
        $commonid_array = array_keys($common_array);
        $where = array();
        $where['doctors_commonid'] = array('in', $commonid_array);
        $return1 = $this->editdoctorsCommon($update1, $where);
        $return2 = $this->editdoctors($update2, $where);
        if ($return1 && $return2) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 商品下架
     * @param array $condition 条件
     * @return boolean
     */
    public function editProducesOffline($condition){
        $update = array('doctors_state' => self::STATE0);
        return $this->editProducesNoLock($condition, $update);
    }

    /**
     * 商品上架
     * @param array $condition 条件
     * @return boolean
     */
    public function editProducesOnline($condition){
        $update = array('doctors_state' => self::STATE1);
        // 禁售商品、审核失败商品不能上架。
        $condition['doctors_state'] = self::STATE0;
        $condition['doctors_verify'] = array('neq', self::VERIFY0);
        return $this->editProduces($condition, $update);
    }
    
    /**
     * 违规下架
     * 
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editProducesLockUp($update, $condition) {
        $update_param['doctors_state'] = self::STATE10;
        $update = array_merge($update, $update_param);
        return $this->editProduces($condition, $update, $update_param);
    }
    
    /**
     * 获取单条商品SKU信息
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsInfo($condition, $field = '*') {
        return $this->table('doctors')->field($field)->where($condition)->find();
    }
    
    /**
     * 获取单条商品SKU信息
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsOnlineInfo($condition, $field = '*') {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->table('doctors')->field($field)->where($condition)->find();
    }

    /**
     * 获取单条商品SKU信息，doctors_show = 1 为出售中，doctors_show = 0为未出售（仓库中，违规，等待审核）
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsAsdoctorsShowInfo($condition, $field = '*') {
        $field = $this->_asdoctorsShow($field);
        return $this->getdoctorsInfo($condition, $field);
    }

    /**
     * 获取单条商品信息
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctoreCommonInfo($condition, $field = '*') {
        return $this->table('doctors_common')->field($field)->where($condition)->find();
    }

    /**
     * 获取单条商品信息，doctors_show = 1 为出售中，doctors_show = 0为未出售（仓库中，违规，等待审核）
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctoreCommonAsdoctorsShowInfo($condition, $field = '*') {
        $field = $this->_asdoctorsShow($field);
        return $this->getdoctoreCommonInfo($condition, $field);
    }
    
    /**
     * 获取单条商品信息
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getdoctorsDetail($doctors_id) {
        if($doctors_id <= 0) {
            return null;
        }
        $result1 = $this->getdoctorsAsdoctorsShowInfo(array('doctors_id' => $doctors_id));
        if (empty($result1)) {
            return null;
        }
        $result2 = $this->getdoctoreCommonAsdoctorsShowInfo(array('doctors_commonid' => $result1['doctors_commonid']));
        $doctors_info = array_merge($result2, $result1);

        $doctors_info['spec_value'] = unserialize($doctors_info['spec_value']);
        $doctors_info['spec_name'] = unserialize($doctors_info['spec_name']);
        $doctors_info['doctors_spec'] = unserialize($doctors_info['doctors_spec']);
        $doctors_info['doctors_attr'] = unserialize($doctors_info['doctors_attr']);

        // 查询所有规格商品
        $spec_array = $this->getdoctorsList(array('doctors_commonid' => $doctors_info['doctors_commonid']), 'doctors_spec,doctors_id,clic_id,doctors_image,color_id');
        $spec_list = array();       // 各规格商品地址，js使用
        $spec_list_mobile = array();       // 各规格商品地址，js使用
        $spec_image = array();      // 各规格商品主图，规格颜色图片使用
        foreach ($spec_array as $key => $value) {
            $s_array = unserialize($value['doctors_spec']);
            $tmp_array = array();
            if (!empty($s_array) && is_array($s_array)) {
                foreach ($s_array as $k => $v) {
                    $tmp_array[] = $k;
                }
            }
            sort($tmp_array);
            $spec_sign = implode('|', $tmp_array);
            $tpl_spec = array();
            $tpl_spec['sign'] = $spec_sign;
            $tpl_spec['url'] = urlclinic('doctors', 'index', array('doctors_id' => $value['doctors_id']));
            $spec_list[] = $tpl_spec;
            $spec_list_mobile[$spec_sign] = $value['doctors_id'];
            $spec_image[$value['color_id']] = thumb($value, 60);
        }
        $spec_list = json_encode($spec_list);

        // 商品多图
        $image_more = $this->getdoctorsImageList(array('doctors_commonid' => $doctors_info['doctors_commonid'], 'color_id' => $doctors_info['color_id']), 'doctors_image');
        $doctors_image = array();
        $doctors_image_mobile = array();
        if (!empty($image_more)) {
            foreach ($image_more as $val) {
                $doctors_image[] = "{ title : '', levelA : '".cthumb($val['doctors_image'], 60, $doctors_info['clic_id'])."', levelB : '".cthumb($val['doctors_image'], 360, $doctors_info['clic_id'])."', levelC : '".cthumb($val['doctors_image'], 360, $doctors_info['clic_id'])."', levelD : '".cthumb($val['doctors_image'], 1280, $doctors_info['clic_id'])."'}";
                $doctors_image_mobile[] = cthumb($val['doctors_image'], 360, $doctors_info['clic_id']);
            }
        } else {
            $doctors_image[] = "{ title : '', levelA : '".thumb($doctors_info, 60)."', levelB : '".thumb($doctors_info, 360)."', levelC : '".thumb($doctors_info, 360)."', levelD : '".thumb($doctors_info, 1280)."'}";
            $doctors_image_mobile[] = thumb($doctors_info, 360);
        }

        //团购
        if (C('groupbuy_allow')) {
            $groupbuy_info = Model('groupbuy')->getGroupbuyInfoBydoctorsCommonID($doctors_info['doctors_commonid']);
            if (!empty($groupbuy_info)) {
                $doctors_info['promotion_type'] = 'groupbuy';
                $doctors_info['remark'] = $groupbuy_info['remark'];
                $doctors_info['promotion_price'] = $groupbuy_info['groupbuy_price'];
                $doctors_info['down_price'] = ncPriceFormat($doctors_info['doctors_price'] - $groupbuy_info['groupbuy_price']);
                $doctors_info['upper_limit'] = $groupbuy_info['upper_limit'];
            }
        }

        //限时折扣
        if (C('promotion_allow') && empty($groupbuy_info)) {
            $xianshi_info = Model('p_xianshi_doctors')->getXianshidoctorsInfoBydoctorsID($doctors_id);
            if (!empty($xianshi_info)) {
                $doctors_info['promotion_type'] = 'xianshi';
                $doctors_info['remark'] = $xianshi_info['xianshi_title'];
                $doctors_info['promotion_price'] = $xianshi_info['xianshi_price'];     
                $doctors_info['down_price'] = ncPriceFormat($doctors_info['doctors_price'] - $xianshi_info['xianshi_price']);
                $doctors_info['lower_limit'] = $xianshi_info['lower_limit'];
            }
        }

        //满即送
        $mansong_info = Model('p_mansong')->getMansongInfoByclicID($doctors_info['clic_id']);

        // 商品受关注次数加1
        $_times = cookie('tm_visit_doc');
        if (empty($_times)) {
            $this->editdoctors(array('doctors_click' => array('exp', 'doctors_click + 1')), array('doctors_id' => $doctors_id));
            setNcCookie('tm_visit_doc', 1);
            $doctors_info['doctors_click'] = intval($doctors_info['doctors_click']) + 1;
        }
 
        $result = array();
        $result['doctors_info'] = $doctors_info;
        $result['spec_list'] = $spec_list;
        $result['spec_list_mobile'] = $spec_list_mobile;
        $result['spec_image'] = $spec_image;
        $result['doctors_image'] = $doctors_image;
        $result['doctors_image_mobile'] = $doctors_image_mobile;
        $result['groupbuy_info'] = $groupbuy_info;
        $result['xianshi_info'] = $xianshi_info;
        $result['mansong_info'] = $mansong_info;
        return $result;
    }
    
    /**
     * 获得商品SKU某字段的和
     * 
     * @param array $condition
     * @param string $field
     * @return boolean
     */
    public function getdoctorsSum($condition, $field) {
        return $this->table('doctors')->where($condition)->sum($field);
    }
    
    /**
     * 获得商品SKU数量
     * 
     * @param array $condition
     * @param string $field
     * @return int
     */
    public function getdoctorsCount($condition) {
        return $this->table('doctors')->where($condition)->count();
    }

    /**
     * 获得出售中商品SKU数量
     *
     * @param array $condition
     * @param string $field
     * @return int
     */
    public function getdoctorsOnlineCount($condition, $field = '*', $group = '') {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->table('doctors')->where($condition)->group($group)->count1($field);
    }
    /**
     * 获得商品数量
     *
     * @param array $condition
     * @param string $field
     * @return int
     */
    public function getdoctorsCommonCount($condition) {
        return $this->table('doctors_common')->where($condition)->count();
    }
    
    /**
     * 出售中的商品数量
     *
     * @param array $condition
     * @return int
     */
    public function getdoctorsCommonOnlineCount($condition) {
        $condition['doctors_state']   = self::STATE1;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonCount($condition);
    }

    /**
     * 仓库中的商品数量
     *
     * @param array $condition
     * @return int
     */
    public function getdoctorsCommonOfflineCount($condition) {
        $condition['doctors_state']   = self::STATE0;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonCount($condition);
    }
     
    /**
     * 等待审核的商品数量
     * 
     * @param array $condition
     * @return int
     */
    public function getdoctorsCommonWaitVerifyCount($condition) {
        $condition['doctors_verify']  = self::VERIFY10;
        return $this->getdoctorsCommonCount($condition);
    }
    
    /**
     * 审核是被的商品数量
     * 
     * @param array $condition
     * @return int
     */
    public function getdoctorsCommonVerifyFailCount($condition) {
        $condition['doctors_verify']  = self::VERIFY0;
        return $this->getdoctorsCommonCount($condition);
    }
    
    /**
     * 违规下架的商品数量
     * 
     * @param array $condition
     * @return int
     */
    public function getdoctorsCommonLockUpCount($condition) {
        $condition['doctors_state']   = self::STATE10;
        $condition['doctors_verify']  = self::VERIFY1;
        return $this->getdoctorsCommonCount($condition);
    }
    
    /**
     * 商品图片列表
     * 
     * @param array $condition
     * @param array $appointment
     * @param string $field
     * @return array
     */
    public function getdoctorsImageList($condition, $field = '*', $appointment = 'is_default desc,doctors_image_sort asc') {
        $this->cls();
        return $this->table('doctors_images')->field($field)->where($condition)->appointment($appointment)->select();
    }
    
    /**
     * 浏览过的商品
     * 
     * @return array
     */
    public function getVieweddoctorsList() {
        //取浏览过产品的cookie(最大四组)
        $viewed_doctors = array();
        $cookie_i = 0;
        
        if(cookie('viewed_doctors')){
            $string_viewed_doctors = decrypt(cookie('viewed_doctors'),MD5_KEY);
            if (get_magic_quotes_gpc()) $string_viewed_doctors = stripslashes($string_viewed_doctors);//去除斜杠
            $cookie_array = array_reverse(unserialize($string_viewed_doctors));
            $doctorsid_array = array();
            foreach ((array)$cookie_array as $k=>$v){
                $info = explode("-", $v);
                if (is_numeric($info[0])){
                    $doctorsid_array[] = intval($info[0]);
                }
            }
            $viewed_list    = $this->getdoctorsList(array('doctors_id' => array('in', $doctorsid_array)), 'doctors_id, doctors_name, doctors_price, doctors_image, clic_id');
            foreach ((array)$viewed_list as $val){
                $viewed_doctors[] = array(
                        "doctors_id"      => $val['doctors_id'],
                        "doctors_name"    => $val['doctors_name'],
                        "doctors_image"   => $val['doctors_image'],
                        "doctors_price"   => $val['doctors_price'],
                        "clic_id"      => $val['clic_id']
                );
            }
        }
        return $viewed_doctors;
    }

    /**
     * 删除商品SKU信息
     *
     * @param array $condition
     * @return boolean
     */
    public function deldoctors($condition) {
        $doctors_list = $this->getdoctorsList($condition, 'doctors_id,clic_id');
        if (!empty($doctors_list)) {
            foreach ($doctors_list as $val) {
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$doctors_list['clic_id'].DS.$doctors_list['doctors_id'].'.png');
            }
        }
        return $this->table('doctors')->where($condition)->delete();
    }
    
    /**
     * 删除商品图片表信息
     * 
     * @param array $condition
     * @return boolean
     */
    public function deldoctorsImages($condition) {
        return $this->table('doctors_images')->where($condition)->delete();
    }
    
    /**
     * 商品删除及相关信息
     *
     * @param   array $condition 列表条件
     * @return boolean
     */
    public function deldoctorsAll($condition) {
        $doctors_list = $this->getdoctorsList($condition, 'doctors_id,doctors_commonid,clic_id');
        if (empty($doctors_list)) {
            return false;
        }
        $doctorsid_array = array();
        $commonid_array = array();
        foreach ($doctors_list as $val) {
            $doctorsid_array[] = $val['doctors_id'];
            $commonid_array[] = $val['doctors_commonid'];
            // 删除二维码
            unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$val['clic_id'].DS.$val['doctors_id'].'.png');
        }
        $commonid_array = array_unique($commonid_array);
        
        // 删除商品表数据
        $this->deldoctors(array('doctors_id' => array('in', $doctorsid_array)));
        // 删除商品公共表数据
        $this->table('doctors_common')->where(array('doctors_commonid' => array('in', $commonid_array)))->delete();
        // 删除商品图片表数据
        $this->deldoctorsImages(array('doctors_commonid' => array('in', $commonid_array)));
        // 删除属性关联表数据
        $this->table('doctors_attr_index')->where(array('doctors_id' => array('in', $doctorsid_array)))->delete();
        // 删除买家收藏表数据
        $this->table('favorites')->where(array('fav_id' => array('in', $doctorsid_array), 'fav_type' => 'doctors'))->delete();
        // 删除优惠套装商品
        Model('p_bundling')->delBundlingdoctors(array('doctors_id' => array('in', $doctorsid_array)));
        // 优惠套餐活动下架
        Model('p_bundling')->editBundlingCloseBydoctorsIds(array('doctors_id' => array('in', $doctorsid_array)));
        // 推荐展位商品
        Model('p_booth')->delBoothdoctors(array('doctors_id' => array('in', $doctorsid_array)));

        return true;
    }
    
    /**
     * 删除未锁定商品
     * @param unknown $condition
     */
    public function deldoctorsNoLock($condition) {
        $condition['doctors_lock'] = 0;
        $common_array = $this->getdoctorsCommonList($condition, 'doctors_commonid');
        $common_array = array_under_reset($common_array, 'doctors_commonid');
        $commonid_array = array_keys($common_array);
        return $this->deldoctorsAll(array('doctors_commonid' => array('in', $commonid_array)));
    }
    
    /**
     * doctors_show = 1 为出售中，doctors_show = 0为未出售（仓库中，违规，等待审核）
     * 
     * @param string $field
     * @return string
     */
    private function _asdoctorsShow($field) {
        return $field.',(doctors_state=' . self::STATE1 . ' && doctors_verify=' . self::VERIFY1 . ') as doctors_show';
    }

     /**
      * 获得商品子分类的ID
      * @param array $condition
      * @return array 
      */
    private function _getRecursiveClass($condition){
        if (isset($condition['gc_id']) && !is_array($condition['gc_id'])) {
            $gc_list = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
            if (!empty($gc_list[$condition['gc_id']])) {
                $gc_id[] = $condition['gc_id'];
                $gcchild_id = empty($gc_list[$condition['gc_id']]['child']) ? array() : explode(',', $gc_list[$condition['gc_id']]['child']);
                $gcchildchild_id = empty($gc_list[$condition['gc_id']]['childchild']) ? array() : explode(',', $gc_list[$condition['gc_id']]['childchild']);
                $gc_id = array_merge($gc_id, $gcchild_id, $gcchildchild_id);
                $condition['gc_id'] = array('in', $gc_id);
            }
        }
        return $condition;
    }
}
