<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 共有语言
 */
$lang['store_storeinfo_error']			= 'Clinic Informaion Error';
/**
 * 开店
 */
$lang['store_create_right_closed']		= 'You have already created the clinic and cannot create it again';
$lang['store_create_created']			= 'You have already created the clinic and cannot create it again';
$lang['store_create_grade_not_exists']	= '错误的操作，该等级不存在';
$lang['store_create_choose_store_class']	= 'Select Clinic Type';
$lang['store_create_input_owner_info']	= 'Fill in the owner and clinic information';
$lang['store_create_finish']			= 'Finish';
$lang['store_create_goods_num']			= 'doctor No.';
$lang['store_create_upload_space']		= 'Upload space';
$lang['store_create_template_num']		= '模板数';
$lang['store_create_charge_standard']	= 'Charge standard';
$lang['store_create_need_audit']		= 'Need review';
$lang['store_create_yes']				= 'yes';
$lang['store_create_no']				= 'no';
$lang['store_create_additional_function']	= '附加功能';
$lang['store_create_create_now']		= 'Open clinic';
$lang['store_create_back'] 				= 'back';
$lang['store_create_input_store_name']	= 'Please enter the clinic name';
$lang['store_create_store_name_exists']	= 'This clinic name already exists, please change';
$lang['store_create_input_phone']		= 'Please enter a contact number';
$lang['store_create_input_store_card']	= 'Incorrect id';
$lang['store_create_input_zip_code_is_number']	= 'The zip code must be numeric';
$lang['store_create_input_zip_code']	= 'The postcode format is incorrect';
$lang['store_create_phone_rule']		= 'The phone number must be no less than 6 digits';
$lang['store_create_choose_store_class']	= 'Please select the clinic category';
$lang['store_create_choose_area']		= 'Please select district';
$lang['store_create_upload_type']		= 'Please upload the format as jpg,jpeg,png,gif';
$lang['store_create_read_agreement']	= 'Please agree to the shop opening agreement';
$lang['store_create_card_no']			= 'Id number';
$lang['store_create_input_true_card']	= 'Please fill in the true and accurate id number';
$lang['store_create_store_name']		= 'clinic name';
$lang['store_create_name_rule']			= 'Please limit it to 20 characters';
$lang['store_create_store_class']		= 'clinic class';
$lang['store_create_please_choose']		= 'please choose';
$lang['store_create_location']			= 'location';
$lang['store_create_address']			= 'Detailed address';
$lang['store_create_zipcode']			= 'The zip code';
$lang['store_create_phone']				= 'Contact phone number';
$lang['store_create_input_phone']		= 'Please enter a contact number';
$lang['store_create_upload_paper']		= 'Upload id card';
$lang['store_create_true_name_intro']	= 'What is real name authentication';
$lang['store_create_upload_rule']		= 'Supported formats:jpg,jpeg,png,gif，Make sure the image is clear and the file size does not exceed 400KB';
$lang['store_create_upload_licence']	= 'Upload the license';
$lang['store_create_true_store_intro']	= 'What is physical clinic certification';
$lang['store_create_read_agreement1']	= 'I have read it carefully and agree';
$lang['store_create_store_agreement']	= 'Open agreement';
$lang['store_create_read_agreement2']	= 'of the conditions';
$lang['store_create_store_editor_multimedia']	= 'Editor multimedia features';
$lang['store_create_store_groupbuy']	= '团购活动';
$lang['store_create_store_null']	    = 'null';
/**
 * 保存店铺
 */
$lang['store_save_owner_null']			= 'The owner name cannot be empty';
$lang['store_save_store_name_null']		= 'The clinic name cannot be empty';
$lang['store_save_store_class_null']	= 'The clinic category cannot be empty';
$lang['store_save_area_null']			= 'The area cannot be empty';
$lang['store_save_create_success']		= 'Congratulations, your clinic has been successfully established.';
$lang['store_save_waiting_for_review']	= 'Wait for administrator review.';
$lang['store_save_create_fail']			= 'Failed to create clinic';
$lang['store_save_defaultalbumclass_name']	= 'default photo';
/**
 * 卖家商品分类
 */
$lang['store_goods_class_csc_null']		= 'The imported CSV file cannot be empty';
$lang['store_goods_class_new_class']	= 'new classification';
$lang['store_goods_class_import']		= 'import';
$lang['store_goods_class_export']		= 'export';
$lang['store_goods_class_ensure_del']	= 'Are you sure you want to delete the category';
$lang['store_goods_class_name']			= 'Category name';
$lang['store_goods_class_sort']			= 'sort';
$lang['store_goods_class_add_sub']		= 'New subordinate';
$lang['store_goods_class_no_record']	= 'No clinic classification';
$lang['store_goods_class_name_null']	= 'The category name cannot be empty';
$lang['store_goods_class_input_int']	= 'need to enter a number';
$lang['store_goods_class_edit_class']	= 'edit class';
$lang['store_goods_class_add_class']	= 'add class';
$lang['store_goods_class_sup_class']	= 'sup class';
$lang['store_goods_class_display_state']	= 'display state';
$lang['store_goods_class_submit']		= 'submit';
$lang['store_goods_class_problem']		= 'Export the data of your clinic classification';
$lang['store_goods_class_choose_file']	= 'Please select file';
$lang['store_goods_class_choose_code']	= 'Please select the file encoding';
$lang['store_goods_class_trans_tip']	= 'If the file is large, convert the file to utf-8';
$lang['store_goods_class_file_format']	= 'The file format';
$lang['store_goods_class_csv_file']		= 'csv file';
$lang['store_goods_class_csv_download']	= 'CSV sample';
$lang['store_goods_class_download']		= 'Click download';
$lang['store_goods_class_wrong']		= 'Incorrect operation, no such classification';
$lang['store_goods_class_modify_fail']	= 'clinic class modification failed';
$lang['store_goods_class_add_fail']		= 'Failed to add clinic category';
$lang['store_goods_class_no_csv']		= 'Please select the CSV file';
/**
 * 订单
 */

$lang['store_order_order_sn']		= 'order no.';
$lang['store_order_order_sn_search']= 'Enter the order number to query';
$lang['store_order_comp_exp']		= '快递公司';
$lang['store_order_goods_detail']		= 'doctor detail';
$lang['store_order_goods_single_price']	= 'price';
$lang['store_order_sell_back']			= '售后';
$lang['store_order_order_stateop']		= 'state';
$lang['store_order_order_confirm']		= 'order confirm';
$lang['store_order_confirm_order']		= 'confirm order';
$lang['store_order_shipping_order']		= '确认货到付款订单';
$lang['store_order_add_time']		= 'order time';
$lang['store_order_buyer']			= 'patient';
$lang['store_order_search']			= 'search';
$lang['store_order_cancel_order']	= 'cancel';
$lang['store_order_show_deliver']	= '查看物流';
$lang['store_order_buyer_info']		= 'contact info';
$lang['store_order_receiver']		= 'name';
$lang['store_order_phone']			= 'phone';
$lang['store_order_mobile']			= 'mobile';
$lang['store_order_email']			= 'email';
$lang['store_order_area']			= 'city';
$lang['store_order_address']		= '收货地址';
$lang['store_order_zip_code']		= '邮政编码';
$lang['store_order_pay_method']		= '支付方式';
$lang['store_order_sum']			= '订单总价';
$lang['store_order_state']			= '订单状态';
$lang['store_order_group']			= '团购';
$lang['store_order_evaluated']		= '已评价';
$lang['store_order_received_price']	= '收到货款';
$lang['store_order_modify_price']	= '调整费用';
$lang['store_order_modify_price_gpriceerror']	= 'The total price cannot be empty and must be a number';
$lang['store_order_send']			= '设置发货';
$lang['store_order_refund']			= 'refund';
$lang['store_buyer_confirm']		= 'confirm';
$lang['store_order_return']			= 'reback';
$lang['store_order_modify_no']		= 'modify No.';
$lang['store_order_view_order']		= 'view order';
$lang['store_order_complain']	= 'complain';
$lang['store_order_no_result']		= 'There is no qualified order';
$lang['store_order_ensure_cancel']	= 'you want to cancel the order?';
$lang['store_order_cancel_reason']	= '取消缘由';
$lang['store_order_lose_goods']		= '无法备齐货物';
$lang['store_order_invalid_order']	= '不是有效的订单';
$lang['store_order_buy_apply']		= '买家主动要求';
$lang['store_order_other_reason']	= 'other reason';
$lang['store_order_buyer_with']		= 'patient';
$lang['store_order_sn']				= 'No.';
$lang['store_order_modify_rule']	= '输入要修改的金额，只能为数字';
$lang['store_order_ensure_receive_fee']	= '您确定已经收到货款了吗';
$lang['store_order_handle_desc']		= '操作备注';
$lang['store_order_shipping_no_null']	= '物流单号不能为空';
$lang['store_order_input_shipping_no']	= '请输入您的物流单号';
$lang['store_order_shipping_no']		= '物流单号';
$lang['store_order_want_evaluate']	= '我要评价';
$lang['store_show_order_detail']		= '订单详情';
$lang['store_show_order_info']			= '订单信息';
$lang['store_show_order_seller_info']	= '卖家信息';
$lang['store_show_order_store_name']	= 'clinic name';
$lang['store_show_order_wangwang']		= '旺旺';
$lang['store_show_order_goods_name']	= 'doctor name';
$lang['store_show_order_amount']		= 'amount';
$lang['store_show_order_price']			= 'price';
$lang['store_show_order_tp_fee']		= '运费';
$lang['store_show_order_pay_message']	= 'pay message';
$lang['store_show_order_pay_time']		= '付款时间';
$lang['store_show_order_send_time']		= '发货时间';
$lang['store_show_order_finish_time']	= 'finish time';
$lang['store_show_order_shipping_info']	= '物流信息';
$lang['store_show_order_receiver']		= '收 货 人';
$lang['store_show_order_receiver_address']	= '收货地址';
$lang['store_show_order_mobile']			= '手机号码';
$lang['store_show_order_buyer_message']		= '买家留言';
$lang['store_show_order_handle_history']	= '操作历史';
$lang['store_show_system']				= 'system';
$lang['store_show_order_at']				= '于';
$lang['store_show_order_cur_state']			= 'Current order state';
$lang['store_show_order_next_state']		= 'next state';
$lang['store_show_order_reason']			= 'reason';
$lang['store_show_order_printorder']		= '打印发货单';
$lang['store_show_order_shipping_han']		= '含';
$lang['store_order_tip1']		= '平台收款，确认收款由系统自动或管理员手动完成，卖家不能进行收款操作，管理员可以取消未付款的线下支付订单';
$lang['store_order_cancel_success']	= 'cancelled successful';
$lang['store_order_edit_ship_success']	= '成功修改了运费';
$lang['store_order_none_exist']	= 'order not exist';
/**
 * 支付
 */
$lang['store_payment_name']				= 'name';
$lang['store_payment_intro']			= 'Plugin instructions';
$lang['store_payment_enable']			= 'enable';
$lang['store_payment_yes']				= 'yes';
$lang['store_payment_no']				= 'no';
$lang['store_payment_config']			= 'configue plugin';
$lang['store_payment_ensure_uninstall']	= '您确实要卸载该插件吗';
$lang['store_payment_uninstall']		= 'uninstall';
$lang['store_payment_install']			= 'install';
$lang['store_payment_not_exists']		= 'The payment interface does not exist in the system';
$lang['store_payment_add']				= 'Configure payment method';
$lang['store_payment_info']				= 'Prompt information';
$lang['store_payment_display']			= 'Prompt message when the user pays';
$lang['store_payment_uninstall_fail']	= 'uninstall failed';
$lang['store_payment_edit_not_null']	= 'not null';
/**
 * 广告管理
 */
$lang['store_adv_buy']			= '购买广告';
/**
 * 导航
 */
$lang['store_navigation_name_null']		= 'The navigation name cannot be empty';
$lang['store_navigation_name_max']		= 'Navigation name up to 10 words';
$lang['store_navigation_del_fail']		= 'Delete navigation failed';
$lang['store_navigation_new']			= 'The new navigation';
$lang['store_navigation_edit']			= 'Edit navigation';
$lang['store_navigation_name']			= 'Navigation name';
$lang['store_navigation_display']		= 'Whether to display';
$lang['store_navigation_content']		= 'content';
$lang['store_navigation_no_result']		= 'There is no navigation that meets the criteria';
$lang['store_navigation_url']		    = 'Navigation links URL';
$lang['store_navigation_url_tip']		= '请填写包含http://的完整URL地址,如果填写此项则点击该导航会跳转到外链';
$lang['store_navigation_new_open']		= '新窗口打开';
$lang['store_navigation_new_open_yes']	= 'yes';
$lang['store_navigation_new_open_no']	= 'no';

/**
 * 合作伙伴
 */
$lang['store_partner_title_null']	= 'The title cannot be empty';
$lang['store_partner_wrong_href']	= 'Incorrect link format';
$lang['store_partner_add_fail']		= 'New partner failed';
$lang['store_partner_del_fail']		= 'Failed to delete partner';
$lang['store_partner_add']			= 'New partners';
$lang['store_partner_edit']			= 'Edit content';
$lang['store_partner_title']		= 'title';
$lang['store_partner_href']			= 'link';
$lang['store_partner_href_tip']		= 'The number should be greater than zero, the smaller the more forward';
$lang['store_partner_sign']			= '标识';
$lang['store_partner_pic_upload']	= 'upload image';
$lang['store_partner_href_null']	= 'The link cannot be empty';
$lang['store_partner_no_result']	= 'No qualified partner';
$lang['store_partner_des_one']		= '填写链接地址，您可以在';
$lang['store_partner_des_two']		= '中复制链接。';
/**
 * 店铺设置
 */
$lang['store_setting_name_null']			= '店铺名称不能为空';
$lang['store_setting_wrong_uri']			= '二级域名长度不符合要求';
$lang['store_setting_exists_uri']			= '该二级域名已存在,请更换其它域名';
$lang['store_setting_invalid_uri']			= '该二级域名为系统禁止域名,请更换其它域名';
$lang['store_setting_lack_uri']				= '该二级域名不符合域名命名规范,请不要使用特殊字符';
$lang['store_create_store_name_hint']		= '店铺名称请控制长度不超过20字';
$lang['store_create_store_zy_hint']			= 'he keyword (Tag) helps you find your clinic when searching for it<br/>Keywords can be entered up to 50 words, please use "," to separate';

$lang['store_setting_change_label']			= 'cliniclogo';
$lang['store_setting_label_tip']			= 'Here is your clinic logo, which will be displayed in the clinic logo bar;<br/><span style="color:orange;">It is recommended to use a transparent GIF or PNG image between 200 pixels wide and 60 pixels high. Click the "submit" button below to take effect.</span>';
$lang['store_setting_change_sign']			= 'clinic标志';
$lang['store_setting_sign_tip']				= 'Here is your clinic logo, which will be displayed in the clinic information bar;<br/><span style="color:orange;">It is recommended to use a square image with a width of 100 pixels * a height of 100 pixels; Click the "submit" button below to take effect.</span>';
$lang['store_setting_change_banner']		= 'clinic banner';
$lang['store_setting_banner_tip']			= 'Here is your clinic banner, which will be displayed in the banner position above the clinic navigation；<br/><span style="color:orange;">It is recommended to use images 1000 pixels wide by 250 pixels high; Click the "submit" button below to take effect.</span>';
$lang['store_setting_uri']					= '二级域名';
$lang['store_setting_uri_tip']				= '可留空，域名长度应为';
$lang['store_setting_domain_times']			= '已修改次数为';
$lang['store_setting_domain_times_max']		= '最多可修改次数为';
$lang['store_setting_domain_notice']		= '注意！设置后将不能修改';
$lang['store_setting_domain_tip']			= '不可修改';
$lang['store_setting_domain_valid']			= 'Letters, Numbers, underscores, and underscores are valid characters';
$lang['store_setting_domain_rangelength']   = '二级域名长度为 {0} 到 {1} 个字符之间';
$lang['store_setting_my_homepage']			= 'my homepage';
$lang['store_setting_grade']				= 'clinic level';
$lang['store_setting_upgrade']				= '马上升级店铺等级';
$lang['store_setting_location_tip']			= '不必重复填写所在地区';
$lang['store_setting_contact']				= 'contact';
$lang['store_setting_wangwang']				= 'wangwang';
$lang['store_setting_intro']				= 'clinic intro';
$lang['store_setting_customer_service']		= 'customer service';
$lang['store_setting_username']				= 'username';
$lang['store_setting_password']				= 'password';
$lang['store_setting_checking']				= 'checking...';
$lang['store_setting_apply']				= 'apply';
$lang['store_setting_applying']				= 'applying...';
$lang['store_setting_apply_success']		= '在线客服申请成功,请等待管理员审核开通';
$lang['store_setting_apply_error']			= '网络忙,在线客服申请失败,请稍后再试';
$lang['store_setting_seo_keywords']			= 'keyword';
$lang['store_setting_store_zy']				= 'The main diagnostic';
$lang['store_setting_seo_description']		= 'clinic description';
$lang['store_setting_seo_keywords_help']	= 'please use English comma separated keywords';
$lang['store_setting_seo_description_help']	= 'suggested within 120 words';
$lang['store_settine_browse']				= 'browse...';
$lang['store_setting_store_url']			= '当前店铺首页连接：';
/**
 * 升级店铺
 */
$lang['store_upgrade_submit']		= 'The application for clinic level has been submitted to the administrator, please wait for your review';
$lang['store_upgrade_submit_fail']	= 'The clinic level submission failed, please operate again';
$lang['store_upgrade_cur_grade']	= 'Current level of clinic';
//$lang['store_upgrade_tip']			= '如果店铺等级需要审核，升级后在待审核这段期间，店铺部分功能不能正常使用，您确定要升级吗?';
$lang['store_upgrade_tip']			= 'Are you sure you want to upgrade?';
$lang['store_upgrade_now']			= 'upgrade now';
$lang['store_upgrade_store_error']			= 'clinic information error';
$lang['store_upgrade_gradesort_error']		= '等级错误,升级级别应高于当前级别';
$lang['store_upgrade_exist_error']			= '店铺等级升级申请已经提交，正在审核中，请耐心等待';
$lang['store_upgrade_exist_tip_1']			= 'clinic level upgraded to';
$lang['store_upgrade_exist_tip_2']			= '的申请，正在审核中...';
/**
 * 主题
 */
$lang['store_theme_load_preview_fail']	= 'Load preview failed';
$lang['store_theme_effect_preview']		= 'preview';
$lang['store_theme_loading1']			= 'loading';
$lang['store_theme_use']				= 'use';
$lang['store_theme_loading2']			= 'loading';
$lang['store_theme_congfig_success']	= 'congfig success';
$lang['store_theme_error']				= 'error';
$lang['store_theme_homepage']			= 'homepage';
$lang['store_theme_tpl_name']			= 'tpl name';
$lang['store_theme_style_name']			= 'style name';
$lang['store_theme_valid']				= 'Available themes';
$lang['store_theme_tpl_name1']			= 'tpl name';
$lang['store_theme_style_name1']		= 'style name';
$lang['store_theme_preview']			= 'preview';
$lang['store_theme_congfig_fail']		= 'congfig fail';
/**
 * 活动
 */
$lang['store_activity_year']		= 'year';
$lang['store_activity_month']		= 'month';
$lang['store_activity_day']			= 'day';
$lang['store_activity_theme']		= 'theme';
$lang['store_activity_intro']		= 'activity intro';
$lang['store_activity_start_time']	= 'start time';
$lang['store_activity_end_time']	= '结束时间';
$lang['store_activity_long_time']	= '长期活动';
$lang['store_activity_type']		= '活动类型';
$lang['store_activity_goods']		= '商品';
$lang['store_activity_group']		= '团购';
$lang['store_activity_join']		= '参与活动';
$lang['store_activity_no_record']	= '没有符合条件的活动';
$lang['store_activity_goods_name']	= '商品名称';
$lang['store_activity_goods_class']	= '商品类别';
$lang['store_activity_goods_brand']	= '商品品牌';
$lang['store_activity_pass']		= '已通过';
$lang['store_activity_audit']		= '审核中';
$lang['store_activity_refuse']		= '未通过';
$lang['store_activity_join_tip']	= '您尚未参与本活动,可以在本页下方进行选择';
$lang['store_activity_group_name']	= '团购名称';
$lang['store_activity_group_intro']	= '团购介绍';
$lang['store_activity_class']		= '类别';
$lang['store_activity_choose']		= '请选择';
$lang['store_activity_brand']		= '品牌';
$lang['store_activity_name']		= '名称';
$lang['store_activity_search']		= '查找';
$lang['store_activity_goods_applied']	= '您的商品已经全部申请完毕';
$lang['store_activity_none_goods']		= '您尚未发布任何商品';
$lang['store_activity_group_applied']	= '您的团购已经全部申请完毕';
$lang['store_activity_none_group']		= '您尚未发布任何团购';
$lang['store_activity_join_now']		= '选择完毕,参与活动';
$lang['store_activity_choose_goods']	= '请手动选择内容后再保存';
$lang['store_activity_not_exists']		= '该活动并不存在';
$lang['store_activity_unknown_type']	= '该活动类型不明';
$lang['store_activity_id_is']			= '编号为';
$lang['store_activity_goods_not_exists']	= '的商品并不存在';
$lang['store_activity_group_not_exists']	= '的团购并不存在';
$lang['store_activity_submitted']			= '参与申请已提交';
$lang['store_activity_info_title']			= '活动信息';
$lang['store_activity_goods_tip']			= '活动商品如下';
$lang['store_activity_confirmstatus']		= '审核状态';
$lang['store_activity_choosegoods']		= 'choose doctor';
/**
 * ajax修改商品分类
 */
$lang['store_goods_class_ajax_update_fail']	= '更新数据库失败';
/**
 * 水印管理
 */
$lang['store_watermark_pic']		= 'watermark pic：';
$lang['store_watermark_del']		= 'delete';
$lang['store_watermark_del_pic']		= 'delete watermark';
$lang['store_watermark_choose_pic']		= 'choose watermark';
$lang['store_watermark_pic_quality']		= 'Image quality：';
$lang['store_watermark_pic_pos']		= 'Image location:';
$lang['store_watermark_choose_pos']		= 'Select the watermark image location';
$lang['store_watermark_pic_pos1']		= 'Upper left';
$lang['store_watermark_pic_pos2']		= '正上';
$lang['store_watermark_pic_pos3']		= '右上';
$lang['store_watermark_pic_pos4']		= '左中';
$lang['store_watermark_pic_pos5']		= '中间';
$lang['store_watermark_pic_pos6']		= '右中';
$lang['store_watermark_pic_pos7']		= '左下';
$lang['store_watermark_pic_pos8']		= '中下';
$lang['store_watermark_pic_pos9']		= '右下';
$lang['store_watermark_transition']		= '融合度：';
$lang['store_watermark_transition_notice']		= '水印图片与原图片的融合度';
$lang['store_watermark_text']		= '水印文字：';
$lang['store_watermark_text_notice']		= '水印文字';
$lang['store_watermark_text_size']		= '文字大小：';
$lang['store_watermark_text_size_notice']		= '设置水印文字大小';
$lang['store_watermark_text_angle']		= '文字角度：';
$lang['store_watermark_text_angle_notice']		= '水印文字角度,尽量不要更改';
$lang['store_watermark_text_pos']		= '文字位置：';
$lang['store_watermark_text_pos_notice']		= '选择水印文字放置位置';
$lang['store_watermark_text_pos1']		= '左上';
$lang['store_watermark_text_pos2']		= '正上';
$lang['store_watermark_text_pos3']		= '右上';
$lang['store_watermark_text_pos4']		= '左中';
$lang['store_watermark_text_pos5']		= '中间';
$lang['store_watermark_text_pos6']		= '右中';
$lang['store_watermark_text_pos7']		= '左下';
$lang['store_watermark_text_pos8']		= '中下';
$lang['store_watermark_text_pos9']		= '右下';
$lang['store_watermark_text_font']		= '文字字体：';
$lang['store_watermark_text_font_notice']		= '水印文字的字体';
$lang['store_watermark_text_color']		= '文字颜色：';
$lang['store_watermark_text_color_notice']		= '水印字体的颜色值';
$lang['store_watermark_is_open']		= '是否开启：';
$lang['store_watermark_is_open_notice']		= '是否开启水印';
$lang['store_watermark_is_open1']		= '开启';
$lang['store_watermark_is_open0']		= '关闭';
$lang['store_watermark_submit']		= '提交';
$lang['store_watermark_del_pic_confirm']		= '确定删除水印图片?';
$lang['store_watermark_pic_quality_null']		= '水印图片质量不能为空';
$lang['store_watermark_pic_quality_number']		= '水印图片质量必须为数字';
$lang['store_watermark_pic_quality_min']		= '水印图片质量在 0-100 之间';
$lang['store_watermark_pic_quality_max']		= '水印图片质量在 0-100 之间';
$lang['store_watermark_transition_null']		= '水印图片融合度不能为空';
$lang['store_watermark_transition_number']		= '水印图片融合度必须为数字';
$lang['store_watermark_transition_min']		= '水印图片融合度在 0-100 之间';
$lang['store_watermark_transition_max']		= '水印图片融合度在 0-100 之间';
$lang['store_watermark_text_size_null']		= '水印文字大小不能为空';
$lang['store_watermark_text_size_number']		= '水印文字大小必须为数字';
$lang['store_watermark_text_color_null']		= '水印字体颜色不能为空';
$lang['store_watermark_text_color_max']		= '字体颜色值格式不正确';
$lang['store_watermark_congfig_success']		= '设置成功';
$lang['store_watermark_congfig_fail']		= '设置失败';
$lang['store_watermark_congfig_notice']		= '如果开启水印,必须设置水印图片或者水印文字';
$lang['store_watermark_browse']				= '浏览...';
/**
 * 优惠券管理
 */
$lang['store_coupon_name']		= '优惠券名称';
$lang['store_coupon_period']	= '有效期：';
$lang['store_coupon_add']		= '新增优惠券';
$lang['store_coupon_pic']		= '优惠券图片';
$lang['store_coupon_price']		= '优惠金额';
$lang['store_coupon_lifetime']	= '使用期限';
$lang['store_coupon_state']		= '上架';
$lang['store_coupon_no_result']		= '没有符合条件的记录';
$lang['store_coupon_null_class']		= '总后台管理员新增优惠券分类后方可添加优惠券';
$lang['store_coupon_name_null']		= '优惠券名称不能为空';
$lang['store_coupon_price_error']		= '优惠金额错误';
$lang['store_coupon_price_min']		= '最小金额为1';
$lang['store_coupon_start_time_null']		= '优惠券开始日期不能为空';
$lang['store_coupon_end_time_null']		= '优惠券结束日期不能为空';
$lang['store_coupon_update_success']		= '更新优惠券成功';
$lang['store_coupon_update_fail']		= '更新优惠券失败';
$lang['store_coupon_add_success']		= '增加优惠券成功';
$lang['store_coupon_add_fail']		= '增加优惠券失败';
$lang['store_coupon_del_success']		= '删除成功';
$lang['store_coupon_del_fail']		= '删除失败';
$lang['store_coupon_time_error']		= '有效期条件错误';
$lang['store_coupon_edit']		= '修改优惠券';
$lang['store_coupon_class']		= '优惠券分类';
$lang['store_coupon_to']		= '至';
$lang['store_coupon_notice']		= '使用条件';
$lang['store_coupon_coupon_pic_notice']		= '填写链接地址，建议图片的比例为：300×90';
$lang['store_coupon_coupon_pic_notice_one'] = '可以在';
$lang['store_coupon_coupon_pic_notice_two'] = '中，复制图片链接。';
$lang['store_coupon_pic_null']		= '请上传优惠券图片';
$lang['store_coupon_pic_format_error'] = '格式错误，必须填写链接地址';
$lang['store_coupon_allow']		= '审核状态';
$lang['store_coupon_allow_state']		= '待审核';
$lang['store_coupon_allow_yes']		= '已通过';
$lang['store_coupon_allow_no']		= '未通过';
$lang['store_coupon_allow_remark']		= '审核备注';
$lang['store_coupon_allow_notice']		= '注意：提交后需要重新审核';
/**
 * 优惠券打印
 */
$lang['store_coupon_print']		= '优惠券打印';
$lang['store_coupon_choose_print']		= '你选择打印';
$lang['store_coupon_print_notice']		= '张优惠券，预计将打印在1张A4纸上。';
$lang['store_coupon_print_coupon']		= '打印优惠券';
$lang['store_coupon_id_error']		= '优惠券ID错误';
$lang['store_coupon_num_error']		= '打印数量错误';
$lang['store_coupon_error']		= '该优惠券不存在';

/**
 * 幻灯片
 */
$lang['store_slide_upload_fail']		= 'upload failed';
$lang['store_slide_image_upload']		= 'image upload';
$lang['store_slide_description_one']	= 'You can upload up to 5 slide images';
$lang['store_slide_description_two']	= 'JPG, jpeg, GIF and PNG formats are supported. It is recommended to upload images with a width of 790px, a height of 300px to 400px and a size of %.2fM. Submit 2~5 images for slide show, one image does not have slide show effect.';
$lang['store_slide_description_three']	= 'After the operation is completed, press the "submit" button to present a slide show on the current page.';
$lang['store_slide_description_fore']	= 'Jump links must be present <b style="color:red;">“http://”</b>';
$lang['store_slide_submit']				= 'submit';
$lang['store_slide_image_url']			= 'jump URL...';

/**
 * 店铺印章
 */
$lang['store_printsetup_stampimg']			= '印章图片';
$lang['store_printsetup_tip2']			= '印章图片将出现在打印订单的右下角位置，请选择120x120px大小<br/>透明GIF/PNG格式图片上传作为您店铺的电子印章使用。';
$lang['store_printsetup_tip1']			= '打印备注信息将出现在打印订单的下方位置，用于注明店铺简介或发货、<br/>退换货相关规则等；<span class="orange">内容不要超过100字。</span>';
$lang['store_printsetup_desc_error']	= '备注信息长度为1到100个字符之间';
$lang['store_printsetup_desc']	= '备注信息';

$lang['pay_bank_user']			= '汇款人姓名';
$lang['pay_bank_bank']			= '汇入银行';
$lang['pay_bank_account']		= '汇款入账号';
$lang['pay_bank_num']			= '汇款金额';
$lang['pay_bank_date']			= '汇款日期';
$lang['pay_bank_extend']		= '其它';
$lang['pay_bank_order']			= '汇款单号';

/**
 * 客服中心
 */
$lang['store_callcenter_notes']		= 'The customer service information needs to be completed. Incomplete information will not be saved.';
$lang['store_callcenter_presales_service']	= 'presales service';
$lang['store_callcenter_aftersales_service']= 'aftersales service';
$lang['store_callcenter_service_name']		= 'servicer name';
$lang['store_callcenter_service_tool']		= 'service tool';
$lang['store_callcenter_service_number']	= 'servicer No.';
$lang['store_callcenter_presales']			= 'presales';
$lang['store_callcenter_aftersales']		= 'aftersales';
$lang['store_callcenter_name_title']		= 'Use the default values or modify customer service name';
$lang['store_callcenter_tool_title']		= 'Please select the type of instant messaging too';
$lang['store_callcenter_number_title']		= 'Enter the correct user account based on the type of instant messaging tool you choose';
$lang['store_callcenter_please_choose']		= '-please choose-';
$lang['store_callcenter_wangwang']			= '旺旺';
$lang['store_callcenter_add_service']		= 'add servicer';
$lang['store_callcenter_working_time']		= 'work time';
$lang['store_callcenter_working_time_title']= 'Exp：（work time AM 10:00 - PM 18:00）';

$lang['nc_cut']				= 'cut';