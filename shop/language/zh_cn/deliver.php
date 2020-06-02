<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 共有语言
 */

/**
 * 收货人信息
 */
$lang['member_address_receiver_name']	= 'patient name';
$lang['member_address_location']		= 'location';
$lang['member_address_address']			= 'address';
$lang['member_address_zipcode']			= 'zipcode';
$lang['member_address_phone']			= 'phone';
$lang['member_address_mobile']			= 'mobile';
$lang['member_address_edit_address']	= 'edit address';
$lang['member_address_no_address']		= 'You did not add the address';
$lang['member_address_input_name']		= 'Please fill in your real name';
$lang['member_address_please_choose']	= 'please choose';
$lang['member_address_not_repeat']		= 'There is no need to duplicate the area';
$lang['member_address_phone_num']		= 'phone number';
$lang['member_address_area_num']		= 'area number';
$lang['member_address_sub_phone']		= 'sub phone';
$lang['member_address_phone']		    = 'phone';
$lang['member_address_input_receiver']	= 'Please fill in the patient name';
$lang['member_address_choose_location']	= 'Please select the location';
$lang['member_address_input_address']	= 'Please fill in the detailed address';
$lang['member_address_zip_code']		= 'The zip code consists of 4 digits';
$lang['member_address_phone_and_mobile']= 'Please fill in at least one entry for landline and mobile phones.';
$lang['member_address_phone_rule']		= 'Telephone Numbers consist of Numbers, plus, minus, Spaces, and parentheses, and are no less than 6 digits long.';
$lang['member_address_wrong_mobile']	= 'Wrong phone number';

/**
 * 设置发货地址
 */
$lang['store_daddress_wrong_argument']	= 'Incorrect parameter';
$lang['store_daddress_receiver_null']	= 'patient cannot be empty';
$lang['store_daddress_wrong_area']		= 'Incorrect location selection';
$lang['store_daddress_area_null']		= 'Location information cannot be empty';
$lang['store_daddress_address_null']	= 'The full address cannot be empty';
$lang['store_daddress_modify_fail']		= 'Address modification failed';
$lang['store_daddress_add_fail']		= 'New address failed';
$lang['store_daddress_del_fail']		= 'Address deletion failed';
$lang['store_daddress_del_succ']		= 'Delete successfully';
$lang['store_daddress_new_address']		= 'new address';
$lang['store_daddress_deliver_address']	= 'service address';
$lang['store_daddress_default']			= 'default';
$lang['store_daddress_receiver_name']	= 'patient name';
$lang['store_daddress_location']		= 'location';
$lang['store_daddress_address']			= 'address';
$lang['store_daddress_zipcode']			= 'zipcode';
$lang['store_daddress_phone']			= 'phone';
$lang['store_daddress_mobile']			= 'mobile';
$lang['store_daddress_company']			= 'company';
$lang['store_daddress_content']			= 'content';
$lang['store_daddress_edit_address']	= 'edit address';
$lang['store_daddress_no_address']		= 'no service address';
$lang['store_daddress_input_name']		= 'Please fill in your real name';
$lang['store_daddress_please_choose']	= 'please choose';
$lang['store_daddress_not_repeat']		= 'There is no need to duplicate the area';
$lang['store_daddress_phone_num']		= 'phone';
$lang['store_daddress_area_num']		= 'area number';
$lang['store_daddress_sub_phone']		= 'sub phone';
$lang['store_daddress_mobile_num']		= 'mobile num';
$lang['store_daddress_input_receiver']	= 'Please fill in the patient name';
$lang['store_daddress_choose_location']	= 'Please select the location';
$lang['store_daddress_input_address']	= 'Please fill in the detailed address';
$lang['store_daddress_zip_code']		= 'The zip code consists of 4 digits';
$lang['store_daddress_phone']	        = 'phone';
$lang['store_daddress_phone_rule']		= 'Telephone Numbers consist of Numbers, plus, minus, Spaces, and parentheses, and are no less than 6 digits long. ';
$lang['store_daddress_wrong_mobile']	= 'Wrong phone number';

/**
 * 设置物流公司
 */
$lang['store_deliver_express_title']	= '物流公司';

/**
 * 发货
 */
$lang['store_deliver_order_state_send']		= 'comfirmed';
$lang['store_deliver_order_state_receive']	= 'To serve';
// $lang['store_deliver_modfiy_address']		= '修改收货信息';
$lang['store_deliver_select_daddress']		= 'Select address';
$lang['store_deliver_select_ather_daddress']= 'select anther daddress';
$lang['store_deliver_daddress_list']		= 'address list';
$lang['store_deliver_default_express']		= '默认物流公司';
$lang['store_deliver_buyer_name']			= 'booker';
$lang['store_deliver_buyer_address']		= 'address';
$lang['store_deliver_shipping_amount']		= '运费';
$lang['store_deliver_modify_info']			= 'modify info';
$lang['store_deliver_first_step']			= 'first step';
$lang['store_deliver_second_step']			= 'second step';
$lang['store_deliver_third_step']			= 'third step';
$lang['store_deliver_confirm_trade']		= 'Confirm information and transaction details';
$lang['store_deliver_forget']				= 'notes';
$lang['store_deliver_forget_tips']			= 'You can enter some memo information (only visible to the clinic administrator)';
$lang['store_deliver_buyer_adress']			= 'patient info';
$lang['store_deliver_confirm_daddress']		= 'confirm info';
$lang['store_deliver_my_daddress']			= 'my info';
$lang['store_deliver_none_set']				= 'The address has not been set, please go to the Settings > address library to add';
$lang['store_deliver_add_daddress']			= 'Add address';
$lang['store_deliver_express_select']		= '选择物流服务';
$lang['store_deliver_express_note']			= '您可以通过"发货设置-><a href="index.php?act=store_deliver_set&op=express" target="_parent" >默认物流公司</a>"添加或修改常用货运物流。免运或自提商品可切换下方<span class="red">[无需物流运输服务]</span>选项卡并操作。';
$lang['store_deliver_express_zx']			= '自行联系物流公司';
$lang['store_deliver_express_wx']			= '无需物流运输服务';
$lang['store_deliver_company_name']			= '公司名称';
$lang['store_deliver_shipping_code']		= '物流单号';
$lang['store_deliver_bforget']				= '备忘';
$lang['store_deliver_shipping_code_tips']	= '正确填写物流单号，确保快递跟踪查询信息正确';
$lang['store_deliver_no_deliver_tips']		= '如果订单中的商品无需物流运送，您可以直接点击确认';
$lang['store_deliver_shipping_code_pl']		= '请填写物流单号';

/**
 * 选择发货地址
 */
$lang['store_deliver_man']			= 'receptionest';
$lang['store_deliver_daddress']		= 'address';
$lang['store_deliver_telphone']		= 'telphone';

/**
 * 搜索动态物流
 */
$lang['member_show_expre_my_fdback']		= 'my fdback';
$lang['member_show_expre_type']				= 'Way: contact yourself';
$lang['member_show_expre_company']			= '物流公司';
$lang['member_show_receive_info']			= '收货信息';
$lang['member_show_deliver_info']			= '发货信息';