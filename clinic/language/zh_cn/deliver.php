<?php
defined('InclinicNC') or exit('Access Invalid!');
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
$lang['clic_daddress_wrong_argument']	= 'Incorrect parameter';
$lang['clic_daddress_receiver_null']	= 'patient cannot be empty';
$lang['clic_daddress_wrong_area']		= 'Incorrect location selection';
$lang['clic_daddress_area_null']		= 'Location information cannot be empty';
$lang['clic_daddress_address_null']	= 'The full address cannot be empty';
$lang['clic_daddress_modify_fail']		= 'Address modification failed';
$lang['clic_daddress_add_fail']		= 'New address failed';
$lang['clic_daddress_del_fail']		= 'Address deletion failed';
$lang['clic_daddress_del_succ']		= 'Delete successfully';
$lang['clic_daddress_new_address']		= 'new address';
$lang['clic_daddress_deliver_address']	= 'service address';
$lang['clic_daddress_default']			= 'default';
$lang['clic_daddress_receiver_name']	= 'patient name';
$lang['clic_daddress_location']		= 'location';
$lang['clic_daddress_address']			= 'address';
$lang['clic_daddress_zipcode']			= 'zipcode';
$lang['clic_daddress_phone']			= 'phone';
$lang['clic_daddress_mobile']			= 'mobile';
$lang['clic_daddress_company']			= 'company';
$lang['clic_daddress_content']			= 'content';
$lang['clic_daddress_edit_address']	= 'edit address';
$lang['clic_daddress_no_address']		= 'no service address';
$lang['clic_daddress_input_name']		= 'Please fill in your real name';
$lang['clic_daddress_please_choose']	= 'please choose';
$lang['clic_daddress_not_repeat']		= 'There is no need to duplicate the area';
$lang['clic_daddress_phone_num']		= 'phone';
$lang['clic_daddress_area_num']		= 'area number';
$lang['clic_daddress_sub_phone']		= 'sub phone';
$lang['clic_daddress_mobile_num']		= 'mobile num';
$lang['clic_daddress_input_receiver']	= 'Please fill in the patient name';
$lang['clic_daddress_choose_location']	= 'Please select the location';
$lang['clic_daddress_input_address']	= 'Please fill in the detailed address';
$lang['clic_daddress_zip_code']		= 'The zip code consists of 4 digits';
$lang['clic_daddress_phone']	        = 'phone';
$lang['clic_daddress_phone_rule']		= 'Telephone Numbers consist of Numbers, plus, minus, Spaces, and parentheses, and are no less than 6 digits long. ';
$lang['clic_daddress_wrong_mobile']	= 'Wrong phone number';

/**
 * 设置物流公司
 */
$lang['clic_deliver_express_title']	= '物流公司';

/**
 * 发货
 */
$lang['clic_deliver_appointment_state_send']		= 'comfirmed';
$lang['clic_deliver_appointment_state_receive']	= 'To serve';
// $lang['clic_deliver_modfiy_address']		= '修改收货信息';
$lang['clic_deliver_select_daddress']		= 'Select address';
$lang['clic_deliver_select_ather_daddress']= 'select anther daddress';
$lang['clic_deliver_daddress_list']		= 'address list';
$lang['clic_deliver_default_express']		= '默认物流公司';
$lang['clic_deliver_buyer_name']			= 'booker';
$lang['clic_deliver_buyer_address']		= 'address';
$lang['clic_deliver_shipping_amount']		= '运费';
$lang['clic_deliver_modify_info']			= 'modify info';
$lang['clic_deliver_first_step']			= 'first step';
$lang['clic_deliver_second_step']			= 'second step';
$lang['clic_deliver_third_step']			= 'third step';
$lang['clic_deliver_confirm_trade']		= 'Confirm information and transaction details';
$lang['clic_deliver_forget']				= 'notes';
$lang['clic_deliver_forget_tips']			= 'You can enter some memo information (only visible to the clinic administrator)';
$lang['clic_deliver_buyer_adress']			= 'patient info';
$lang['clic_deliver_confirm_daddress']		= 'confirm info';
$lang['clic_deliver_my_daddress']			= 'my info';
$lang['clic_deliver_none_set']				= 'The address has not been set, please go to the Settings > address library to add';
$lang['clic_deliver_add_daddress']			= 'Add address';
$lang['clic_deliver_express_select']		= '选择物流服务';
$lang['clic_deliver_express_note']			= '您可以通过"发货设置-><a href="index.php?act=clic_deliver_set&op=express" target="_parent" >默认物流公司</a>"添加或修改常用货运物流。免运或自提商品可切换下方<span class="red">[无需物流运输服务]</span>选项卡并操作。';
$lang['clic_deliver_express_zx']			= '自行联系物流公司';
$lang['clic_deliver_express_wx']			= '无需物流运输服务';
$lang['clic_deliver_company_name']			= '公司名称';
$lang['clic_deliver_shipping_code']		= '物流单号';
$lang['clic_deliver_bforget']				= '备忘';
$lang['clic_deliver_shipping_code_tips']	= '正确填写物流单号，确保快递跟踪查询信息正确';
$lang['clic_deliver_no_deliver_tips']		= '如果订单中的商品无需物流运送，您可以直接点击确认';
$lang['clic_deliver_shipping_code_pl']		= '请填写物流单号';

/**
 * 选择发货地址
 */
$lang['clic_deliver_man']			= 'receptionest';
$lang['clic_deliver_daddress']		= 'address';
$lang['clic_deliver_telphone']		= 'telphone';

/**
 * 搜索动态物流
 */
$lang['member_show_expre_my_fdback']		= 'my fdback';
$lang['member_show_expre_type']				= 'Way: contact yourself';
$lang['member_show_expre_company']			= '物流公司';
$lang['member_show_receive_info']			= '收货信息';
$lang['member_show_deliver_info']			= '发货信息';