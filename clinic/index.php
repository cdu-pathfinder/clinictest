<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
define('APP_ID','clinic');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/clinicnc.php')) exit('clinicnc.php isn\'t exists!');
define('APP_SITE_URL',clinic_SITE_URL);
define('TPL_NAME',TPL_clinic_NAME);
define('clinic_RESOURCE_SITE_URL',clinic_SITE_URL.DS.'resource');
define('clinic_TEMPLATES_URL',clinic_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

Base::run();
?>
