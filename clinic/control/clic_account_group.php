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
defined('InclinicNC') or exit('Access Invalid!');
class clic_account_groupControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_clic_index');
    }

    public function group_listOp() {
        $model_clinicer_group = Model('clinicer_group');
        $clinicer_group_list = $model_clinicer_group->getclinicerGroupList(array('clic_id' => $_SESSION['clic_id']));
        Tpl::output('clinicer_group_list', $clinicer_group_list);
        $this->profile_menu('group_list');
        Tpl::showpage('clic_account_group.list');
    }

    public function group_addOp() {
        $this->profile_menu('group_add');
        Tpl::showpage('clic_account_group.add');
    }

    public function group_editOp() {
        $group_id = intval($_GET['group_id']);
        if ($group_id <= 0) {
            showMessage('Parameter error', '', '', 'error');
        }
        $model_clinicer_group = Model('clinicer_group');
        $clinicer_group_info = $model_clinicer_group->getclinicerGroupInfo(array('group_id' => $group_id));
        if (empty($clinicer_group_info)) {
            showMessage('Group does not exist', '', '', 'error');
        }
        Tpl::output('group_info', $clinicer_group_info);
        Tpl::output('group_limits', explode(',', $clinicer_group_info['limits']));
        $this->profile_menu('group_edit');
        Tpl::showpage('clic_account_group.add');
    }

    public function group_saveOp() {
        $clinicer_info = array();
        $clinicer_info['group_name'] = $_POST['clinicer_group_name'];
        $clinicer_info['limits'] = implode(',', $_POST['limits']);
        $clinicer_info['clic_id'] = $_SESSION['clic_id'];
        $model_clinicer_group = Model('clinicer_group');
        if (empty($_POST['group_id'])) {
            $result = $model_clinicer_group->addclinicerGroup($clinicer_info);
            $this->recordclinicerLog('Group added successfully, group No.'.$result);
            showDialog('added successfully', urlclinic('clic_account_group', 'group_list'),'succ');
        } else {
            $condition = array();
            $condition['group_id'] = intval($_POST['group_id']);  
            $condition['clic_id'] = $_SESSION['clic_id'];
            $model_clinicer_group->editclinicerGroup($clinicer_info, $condition);
            $this->recordclinicerLog('Group edited successfully, group No.'.$_POST['group_id']);
            showDialog('edited successfully', urlclinic('clic_account_group', 'group_list'),'succ');
        }
    }

    public function group_delOp() {
        $group_id = intval($_POST['group_id']);
        if($group_id > 0) {
            $condition = array();
            $condition['group_id'] = $group_id;
            $condition['clic_id'] = $_SESSION['clic_id'];
            $model_clinicer_group = Model('clinicer_group');
            $result = $model_clinicer_group->delclinicerGroup($condition);
            if($result) {
                $this->recordclinicerLog('Group deleted successfully, group No.'.$group_id);
                showDialog(Language::get('nc_common_op_succ'),'reload','succ');
            } else {
                $this->recordclinicerLog('Group added failed, group No.'.$group_id);
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
            'menu_url' => urlclinic('clic_account_group', 'group_list')
        );
        if($menu_key === 'group_add') {
            $menu_array[] = array(
                'menu_key'=>'group_add', 
                'menu_name' => 'group add', 
                'menu_url' => urlclinic('clic_account_group', 'group_add')
            );
        }
        if($menu_key === 'group_edit') {
            $menu_array[] = array(
                'menu_key'=>'group_edit', 
                'menu_name' => 'group edit', 
                'menu_url' => urlclinic('clic_account_group', 'group_edit')
            );
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
