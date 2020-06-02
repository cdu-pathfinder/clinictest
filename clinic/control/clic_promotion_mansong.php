<?php
/**
 * 商户中心-满就送 
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
class clic_promotion_mansongControl extends BaseclinicerControl {

    public function __construct() {

        parent::__construct() ;

        Language::read('member_layout,promotion_mansong');

        //检查满就送是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage(Language::get('promotion_unavailable'),'index.php?act=clinicer_center','','error');
        }

    }

    public function indexOp() {
        $this->mansong_listOp();
    }

    /**
     * 发布的满就送活动列表
     **/
    public function mansong_listOp() {

        $model_mansong_quota = Model('p_mansong_quota');
        $model_mansong = Model('p_mansong');

        $current_mansong_quota = $model_mansong_quota->getMansongQuotaCurrent($_SESSION['clic_id']);
        Tpl::output('current_mansong_quota', $current_mansong_quota);

        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['mansong_name'] = array('like', '%'.$_GET['mansong_name'].'%');
        if(!empty($_GET['state'])) {
            $condition['state'] = intval($_GET['state']);
        }
        $mansong_list = $model_mansong->getMansongList($condition, 10, 'state desc, end_time desc');
        Tpl::output('list', $mansong_list);
        Tpl::output('show_page',$model_mansong->showpage());
        Tpl::output('mansong_state_array', $model_mansong->getMansongStateArray());

        self::profile_menu('mansong_list');
        Tpl::showpage('clic_promotion_mansong.list');
    }

    /**
     * 添加满就送活动
     **/
    public function mansong_addOp() {
        $model_mansong_quota = Model('p_mansong_quota');
        $model_mansong = Model('p_mansong');

        //检查当前套餐是否可用
        $current_mansong_quota = $model_mansong_quota->getMansongQuotaCurrent($_SESSION['clic_id']);
        if(empty($current_mansong_quota)) {
            showMessage(Language::get('mansong_quota_current_error'),'','','error');
        }

        $start_time = $model_mansong->getMansongNewStartTime($_SESSION['clic_id']);
        if(empty($start_time)) {
            $start_time = $current_mansong_quota['start_time'];
        }
        $end_time = $current_mansong_quota['end_time'];
        Tpl::output('start_time',$start_time);
        Tpl::output('end_time',$end_time);

        //输出导航
        self::profile_menu('mansong_add');
        Tpl::showpage('clic_promotion_mansong.add');
    }

    /**
     * 保存添加的满就送活动
     **/
    public function mansong_saveOp() {
        $mansong_name = trim($_POST['mansong_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']);

        $model_mansong_quota = Model('p_mansong_quota');
        $model_mansong = Model('p_mansong');
        $model_mansong_rule = Model('p_mansong_rule');

        //检查当前套餐是否可用
        $current_mansong_quota = $model_mansong_quota->getMansongQuotaCurrent($_SESSION['clic_id']);
        if(empty($current_mansong_quota)) {
            showDialog(Language::get('mansong_quota_current_error'),'reload','error');
        }

        //验证输入
        $quota_start_time = intval($current_mansong_quota['start_time']);
        $quota_end_time = intval($current_mansong_quota['end_time']);
        if(empty($mansong_name)) {
            showDialog(Language::get('mansong_name_error'));
        }
        if($start_time >= $end_time) {
            showDialog(Language::get('greater_than_start_time'));
        }

        $start_time_limit = $model_mansong->getMansongNewStartTime($_SESSION['clic_id']);
        if(!empty($start_time_limit) && $start_time_limit > $start_time) {
            $start_time = $start_time_limit;
        }
        if($start_time < $quota_start_time) {
            showDialog(sprintf(Language::get('mansong_add_start_time_explain'),date('Y-m-d',$current_mansong_quota['start_time'])));
        }
        if($end_time > $quota_end_time) {
            showDialog(sprintf(Language::get('mansong_add_end_time_explain'),date('Y-m-d',$current_mansong_quota['end_time'])));
        }

        if(empty($_POST['mansong_rule'])) {
            showDialog('满即送规则不能为空');
        }

        $param = array();
        $param['mansong_name'] = $mansong_name;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['clic_id'] = $current_mansong_quota['clic_id'];
        $param['clic_name'] = $current_mansong_quota['clic_name'];
        $param['member_id'] = $current_mansong_quota['member_id'];
        $param['member_name'] = $current_mansong_quota['member_name'];
        $param['quota_id'] = $current_mansong_quota['quota_id'];
        $param['remark'] = trim($_POST['remark']);
        $mansong_id = $model_mansong->addMansong($param);
        if($mansong_id) {
            $mansong_rule_array = array();
            foreach ($_POST['mansong_rule'] as $value) {
                list($price, $discount, $doctors_id) = explode(',', $value);
                $mansong_rule = array();
                $mansong_rule['mansong_id'] = $mansong_id;
                $mansong_rule['price'] = $price;
                $mansong_rule['discount'] = $discount;
                $mansong_rule['doctors_id'] = $doctors_id;
                $mansong_rule_array[] = $mansong_rule;
            }
            //生成规则
            $result = $model_mansong_rule->addMansongRuleArray($mansong_rule_array);

            $this->recordclinicerLog('添加满即送活动，活动名称：'.$mansong_name);
            
            // 自动发布动态
            // mansong_name,start_time,end_time,clic_id
            $data_array = array();
            $data_array['mansong_name'] = $param['mansong_name'];
            $data_array['start_time']   = $param['start_time'];
            $data_array['end_time']     = $param['end_time'];
            $data_array['clic_id']     = $_SESSION['clic_id'];
            $this->clicAutoShare($data_array, 'mansong');

            showDialog(Language::get('mansong_add_success'), urlclinic('clic_promotion_mansong', 'mansong_list'), 'succ');
        } else {
            showDialog(Language::get('mansong_add_fail'));
        }
    } 

    /**
     * 满就送活动详细信息
     **/
    public function mansong_detailOp() {
        $mansong_id = intval($_GET['mansong_id']);

        $model_mansong = Model('p_mansong');
        $model_mansong_rule = Model('p_mansong_rule');

        $mansong_info = $model_mansong->getMansongInfoByID($mansong_id, $_SESSION['clic_id']);
        if(empty($mansong_info)) {
            showDialog(L('param_error'));
        }
        Tpl::output('mansong_info', $mansong_info);

        $param = array();
        $param['mansong_id'] = $mansong_id;
        $rule_list = $model_mansong_rule->getMansongRuleListByID($mansong_id);
        Tpl::output('list',$rule_list);

        //输出导航
        self::profile_menu('mansong_detail');
        Tpl::showpage('clic_promotion_mansong.detail');
    }

    /**
     * 满就送活动删除
     **/
    public function mansong_delOp() {
        $mansong_id = intval($_POST['mansong_id']);

        $model_mansong = Model('p_mansong');

        $mansong_info = $model_mansong->getMansongInfoByID($mansong_id, $_SESSION['clic_id']);
        if(empty($mansong_info)) {
            showDialog(L('param_error'));
        }

        $condition = array();
        $condition['mansong_id'] = $mansong_id;
        $result = $model_mansong->delMansong($condition);

        if($result) {
            $this->recordclinicerLog('删除满即送活动，活动名称：'.$mansong_rule['mansong_name']);
            showDialog(L('nc_common_op_succ'), urlclinic('clic_promotion_mansong', 'mansong_list'), 'succ');
        } else {
            showDialog(L('nc_common_op_fail'));
        }
    }

    /**
     * 满就送套餐购买
     **/
    public function mansong_quota_addOp() {
        self::profile_menu('mansong_quota_add');
        Tpl::showpage('clic_promotion_mansong_quota.add');
    }

    /**
     * 满就送套餐购买保存
     **/
    public function mansong_quota_add_saveOp() {
        $mansong_quota_quantity = intval($_POST['mansong_quota_quantity']);

        if($mansong_quota_quantity <= 0 || $mansong_quota_quantity > 12) {
            showDialog(Language::get('mansong_quota_quantity_error'));
        }

        //获取当前价格
        $current_price = intval($GLOBALS['setting_config']['promotion_mansong_price']);

        //获取该用户已有套餐
        $model_mansong_quota = Model('p_mansong_quota');
        $current_mansong_quota= $model_mansong_quota->getMansongQuotaCurrent($_SESSION['clic_id']);
        $add_time = 86400 * 30 * $mansong_quota_quantity;
        if(empty($current_mansong_quota)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['clic_id'] = $_SESSION['clic_id'];
            $param['clic_name'] = $_SESSION['clic_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_mansong_quota->addMansongQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_mansong_quota->editMansongQuota($param, array('quota_id' => $current_mansong_quota['quota_id']));
        }

        //记录店铺费用
        $this->recordclicCost($current_price * $mansong_quota_quantity, '购买满即送');

        $this->recordclinicerLog('购买'.$mansong_quota_quantity.'份满即送套餐，单价'.$current_price.$lang['nc_yuan']);

        showDialog(Language::get('mansong_quota_add_success'), urlclinic('clic_promotion_mansong', 'mansong_list'), 'succ');
    }

    /**
     * 选择活动商品
     **/
    public function search_doctorsOp() {
        $model_doctors = Model('doctors');
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['doctors_name'] = array('like', '%'.$_GET['doctors_name'].'%');
        $doctors_list = $model_doctors->getdoctorsOnlineList($condition, '*', 8);

        Tpl::output('doctors_list', $doctors_list);
        Tpl::output('show_page', $model_doctors->showpage());
        Tpl::showpage('clic_promotion_mansong.doctors', 'null_layout');
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
        $menu_array = array(
            1=>array('menu_key'=>'mansong_list','menu_name'=>Language::get('promotion_active_list'),'menu_url'=>urlclinic('clic_promotion_mansong', 'mansong_list')),
        );
        switch ($menu_key){
        	case 'mansong_add':
                $menu_array[] = array('menu_key'=>'mansong_add','menu_name'=>Language::get('promotion_join_active'),'menu_url'=>urlclinic('clic_promotion_mansong', 'mansong_add'));
        		break;  
        	case 'mansong_quota_add':
                $menu_array[] = array('menu_key'=>'mansong_quota_add','menu_name'=>Language::get('promotion_buy_doc'),'menu_url'=>urlclinic('clic_promotion_mansong', 'mansong_quota_add'));
        		break;
        	case 'mansong_detail':
                $menu_array[] = array('menu_key'=>'mansong_detail','menu_name'=>Language::get('mansong_active_content'),'menu_url'=>urlclinic('clic_promotion_mansong', 'mansong_detail', array('mansong_id' => $_GET['mansong_id'])));
        		break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
