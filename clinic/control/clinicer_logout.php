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
defined('InclinicNC') or exit('Access Invalid!');

class clinicer_logoutControl extends BaseclinicerControl {

	public function __construct() {
		parent::__construct();
	}

    public function indexOp() {
        $this->logoutOp();
    }

    public function logoutOp() {
        $this->recordclinicerLog('logout successful');
        session_destroy();
        showMessage('logout successful', 'index.php?act=clinicer_login');
    }

}
