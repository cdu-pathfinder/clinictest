<?php
/**
 * 卖家帐号组管理
 *
 * 
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class store_account_groupControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    public function group_listOp() {
        $model_seller_group = Model('seller_group');
        $seller_group_list = $model_seller_group->getSellerGroupList(array('store_id' => $_SESSION['store_id']));
        Tpl::output('seller_group_list', $seller_group_list);
        $this->profile_menu('group_list');
        Tpl::showpage('store_account_group.list');
    }

    public function group_addOp() {
        $this->profile_menu('group_add');
        Tpl::showpage('store_account_group.add');
    }

    public function group_editOp() {
        $group_id = intval($_GET['group_id']);
        if ($group_id <= 0) {
            showMessage('Parameter error', '', '', 'error');
        }
        $model_seller_group = Model('seller_group');
        $seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $group_id));
        if (empty($seller_group_info)) {
            showMessage('Group does not exist', '', '', 'error');
        }
        Tpl::output('group_info', $seller_group_info);
        Tpl::output('group_limits', explode(',', $seller_group_info['limits']));
        $this->profile_menu('group_edit');
        Tpl::showpage('store_account_group.add');
    }

    public function group_saveOp() {
        $seller_info = array();
        $seller_info['group_name'] = $_POST['seller_group_name'];
        $seller_info['limits'] = implode(',', $_POST['limits']);
        $seller_info['store_id'] = $_SESSION['store_id'];
        $model_seller_group = Model('seller_group');
        if (empty($_POST['group_id'])) {
            $result = $model_seller_group->addSellerGroup($seller_info);
            $this->recordSellerLog('Group added successfully, group No.'.$result);
            showDialog('added successfully', urlShop('store_account_group', 'group_list'),'succ');
        } else {
            $condition = array();
            $condition['group_id'] = intval($_POST['group_id']);  
            $condition['store_id'] = $_SESSION['store_id'];
            $model_seller_group->editSellerGroup($seller_info, $condition);
            $this->recordSellerLog('Group edited successfully, group No.'.$_POST['group_id']);
            showDialog('edited successfully', urlShop('store_account_group', 'group_list'),'succ');
        }
    }

    public function group_delOp() {
        $group_id = intval($_POST['group_id']);
        if($group_id > 0) {
            $condition = array();
            $condition['group_id'] = $group_id;
            $condition['store_id'] = $_SESSION['store_id'];
            $model_seller_group = Model('seller_group');
            $result = $model_seller_group->delSellerGroup($condition);
            if($result) {
                $this->recordSellerLog('Group deleted successfully, group No.'.$group_id);
                showDialog(Language::get('nc_common_op_succ'),'reload','succ');
            } else {
                $this->recordSellerLog('Group added failed, group No.'.$group_id);
                showDialog(Language::get('nc_common_save_fail'),'reload','error');
            }
        } else {
            showDialog(Language::get('wrong_argument'),'reload','error');
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @param array 	$array		附加菜单
     * @return 
     */
    private function profile_menu($menu_key='') {
        $menu_array = array();
        $menu_array[] = array(
            'menu_key'=>'group_list',
            'menu_name' => 'group list',
            'menu_url' => urlShop('store_account_group', 'group_list')
        );
        if($menu_key === 'group_add') {
            $menu_array[] = array(
                'menu_key'=>'group_add', 
                'menu_name' => 'group add', 
                'menu_url' => urlShop('store_account_group', 'group_add')
            );
        }
        if($menu_key === 'group_edit') {
            $menu_array[] = array(
                'menu_key'=>'group_edit', 
                'menu_name' => 'group edit', 
                'menu_url' => urlShop('store_account_group', 'group_edit')
            );
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
