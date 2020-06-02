<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
define('APP_ID','microclinic');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/clinicnc.php')) exit('clinicnc.php isn\'t exists!');

if (!@include(BASE_PATH.'/config/config.ini.php')){
	@header("Location: install/index.php");die;
}

define('APP_SITE_URL',MICROclinic_SITE_URL);
define('MICROclinic_IMG_URL',UPLOAD_SITE_URL.DS.ATTACH_MICROclinic);
define('TPL_NAME',TPL_MICROclinic_NAME);
define('MICROclinic_RESOURCE_SITE_URL',MICROclinic_SITE_URL.'/resource');
define('MICROclinic_TEMPLATES_URL',MICROclinic_SITE_URL.'/templates/'.TPL_NAME);
define('MICROclinic_BASE_TPL_PATH',dirname(__FILE__).'/templates/'.TPL_NAME);
define('MICROclinic_SEO_KEYWORD',$config['seo_keywords']);
define('MICROclinic_SEO_DESCRIPTION',$config['seo_description']);
//微商城框架扩展
require(BASE_PATH.'/framework/function/function.php');
//引入control父类
require(BASE_PATH.'/control/control.php');
Base::run();
