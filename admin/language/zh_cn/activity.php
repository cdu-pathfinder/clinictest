<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * 公用
 */
$lang['activity_openstate']		= 'Status';
$lang['activity_openstate_open']		= 'Open';
$lang['activity_openstate_close']		= 'Close';
/**
 * 活动列表
 */
$lang['activity_index']				= 'Activity';
$lang['activity_index_content']		= 'Activity Content';
$lang['activity_index_manage']		= 'Activity management';
$lang['activity_index_title']		= 'Activity Title';
$lang['activity_index_type']		= 'Activity Type';
$lang['activity_index_banner']		= 'Banner';
$lang['activity_index_style']		= 'Style';
$lang['activity_index_start']		= 'Start Time';
$lang['activity_index_end']			= 'End Time';
$lang['activity_index_doctors']		= 'Doctor';
$lang['activity_index_group']		= '团购';
$lang['activity_index_default']		= 'Default';
$lang['activity_index_long_time']	= 'Long-term activity';
$lang['activity_index_deal_apply']	= 'Process Apply';
$lang['activity_index_help1']		= '当平台发起活动时，店铺可申请参与活动';
$lang['activity_index_help2']		= '在“页面导航”模块处可选择添加活动导航';
$lang['activity_index_help3']		= '只有关闭或者过期的活动才能删除';
$lang['activity_index_help4']		= '活动列表排序越小越靠前显示';
$lang['activity_index_periodofvalidity']= 'Validity';
/**
 * 添加活动
 */
$lang['activity_new_title_null']	= 'Activity title cannot empty';
$lang['activity_new_style_null']	= 'Must choose page style';
$lang['activity_new_type_null']		= 'Must choose activity type';
$lang['activity_new_sort_tip']		= 'Sort must be numeric, range 0 ~ 255';
$lang['activity_new_end_date_too_early']	= '截止时间必须晚于开始时间';
$lang['activity_new_title_tip']		= '请为您的活动填写一个简明扼要的主题';
$lang['activity_new_type_tip']		= '请为您的活动选择一个类别';
$lang['activity_new_start_tip']		= '留空默认为活动立即开始';
$lang['activity_new_end_tip']		= '留空默认为活动永久进行';
$lang['activity_new_banner_tip']	= 'Support jpg、jpeg、gif、png format';
$lang['activity_new_style']			= 'Page Style';
$lang['activity_new_style_tip']		= 'Please select the style of the page where the activity is ';
$lang['activity_new_desc']			= 'Activity description';
$lang['activity_new_sort_tip1']		= 'The Numbers range from 0 to 255, with the smaller Numbers getting closer to the front';
$lang['activity_new_sort_null']		= 'Sort cannot be empty';
$lang['activity_new_sort_minerror']	= 'The number range is 0~255';
$lang['activity_new_sort_maxerror']	= 'The number range is 0 ~ 255';
$lang['activity_new_sort_error']	= 'Numbers sorted from 0 to 255';
$lang['activity_new_banner_null']   = 'Banner cannot be emopty';
$lang['activity_new_ing_wrong']     = 'Picture foramt only png,gif,jpeg,jpg';
$lang['activity_new_startdate_null']   = 'Start time cannot be empty';
$lang['activity_new_enddate_null']     = 'End time cannot be empty';

/**
 * 删除活动
 */
$lang['activity_del_choose_activity']	= 'Please choose activity';
/**
 * 活动内容
 */
$lang['activity_detail_index_doctors_name']	= 'Doctor Name';
$lang['activity_detail_index_clic']		= 'Belong clinic';
$lang['activity_detail_index_auditstate']	= 'Review status';
$lang['activity_detail_index_to_audit']		= 'Awaiting';
$lang['activity_detail_index_passed']		= 'Approval';
$lang['activity_detail_index_unpassed']		= 'Reject';
$lang['activity_detail_index_apply_again']	= 'Apply again';
$lang['activity_detail_index_pass']			= 'Approval';
$lang['activity_detail_index_refuse']		= 'Refuse';
$lang['activity_detail_index_pass_all']		= 'Are you confirm to select all of the approval information?';
$lang['activity_detail_index_refuse_all']	= 'Are you confirm to select all of the reject information?';
$lang['activity_detail_index_tip1']	= '申请商品在没有审核或者审核失败的时候可以删除';
$lang['activity_detail_index_tip2']	= '本页申请商品的显示规则是未审核先显示，排序越小越靠前显示';
$lang['activity_detail_index_tip3']	= '下架、违规下架商品或者所属店铺已经关闭的商品将不会在活动页面显示，请慎重审核';

/**
 * 活动内容删除
 */
$lang['activity_detail_del_choose_detail']	= '请选择活动内容(比如商品或团购等)';