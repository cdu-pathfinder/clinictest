<?php
/**
 * 关联版式
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
class clic_plateControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
    }
    
    public function indexOp() {
        $this->plate_listOp();
    }
    
    /**
     * 关联版式列表
     */
    public function plate_listOp() {
        // 版式列表
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        if (trim($_GET['p_name']) != '') {
            $where['plate_name'] = array('like', '%'.trim($_GET['p_name']).'%');
        }
        if (in_array($_GET['p_position'], array('0','1'))) {
            $where['plate_position'] = $_GET['p_position'];
        }
        $clic_plate = Model('clic_plate');
        $plate_list = $clic_plate->getPlateList($where, '*', 10);
        Tpl::output('show_page', $clic_plate->showpage(2));
        Tpl::output('plate_list', $plate_list);
        Tpl::output('position', array(0=> '底部', 1 => '顶部'));
        
        $this->profile_menu('plate_list', 'plate_list');
        Tpl::showpage('clic_plate.list');
    }
    
    /**
     * 关联版式添加
     */
    public function plate_addOp() {
        if (chksubmit()) {
            // 验证表单
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input" => $_POST["p_name"], "require" => "true", "message" => '请填写版式名称'),
                    array("input" => $_POST["p_content"], "require" => "true", "message" => '请填写版式内容')
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showDialog(L('error') . $error, urlclinic('clic_plate', 'index'));
            }
            $insert = array();
            $insert['plate_name']     = $_POST['p_name'];
            $insert['plate_position'] = $_POST['p_position'];
            $insert['plate_content']  = $_POST['p_content'];
            $insert['clic_id']       = $_SESSION['clic_id'];
            $result = Model('clic_plate')->addPlate($insert);
            if ($result) {
                showDialog(L('nc_common_op_succ'), urlclinic('clic_plate', 'index'),'succ');
            } else {
                showDialog(L('nc_common_op_fail'), urlclinic('clic_plate', 'index'));
            }
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
        $this->profile_menu('plate_add', 'plate_add');
        Tpl::showpage('clic_plate.add');
    }

    /**
     * 关联版式编辑
     */
    public function plate_editOp() {
        if (chksubmit()) {
            $plate_id = intval($_POST['p_id']);
            if ($plate_id <= 0) {
                showMessage(L('wrong_argument'), '', '', 'error');
            }
            // 验证表单
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input" => $_POST["p_name"], "require" => "true", "message" => '请填写版式名称'),
                    array("input" => $_POST["p_content"], "require" => "true", "message" => '请填写版式内容')
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showDialog(L('error') . $error, urlclinic('clic_plate', 'index'));
            }
            $update = array();
            $update['plate_name']     = $_POST['p_name'];
            $update['plate_position'] = $_POST['p_position'];
            $update['plate_content']  = $_POST['p_content'];
            
            $where = array();
            $where['plate_id']  = $plate_id;
            $where['clic_id']  = $_SESSION['clic_id'];
            $result = Model('clic_plate')->editPlate($update, $where);
            if ($result) {
                showDialog(L('nc_common_op_succ'), urlclinic('clic_plate', 'index'),'succ');
            } else {
                showDialog(L('nc_common_op_fail'), urlclinic('clic_plate', 'index'));
            }
        }
        $plate_id = intval($_GET['p_id']);
        if ($plate_id <= 0) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $plate_info = Model('clic_plate')->getPlateInfo(array('plate_id' => $plate_id, 'clic_id' => $_SESSION['clic_id']));
        Tpl::output('plate_info', $plate_info);
        
        $this->profile_menu('plate_edit', 'plate_edit');
        Tpl::showpage('clic_plate.add');
    }
    
    /**
     * 删除关联版式
     */
    public function drop_plateOp() {
        $plate_id = $_GET['p_id'];
        if (!preg_match('/^[\d,]+$/i', $plate_id)) {
            showdialog(L('wrong_argument'), '', 'error');
        }
        $plateid_array = explode(',', $plate_id);
        $return = Model('clic_plate')->delPlate(array('plate_id' => array('in', $plateid_array), 'clic_id' => $_SESSION['clic_id']));
        if ($return) {
            showDialog(L('nc_common_del_succ'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_del_fail'), '', 'error');
        }
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_type,$menu_key='',$array=array()) {
        $menu_array = array();
        switch ($menu_type) {
            case 'plate_list':
                $menu_array = array(
                    array('menu_key' => 'plate_list', 'menu_name' => '版式列表', 'menu_url' => urlclinic('clic_plate', 'plate_list'))
                );
                break;
            case 'plate_add':
                $menu_array = array(
                    array('menu_key' => 'plate_list', 'menu_name' => '版式列表', 'menu_url' => urlclinic('clic_plate', 'plate_list')),
                    array('menu_key' => 'plate_add', 'menu_name' => '添加版式', 'menu_url' => urlclinic('clic_plate', 'plate_add'))
                );
                break;
            case 'plate_edit':
                $menu_array = array(
                    array('menu_key' => 'plate_list', 'menu_name' => '版式列表', 'menu_url' => urlclinic('clic_plate', 'plate_list')),
                    array('menu_key' => 'plate_add', 'menu_name' => '添加版式', 'menu_url' => urlclinic('clic_plate', 'plate_add')),
                    array('menu_key' => 'plate_edit', 'menu_name' => '编辑版式', 'menu_url' => urlclinic('clic_plate', 'plate_edit'))
                );
                break;
        }
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}