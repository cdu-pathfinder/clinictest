<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * 导航及全局
 */
$lang['adv_index_manage']	= 'advertising';
$lang['adv_manage']	= 'advertising';
$lang['adv_add']	= 'Add ad';
$lang['ap_manage']	= 'Ad position';
$lang['ap_add']	    = 'Add ad position';
$lang['adv_change']	= 'change ad';
$lang['ap_change']	= 'change ad position';
$lang['adv_pic']	= 'Picture';
$lang['adv_word']	= 'Word';
$lang['adv_slide']	= 'Slide';
$lang['adv_edit']	= 'Edit';
$lang['adv_change']	= 'Change';
$lang['adv_pix']	= 'Pixel';
$lang['adv_edit_support'] = 'The image format supported by the system is:';
$lang['adv_cache_refresh'] = 'Clean cache';
$lang['adv_cache_refresh_done'] = 'AD cache cleared';
/**
 * 广告
 */
$lang['adv_name']	         = 'Ad name';
$lang['adv_ap_id']	         = 'belong ad position';
$lang['adv_class']	         = 'Type';
$lang['adv_start_time']	     = 'Start time ';
$lang['adv_end_time']	     = 'End time';
$lang['adv_all']	         = 'All';
$lang['adv_overtime']	     = 'overtime';
$lang['adv_not_overtime']	 = 'not overtime';
$lang['adv_img_upload']	     = 'image upload';
$lang['adv_url']	         = 'Url';
$lang['adv_url_donotadd']	 = 'Url dont add http://';
$lang['adv_word_content']	 = 'Word contenr';
$lang['adv_max']	         = 'Maximum';
$lang['adv_byte']	         = 'Byte';
$lang['adv_slide_upload']	 = 'Slide upload';
$lang['adv_slide_sort']	     = 'Slide sort ';
$lang['adv_slide_sort_role'] = 'The smaller the number, the higher the appointment';
$lang['adv_ap_select']       = 'select ad position';
$lang['adv_search_from']     = 'Release time';
$lang['adv_search_to']	     = 'To';
$lang['adv_click_num']	     = 'Click number';
$lang['adv_admin_add']	     = 'Admin add';
$lang['adv_owner']	         = 'Ad owner';
$lang['adv_wait_check']	     = 'Pending ads';
$lang['adv_flash_upload']	 = 'Flash file upload';
$lang['adv_please_upload_swf_file']	 = 'Please upload swf format file';
$lang['adv_help1']			 = 'Add ad to select ad position ';
$lang['adv_help2']			 = '将广告位调用代码放入前台页面，将显示该广告位的广告';
$lang['adv_help3']			 = 'Clinic can purchase ad ';
$lang['adv_help4']			 = 'Review the Clinic owner purchase of ad';
$lang['adv_help5']			 = '点击查看，在详细页可进行审核操作';

/**
 * 广告位
 */
$lang['ap_name']	         = 'Name';
$lang['ap_intro']	         = 'Intro';
$lang['ap_class']	         = 'Type ';
$lang['ap_show_style']	     = 'Show style';
$lang['ap_width']	         = 'Width/Number';
$lang['ap_height']	         = 'Height';
$lang['ap_price']	         = 'Price';
$lang['ap_show_num']	     = 'Showing';
$lang['ap_publish_num']	     = 'Published';
$lang['ap_is_use']	         = 'use or not ';
$lang['ap_slide_show']	     = 'Slideshow';
$lang['ap_mul_adv']	         = 'Multi-ad display';
$lang['ap_one_adv']	         = 'Single ad display';
$lang['ap_use']	             = 'Activated';
$lang['ap_not_use']	         = 'Inactive';
$lang['ap_get_js']	         = '代码调用';
$lang['ap_use_s']	         = 'Enable';
$lang['ap_not_use_s']	     = 'Disable';
$lang['ap_price_name']	     = 'Price';
$lang['ap_price_unit']	     = '枚金币/月';
$lang['ap_allow_mul_adv']	 = 'Can post multiple ads and display them randomly';
$lang['ap_allow_one_adv']	 = 'Only one ad is allowed to be posted and displayed';
$lang['ap_width_l']	         = 'Width';
$lang['ap_height_l']	     = 'Height';
$lang['ap_word_num']	     = 'Word limit';
$lang['ap_select_showstyle'] = 'Choose the format of this ad slot';
$lang['ap_click_num']	     = 'Click number';
$lang['ap_help1']			 = 'After adding the ad slot, you can choose whether to enable the ad slot';
/**
 * 提示信息
 */
$lang['adv_can_not_null']	    = 'Name is required';
$lang['must_select_ap']	        = 'Must select an ad slot';
$lang['must_select_start_time'] = 'Start time must be selected';
$lang['must_select_end_time']	= 'Close time must be selected';
$lang['must_select_ap_id']		= 'Please select ad position';
$lang['textadv_null_error']		= 'Please add text';
$lang['slideadv_null_error']	= 'Please upload a slideshow picture';
$lang['slideadv_sortnull_error']	= 'Please add slide sort';
$lang['flashadv_null_error']	= 'Please upload the FLASH file';
$lang['picadv_null_error']		= 'Please upload image';
$lang['wordadv_toolong']	    = 'The text of the ad is too long';
$lang['goback_adv_manage']	    = 'Back to advertising management';
$lang['resume_adv_add']	        = 'Continue to add ads';
$lang['resume_ap_add']	        = 'Continue to add ads position';
$lang['adv_add_succ']	        = 'Added successfully';
$lang['adv_add_fail']	        = 'add failed';
$lang['ap_add_succ']	        = 'dded successfully';
$lang['ap_add_fail']	        = 'Add ads position failed';
$lang['goback_ap_manage']	    = '返回广告Back to ads position management';
$lang['ap_stat_edit_fail']	    = 'Ad slot status modification failed';
$lang['ap_del_fail']	        = 'Failed to delete ad position slot';
$lang['ap_del_succ']	        = '广告位成功删除，请即时处理相关模板的广告位js调用';
$lang['adv_del_fail']	        = 'Failed to delete ad';
$lang['adv_del_succ']	        = 'Ads successfully deleted';
$lang['ap_can_not_null']	    = 'Ad name is  required';
$lang['adv_url_can_not_null']	    = 'Ads url is required';
$lang['ap_price_can_not_null']	= 'Ads price is required';
$lang['ap_input_digits_pixel']		= 'Please enter pixel)';
$lang['ap_input_digits_words']		= '请输入文字个数(正整数)';
$lang['ap_default_word_can_not_null'] = 'Default word is required';
$lang['adv_start_time_can_not_null']	= 'Ad start time is required';
$lang['adv_end_time_can_not_null']	= 'Ad End time is required';
$lang['ap_w&h_can_not_null']	= 'ads width and height is required';
$lang['ap_display_can_not_null']	= 'ads method is required';
$lang['ap_wordnum_can_not_null']	= 'ads word is required';
$lang['ap_price_must_num']	    = '广告位价格只能为数字形式';
$lang['ap_width_must_num']	    = 'ads width only number';
$lang['ap_wordwidth_must_num']	= 'ad width only numbwe';
$lang['ap_height_must_num']	    = 'ads height only number';
$lang['ap_change_succ']	        = 'ads position change successfully';
$lang['ap_change_fail']	        = 'ads position change failed';
$lang['adv_change_succ']	    = 'ads message change successfully';
$lang['adv_change_fail']	    = 'ads message change failed';
$lang['adv_del_sure']	        = 'Are you sure you want to delete all ad information ';
$lang['ap_del_sure']	        = 'Are you sure you want to delete all ad position information';
$lang['default_word_can_not_null'] = 'Ads position content is required';
$lang['default_pic_can_not_null']  = 'Ads position image is required';
$lang['must_input_all']  = '(Please input all detail then submit!)';
$lang['adv_index_copy_to_clip']	= 'Please copy and paste the JavaScript or PHP code into the corresponding template file!';

$lang['check_adv_submit']  = 'Review ad application';
$lang['check_adv_yes']     = 'Adv approval';
$lang['check_adv_no']      = 'Fail';
$lang['check_adv_no2']     = 'Pending';
$lang['check_adv_type']    = 'Type';
$lang['check_adv_buy']     = 'Purchase';
$lang['check_adv_appointment']   = 'Pre appointment';
$lang['check_adv_change']  = 'Edit';
$lang['check_adv_view']    = 'View';
$lang['check_adv_nothing'] = 'Currently no pending ads';
$lang['check_adv_chart']   = 'Statistics of ad click rate';
$lang['adv_chart_searchyear_input']  = ' Enter query year:';
$lang['adv_chart_year']    = 'Year';
$lang['adv_chart_years_chart']    = '年的广告点击率统计图';
$lang['ap_default_pic']    = '广告位默认图片:';
$lang['ap_default_pic_upload']    = '广告位默认图片上传:';
$lang['ap_default_word']   = '广告位默认文字';
$lang['ap_show_defaultpic_when_nothing']    = '当没有广告可供展示时使用的默认图片';
$lang['ap_show_defaultword_when_nothing']    = '当没有广告可供展示时使用的默认文字';

$lang['goback_to_adv_check']    = '返回待审核广告列表页面';
$lang['adv_check_ok']      = '广告审核成功';
$lang['adv_check_failed']    = '广告审核失败';
$lang['return_goldpay']    = '返还购买广告的金币';
$lang['adv_chart_nothing_left']    = '此广告没有';
$lang['adv_chart_nothing_right']    = '年的点击率信息';
