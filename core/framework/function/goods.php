<?php
/**
 * 商品图片统一调用函数
 *
 * 
 *
 * @package    function
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @author	   clinicNC Team
 * @since      File available since Release v1.1
 */

defined('InclinicNC') or exit('Access Invalid!');

/**
 * 取得商品缩略图的完整URL路径，接收商品信息数组，返回所需的商品缩略图的完整URL
 *
 * @param array $doctors 商品信息数组
 * @param string $type 缩略图类型  值为60,160,240,310,1280
 * @return string
 */
function thumb($doctors = array(), $type = ''){
    $type_array = explode(',_', ltrim(doctorS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($doctors)){
        return UPLOAD_SITE_URL.'/'.defaultdoctorsImage($type);
    }
    if (array_key_exists('apic_cover', $doctors)) {
        $doctors['doctors_image'] = $doctors['apic_cover'];
    }
    if (empty($doctors['doctors_image'])) {
        return UPLOAD_SITE_URL.'/'.defaultdoctorsImage($type);
    }
    $search_array = explode(',', doctorS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$doctors['doctors_image']);
    $fname = basename($file);
    //取店铺ID
    if (preg_match('/^(\d+_)/',$fname)){
        $clic_id = substr($fname,0,strpos($fname,'_'));
    }else{
        $clic_id = $doctors['clic_id'];
    }
    $file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
    if (!file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_doctorS.'/'.$clic_id.'/'.$file)){
        return UPLOAD_SITE_URL.'/'.defaultdoctorsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_doctorS;
    return $thumb_host.'/'.$clic_id.'/'.$file;

}
/**
 * 取得商品缩略图的完整URL路径，接收图片名称与店铺ID
 *
 * @param string $file 图片名称
 * @param string $type 缩略图尺寸类型，值为60,160,240,310,1280
 * @param mixed $clic_id 店铺ID 如果传入，则返回图片完整URL,如果为假，返回系统默认图
 * @return string
 */
function cthumb($file, $type = '', $clic_id = false) {
    $type_array = explode(',_', ltrim(doctorS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultdoctorsImage ( $type );
    }
    $search_array = explode(',', doctorS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$file);
    $fname = basename($file);
    // 取店铺ID
    if ($clic_id === false || !is_numeric($clic_id)) {
        $clic_id = substr ( $fname, 0, strpos ( $fname, '_' ) );
    }
    // 本地存储时，增加判断文件是否存在，用默认图代替
    if ( !file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_doctorS . '/' . $clic_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)) )) {
        return UPLOAD_SITE_URL.'/'.defaultdoctorsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_doctorS;
    return $thumb_host . '/' . $clic_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
}

/**
 * 取得团购缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small,mid,max
 * @return string
 */
function gthumb($image_name = '', $type = ''){
	if (!in_array($type, array('small','mid','max'))) $type = 'small';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
	}
    list($base_name, $ext) = explode('.', $image_name);
    list($clic_id) = explode('_', $base_name);
    $file_path = ATTACH_GROUPBUY.DS.$clic_id.DS.$base_name.'_'.$type.'.'.$ext;
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得买家缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为240,1024
 * @return string
 */
function snsThumb($image_name = '', $type = ''){
	if (!in_array($type, array('240','1024'))) $type = '240';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
    }

    list($member_id) = explode('_', $image_name);
    $file_path = ATTACH_MALBUM.DS.$member_id.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得积分商品缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small
 * @return string
 */
function pointprodThumb($image_name = '', $type = ''){
	if (!in_array($type, array('small'))) $type = '';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
    }

    if($type) {
        $file_path = ATTACH_POINTPROD.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    } else {
        $file_path = ATTACH_POINTPROD.DS.$image_name;
    }
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultdoctorsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得品牌图片
 * 
 * @param string $image_name
 * @return string
 */
function brandImage($image_name = '') {
    if ($image_name != '') {
        return UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$image_name;
    }
    return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/default_brand_image.gif';
}

/**
* 取得订单状态文字输出形式
*
* @param array $appointment_info 订单数组
* @return string $appointment_state 描述输出
*/
function appointmentState($appointment_info) {
    switch ($appointment_info['appointment_state']) {
        case appointment_STATE_CANCEL:
            $appointment_state = L('appointment_state_cancel');
        break;
        case appointment_STATE_NEW:
            $appointment_state = L('appointment_state_new');
        break;
        case appointment_STATE_PAY:
            $appointment_state = L('appointment_state_pay');
        break;
        case appointment_STATE_SEND:
            $appointment_state = L('appointment_state_send');
        break;
        case appointment_STATE_SUCCESS:
            $appointment_state = L('appointment_state_success');
        break;
    }
    return $appointment_state;
}

/**
 * 取得订单支付类型文字输出形式
 *
 * @param array $payment_code
 * @return string
 */
function appointmentPaymentName($payment_code) {
    return str_replace(
            array('offline','online','alipay','tenpay','chinabank','predeposit'), 
            array('cash','在线付款','支付宝','财付通','网银在线','预存款'), 
            $payment_code);    
}

/**
 * 取得订单商品销售类型文字输出形式
 *
 * @param array $doctors_type
 * @return string 描述输出
 */
function appointmentdoctorsType($doctors_type) {
    return str_replace(
            array('1','2','3','4','5'),
            array('','团购','限时折扣','优惠套装','赠品'),
            $doctors_type);
}

/**
 * 取得结算文字输出形式
 *
 * @param array $bill_state
 * @return string 描述输出
 */
function billState($bill_state) {
    return str_replace(
            array('1','2','3','4'),
            array('已出账','商家已确认','平台已审核','结算完成'),
            $bill_state);
}
?>
