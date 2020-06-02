<?php
defined('InclinicNC') or exit('Access Invalid!');

/**
 * 页面需要的语言
 */
$lang['inform_page_title'] = 'Report doctor';
$lang['inform_manage_title'] = 'Report management';
$lang['inform'] 			= 'Report';

$lang['inform_state_all'] = 'All report';
$lang['inform_state_handled'] = 'Handled';
$lang['inform_state_unhandle'] = 'Pending';
$lang['inform_doctors_name'] = 'Doctor name';
$lang['inform_member_name'] = 'Informer';
$lang['inform_subject'] = 'Report subject';
$lang['inform_type'] = 'Report type';
$lang['inform_type_desc'] = 'Report type description';
$lang['inform_pic'] = 'Image';
$lang['inform_pic_view'] = 'View image';
$lang['inform_pic_none'] = 'No image';
$lang['inform_datetime'] = 'Report time';
$lang['inform_state'] = 'State';
$lang['inform_content'] = 'Report ccontent';
$lang['inform_handle_message'] = 'Processing';
$lang['inform_handle_type'] = 'Result';
$lang['inform_handle_type_unuse'] = 'Invalid report';
$lang['inform_handle_type_venom'] = 'Venom report';
$lang['inform_handle_type_valid'] = 'Sucess';
$lang['inform_handle_type_unuse_message'] = '无效举报--商品会正常销售';
$lang['inform_handle_type_venom_message'] = '恶意举报--该用户的所有未处理举报将被取消，用户将被禁止举报';
$lang['inform_handle_type_valid_message'] = '有效举报--商品将被违规下架';
$lang['inform_subject_add'] = 'Add titile';
$lang['inform_type_add'] = 'Add type';

$lang['inform_text_none'] = 'None';
$lang['inform_text_handle'] = 'Process';
$lang['inform_text_select'] = 'Select...';

/**
 * 提示信息
 */
$lang['inform_content_null'] = '举报内容不能为空且不能大于100个字符';
$lang['inform_subject_add_null'] = '举报主题不能为空且不能大于100个字符';
$lang['inform_handle_message_null'] = '处理信息不能为空且不能大于100个字符';
$lang['inform_type_null'] = '举报类型不能为空且不能大于50个字符';
$lang['inform_type_desc_null'] = '举报类型描述不能为空且不能大于100个字符';
$lang['inform_handle_confirm'] = '确认处理该举报?';
$lang['inform_type_delete_confirm'] = '确认删除举报分类，该分类下的主题也将被删除?';
$lang['confirm_delete'] = '确认删除?';
$lang['inform_pic_error'] = '图片只能是jpg格式';
$lang['inform_handling'] = '该商品已经被举报请等待处理';
$lang['inform_type_error'] = '举报类型不存在请联系平台管理员添加类型';
$lang['inform_subject_null'] = '举报主题不存在请联系平台管理员';
$lang['inform_success'] = '举报成功请等待处理';
$lang['inform_fail'] = '举报失败请联系管理员';
$lang['doctors_null'] = '商品不存在';
$lang['deny_inform'] = '您已经被禁止举报商品，如有疑问请联系平台管理员'; 
$lang['inform_help1']='举报类型和举报主题由管理员在后台设置，在商品信息页会员可根据举报主题举报违规商品';
$lang['inform_help2']='点击详细，查看举报内容';
$lang['inform_help3']='查看已处理举报内容';
$lang['inform_help4']='可在同一举报类型下添加多个举报主题';
$lang['inform_help5']='会员可根据举报主题，举报违规商品';

?>
