<?php
/**
 * 广告展示
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
class advControl {
    /**
	 * 
	 * 广告展示
	 */
	public function advshowOp(){
		import('function.adv');
		$ap_id = intval($_GET['ap_id']);
		echo advshow($ap_id,'js');
	}
}