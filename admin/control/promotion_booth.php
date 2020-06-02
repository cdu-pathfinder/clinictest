<?php
/**
 * 限时折扣管理 
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
class promotion_boothControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        //检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=dashboard&op=welcome',
                    'msg'=>L('close'),
                ),
                array(
                    'url'=>'index.php?act=promotion_bundling&promotion_allow=1',
                    'msg'=>L('open'),
                ),
            );
            showMessage('商品促销功能尚未开启', $url, 'html', 'succ', 1, 6000);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp() {
        //自动开启优惠套装
        if (intval($_GET['promotion_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
        $this->doctors_listOp();
    }
    
    public function doctors_listOp() {
        // 商品分类
        $doctors_class = Model('doctors_class')->getTreeClassList(1);
        Tpl::output('doctors_class', $doctors_class);
        
        $model_booth = Model('p_booth');
        $where = array();
        if (intval($_GET['cate_id']) > 0) {
            $where['gc_id'] = intval($_GET['cate_id']);
        }
        $doctors_list = $model_booth->getBoothdoctorsList($where, 'doctors_id', 10);
        if (!empty($doctors_list)) {
            $doctorsid_array = array();
            foreach ($doctors_list as $val) {
                $doctorsid_array[] = $val['doctors_id'];
            }
            $doctors_list = Model('doctors')->getdoctorsList(array('doctors_id' => array('in', $doctorsid_array)));
        }
        Tpl::output('gc_list', H('doctors_class') ? H('doctors_class') : H('doctors_class', true));
        Tpl::output('doctors_list', $doctors_list);
        Tpl::output('show_page', $model_booth->showpage(2));
        Tpl::showpage('promotion_booth_doctors.list');
    }
    
    /**
     * 套餐列表
     */
    public function booth_quota_listOp() {
        $model_booth = Model('p_booth');
        $where = array();
        if ($_GET['clic_name'] != '') {
            $where['clic_name'] = array('like', '%'.trim($_GET['clic_name']).'%');
        }
        $booth_list = $model_booth->getBoothQuotaList($where, '*', 10);

        // 状态数组
        $state_array = array(0=>L('close') , 1=>L('open'));
        Tpl::output('state_array', $state_array);
        
        Tpl::output('booth_list', $booth_list);
        Tpl::output('show_page', $model_booth->showpage(2));
        Tpl::showpage('promotion_booth_quota.list');
    }
    
    /**
     * 删除推荐商品
     */
    public function del_doctorsOp() {
        $where = array();
        // 验证id是否正确
        if (is_array($_POST['doctors_id'])) {
            foreach ($_POST['doctors_id'] as $val) {
                if (!is_numeric($val)) {
                    showDialog(L('nc_common_del_fail'));
                }
            }
            $where['doctors_id'] = array('in', $_POST['doctors_id']);
        } elseif(intval($_GET['doctors_id']) >= 0) {
            $where['doctors_id'] = intval($_GET['doctors_id']);
        } else {
            showDialog(L('nc_common_del_fail'));
        }
        
        $rs = Model('p_booth')->delBoothdoctors($where);
        if ($rs) {
            showDialog(L('nc_common_del_succ'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_del_fail'));
        }
    }
    
    /**
     * 设置
     */
    public function booth_settingOp() {
        // 实例化模型
        $model_setting = Model('setting');

        if (chksubmit()){
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["promotion_booth_price"], "require"=>"true", 'validator'=>'Number', "message"=>'请填写展位价格'),
                array("input"=>$_POST["promotion_booth_doctors_sum"], "require"=>"true", 'validator'=>'Number', "message"=>'不能为空，且不小于1的整数'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }
        
            $data['promotion_booth_price'] = intval($_POST['promotion_booth_price']);
            $data['promotion_booth_doctors_sum'] = intval($_POST['promotion_booth_doctors_sum']);
        
            $return = $model_setting->updateSetting($data);
            if($return){
                $this->log(L('nc_set').' 推荐展位');
                showMessage(L('nc_common_op_succ'));
            }else{
                showMessage(L('nc_common_op_fail'));
            }
        }
        
        // 查询setting列表
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        Tpl::showpage('promotion_booth.setting');
    }
}
