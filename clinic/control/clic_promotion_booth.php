<?php
/**
 * 推荐展位管理
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class clic_promotion_boothControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage(Language::get('promotion_unavailable'), urlclinic('clinicer_center', 'index'),'','error');
        }
    }

    public function indexOp() {
        $this->booth_doctors_listOp();
    }
    
    /**
     * 套餐商品列表
     */
    public function booth_doctors_listOp() {
        $model_booth = Model('p_booth');
        // 更新套餐状态
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        $where['booth_quota_endtime'] = array('lt', TIMESTAMP);
        $model_booth->editBoothClose($where);
        // 检查是否已购买套餐
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        $booth_quota = $model_booth->getBoothQuotaInfo($where);
        Tpl::output('booth_quota', $booth_quota);
        if (!empty($booth_quota)) {
            // 查询已选择商品
            $boothdoctors_list = $model_booth->getBoothdoctorsList(array('clic_id' => $_SESSION['clic_id']), 'doctors_id');
            if (!empty($boothdoctors_list)) {
                $doctorsid_array = array();
                foreach ($boothdoctors_list as $val) {
                    $doctorsid_array[] = $val['doctors_id'];
                }
                $doctors_list = Model('doctors')->getdoctorsAsdoctorsShowList(array('doctors_id' => array('in', $doctorsid_array)), 'doctors_id,doctors_name,doctors_image,doctors_price,clic_id,gc_id');
                if (!empty($doctors_list)) {
                    $gcid_array = array();  // 商品分类id
                    foreach ($doctors_list as $key => $val) {
                        $gcid_array[] = $val['gc_id'];
                        $doctors_list[$key]['doctors_image'] = thumb($val);
                        $doctors_list[$key]['url'] = urlclinic('doctors', 'index', array('doctors_id' => $val['doctors_id']));
                    }
                    $doctorsclass_list = Model('doctors_class')->getdoctorsClassList(array('gc_id' => array('in', $gcid_array)), 'gc_id,gc_name');
                    $doctorsclass_list = array_under_reset($doctorsclass_list, 'gc_id');
                    Tpl::output('doctors_list', $doctors_list);
                    Tpl::output('doctorsclass_list', $doctorsclass_list);
                }
            }
        }

        $this->profile_menu('booth_doctors_list', 'booth_doctors_list');
        Tpl::showpage('clic_promotion_booth.doctors_list');
    }
    
    /**
     * 选择商品
     */
    public function booth_select_doctorsOp() {
        $model_doctors = Model('doctors');
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        if ($_POST['doctors_name'] != '') {
            $condition['doctors_name'] = array('like', '%'.$_POST['doctors_name'].'%');
        }
        $doctors_list = $model_doctors->getdoctorsOnlineList($condition, '*', 10);
        
        Tpl::output('doctors_list', $doctors_list);
        Tpl::output('show_page', $model_doctors->showpage());
        Tpl::showpage('clic_promotion_booth.select_doctors', 'null_layout');
    }
    
    /**
     * 购买套餐
     */
    public function booth_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['booth_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_booth_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlclinic('clic_promotion_booth', 'booth_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_booth = Model('p_booth');
            
            $data = array();
            $data['clic_id']               = $_SESSION['clic_id'];
            $data['clic_name']             = $_SESSION['clic_name'];
            $data['booth_quota_starttime']  = TIMESTAMP;
            $data['booth_quota_endtime']    = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            $data['booth_state']            = 1;
            
            $return = $model_booth->addBoothQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordclicCost($price_quantity, '购买推荐展位');

                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                $this->addcron(array('exetime' => $end_time, 'exeid' => $_SESSION['clic_id'], 'type' => 4));
                $this->recordclinicerLog('购买'.$quantity.'套推荐展位，单位元');
                showDialog('购买成功', urlclinic('clic_promotion_booth', 'booth_doctors_list'), 'succ');
            } else {
                showDialog('购买失败', urlclinic('clic_promotion_booth', 'booth_quota_add'));
            }
        }
        // 输出导航
        self::profile_menu('booth_quota_add', 'booth_quota_add');
        Tpl::showpage('clic_promotion_booth.quota_add');
    }
    
    /**
     * 套餐续费
     */
    public function booth_renewOp() {
        if (chksubmit()) {
            $model_booth = Model('p_booth');
            $quantity = intval($_POST['booth_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_booth_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlclinic('clic_promotion_booth', 'booth_quota_add'), '', 'error' );
            }
            $where = array();
            $where['clic_id'] = $_SESSION ['clic_id'];
            $booth_quota = $model_booth->getBoothQuotaInfo($where);
            if ($booth_quota['booth_quota_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['booth_quota_endtime']   = intval($booth_quota['booth_quota_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['booth_quota_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_booth->editBoothQuotaOpen($update, $where);
        
            if ($return) {
                // 添加店铺费用记录
                $this->recordclicCost($price_quantity, '购买推荐展位');

                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                $this->addcron(array('exetime' => $end_time, 'exeid' => $_SESSION['clic_id'], 'type' => 4), true);
                $this->recordclinicerLog('续费'.$quantity.'套推荐展位，单位元');
                showDialog('购买成功', urlclinic('clic_promotion_booth', 'booth_list'), 'succ');
            } else {
                showDialog('购买失败', urlclinic('clic_promotion_booth', 'booth_quota_add'));
            }
        }
    
        self::profile_menu('booth_renew', 'booth_renew');
        Tpl::showpage('clic_promotion_booth.quota_add');
    }
    
    /**
     * 选择商品
     */
    public function choosed_doctorsOp() {
        $gid = $_GET['gid'];
        if ($gid <= 0) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }
        
        // 验证商品是否存在
        $doctors_info = Model('doctors')->getdoctorsInfo(array('doctors_id' => $gid, 'clic_id' => $_SESSION['clic_id']), 'doctors_id,doctors_name,doctors_image,doctors_price,clic_id,gc_id');
        if (empty($doctors_info)) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }

        $model_booth = Model('p_booth');
        // 验证套餐时候过期
        $booth_info = $model_booth->getBoothQuotaInfo(array('clic_id' => $_SESSION['clic_id'], 'booth_quota_endtime' => array('gt', TIMESTAMP)), 'booth_quota_id');
        if (empty($booth_info)) {
            $data = array('result' => 'false', 'msg' => '套餐过期请重新购买套餐');
            $this->_echoJson($data);
        }
        
        // 验证已添加商品数量，及选择商品是否已经被添加过
        $bootdoctors_info = $model_booth->getBoothdoctorsList(array('clic_id' => $_SESSION['clic_id']), 'doctors_id');
        // 已添加商品总数
        if (count($bootdoctors_info) >= C('promotion_booth_doctors_sum')) {
            $data = array('result' => 'false', 'msg' => '只能添加'.C('promotion_booth_doctors_sum').'个商品');
            $this->_echoJson($data);
        }
        // 商品是否已经被添加
        $bootdoctors_info = array_under_reset($bootdoctors_info, 'doctors_id');
        if (isset($bootdoctors_info[$gid])) {
            $data = array('result' => 'false', 'msg' => '商品已经添加，请选择其他商品');
            $this->_echoJson($data);
        }
        
        // 保存到推荐展位商品表
        $insert = array();
        $insert['clic_id'] = $_SESSION['clic_id'];
        $insert['doctors_id'] = $doctors_info['doctors_id'];
        $insert['gc_id'] = $doctors_info['gc_id'];
        $model_booth->addBoothdoctors($insert);

        $this->recordclinicerLog('添加推荐展位商品，商品id：'.$doctors_info['doctors_id']);
        
        // 输出商品信息
        $doctors_info['doctors_image'] = thumb($doctors_info);
        $doctors_info['url'] = urlclinic('doctors', 'index', array('doctors_id' => $doctors_info['doctors_id']));
        $doctors_class = Model('doctors_class')->getdoctorsClassInfo(array('gc_id' => $doctors_info['gc_id']), 'gc_name');
        $doctors_info['gc_name'] = $doctors_class['gc_name'];
        $doctors_info['result'] = 'true';
        $this->_echoJson($doctors_info);
    }
    
    /**
     * 删除选择商品
     */
    public function del_choosed_doctorsOp() {
        $gid = $_GET['gid'];
        if ($gid <= 0) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }
        
        $result = Model('p_booth')->delBoothdoctors(array('doctors_id' => $gid, 'clic_id' => $_SESSION['clic_id']));
        if ($result) {
            $this->recordclinicerLog('删除推荐展位商品，商品id：'.$gid);
            $data = array('result' => 'true');
        } else {
            $data = array('result' => 'false', 'msg' => '删除失败');
        }
        $this->_echoJson($data);
    }
    
    /**
     * 输出JSON
     * @param array $data
     */
    private function _echoJson($data) {
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($data);exit();
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array	= array();
        switch ($menu_type) {
            case 'booth_doctors_list':
                $menu_array	= array(
                    1=>array('menu_key'=>'booth_doctors_list', 'menu_name'=>'商品列表', 'menu_url'=>urlclinic('clic_promotion_booth', 'booth_doctors_list'))
                );
                break;
            case 'booth_quota_add':
                $menu_array = array(
                    1=>array('menu_key'=>'booth_doctors_list', 'menu_name'=>'商品列表', 'menu_url'=>urlclinic('clic_promotion_booth', 'booth_doctors_list')),
                    2=>array('menu_key'=>'booth_quota_add', 'menu_name'=>'购买套餐', 'menu_url'=>urlclinic('clic_promotion_booth', 'booth_quota_add'))
                );
                break;
            case 'booth_renew':
                $menu_array = array(
                    1=>array('menu_key'=>'booth_doctors_list', 'menu_name'=>'商品列表', 'menu_url'=>urlclinic('clic_promotion_booth', 'booth_doctors_list')),
                    2=>array('menu_key'=>'booth_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlclinic('clic_promotion_booth', 'booth_renew'))
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
        Tpl::output('menu_sign','booth');
        Tpl::output('menu_sign_url','index.php?act=clic_promotion_booth');
        Tpl::output('menu_sign1',$menu_key);
    }
}
