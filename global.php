<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 *


* @liam      s328995
 * @since      File available since Release v1.1
 */

error_reporting(E_ALL & ~E_NOTICE);
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));
/**
 * 安装判断
 */
if (!is_file(BASE_ROOT_PATH."/clinic/install/lock") && is_file(BASE_ROOT_PATH."/clinic/install/index.php")){
    if (ProjectName != 'clinic'){
        @header("location: ../clinic/install/index.php");
    }else{
        @header("location: install/index.php");
    }
    exit;
}
define('BASE_CORE_PATH',BASE_ROOT_PATH.'/core');
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define('DS','/');
define('InclinicNC',true);
define('StartTime',microtime(true));
define('TIMESTAMP',time());
define('DIR_clinic','clinic');
define('DIR_CMS','cms');
define('DIR_CIRCLE','circle');
define('DIR_MICROclinic','microclinic');
define('DIR_ADMIN','admin');
define('DIR_API','api');
define('DIR_MOBILE','mobile');
define('DIR_WAP','wap');

define('DIR_RESOURCE','data/resource');
define('DIR_UPLOAD','data/upload');

define('ATTACH_PATH','clinic');
define('ATTACH_COMMON','clinic/common');
define('ATTACH_AVATAR','clinic/avatar');
define('ATTACH_EDITOR','clinic/editor');
define('ATTACH_MEMBERTAG','clinic/membertag');
define('ATTACH_clic','clinic/clic');
define('ATTACH_doctorS','clinic/clic/doctors');
define('ATTACH_LOGIN','clinic/login');
define('ATTACH_ARTICLE','clinic/article');
define('ATTACH_BRAND','clinic/brand');
define('ATTACH_ADV','clinic/adv');
define('ATTACH_ACTIVITY','clinic/activity');
define('ATTACH_WATERMARK','clinic/watermark');
define('ATTACH_POINTPROD','clinic/pointprod');
define('ATTACH_GROUPBUY','clinic/groupbuy');
define('ATTACH_SLIDE','clinic/clic/slide');
define('ATTACH_VOUCHER','clinic/voucher');
define('ATTACH_clic_JOININ','clinic/clic_joinin');
define('ATTACH_REC_POSITION','clinic/rec_position');
define('ATTACH_MOBILE','mobile');
define('ATTACH_CIRCLE','circle');
define('ATTACH_CMS','cms');
define('ATTACH_MALBUM','clinic/member');
define('ATTACH_MICROclinic','microclinic');
define('TPL_clinic_NAME','default');
define('TPL_CIRCLE_NAME', 'default');
define('TPL_MICROclinic_NAME', 'default');
define('TPL_CMS_NAME', 'default');
define('TPL_ADMIN_NAME', 'default');

/*
 * 商家入驻状态定义
 */
//新申请
define('clic_JOIN_STATE_NEW', 10);
//完成付款
define('clic_JOIN_STATE_PAY', 11);
//初审成功
define('clic_JOIN_STATE_VERIFY_SUCCESS', 20);
//初审失败
define('clic_JOIN_STATE_VERIFY_FAIL', 30);
//付款审核失败
define('clic_JOIN_STATE_PAY_FAIL', 31);
//开店成功
define('clic_JOIN_STATE_FINAL', 40);

//默认颜色规格id(前台显示图片的规格)
define('DEFAULT_SPEC_COLOR_ID', 1);


/**
 * 商品图片
 */
define('doctorS_IMAGES_WIDTH', '60,240,360,1280');
define('doctorS_IMAGES_HEIGHT', '60,240,360,12800');
define('doctorS_IMAGES_EXT', '_60,_240,_360,_1280');

/**
 *  订单状态
 */
//已取消
define('appointment_STATE_CANCEL', 0);
//已产生但未支付
define('appointment_STATE_NEW', 10);
//已支付
define('appointment_STATE_PAY', 20);
//已发货
define('appointment_STATE_SEND', 30);
//已收货，交易成功
define('appointment_STATE_SUCCESS', 40);

//订单结束后可评论时间，15天，60*60*24*15
define('appointment_EVALUATE_TIME', 1296000);
