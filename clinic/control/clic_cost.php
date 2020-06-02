<?php
/**
 * 商户消费日志
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
class clic_costControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
    }

    public function cost_listOp() {
        $model_clic_cost = Model('clic_cost');
        $condition = array();
        $condition['cost_clic_id'] = $_SESSION['clic_id'];
        if(!empty($_GET['cost_remark'])) {
            $condition['cost_remark'] = array('like', '%'.$_GET['cost_remark'].'%');
        }
        $condition['cost_time'] = array('time', array(strtotime($_GET['add_time_from']), strtotime($_GET['add_time_to'])));
        $cost_list = $model_clic_cost->getclicCostList($condition, 10, 'cost_id desc');
        Tpl::output('cost_list', $cost_list);
        Tpl::output('show_page', $model_clic_cost->showpage(2));	

        $this->profile_menu('cost_list');
        Tpl::showpage('clic_cost.list');
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
            'menu_key' => 'cost_list', 
            'menu_name' => '消费列表', 
            'menu_url' => urlclinic('clic_cost', 'cost_list')
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
