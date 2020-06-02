<?php
/**
 * 店铺卖家注销
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

class seller_logoutControl extends BaseSellerControl {

	public function __construct() {
		parent::__construct();
	}

    public function indexOp() {
        $this->logoutOp();
    }

    public function logoutOp() {
        $this->recordSellerLog('logout successful');
        session_destroy();
        showMessage('logout successful', 'index.php?act=seller_login');
    }

}
