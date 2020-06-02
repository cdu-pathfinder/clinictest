<?php
/**
 * 卖家帐号管理
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
class clic_accountControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_clic_index');
    }

    public function account_listOp() {
        $model_clinicer = Model('clinicer');
        $condition = array(
            'clic_id' => $_SESSION['clic_id'],
            'clinicer_group_id' => array('gt', 0)
        );
        $clinicer_list = $model_clinicer->getclinicerList($condition);
        Tpl::output('clinicer_list', $clinicer_list);

        $model_clinicer_group = Model('clinicer_group');
        $clinicer_group_list = $model_clinicer_group->getclinicerGroupList(array('clic_id' => $_SESSION['clic_id']));
        $clinicer_group_array = array_under_reset($clinicer_group_list, 'group_id');
        Tpl::output('clinicer_group_array', $clinicer_group_array);

        $this->profile_menu('account_list');
        Tpl::showpage('clic_account.list');
    }

    public function account_addOp() {
        $model_clinicer_group = Model('clinicer_group');
        $clinicer_group_list = $model_clinicer_group->getclinicerGroupList(array('clic_id' => $_SESSION['clic_id']));
        if (empty($clinicer_group_list)) {
            showMessage('请先建立帐号组', urlclinic('clic_account_group', 'group_add'), '', 'error');
        }
        Tpl::output('clinicer_group_list', $clinicer_group_list);
        $this->profile_menu('account_add');
        Tpl::showpage('clic_account.add');
    }

    public function account_editOp() {
        $clinicer_id = intval($_GET['clinicer_id']);
        if ($clinicer_id <= 0) {
            showMessage('Parameter error', '', '', 'error');
        }
        $model_clinicer = Model('clinicer');
        $clinicer_info = $model_clinicer->getclinicerInfo(array('clinicer_id' => $clinicer_id));
        if (empty($clinicer_info) || intval($clinicer_info['clic_id']) !== intval($_SESSION['clic_id'])) {
            showMessage('Account does not exist', '', '', 'error');
        }
        Tpl::output('clinicer_info', $clinicer_info);

        $model_clinicer_group = Model('clinicer_group');
        $clinicer_group_list = $model_clinicer_group->getclinicerGroupList(array('clic_id' => $_SESSION['clic_id']));
        if (empty($clinicer_group_list)) {
            showMessage('Please create an account group first', urlclinic('clic_account_group', 'group_add'), '', 'error');
        }
        Tpl::output('clinicer_group_list', $clinicer_group_list);

        $this->profile_menu('account_edit');
        Tpl::showpage('clic_account.edit');
    }

    public function account_saveOp() {
        $member_name = $_POST['member_name'];
        $password = $_POST['password'];
        $member_info = $this->_check_clinicer_member($member_name, $password);
        if(!$member_info) {
            showDialog('用户验证失败', 'reload', 'error');
        }

        $clinicer_name = $_POST['clinicer_name'];
        if($this->_is_clinicer_name_exist($clinicer_name)) {
            showDialog('卖家帐号已存在', 'reload', 'error');
        }

        $group_id = intval($_POST['group_id']);

        $clinicer_info = array(
            'clinicer_name' => $clinicer_name,
            'member_id' => $member_info['member_id'],
            'clinicer_group_id' => $group_id,
            'clic_id' => $_SESSION['clic_id'],
            'is_admin' => 0
        );
        $model_clinicer = Model('clinicer');
        $result = $model_clinicer->addclinicer($clinicer_info);

        if($result) {
            $this->recordclinicerLog('Add account successfully, account No.'.$result);
            showDialog(Language::get('nc_common_op_succ'), urlclinic('clic_account', 'account_list'), 'succ');
        } else {
            $this->recordclinicerLog('Add account failed');
            showDialog(Language::get('nc_common_save_fail'), urlclinic('clic_account', 'account_list'), 'error');
        }
    }
    
    public function account_edit_saveOp() {
        $param = array('clinicer_group_id' => intval($_POST['group_id']));
        $condition = array(
            'clinicer_id' => intval($_POST['clinicer_id']),
            'clic_id' =>  $_SESSION['clic_id']
        );
        $model_clinicer = Model('clinicer');
        $result = $model_clinicer->editclinicer($param, $condition);
        if($result) {
            $this->recordclinicerLog('Edit account successfully, account No.：'.$_POST['clinicer_id']);
            showDialog(Language::get('nc_common_op_succ'), urlclinic('clic_account', 'account_list'), 'succ');
        } else {
            $this->recordclinicerLog('Edit account failed, account No.：'.$_POST['clinicer_id'], 0);
            showDialog(Language::get('nc_common_save_fail'), urlclinic('clic_account', 'account_list'), 'error');
        }
    }

    public function account_delOp() {
        $clinicer_id = intval($_POST['clinicer_id']);
        if($clinicer_id > 0) {
            $condition = array();
            $condition['clinicer_id'] = $clinicer_id;
            $condition['clic_id'] = $_SESSION['clic_id'];
            $model_clinicer = Model('clinicer');
            $result = $model_clinicer->delclinicer($condition);
            if($result) {
                $this->recordclinicerLog('Delete account successfully, account No.'.$clinicer_id);
                showDialog(Language::get('nc_common_op_succ'),'reload','succ');
            } else {
                $this->recordclinicerLog('Delete account failed, account No.'.$clinicer_id);
                showDialog(Language::get('nc_common_save_fail'),'reload','error');
            }
        } else {
            showDialog(Language::get('wrong_argument'),'reload','error');
        }
    }

    public function check_clinicer_name_existOp() {
        $clinicer_name = $_GET['clinicer_name'];
        $result = $this->_is_clinicer_name_exist($clinicer_name);
        if($result) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    private function _is_clinicer_name_exist($clinicer_name) {
        $condition = array();
        $condition['clinicer_name'] = $clinicer_name;
        $model_clinicer = Model('clinicer');
        return $model_clinicer->isclinicerExist($condition);
    }

    public function check_clinicer_memberOp() {
        $member_name = $_GET['member_name'];
        $password = $_GET['password'];
        $result = $this->_check_clinicer_member($member_name, $password);
        if($result) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    private function _check_clinicer_member($member_name, $password) {
        $member_info = $this->_check_member_password($member_name, $password);
        if($member_info && !$this->_is_clinicer_member_exist($member_info['member_id'])) {
            return $member_info;
        } else {
            return false;
        }
    }

    private function _check_member_password($member_name, $password) {
        $condition = array();
        $condition['member_name']	= $member_name;
        $condition['member_passwd']	= md5($password);
        $model_member = Model('member');
        $member_info = $model_member->infoMember($condition);
        return $member_info;
    }

    private function _is_clinicer_member_exist($member_id) {
        $condition = array();
        $condition['member_id'] = $member_id;
        $model_clinicer = Model('clinicer');
        return $model_clinicer->isclinicerExist($condition);
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string 	$menu_key	当前导航的menu_key
     * @return 
     */
    private function profile_menu($menu_key = '') {
        $menu_array = array();
        $menu_array[] = array(
            'menu_key' => 'account_list', 
            'menu_name' => 'account list', 
            'menu_url' => urlclinic('clic_account', 'account_list')
        );
        if($menu_key === 'account_add') {
            $menu_array[] = array(
                'menu_key'=>'account_add', 
                'menu_name' => 'account add', 
                'menu_url' => urlclinic('clic_account', 'account_add')
            );
        }
        if($menu_key === 'account_edit') {
            $menu_array[] = array(
                'menu_key'=>'account_edit', 
                'menu_name' => 'account edit', 
                'menu_url' => urlclinic('clic_account', 'account_edit')
            );
        }

        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
