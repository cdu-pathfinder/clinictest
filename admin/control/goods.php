<?php
/**
 * 商品栏目管理
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
class doctorsControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct() {
        parent::__construct ();
        Language::read('doctors');
    }
    
    /**
     * 商品设置
     */
    public function doctors_setOp() {
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['doctors_verify'] = $_POST['doctors_verify'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,nc_doctors_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,nc_doctors_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
        Tpl::showpage('doctors.setting');
    }
    /**
     * 商品管理
     */
    public function doctorsOp() {
        $model_doctors = Model ( 'doctors' );
        
        /**
         * 查询条件
         */
        $where = array();
        if ($_GET['search_doctors_name'] != '') {
            $where['doctors_name'] = array('like', '%' . trim($_GET['search_doctors_name']) . '%');
        }
        if (intval($_GET['search_commonid']) > 0) {
            $where['doctors_commonid'] = intval($_GET['search_commonid']);
        }
        if ($_GET['search_clic_name'] != '') {
            $where['clic_name'] = array('like', '%' . trim($_GET['search_clic_name']) . '%');
        }
        if (intval($_GET['search_brand_id']) > 0) {
            $where['brand_id'] = intval($_GET['search_brand_id']);
        }
        if (intval($_GET['cate_id']) > 0) {
            $where['gc_id'] = intval($_GET['cate_id']);
        }
        if (in_array($_GET['search_state'], array('0','1','10'))) {
            $where['doctors_state'] = $_GET['search_state'];
        }
        if (in_array($_GET['search_verify'], array('0','1','10'))) {
            $where['doctors_verify'] = $_GET['search_verify'];
        }
        
        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                $doctors_list = $model_doctors->getdoctorsCommonLockUpList($where);
                break;
            // 等待审核
            case 'waitverify':
                $doctors_list = $model_doctors->getdoctorsCommonWaitVerifyList($where, '*', 10, 'doctors_verify desc, doctors_commonid desc');
                break;
            // 全部商品
            default:
                $doctors_list = $model_doctors->getdoctorsCommonList($where);
                break;
        }
        
        Tpl::output('doctors_list', $doctors_list);
        Tpl::output('page', $model_doctors->showpage(2));
        
        $storage_array = $model_doctors->calculateStorage($doctors_list);
        Tpl::output('storage_array', $storage_array);

        $doctors_class = Model('doctors_class')->getTreeClassList ( 1 );
        // 品牌
        $condition = array();
        $condition['brand_apply'] = '1';
        $brand_list = Model('brand')->getBrandList ( $condition );
        
        Tpl::output('search', $_GET);
        Tpl::output('doctors_class', $doctors_class);
        Tpl::output('brand_list', $brand_list);
        
        Tpl::output('state', array('1' => 'release', '0' => 'servicing', '10' => 'Illegal removal'));
        
        Tpl::output('verify', array('1' => 'pass', '0' => 'failed', '10' => 'Waiting for audit'));
        
        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                Tpl::showpage('doctors.close');
                break;
            // 等待审核
            case 'waitverify':
                Tpl::showpage('doctors.verify');
                break;
            // 全部商品
            default:
                Tpl::showpage('doctors.index');
                break;
        }
    }
    
    /**
     * 违规下架
     */
    public function doctors_lockupOp() {
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update = array();
            $update['doctors_stateremark'] = trim($_POST['close_reason']);
            
            $where = array();
            $where['doctors_commonid'] = array('in', $commonid_array);
            
            Model('doctors')->editProducesLockUp($update, $where);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('doctors.close_remark', 'null_layout');
    }
    
    /**
     * 删除商品
     */
    public function doctors_delOp() {
        if (chksubmit()) {
            $commonid_array = $_POST['id'];
            foreach ($commonid_array as $value) {
                if ( !is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            Model('doctors')->deldoctorsAll(array('doctors_commonid' => array('in', $commonid_array)));
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
    }
    
    /**
     * 审核商品
     */
    public function doctors_verifyOp(){
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update2 = array();
            $update2['doctors_verify'] = intval($_POST['verify_state']);
            
            $update1 = array();
            $update1['doctors_verifyremark'] = trim($_POST['verify_reason']);
            $update1 = array_merge($update1, $update2);
            $where = array();
            $where['doctors_commonid'] = array('in', $commonid_array);
            
            Model('doctors')->editProduces($where, $update1, $update2);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('doctors.verify_remark', 'null_layout');
    }
    
    /**
     * ajax获取商品列表
     */
    public function get_doctors_list_ajaxOp() {
        $commonid = $_GET['commonid'];
        if ($commonid <= 0) {
            echo 'false';exit();
        }
        $model_doctors = Model('doctors');
        $doctorscommon_list = $model_doctors->getdoctoreCommonInfo(array('doctors_commonid' => $commonid), 'spec_name');
        if (empty($doctorscommon_list)) {
            echo 'false';exit();
        }
        $doctors_list = $model_doctors->getdoctorsList(array('doctors_commonid' => $commonid), 'doctors_id,doctors_spec,clic_id,doctors_price,doctors_serial,doctors_storage,doctors_image');
        if (empty($doctors_list)) {
            echo 'false';exit();
        }
        
        $spec_name = array_values((array)unserialize($doctorscommon_list['spec_name']));
        foreach ($doctors_list as $key => $val) {
            $doctors_spec = array_values((array)unserialize($val['doctors_spec']));
            $spec_array = array();
            foreach ($doctors_spec as $k => $v) {
                $spec_array[] = '<div class="doctors_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
            $doctors_list[$key]['doctors_image'] = thumb($val, '60');
            $doctors_list[$key]['doctors_spec'] = implode('', $spec_array);
            $doctors_list[$key]['url'] = urlclinic('doctors', 'index', array('doctors_id' => $val['doctors_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($doctors_list);
        }
        echo json_encode($doctors_list);
    }

}
