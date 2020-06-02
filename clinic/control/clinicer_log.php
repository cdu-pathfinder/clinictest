<?php
/**
 * 卖家帐号日志
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
class clinicer_logControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
    }

    public function log_listOp() {
        $model_clinicer_log = Model('clinicer_log');
        $condition = array();
        $condition['log_clic_id'] = $_SESSION['clic_id'];
        if(!empty($_GET['clinicer_name'])) {
            $condition['log_clinicer_name'] = array('like', '%'.$_GET['clinicer_name'].'%');
        }
        if(!empty($_GET['log_content'])) {
            $condition['log_content'] = array('like', '%'.$_GET['log_content'].'%');
        }
        $condition['log_time'] = array('time', array(strtotime($_GET['add_time_from']), strtotime($_GET['add_time_to'])));
        $log_list = $model_clinicer_log->getclinicerLogList($condition, 10, 'log_id desc');
        Tpl::output('log_list', $log_list);
        Tpl::output('show_page', $model_clinicer_log->showpage(2));	

        $this->profile_menu('log_list');
        Tpl::showpage('clinicer_log.list');
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
            'menu_key' => 'log_list', 
            'menu_name' => 'log list', 
            'menu_url' => urlclinic('clinicer_log', 'log_list')
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
