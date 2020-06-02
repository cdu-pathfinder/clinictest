<?php
defined('InclinicNC') or exit('Access Invalid!');
$config = array();
$config['clinic_site_url'] 		= 'http://localhost:8888/clinic/clinic';
$config['cms_site_url'] 		= 'http://localhost:8888/clinic/cms';
$config['microclinic_site_url'] 	= 'http://localhost:8888/clinic/microclinic';
$config['circle_site_url'] 		= 'http://localhost:8888/clinic/circle';
$config['admin_site_url'] 		= 'http://localhost:8888/clinic/admin';
$config['mobile_site_url'] 		= 'http://localhost:8888/clinic/mobile';
$config['wap_site_url'] 		= 'http://localhost:8888/clinic/wap';
$config['upload_site_url']		= 'http://localhost:8888/clinic/data/upload';
$config['resource_site_url']	= 'http://localhost:8888/clinic/data/resource';
$config['version'] 		= '201401162490';
$config['setup_date'] 	= '2020-04-03 15:32:29';
$config['gip'] 			= 0;
$config['dbdriver'] 	= 'mysqli';
$config['tablepre']		= 'clinic_';
$config['db'][1]['dbhost']  	= 'localhost';
$config['db'][1]['dbport']		= '3306';
$config['db'][1]['dbuser']  	= 'root';
$config['db'][1]['dbpwd'] 	 	= '123123';
$config['db'][1]['dbname']  	= 'clinicnc';
$config['db'][1]['dbcharset']   = 'UTF-8';
$config['db']['slave'] 		= array();
$config['session_expire'] 	= 3600;
$config['lang_type'] 		= 'zh_cn';
$config['cookie_pre'] 		= 'B1A7_';
$config['tpl_name'] 		= 'default';
$config['thumb']['cut_type'] = 'gd';
$config['thumb']['impath'] = '';
$config['cache']['type'] 			= 'file';
//$config['memcache']['prefix']      	= 'nc_';
//$config['memcache'][1]['port']     	= 11211;
//$config['memcache'][1]['host']     	= '127.0.0.1';
//$config['memcache'][1]['pconnect'] 	= 0;
//$config['redis']['prefix']      	= 'nc_';
//$config['redis']['master']['port']     	= 6379;
//$config['redis']['master']['host']     	= '127.0.0.1';
//$config['redis']['master']['pconnect'] 	= 0;
//$config['redis']['slave']      	    = array();
//$config['fullindexer']['open']      = false;
//$config['fullindexer']['appname']   = 'clinicnc';
$config['debug'] 			= false;
$config['default_clic_id'] = '1';
// 是否开启伪静态
$config['url_model'] = false;
// 二级域名后缀
$config['subdomain_suffix'] = '';