<?php
/**
 * 支付宝返回地址
 *
 * 
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'alipay';
require_once(dirname(__FILE__).'/../../../index.php');
?>