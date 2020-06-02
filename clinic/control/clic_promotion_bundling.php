<?php
/**
 * 用户中心-优惠套装
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
class clic_promotion_bundlingControl extends BaseclinicerControl {

    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('member_layout,member_clic_promotion_bundling');
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage(Language::get('promotion_unavailable'), urlclinic('clinicer_center', 'index'),'','error');
        }

    }

    public function indexOp() {
        $this->bundling_listOp();
    }

    /**
     * 发布的活动列表
     */
    public function bundling_listOp() {
        $model_bundling = Model('p_bundling');
        
        // 更新套装状态
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        $where['bl_quota_endtime'] = array('lt', TIMESTAMP);
        $model_bundling->editBundlingQuotaClose($where);
        
        // 检查是否已购买套餐
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        $bundling_quota = $model_bundling->getBundlingQuotaInfo($where);
        Tpl::output('bundling_quota', $bundling_quota);
        if (!empty($bundling_quota)) {
            // 计算已经发布活动、剩余活动数量
            $bundling_published  = $model_bundling->getBundlingCount(array('clic_id' => $_SESSION['clic_id']));
            $bundling_surplus    = intval(C('promotion_bundling_sum')) - intval($bundling_published);
            Tpl::output('bundling_published', $bundling_published);
            Tpl::output('bundling_surplus', $bundling_surplus);

            // 查询活动
            $where = array();
            $where['clic_id'] = $_SESSION['clic_id'];
            if ($_GET['bundling_name'] != '') {
                $where['bl_name'] = array('like', '%' . trim($_GET['bundling_name']) . '%');
            }
            if (is_numeric($_GET['state'])) {
                $where['bl_state'] = $_GET['state'];
            }
            $bundling_list = $model_bundling->getBundlingList($where, '*', 'bl_id desc', 10, 0, $bundling_published);
            $bundling_list = array_under_reset($bundling_list, 'bl_id');
            Tpl::output('show_page',$model_bundling->showpage(2));
            if (!empty($bundling_list)) {
                $blid_array = array_keys($bundling_list);
                $bdoctors_array = $model_bundling->getBundlingdoctorsList(array('bl_id' => array('in', $blid_array), 'bl_appoint' => 1), 'bl_id,doctors_id,count(*) as count', 'bl_appoint desc', 'bl_id');
                $bdoctors_array = array_under_reset($bdoctors_array, 'doctors_id');
                if (!empty($bdoctors_array)) {
                    $doctorsid_array = array_keys($bdoctors_array);
                    $doctors_array = Model('doctors')->getdoctorsList(array('doctors_id' => array('in', $doctorsid_array)), 'doctors_id,doctors_image');
                    $doctors_array = array_under_reset($doctors_array, 'doctors_id');
                }
                $bdoctors_array = array_under_reset($bdoctors_array, 'bl_id');
                foreach ($bundling_list as $key => $val) {
                    $bundling_list[$key]['doctors_id'] = $bdoctors_array[$val['bl_id']]['doctors_id'];
                    $bundling_list[$key]['count'] = $bdoctors_array[$val['bl_id']]['count'];
                    $bundling_list[$key]['img'] = thumb($doctors_array[$bdoctors_array[$val['bl_id']]['doctors_id']], 60);
                }
            }
            Tpl::output('list', $bundling_list);
            
            // 状态数组
            $state_array = array(0=>Language::get('bundling_status_0') , 1=>Language::get('bundling_status_1'));
            Tpl::output('state_array', $state_array);
        }
        $this->profile_menu('bundling_list', 'bundling_list');
        Tpl::showpage('clic_promotion_bundling.list');
    }
    
    /**
     * 套餐购买
     */
    public function bundling_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['bundling_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_bundling_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog(Language::get('bundling_quota_price_fail'), urlclinic('clic_promotion_bundling', 'bundling_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_bundling = Model('p_bundling');
            
            $data = array();
            $data['clic_id']           = $_SESSION['clic_id'];
            $data['clic_name']         = $_SESSION['clic_name'];
            $data['member_id']          = $_SESSION['member_id'];
            $data['member_name']        = $_SESSION['member_name'];
            $data['bl_quota_month']     = $quantity;
            $data['bl_quota_starttime'] = TIMESTAMP;
            $data['bl_quota_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            $data['bl_state']     = 1;
            
            $return = $model_bundling->addBundlingQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordclicCost($price_quantity, '购买优惠套装');
                
                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                $this->addcron(array('exetime' => $end_time, 'exeid' => $_SESSION['clic_id'], 'type' => 3), true);

                $this->recordclinicerLog('购买'.$quantity.'套优惠套装，单位元');
                showDialog(L('bundling_quota_price_succ'), urlclinic('clic_promotion_bundling', 'bundling_list'), 'succ');
            } else {
                showDialog(L('bundling_quota_price_fail'), urlclinic('clic_promotion_bundling', 'bundling_quota_add'));
            }
        }
        // 输出导航
        self::profile_menu('bundling_quota_add', 'bundling_quota_add');
        Tpl::showpage('clic_promotion_bundling.quota_add');
    }

    /**
     * 套餐续费
     */
    public function bundling_renewOp() {
        if (chksubmit()) {
            $model_bundling = Model('p_bundling');
            $quantity = intval($_POST['bundling_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_bundling_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog(Language::get('bundling_quota_price_fail'), urlclinic('clic_promotion_bundling', 'bundling_quota_add'), '', 'error' );
            }
            $where = array();
            $where['clic_id'] = $_SESSION ['clic_id'];
            $bundling_quota = $model_bundling->getBundlingQuotaInfo($where);
            if ($bundling_quota['bl_quota_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['bl_quota_endtime']   = intval($bundling_quota['bl_quota_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['bl_quota_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_bundling->editBundlingQuotaOpen($update, $where);
        
            if ($return) {
                // 添加店铺费用记录
                $this->recordclicCost($price_quantity, '购买优惠套装');

                // 添加任务队列
                $this->addcron(array('exetime' => $update['bl_quota_endtime'], 'exeid' => $_SESSION['clic_id'], 'type' => 3), true);

                $this->recordclinicerLog('续费'.$quantity.'套优惠套装，单位元');
                showDialog(L('bundling_quota_price_succ'), urlclinic('clic_promotion_bundling', 'bundling_list'), 'succ');
            } else {
                showDialog(L('bundling_quota_price_fail'), urlclinic('clic_promotion_bundling', 'bundling_quota_add'));
            }
        }
    
        self::profile_menu('bundling_renew', 'bundling_renew');
        Tpl::showpage('clic_promotion_bundling.quota_add');
    }

    /**
     * 套餐活动添加
     */
    public function bundling_addOp() {
        /**
         * 实例化模型
         */
        $model_bundling = Model('p_bundling');
        
        // 验证套餐数量
        if (intval(C('promotion_bundling_sum')) != 0 && !isset($_REQUEST['bundling_id'])) {
            $count = $model_bundling->getBundlingCount(array('clic_id' => $_SESSION['clic_id']));
            if (intval(C('promotion_bundling_sum')) <= intval($count)) {
                showMessage(L('bundling_add_fail_quantity_beyond'), '', '', 'error');
            }
        }
        
        if (chksubmit()) {
            // 插入套餐
            $data = array();
            if (isset($_POST['bundling_id'])) {
                $data['bl_id'] = intval($_POST['bundling_id']);
            }
            $data['bl_name'] = $_POST['bundling_name'];
            $data['clic_id'] = $_SESSION['clic_id'];
            $data['clic_name'] = $_SESSION['clic_name'];
            $data['bl_discount_price'] = $_POST['discount_price'];
            $data['bl_freight_choose'] = $_POST['bundling_freight_choose'];
            $data['bl_freight'] = $_POST['bundling_freight'];
            $data['bl_state'] = intval($_POST['state']);
            $return = $model_bundling->addBundling($data, true);
            if (!$return) {
                showDialog(L('nc_common_op_fail'), '', '', 'error');
            }
            
            // 插入套餐商品
            $model_doctors = Model('doctors');
            $data_doctors = array();
            $appoint_doctorsid = false;
            $model_bundling->delBundlingdoctors(array('bl_id' => intval($_POST['bundling_id'])));
            if (!empty($_POST['doctors']) && is_array($_POST['doctors'])) {
                foreach ($_POST['doctors'] as $key => $val) {
                    // 验证是否为本店铺商品
                    $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id' => $val['gid'], 'clic_id' => $_SESSION['clic_id']), 'doctors_id,doctors_name,doctors_image');
                    if (empty($doctors_info)) {
                        continue;
                    }
                    $data = array();
                    $data['bl_id'] = isset($_POST['bundling_id']) ? intval($_POST['bundling_id']) : $return;
                    $data['doctors_id'] = $doctors_info['doctors_id'];
                    $data['doctors_name'] = $doctors_info['doctors_name'];
                    $data['doctors_image'] = $doctors_info['doctors_image'];
                    $data['bl_doctors_price'] = ncPriceFormat($val['price']);
                    $data['bl_appoint'] = intval($val['appoint']);
                    if (!$appoint_doctorsid && intval($val['appoint']) == 1) {
                        $appoint_doctorsid = intval($val['gid']);
                    }
                    $data_doctors[] = $data;
                }
            }
            // 插入数据
            $return = $model_bundling->addBundlingdoctorsAll($data_doctors);
            

            if (!isset($_POST['bundling_id']) && !$appoint_doctorsid) {
                // 自动发布动态
                // bl_id,bl_name,image_path,bl_discount_price,bl_freight_choose,bl_freight,clic_id
                $data_array = array();
                $data_array['bl_id'] = $return;
                $data_array['doctors_id'] = $appoint_doctorsid;
                $data_array['bl_name'] = $data['bl_name'];
                $data_array['bl_img'] = empty($_POST['image_path']) ? '' : $_POST['image_path'][0];
                $data_array['bl_discount_price'] = $data['bl_discount_price'];
                $data_array['bl_freight_choose'] = $data['bl_freight_choose'];
                $data_array['bl_freight'] = $data['bl_freight'];
                $data_array['clic_id'] = $_SESSION['clic_id'];
                $this->clicAutoShare($data_array, 'bundling');
            }

            $this->recordclinicerLog('添加优惠套装，名称：'.$data['bl_name'] . ' id：'.$return);
            showDialog(L('nc_common_op_succ'), urlclinic('clic_promotion_bundling', 'bundling_list'), 'succ');
        }
        
        // 是否能使用编辑器
        if(checkPlatformclic()){ // 平台店铺可以使用编辑器
            $editor_multimedia = true;
        } else {    // 三方店铺需要
            $editor_multimedia = false;
            if ($this->clic_grade['sg_function'] == 'editor_multimedia') {
                $editor_multimedia = true;
            }
        }
        Tpl::output('editor_multimedia', $editor_multimedia);
        
        if (intval($_GET['bundling_id']) > 0) {
            $bundling_info = $model_bundling->getBundlingInfo(array('bl_id' => intval($_GET['bundling_id']), 'clic_id' => $_SESSION['clic_id']));
            Tpl::output('bundling_info', $bundling_info);
            // 验证是否属于自己的组合套餐
            if (empty($bundling_info['clic_id'])) {
                showMessage(L( 'wrong_argument'), urlclinic('clic_promotion_bundling', 'bundling_list'), '', 'error' );
            }
            
            $b_doctors_list = $model_bundling->getBundlingdoctorsList(array('bl_id' => intval($_GET['bundling_id'])));
            if (!empty($b_doctors_list)) {
                $doctorsid_array = array();
                foreach ($b_doctors_list as $val) {
                    $doctorsid_array[] = $val['doctors_id'];
                }
                $doctors_list = Model('doctors')->getdoctorsAsdoctorsShowList(array('doctors_id' => array('in', $doctorsid_array)), 'doctors_id,doctors_price,doctors_image,doctors_name');
                Tpl::output('doctors_list', array_under_reset($doctors_list, 'doctors_id'));
            }
            Tpl::output('b_doctors_list', $b_doctors_list);
            // 输出导航
            self::profile_menu('bundling_edit', 'bundling_edit');
        } else {
            // 输出导航
            self::profile_menu('bundling_add', 'bundling_add');
        }
        Tpl::showpage('clic_promotion_bundling.add');
    }
    
    /**
     * 套餐活动添加商品
     */
    public function bundling_add_doctorsOp() {
        /**
         * 实例化模型
         */
        $model_doctors =Model('doctors');
        
        // where条件
        $where = array ();
        $where['clic_id'] = $_SESSION['clic_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['doctors_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
        }
        if (trim($_GET['keyword']) != '') {
            $where['doctors_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
        }
        
        $doctors_list = $model_doctors->getdoctorsOnlineList($where, '*', 8);
        Tpl::output('show_page', $model_doctors->showpage(2));
        Tpl::output('doctors_list', $doctors_list);
        
        /**
         * 商品分类
         */
        $clic_doctors_class = Model('my_doctors_class')->getClassTree(array('clic_id' => $_SESSION['clic_id'], 'stc_state' => '1'));
        Tpl::output('clic_doctors_class', $clic_doctors_class);
        
        Tpl::showpage('clic_promotion_bundling.add_doctors', 'null_layout');
    }
    
    /**
     * 删除优惠套装活动
     */
    public function drop_bundlingOp(){
        /**
         * 参数验证
         */
        $blids = trim($_GET['bundling_id']);
        if (empty($blids)) {
            showdialog(L('para_error'), '', 'error');
        }
        
        $return = Model('p_bundling')->delBundling($blids, $_SESSION['clic_id']);
        if ($return) {
            $this->recordclinicerLog('删除优惠套装，套餐id：'.$blids);
            showDialog(L('bundling_delete_success'), 'reload', 'succ');
        } else {
            showDialog(L('bundling_delete_fail'), '', 'error');
        }
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
            case 'bundling_list':
            case 'bundling_quota_list':
                $menu_array	= array(
                    1=>array('menu_key'=>'bundling_list', 'menu_name'=>Language::get('bundling_list'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_list'))
                );
                break;
            case 'bundling_quota_add':
                $menu_array	= array(
                    1=>array('menu_key'=>'bundling_list', 'menu_name'=>Language::get('bundling_list'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_list')),
                    2=>array('menu_key'=>'bundling_quota_add', 'menu_name'=>Language::get('bundling_quota_add'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_quota_add'))
                );
                break;
            case 'bundling_renew':
                $menu_array	= array(
                    1=>array('menu_key'=>'bundling_list', 'menu_name'=>Language::get('bundling_list'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_list')),
                    2=>array('menu_key'=>'bundling_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_renew'))
                );
                break;
            case 'bundling_add':
                $menu_array	= array(
                    1=>array('menu_key'=>'bundling_list', 'menu_name'=>Language::get('bundling_list'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_list')),
                    2=>array('menu_key'=>'bundling_add', 'menu_name'=>Language::get('bundling_add'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_add'))
                );
                break;
            case 'bundling_edit':
                $menu_array	= array(
                    1=>array('menu_key'=>'bundling_list', 'menu_name'=>Language::get('bundling_list'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_list')),
                    2=>array('menu_key'=>'bundling_edit', 'menu_name'=>Language::get('bundling_edit'), 'menu_url'=>urlclinic('clic_promotion_bundling', 'bundling_edit'))
                );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
        Tpl::output('menu_sign','bundling');
        Tpl::output('menu_sign_url','index.php?act=clic_promotion_bundling');
        Tpl::output('menu_sign1',$menu_key);
    }
}
