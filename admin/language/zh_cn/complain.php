<?php
defined('InclinicNC') or exit('Access Invalid!');

/**
 * 导航菜单
 */
$lang['complain_new_list'] = 'New complain';
$lang['complain_handle_list'] = 'Pending';
$lang['complain_appeal_list'] = 'Appeal list';
$lang['complain_talk_list'] = 'Talking';
$lang['complain_finish_list'] = 'Finished';
$lang['complain_subject_list'] = 'Complain title';

/**
 * 导航菜单
 */
$lang['complain_manage_title'] = 'Complain management';
$lang['complain_submit'] = 'Complain process';
$lang['complain_setting'] = 'Compalin setting';

$lang['complain_state_new'] = 'New complaon';
$lang['complain_state_handle'] = 'Pending';
$lang['complain_state_appeal'] = 'Appeal list';
$lang['complain_state_talk'] = 'Talking';
$lang['complain_state_finish'] = 'Finished';
$lang['complain_subject_list'] = 'Complain title';

$lang['complain_pic'] = 'Image';
$lang['complain_pic_view'] = 'Image view';
$lang['complain_pic_none'] = 'None';
$lang['complain_detail'] = 'Complain detail';
$lang['complain_message'] = 'Compalin message';
$lang['complain_evidence'] = 'Compalin evidence';
$lang['complain_evidence_upload'] = 'Upload evidence';
$lang['complain_content'] = 'Complain cintent';
$lang['complain_accuser'] = 'Complainant';
$lang['complain_accused'] = 'Complained Clinic';
$lang['complain_admin'] = 'Admin';
$lang['complain_unknow'] = 'Unknow';
$lang['complain_datetime'] = 'Complain time';
$lang['complain_doctors'] = '投诉的商品';
$lang['complain_doctors_name'] = 'Doctor name';
$lang['complain_state'] = 'Complain status';
$lang['complain_progress'] = 'Complain progress';
$lang['complain_handle'] = 'Compalain handle';
$lang['complain_subject_content'] = 'Complain content';
$lang['complain_subject_select'] = 'Select Complain title';
$lang['complain_subject_desc'] = 'Complain title description';
$lang['complain_subject_add'] = 'Add type';
$lang['complain_appeal_detail'] = 'Complain deatail';
$lang['complain_appeal_message'] = 'Complain message';
$lang['complain_appeal_content'] = 'Complain content';
$lang['complain_appeal_datetime'] = 'Complain time';
$lang['complain_appeal_evidence'] = 'Complain evidence';
$lang['complain_appeal_evidence_upload'] = 'Upload Complain evidence';
$lang['complain_state_inprogress'] = 'Inprogress';
$lang['complain_state_finish'] = 'Finished';
$lang['final_handle_detail'] = 'Preocess detail';
$lang['final_handle_message'] = 'Result';
$lang['final_handle_datetime'] = 'Process time';
$lang['appointment_detail'] = '订单详情';
$lang['appointment_message'] = '订单信息';
$lang['appointment_state'] = '订单状态';
$lang['appointment_sn'] = '订单号';
$lang['appointment_datetime'] = '下单时间';
$lang['appointment_price'] = '订单总额';
$lang['appointment_discount'] = '优惠打折';
$lang['appointment_voucher_price'] = '使用的代金券面额';
$lang['appointment_voucher_sn'] = '代金券编码';
$lang['appointment_buyer_message'] = '买家信息';
$lang['appointment_clinicer_message'] = '店铺信息';
$lang['appointment_clinic_name'] = '店铺名称';
$lang['appointment_buyer_name'] = '买家名称';
$lang['appointment_state_cancel'] = '已取消';
$lang['appointment_state_unpay'] = '未付款';
$lang['appointment_state_payed'] = '已付款';
$lang['appointment_state_send'] = '已发货';
$lang['appointment_state_receive'] = '已收货';
$lang['appointment_state_commit'] = '已提交';
$lang['appointment_state_verify'] = '已确认';
$lang['complain_time_limit'] = '投诉时效';
$lang['complain_time_limit_desc'] = '单位为天，订单完成后开始计算，多少天内可以发起投诉';

$lang['refund_message']	= '退款信息';
$lang['refund_appointment_refund']	= '已确认退款金额';

/**
 * 提示信息
 */
$lang['confirm_delete'] = '您确定要删除吗?';
$lang['complain_content_error'] = '投诉内容不能为空且必须小于100个字符';
$lang['appeal_message_error'] = '投诉内容不能为空且必须小于100个字符';
$lang['complain_pic_error'] = '图片必须是jpg格式';
$lang['complain_time_limit_error'] = '投诉时效不能为空且必须为数字';
$lang['complain_subject_content_error'] = '投诉主题不能为空且必须小于50个字符';
$lang['complain_subject_desc_error'] = '投诉主题描述不能为空且必须小于100个字符';
$lang['complain_subject_type_error'] = '未知投诉主题类型';
$lang['complain_subject_add_success'] = '投诉主题添加成功';
$lang['complain_subject_add_fail'] = '投诉主题添加失败';
$lang['complain_subject_delete_success'] = '投诉主题删除成功';
$lang['complain_subject_delete_fail'] = '投诉主题删除失败';
$lang['complain_setting_save_success'] = '投诉设置保存成功';
$lang['complain_setting_save_fail'] = '投诉设置保存失败';
$lang['complain_doctors_select'] = '选择要投诉的商品';
$lang['complain_submit_success'] = '投诉提交成功';
$lang['complain_close_confirm'] = '确认关闭此投诉?';
$lang['appeal_submit_success'] = '申诉提交成功';
$lang['talk_detail'] = '对话详情';
$lang['talk_null'] = '对话不能为空';
$lang['talk_none'] = '目前没有对话';
$lang['talk_list'] = '对话记录';
$lang['talk_send'] = '发布对话';
$lang['talk_refresh'] = '刷新对话';
$lang['talk_send_success'] = '对话发送成功';
$lang['talk_send_fail'] = '对话发送失败';
$lang['talk_forbit_success'] = '对话屏蔽成功';
$lang['talk_forbit_fail'] = '对话屏蔽失败';
$lang['complain_verify_success'] = '投诉审核成功';
$lang['complain_verify_fail'] = '投诉审核失败';
$lang['complain_close_success'] = '投诉关闭成功';
$lang['complain_close_fail'] = '投诉关闭失败';
$lang['talk_forbit_message'] =  '<该对话被管理员屏蔽>';
$lang['final_handle_message_error'] = '处理意见不能为空且必须小于255个字符';
$lang['final_handle_message'] = '处理意见';
$lang['handle_submit'] = '提交仲裁';
$lang['complain_repeat'] = '您已经投诉了该订单请等待处理';
$lang['verify_submit_message'] = '确认审核此投诉';


/**
 * 文本
 */
$lang['complain_text_select'] = '请选择...';
$lang['complain_text_handle'] = '操作';
$lang['complain_text_detail'] = '详细';
$lang['complain_text_submit'] = '提交';
$lang['complain_text_pic'] = '图片';
$lang['complain_text_num'] = '数量';
$lang['complain_text_price'] = '价格';
$lang['complain_text_problem'] = '问题描述';
$lang['complain_text_say'] = '说';
$lang['complain_text_verify'] = '审核';
$lang['complain_text_close'] = '关闭投诉';
$lang['complain_text_forbit'] = '屏蔽';
$lang['complain_help1']='在投诉时效内，买家可对订单进行投诉，投诉主题由管理员在后台统一设置';
$lang['complain_help2']='投诉时效可在系统设置处进行设置';
$lang['complain_help3']='点击详细，可进行投诉审核。审核完成后，被投诉店铺可进行申诉。申诉成功后，投诉双方进行对话，最后由后台管理员进行仲裁操作';