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
defined('InclinicNC') or exit ('Access Invalid!');
class clic_doctors_offlineControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct ();
        Language::read ('member_clic_doctors_index');
    }
    public function indexOp() {
        $this->doctors_storageOp();
    }
    
    /**
     * 仓库中的商品列表
     */
    public function doctors_storageOp() {
        $model_doctors = Model('doctors');
        
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['doctors_stcids'] = array('like', '%' . intval($_GET['stc_id']) . '%');
        }
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['doctors_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['doctors_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['doctors_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        
        switch ($_GET['type']) {
            // 违规的商品
            case 'lock_up':
                $this->profile_menu('doctors_lockup');
                $doctors_list = $model_doctors->getdoctorsCommonLockUpList($where);
                break;
            // 等待审核或审核失败的商品
            case 'wait_verify':
                $this->profile_menu('doctors_verify');
                if (isset($_GET['verify']) && in_array($_GET['verify'], array('0', '10'))) {
                    $where['doctors_verify']  = $_GET['verify'];
                }
                $doctors_list = $model_doctors->getdoctorsCommonWaitVerifyList($where);
                break;
            // 仓库中的商品
            default:
                $this->profile_menu('doctors_storage');
                $doctors_list = $model_doctors->getdoctorsCommonOfflineList($where);
                break;
        }
        
        Tpl::output('show_page', $model_doctors->showpage());
        Tpl::output('doctors_list', $doctors_list);
            
        // 计算库存
        $storage_array = $model_doctors->calculateStorage($doctors_list);
        Tpl::output('storage_array', $storage_array);
        
        // 商品分类
        $clic_doctors_class = Model('my_doctors_class')->getClassTree(array(
                                    'clic_id' => $_SESSION['clic_id'],
                                    'stc_state' => '1' 
                                ));
        Tpl::output('clic_doctors_class', $clic_doctors_class);
        
        switch ($_GET['type']) {
            // 违规的商品
            case 'lock_up':
                Tpl::showpage('clic_doctors_list.offline_lockup');
                break;
            // 等待审核或审核失败的商品
            case 'wait_verify':
                Tpl::output('verify', array('0' => '未通过', '10' => '等待审核'));
                Tpl::showpage('clic_doctors_list.offline_waitverify');
                break;
            // 仓库中的商品
            default:
                Tpl::showpage('clic_doctors_list.offline');
                break;
        }
    }
    
    /**
     * 商品上架
     */
    public function doctors_showOp() {
        $commonid = $_GET['commonid'];
        if (!preg_match('/^[\d,]+$/i', $commonid)) {
            showdialog(L('para_error'), '', 'error');
        }
        $commonid_array = explode(',', $commonid);
        if ($this->clic_info['clic_state'] != 1) {
            showdialog(L('clic_doctors_index_doctors_show_fail') . '，店铺正在审核中或已经关闭', '', 'error');
        }
        $return = Model('doctors')->editProducesOnline(array('doctors_commonid' => array('in', $commonid_array), 'clic_id' => $_SESSION['clic_id']));
        if ($return) {
            // 添加操作日志
            $this->recordclinicerLog('商品上架，平台货号：'.$commonid);
            showdialog(L('clic_doctors_index_doctors_show_success'), 'reload', 'succ');
        } else {
            showdialog(L('clic_doctors_index_doctors_show_fail'), '', 'error');
        }
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key = '') {
        $menu_array = array(
            array('menu_key' => 'doctors_storage',    'menu_name' => L('nc_member_path_doctors_storage'),   'menu_url' => urlclinic('clic_doctors_offline', 'index')),
            array('menu_key' => 'doctors_lockup',     'menu_name' => L('nc_member_path_doctors_state'),     'menu_url' => urlclinic('clic_doctors_offline', 'index', array('type' => 'lock_up'))),
            array('menu_key' => 'doctors_verify',     'menu_name' => L('nc_member_path_doctors_verify'),    'menu_url' => urlclinic('clic_doctors_offline', 'index', array('type' => 'wait_verify')))
        );
        Tpl::output ( 'member_menu', $menu_array );
        Tpl::output ( 'menu_key', $menu_key );
    }
}