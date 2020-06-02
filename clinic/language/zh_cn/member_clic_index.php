<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * 共有语言
 */
$lang['clic_clicinfo_error']			= 'Clinic Informaion Error';
/**
 * 开店
 */
$lang['clic_create_right_closed']		= 'You have already created the clinic and cannot create it again';
$lang['clic_create_created']			= 'You have already created the clinic and cannot create it again';
$lang['clic_create_grade_not_exists']	= '错误的操作，该等级不存在';
$lang['clic_create_choose_clic_class']	= 'Select Clinic Type';
$lang['clic_create_input_owner_info']	= 'Fill in the owner and clinic information';
$lang['clic_create_finish']			= 'Finish';
$lang['clic_create_doctors_num']			= 'doctor No.';
$lang['clic_create_upload_space']		= 'Upload space';
$lang['clic_create_template_num']		= '模板数';
$lang['clic_create_charge_standard']	= 'Charge standard';
$lang['clic_create_need_audit']		= 'Need review';
$lang['clic_create_yes']				= 'yes';
$lang['clic_create_no']				= 'no';
$lang['clic_create_additional_function']	= '附加功能';
$lang['clic_create_create_now']		= 'Open clinic';
$lang['clic_create_back'] 				= 'back';
$lang['clic_create_input_clic_name']	= 'Please enter the clinic name';
$lang['clic_create_clic_name_exists']	= 'This clinic name already exists, please change';
$lang['clic_create_input_phone']		= 'Please enter a contact number';
$lang['clic_create_input_clic_card']	= 'Incorrect id';
$lang['clic_create_input_zip_code_is_number']	= 'The zip code must be numeric';
$lang['clic_create_input_zip_code']	= 'The postcode format is incorrect';
$lang['clic_create_phone_rule']		= 'The phone number must be no less than 6 digits';
$lang['clic_create_choose_clic_class']	= 'Please select the clinic category';
$lang['clic_create_choose_area']		= 'Please select district';
$lang['clic_create_upload_type']		= 'Please upload the format as jpg,jpeg,png,gif';
$lang['clic_create_read_agreement']	= 'Please agree to the clinic opening agreement';
$lang['clic_create_card_no']			= 'Id number';
$lang['clic_create_input_true_card']	= 'Please fill in the true and accurate id number';
$lang['clic_create_clic_name']		= 'clinic name';
$lang['clic_create_name_rule']			= 'Please limit it to 20 characters';
$lang['clic_create_clic_class']		= 'clinic class';
$lang['clic_create_please_choose']		= 'please choose';
$lang['clic_create_location']			= 'location';
$lang['clic_create_address']			= 'Detailed address';
$lang['clic_create_zipcode']			= 'The zip code';
$lang['clic_create_phone']				= 'Contact phone number';
$lang['clic_create_input_phone']		= 'Please enter a contact number';
$lang['clic_create_upload_paper']		= 'Upload id card';
$lang['clic_create_true_name_intro']	= 'What is real name authentication';
$lang['clic_create_upload_rule']		= 'Supported formats:jpg,jpeg,png,gif，Make sure the image is clear and the file size does not exceed 400KB';
$lang['clic_create_upload_licence']	= 'Upload the license';
$lang['clic_create_true_clic_intro']	= 'What is physical clinic certification';
$lang['clic_create_read_agreement1']	= 'I have read it carefully and agree';
$lang['clic_create_clic_agreement']	= 'Open agreement';
$lang['clic_create_read_agreement2']	= 'of the conditions';
$lang['clic_create_clic_editor_multimedia']	= 'Editor multimedia features';
$lang['clic_create_clic_groupbuy']	= '团购活动';
$lang['clic_create_clic_null']	    = 'null';
/**
 * 保存店铺
 */
$lang['clic_save_owner_null']			= 'The owner name cannot be empty';
$lang['clic_save_clic_name_null']		= 'The clinic name cannot be empty';
$lang['clic_save_clic_class_null']	= 'The clinic category cannot be empty';
$lang['clic_save_area_null']			= 'The area cannot be empty';
$lang['clic_save_create_success']		= 'Congratulations, your clinic has been successfully established.';
$lang['clic_save_waiting_for_review']	= 'Wait for administrator review.';
$lang['clic_save_create_fail']			= 'Failed to create clinic';
$lang['clic_save_defaultalbumclass_name']	= 'default photo';
/**
 * 卖家商品分类
 */
$lang['clic_doctors_class_csc_null']		= 'The imported CSV file cannot be empty';
$lang['clic_doctors_class_new_class']	= 'new classification';
$lang['clic_doctors_class_import']		= 'import';
$lang['clic_doctors_class_export']		= 'export';
$lang['clic_doctors_class_ensure_del']	= 'Are you sure you want to delete the category';
$lang['clic_doctors_class_name']			= 'Category name';
$lang['clic_doctors_class_sort']			= 'sort';
$lang['clic_doctors_class_add_sub']		= 'New subordinate';
$lang['clic_doctors_class_no_record']	= 'No clinic classification';
$lang['clic_doctors_class_name_null']	= 'The category name cannot be empty';
$lang['clic_doctors_class_input_int']	= 'need to enter a number';
$lang['clic_doctors_class_edit_class']	= 'edit class';
$lang['clic_doctors_class_add_class']	= 'add class';
$lang['clic_doctors_class_sup_class']	= 'sup class';
$lang['clic_doctors_class_display_state']	= 'display state';
$lang['clic_doctors_class_submit']		= 'submit';
$lang['clic_doctors_class_problem']		= 'Export the data of your clinic classification';
$lang['clic_doctors_class_choose_file']	= 'Please select file';
$lang['clic_doctors_class_choose_code']	= 'Please select the file encoding';
$lang['clic_doctors_class_trans_tip']	= 'If the file is large, convert the file to utf-8';
$lang['clic_doctors_class_file_format']	= 'The file format';
$lang['clic_doctors_class_csv_file']		= 'csv file';
$lang['clic_doctors_class_csv_download']	= 'CSV sample';
$lang['clic_doctors_class_download']		= 'Click download';
$lang['clic_doctors_class_wrong']		= 'Incorrect operation, no such classification';
$lang['clic_doctors_class_modify_fail']	= 'clinic class modification failed';
$lang['clic_doctors_class_add_fail']		= 'Failed to add clinic category';
$lang['clic_doctors_class_no_csv']		= 'Please select the CSV file';
/**
 * 订单
 */

$lang['clic_appointment_appointment_sn']		= 'appointment no.';
$lang['clic_appointment_appointment_sn_search']= 'Enter the appointment number to query';
$lang['clic_appointment_comp_exp']		= '快递公司';
$lang['clic_appointment_doctors_detail']		= 'doctor detail';
$lang['clic_appointment_doctors_single_price']	= 'price';
$lang['clic_appointment_sell_back']			= '售后';
$lang['clic_appointment_appointment_stateop']		= 'state';
$lang['clic_appointment_appointment_confirm']		= 'appointment confirm';
$lang['clic_appointment_confirm_appointment']		= 'confirm appointment';
$lang['clic_appointment_shipping_appointment']		= '确认货到付款订单';
$lang['clic_appointment_add_time']		= 'appointment time';
$lang['clic_appointment_buyer']			= 'patient';
$lang['clic_appointment_search']			= 'search';
$lang['clic_appointment_cancel_appointment']	= 'cancel';
$lang['clic_appointment_show_deliver']	= '查看物流';
$lang['clic_appointment_buyer_info']		= 'contact info';
$lang['clic_appointment_receiver']		= 'name';
$lang['clic_appointment_phone']			= 'phone';
$lang['clic_appointment_mobile']			= 'mobile';
$lang['clic_appointment_email']			= 'email';
$lang['clic_appointment_area']			= 'city';
$lang['clic_appointment_address']		= '收货地址';
$lang['clic_appointment_zip_code']		= '邮政编码';
$lang['clic_appointment_pay_method']		= '支付方式';
$lang['clic_appointment_sum']			= '订单总价';
$lang['clic_appointment_state']			= '订单状态';
$lang['clic_appointment_group']			= '团购';
$lang['clic_appointment_evaluated']		= '已评价';
$lang['clic_appointment_received_price']	= '收到货款';
$lang['clic_appointment_modify_price']	= '调整费用';
$lang['clic_appointment_modify_price_gpriceerror']	= 'The total price cannot be empty and must be a number';
$lang['clic_appointment_send']			= '设置发货';
$lang['clic_appointment_refund']			= 'refund';
$lang['clic_buyer_confirm']		= 'confirm';
$lang['clic_appointment_return']			= 'reback';
$lang['clic_appointment_modify_no']		= 'modify No.';
$lang['clic_appointment_view_appointment']		= 'view appointment';
$lang['clic_appointment_complain']	= 'complain';
$lang['clic_appointment_no_result']		= 'There is no qualified appointment';
$lang['clic_appointment_ensure_cancel']	= 'you want to cancel the appointment?';
$lang['clic_appointment_cancel_reason']	= '取消缘由';
$lang['clic_appointment_lose_doctors']		= '无法备齐货物';
$lang['clic_appointment_invalid_appointment']	= '不是有效的订单';
$lang['clic_appointment_buy_apply']		= '买家主动要求';
$lang['clic_appointment_other_reason']	= 'other reason';
$lang['clic_appointment_buyer_with']		= 'patient';
$lang['clic_appointment_sn']				= 'No.';
$lang['clic_appointment_modify_rule']	= '输入要修改的金额，只能为数字';
$lang['clic_appointment_ensure_receive_fee']	= '您确定已经收到货款了吗';
$lang['clic_appointment_handle_desc']		= '操作备注';
$lang['clic_appointment_shipping_no_null']	= '物流单号不能为空';
$lang['clic_appointment_input_shipping_no']	= '请输入您的物流单号';
$lang['clic_appointment_shipping_no']		= '物流单号';
$lang['clic_appointment_want_evaluate']	= '我要评价';
$lang['clic_show_appointment_detail']		= '订单详情';
$lang['clic_show_appointment_info']			= '订单信息';
$lang['clic_show_appointment_seller_info']	= '卖家信息';
$lang['clic_show_appointment_clic_name']	= 'clinic name';
$lang['clic_show_appointment_wangwang']		= '旺旺';
$lang['clic_show_appointment_doctors_name']	= 'doctor name';
$lang['clic_show_appointment_amount']		= 'amount';
$lang['clic_show_appointment_price']			= 'price';
$lang['clic_show_appointment_tp_fee']		= '运费';
$lang['clic_show_appointment_pay_message']	= 'pay message';
$lang['clic_show_appointment_pay_time']		= '付款时间';
$lang['clic_show_appointment_send_time']		= '发货时间';
$lang['clic_show_appointment_finish_time']	= 'finish time';
$lang['clic_show_appointment_shipping_info']	= '物流信息';
$lang['clic_show_appointment_receiver']		= '收 货 人';
$lang['clic_show_appointment_receiver_address']	= '收货地址';
$lang['clic_show_appointment_mobile']			= '手机号码';
$lang['clic_show_appointment_buyer_message']		= '买家留言';
$lang['clic_show_appointment_handle_history']	= '操作历史';
$lang['clic_show_system']				= 'system';
$lang['clic_show_appointment_at']				= '于';
$lang['clic_show_appointment_cur_state']			= 'Current appointment state';
$lang['clic_show_appointment_next_state']		= 'next state';
$lang['clic_show_appointment_reason']			= 'reason';
$lang['clic_show_appointment_printappointment']		= '打印发货单';
$lang['clic_show_appointment_shipping_han']		= '含';
$lang['clic_appointment_tip1']		= '平台收款，确认收款由系统自动或管理员手动完成，卖家不能进行收款操作，管理员可以取消未付款的线下支付订单';
$lang['clic_appointment_cancel_success']	= 'cancelled successful';
$lang['clic_appointment_edit_ship_success']	= '成功修改了运费';
$lang['clic_appointment_none_exist']	= 'appointment not exist';
/**
 * 支付
 */
$lang['clic_payment_name']				= 'name';
$lang['clic_payment_intro']			= 'Plugin instructions';
$lang['clic_payment_enable']			= 'enable';
$lang['clic_payment_yes']				= 'yes';
$lang['clic_payment_no']				= 'no';
$lang['clic_payment_config']			= 'configue plugin';
$lang['clic_payment_ensure_uninstall']	= '您确实要卸载该插件吗';
$lang['clic_payment_uninstall']		= 'uninstall';
$lang['clic_payment_install']			= 'install';
$lang['clic_payment_not_exists']		= 'The payment interface does not exist in the system';
$lang['clic_payment_add']				= 'Configure payment method';
$lang['clic_payment_info']				= 'Prompt information';
$lang['clic_payment_display']			= 'Prompt message when the user pays';
$lang['clic_payment_uninstall_fail']	= 'uninstall failed';
$lang['clic_payment_edit_not_null']	= 'not null';
/**
 * 广告管理
 */
$lang['clic_adv_buy']			= '购买广告';
/**
 * 导航
 */
$lang['clic_navigation_name_null']		= 'The navigation name cannot be empty';
$lang['clic_navigation_name_max']		= 'Navigation name up to 10 words';
$lang['clic_navigation_del_fail']		= 'Delete navigation failed';
$lang['clic_navigation_new']			= 'The new navigation';
$lang['clic_navigation_edit']			= 'Edit navigation';
$lang['clic_navigation_name']			= 'Navigation name';
$lang['clic_navigation_display']		= 'Whether to display';
$lang['clic_navigation_content']		= 'content';
$lang['clic_navigation_no_result']		= 'There is no navigation that meets the criteria';
$lang['clic_navigation_url']		    = 'Navigation links URL';
$lang['clic_navigation_url_tip']		= '请填写包含http://的完整URL地址,如果填写此项则点击该导航会跳转到外链';
$lang['clic_navigation_new_open']		= '新窗口打开';
$lang['clic_navigation_new_open_yes']	= 'yes';
$lang['clic_navigation_new_open_no']	= 'no';

/**
 * 合作伙伴
 */
$lang['clic_partner_title_null']	= 'The title cannot be empty';
$lang['clic_partner_wrong_href']	= 'Incorrect link format';
$lang['clic_partner_add_fail']		= 'New partner failed';
$lang['clic_partner_del_fail']		= 'Failed to delete partner';
$lang['clic_partner_add']			= 'New partners';
$lang['clic_partner_edit']			= 'Edit content';
$lang['clic_partner_title']		= 'title';
$lang['clic_partner_href']			= 'link';
$lang['clic_partner_href_tip']		= 'The number should be greater than zero, the smaller the more forward';
$lang['clic_partner_sign']			= '标识';
$lang['clic_partner_pic_upload']	= 'upload image';
$lang['clic_partner_href_null']	= 'The link cannot be empty';
$lang['clic_partner_no_result']	= 'No qualified partner';
$lang['clic_partner_des_one']		= '填写链接地址，您可以在';
$lang['clic_partner_des_two']		= '中复制链接。';
/**
 * 店铺设置
 */
$lang['clic_setting_name_null']			= '店铺名称不能为空';
$lang['clic_setting_wrong_uri']			= '二级域名长度不符合要求';
$lang['clic_setting_exists_uri']			= '该二级域名已存在,请更换其它域名';
$lang['clic_setting_invalid_uri']			= '该二级域名为系统禁止域名,请更换其它域名';
$lang['clic_setting_lack_uri']				= '该二级域名不符合域名命名规范,请不要使用特殊字符';
$lang['clic_create_clic_name_hint']		= '店铺名称请控制长度不超过20字';
$lang['clic_create_clic_zy_hint']			= 'he keyword (Tag) helps you find your clinic when searching for it<br/>Keywords can be entered up to 50 words, please use "," to separate';

$lang['clic_setting_change_label']			= 'cliniclogo';
$lang['clic_setting_label_tip']			= 'Here is your clinic logo, which will be displayed in the clinic logo bar;<br/><span style="color:orange;">It is recommended to use a transparent GIF or PNG image between 200 pixels wide and 60 pixels high. Click the "submit" button below to take effect.</span>';
$lang['clic_setting_change_sign']			= 'clinic标志';
$lang['clic_setting_sign_tip']				= 'Here is your clinic logo, which will be displayed in the clinic information bar;<br/><span style="color:orange;">It is recommended to use a square image with a width of 100 pixels * a height of 100 pixels; Click the "submit" button below to take effect.</span>';
$lang['clic_setting_change_banner']		= 'clinic banner';
$lang['clic_setting_banner_tip']			= 'Here is your clinic banner, which will be displayed in the banner position above the clinic navigation；<br/><span style="color:orange;">It is recommended to use images 1000 pixels wide by 250 pixels high; Click the "submit" button below to take effect.</span>';
$lang['clic_setting_uri']					= '二级域名';
$lang['clic_setting_uri_tip']				= '可留空，域名长度应为';
$lang['clic_setting_domain_times']			= '已修改次数为';
$lang['clic_setting_domain_times_max']		= '最多可修改次数为';
$lang['clic_setting_domain_notice']		= '注意！设置后将不能修改';
$lang['clic_setting_domain_tip']			= '不可修改';
$lang['clic_setting_domain_valid']			= 'Letters, Numbers, underscores, and underscores are valid characters';
$lang['clic_setting_domain_rangelength']   = '二级域名长度为 {0} 到 {1} 个字符之间';
$lang['clic_setting_my_homepage']			= 'my homepage';
$lang['clic_setting_grade']				= 'clinic level';
$lang['clic_setting_upgrade']				= '马上升级店铺等级';
$lang['clic_setting_location_tip']			= '不必重复填写所在地区';
$lang['clic_setting_contact']				= 'contact';
$lang['clic_setting_wangwang']				= 'wangwang';
$lang['clic_setting_intro']				= 'clinic intro';
$lang['clic_setting_customer_service']		= 'customer service';
$lang['clic_setting_username']				= 'username';
$lang['clic_setting_password']				= 'password';
$lang['clic_setting_checking']				= 'checking...';
$lang['clic_setting_apply']				= 'apply';
$lang['clic_setting_applying']				= 'applying...';
$lang['clic_setting_apply_success']		= '在线客服申请成功,请等待管理员审核开通';
$lang['clic_setting_apply_error']			= '网络忙,在线客服申请失败,请稍后再试';
$lang['clic_setting_seo_keywords']			= 'keyword';
$lang['clic_setting_clic_zy']				= 'The main diagnostic';
$lang['clic_setting_seo_description']		= 'clinic description';
$lang['clic_setting_seo_keywords_help']	= 'please use English comma separated keywords';
$lang['clic_setting_seo_description_help']	= 'suggested within 120 words';
$lang['clic_settine_browse']				= 'browse...';
$lang['clic_setting_clic_url']			= '当前店铺首页连接：';
/**
 * 升级店铺
 */
$lang['clic_upgrade_submit']		= 'The application for clinic level has been submitted to the administrator, please wait for your review';
$lang['clic_upgrade_submit_fail']	= 'The clinic level submission failed, please operate again';
$lang['clic_upgrade_cur_grade']	= 'Current level of clinic';
//$lang['clic_upgrade_tip']			= '如果店铺等级需要审核，升级后在待审核这段期间，店铺部分功能不能正常使用，您确定要升级吗?';
$lang['clic_upgrade_tip']			= 'Are you sure you want to upgrade?';
$lang['clic_upgrade_now']			= 'upgrade now';
$lang['clic_upgrade_clic_error']			= 'clinic information error';
$lang['clic_upgrade_gradesort_error']		= '等级错误,升级级别应高于当前级别';
$lang['clic_upgrade_exist_error']			= '店铺等级升级申请已经提交，正在审核中，请耐心等待';
$lang['clic_upgrade_exist_tip_1']			= 'clinic level upgraded to';
$lang['clic_upgrade_exist_tip_2']			= '的申请，正在审核中...';
/**
 * 主题
 */
$lang['clic_theme_load_preview_fail']	= 'Load preview failed';
$lang['clic_theme_effect_preview']		= 'preview';
$lang['clic_theme_loading1']			= 'loading';
$lang['clic_theme_use']				= 'use';
$lang['clic_theme_loading2']			= 'loading';
$lang['clic_theme_congfig_success']	= 'congfig success';
$lang['clic_theme_error']				= 'error';
$lang['clic_theme_homepage']			= 'homepage';
$lang['clic_theme_tpl_name']			= 'tpl name';
$lang['clic_theme_style_name']			= 'style name';
$lang['clic_theme_valid']				= 'Available themes';
$lang['clic_theme_tpl_name1']			= 'tpl name';
$lang['clic_theme_style_name1']		= 'style name';
$lang['clic_theme_preview']			= 'preview';
$lang['clic_theme_congfig_fail']		= 'congfig fail';
/**
 * 活动
 */
$lang['clic_activity_year']		= 'year';
$lang['clic_activity_month']		= 'month';
$lang['clic_activity_day']			= 'day';
$lang['clic_activity_theme']		= 'theme';
$lang['clic_activity_intro']		= 'activity intro';
$lang['clic_activity_start_time']	= 'start time';
$lang['clic_activity_end_time']	= '结束时间';
$lang['clic_activity_long_time']	= '长期活动';
$lang['clic_activity_type']		= '活动类型';
$lang['clic_activity_doctors']		= '商品';
$lang['clic_activity_group']		= '团购';
$lang['clic_activity_join']		= '参与活动';
$lang['clic_activity_no_record']	= '没有符合条件的活动';
$lang['clic_activity_doctors_name']	= '商品名称';
$lang['clic_activity_doctors_class']	= '商品类别';
$lang['clic_activity_doctors_brand']	= '商品品牌';
$lang['clic_activity_pass']		= '已通过';
$lang['clic_activity_audit']		= '审核中';
$lang['clic_activity_refuse']		= '未通过';
$lang['clic_activity_join_tip']	= '您尚未参与本活动,可以在本页下方进行选择';
$lang['clic_activity_group_name']	= '团购名称';
$lang['clic_activity_group_intro']	= '团购介绍';
$lang['clic_activity_class']		= '类别';
$lang['clic_activity_choose']		= '请选择';
$lang['clic_activity_brand']		= '品牌';
$lang['clic_activity_name']		= '名称';
$lang['clic_activity_search']		= '查找';
$lang['clic_activity_doctors_applied']	= '您的商品已经全部申请完毕';
$lang['clic_activity_none_doctors']		= '您尚未发布任何商品';
$lang['clic_activity_group_applied']	= '您的团购已经全部申请完毕';
$lang['clic_activity_none_group']		= '您尚未发布任何团购';
$lang['clic_activity_join_now']		= '选择完毕,参与活动';
$lang['clic_activity_choose_doctors']	= '请手动选择内容后再保存';
$lang['clic_activity_not_exists']		= '该活动并不存在';
$lang['clic_activity_unknown_type']	= '该活动类型不明';
$lang['clic_activity_id_is']			= '编号为';
$lang['clic_activity_doctors_not_exists']	= '的商品并不存在';
$lang['clic_activity_group_not_exists']	= '的团购并不存在';
$lang['clic_activity_submitted']			= '参与申请已提交';
$lang['clic_activity_info_title']			= '活动信息';
$lang['clic_activity_doctors_tip']			= '活动商品如下';
$lang['clic_activity_confirmstatus']		= '审核状态';
$lang['clic_activity_choosedoctors']		= 'choose doctor';
/**
 * ajax修改商品分类
 */
$lang['clic_doctors_class_ajax_update_fail']	= '更新数据库失败';
/**
 * 水印管理
 */
$lang['clic_watermark_pic']		= 'watermark pic：';
$lang['clic_watermark_del']		= 'delete';
$lang['clic_watermark_del_pic']		= 'delete watermark';
$lang['clic_watermark_choose_pic']		= 'choose watermark';
$lang['clic_watermark_pic_quality']		= 'Image quality：';
$lang['clic_watermark_pic_pos']		= 'Image location:';
$lang['clic_watermark_choose_pos']		= 'Select the watermark image location';
$lang['clic_watermark_pic_pos1']		= 'Upper left';
$lang['clic_watermark_pic_pos2']		= '正上';
$lang['clic_watermark_pic_pos3']		= '右上';
$lang['clic_watermark_pic_pos4']		= '左中';
$lang['clic_watermark_pic_pos5']		= '中间';
$lang['clic_watermark_pic_pos6']		= '右中';
$lang['clic_watermark_pic_pos7']		= '左下';
$lang['clic_watermark_pic_pos8']		= '中下';
$lang['clic_watermark_pic_pos9']		= '右下';
$lang['clic_watermark_transition']		= '融合度：';
$lang['clic_watermark_transition_notice']		= '水印图片与原图片的融合度';
$lang['clic_watermark_text']		= '水印文字：';
$lang['clic_watermark_text_notice']		= '水印文字';
$lang['clic_watermark_text_size']		= '文字大小：';
$lang['clic_watermark_text_size_notice']		= '设置水印文字大小';
$lang['clic_watermark_text_angle']		= '文字角度：';
$lang['clic_watermark_text_angle_notice']		= '水印文字角度,尽量不要更改';
$lang['clic_watermark_text_pos']		= '文字位置：';
$lang['clic_watermark_text_pos_notice']		= '选择水印文字放置位置';
$lang['clic_watermark_text_pos1']		= '左上';
$lang['clic_watermark_text_pos2']		= '正上';
$lang['clic_watermark_text_pos3']		= '右上';
$lang['clic_watermark_text_pos4']		= '左中';
$lang['clic_watermark_text_pos5']		= '中间';
$lang['clic_watermark_text_pos6']		= '右中';
$lang['clic_watermark_text_pos7']		= '左下';
$lang['clic_watermark_text_pos8']		= '中下';
$lang['clic_watermark_text_pos9']		= '右下';
$lang['clic_watermark_text_font']		= '文字字体：';
$lang['clic_watermark_text_font_notice']		= '水印文字的字体';
$lang['clic_watermark_text_color']		= '文字颜色：';
$lang['clic_watermark_text_color_notice']		= '水印字体的颜色值';
$lang['clic_watermark_is_open']		= '是否开启：';
$lang['clic_watermark_is_open_notice']		= '是否开启水印';
$lang['clic_watermark_is_open1']		= '开启';
$lang['clic_watermark_is_open0']		= '关闭';
$lang['clic_watermark_submit']		= '提交';
$lang['clic_watermark_del_pic_confirm']		= '确定删除水印图片?';
$lang['clic_watermark_pic_quality_null']		= '水印图片质量不能为空';
$lang['clic_watermark_pic_quality_number']		= '水印图片质量必须为数字';
$lang['clic_watermark_pic_quality_min']		= '水印图片质量在 0-100 之间';
$lang['clic_watermark_pic_quality_max']		= '水印图片质量在 0-100 之间';
$lang['clic_watermark_transition_null']		= '水印图片融合度不能为空';
$lang['clic_watermark_transition_number']		= '水印图片融合度必须为数字';
$lang['clic_watermark_transition_min']		= '水印图片融合度在 0-100 之间';
$lang['clic_watermark_transition_max']		= '水印图片融合度在 0-100 之间';
$lang['clic_watermark_text_size_null']		= '水印文字大小不能为空';
$lang['clic_watermark_text_size_number']		= '水印文字大小必须为数字';
$lang['clic_watermark_text_color_null']		= '水印字体颜色不能为空';
$lang['clic_watermark_text_color_max']		= '字体颜色值格式不正确';
$lang['clic_watermark_congfig_success']		= '设置成功';
$lang['clic_watermark_congfig_fail']		= '设置失败';
$lang['clic_watermark_congfig_notice']		= '如果开启水印,必须设置水印图片或者水印文字';
$lang['clic_watermark_browse']				= '浏览...';
/**
 * 优惠券管理
 */
$lang['clic_coupon_name']		= '优惠券名称';
$lang['clic_coupon_period']	= '有效期：';
$lang['clic_coupon_add']		= '新增优惠券';
$lang['clic_coupon_pic']		= '优惠券图片';
$lang['clic_coupon_price']		= '优惠金额';
$lang['clic_coupon_lifetime']	= '使用期限';
$lang['clic_coupon_state']		= '上架';
$lang['clic_coupon_no_result']		= '没有符合条件的记录';
$lang['clic_coupon_null_class']		= '总后台管理员新增优惠券分类后方可添加优惠券';
$lang['clic_coupon_name_null']		= '优惠券名称不能为空';
$lang['clic_coupon_price_error']		= '优惠金额错误';
$lang['clic_coupon_price_min']		= '最小金额为1';
$lang['clic_coupon_start_time_null']		= '优惠券开始日期不能为空';
$lang['clic_coupon_end_time_null']		= '优惠券结束日期不能为空';
$lang['clic_coupon_update_success']		= '更新优惠券成功';
$lang['clic_coupon_update_fail']		= '更新优惠券失败';
$lang['clic_coupon_add_success']		= '增加优惠券成功';
$lang['clic_coupon_add_fail']		= '增加优惠券失败';
$lang['clic_coupon_del_success']		= '删除成功';
$lang['clic_coupon_del_fail']		= '删除失败';
$lang['clic_coupon_time_error']		= '有效期条件错误';
$lang['clic_coupon_edit']		= '修改优惠券';
$lang['clic_coupon_class']		= '优惠券分类';
$lang['clic_coupon_to']		= '至';
$lang['clic_coupon_notice']		= '使用条件';
$lang['clic_coupon_coupon_pic_notice']		= '填写链接地址，建议图片的比例为：300×90';
$lang['clic_coupon_coupon_pic_notice_one'] = '可以在';
$lang['clic_coupon_coupon_pic_notice_two'] = '中，复制图片链接。';
$lang['clic_coupon_pic_null']		= '请上传优惠券图片';
$lang['clic_coupon_pic_format_error'] = '格式错误，必须填写链接地址';
$lang['clic_coupon_allow']		= '审核状态';
$lang['clic_coupon_allow_state']		= '待审核';
$lang['clic_coupon_allow_yes']		= '已通过';
$lang['clic_coupon_allow_no']		= '未通过';
$lang['clic_coupon_allow_remark']		= '审核备注';
$lang['clic_coupon_allow_notice']		= '注意：提交后需要重新审核';
/**
 * 优惠券打印
 */
$lang['clic_coupon_print']		= '优惠券打印';
$lang['clic_coupon_choose_print']		= '你选择打印';
$lang['clic_coupon_print_notice']		= '张优惠券，预计将打印在1张A4纸上。';
$lang['clic_coupon_print_coupon']		= '打印优惠券';
$lang['clic_coupon_id_error']		= '优惠券ID错误';
$lang['clic_coupon_num_error']		= '打印数量错误';
$lang['clic_coupon_error']		= '该优惠券不存在';

/**
 * 幻灯片
 */
$lang['clic_slide_upload_fail']		= 'upload failed';
$lang['clic_slide_image_upload']		= 'image upload';
$lang['clic_slide_description_one']	= 'You can upload up to 5 slide images';
$lang['clic_slide_description_two']	= 'JPG, jpeg, GIF and PNG formats are supported. It is recommended to upload images with a width of 790px, a height of 300px to 400px and a size of %.2fM. Submit 2~5 images for slide show, one image does not have slide show effect.';
$lang['clic_slide_description_three']	= 'After the operation is completed, press the "submit" button to present a slide show on the current page.';
$lang['clic_slide_description_fore']	= 'Jump links must be present <b style="color:red;">“http://”</b>';
$lang['clic_slide_submit']				= 'submit';
$lang['clic_slide_image_url']			= 'jump URL...';

/**
 * 店铺印章
 */
$lang['clic_printsetup_stampimg']			= '印章图片';
$lang['clic_printsetup_tip2']			= '印章图片将出现在打印订单的右下角位置，请选择120x120px大小<br/>透明GIF/PNG格式图片上传作为您店铺的电子印章使用。';
$lang['clic_printsetup_tip1']			= '打印备注信息将出现在打印订单的下方位置，用于注明店铺简介或发货、<br/>退换货相关规则等；<span class="orange">内容不要超过100字。</span>';
$lang['clic_printsetup_desc_error']	= '备注信息长度为1到100个字符之间';
$lang['clic_printsetup_desc']	= '备注信息';

$lang['pay_bank_user']			= '汇款人姓名';
$lang['pay_bank_bank']			= '汇入银行';
$lang['pay_bank_account']		= '汇款入账号';
$lang['pay_bank_num']			= '汇款金额';
$lang['pay_bank_date']			= '汇款日期';
$lang['pay_bank_extend']		= '其它';
$lang['pay_bank_appointment']			= '汇款单号';

/**
 * 客服中心
 */
$lang['clic_callcenter_notes']		= 'The customer service information needs to be completed. Incomplete information will not be saved.';
$lang['clic_callcenter_presales_service']	= 'presales service';
$lang['clic_callcenter_aftersales_service']= 'aftersales service';
$lang['clic_callcenter_service_name']		= 'servicer name';
$lang['clic_callcenter_service_tool']		= 'service tool';
$lang['clic_callcenter_service_number']	= 'servicer No.';
$lang['clic_callcenter_presales']			= 'presales';
$lang['clic_callcenter_aftersales']		= 'aftersales';
$lang['clic_callcenter_name_title']		= 'Use the default values or modify customer service name';
$lang['clic_callcenter_tool_title']		= 'Please select the type of instant messaging too';
$lang['clic_callcenter_number_title']		= 'Enter the correct user account based on the type of instant messaging tool you choose';
$lang['clic_callcenter_please_choose']		= '-please choose-';
$lang['clic_callcenter_wangwang']			= '旺旺';
$lang['clic_callcenter_add_service']		= 'add servicer';
$lang['clic_callcenter_working_time']		= 'work time';
$lang['clic_callcenter_working_time_title']= 'Exp：（work time AM 10:00 - PM 18:00）';

$lang['nc_cut']				= 'cut';