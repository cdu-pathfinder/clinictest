<?php
/**
 * 免运费额度设置
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
class clic_free_freightControl extends BaseclinicerControl {

    public function __construct(){
    	parent::__construct();
    }

    public function indexOp(){
        $model_clic = Model('clic');
        if (chksubmit()) {
            $clic_free_price = floatval(abs($_POST['clic_free_price']));
            $model_clic->editclic(array('clic_free_price'=>$clic_free_price),array('clic_id'=>$_SESSION['clic_id']));
            showDialog(L('nc_common_save_succ'),'reload','succ');
        }
        Tpl::output('clic_free_price',$this->clic_info['clic_free_price']);
        self::profile_menu('free_freight','free_freight');
        Tpl::showpage('clic_free_freight.index');
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array		= array();
        switch ($menu_type) {
        	case 'free_freight':
        		$menu_array = array(
        		 array('menu_key'=>'free_freight',	'menu_name'=>'免运费额度',		'menu_url'=>'index.php?act=clic_free_freight')
        		);
        	break;
        }
    	Tpl::output('member_menu',$menu_array);
    	Tpl::output('menu_key',$menu_key);
    }
}
?>
