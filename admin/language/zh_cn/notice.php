<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * index
 */
$lang['notice_index_member_list_null']	= 'Member list is required';
$lang['notice_index_clic_grade_null']	= 'Clinic level is required';
$lang['notice_index_title_null']		= 'Notice title is required';
$lang['notice_index_content_null']		= 'Notice content is required';
$lang['notice_index_batch_int']			= 'The number of batches must be digital';
$lang['notice_index_member_error']		= 'Member information is wrong, please try again';
$lang['notice_index_sending']			= 'Sending';
$lang['notice_index_send_succ']			= 'Sent';
$lang['notice_index_member_notice']		= 'Member Notice';
$lang['notice_index_send']				= 'Send notification';
$lang['notice_index_send_type']			= 'Type of send';
$lang['notice_index_spec_member']		= 'Select Member';
$lang['notice_index_all_member']		= 'All member';
$lang['notice_index_smtp_incomplate']	= 'SMTP information settings are incomplete';
$lang['notice_index_smtp_close']		= 'Email feature close';
$lang['notice_index_spec_clic_grade']	= 'Select Clinic level';
$lang['notice_index_all_clic']			= 'All clinic';
$lang['notice_index_member_list']		= 'Member list';
$lang['notice_index_member_tip']		= 'Fill in one member name per line';
$lang['notice_index_clic_grade']		= 'Clinic level';
$lang['notice_index_clic_tip']			= ' press Ctrl button to select multiple options';
$lang['notice_index_batch']				= 'Number of batches sent';
$lang['notice_index_batch_tip']			= 'The number of notifications sent per batch. If there are too many, the program may terminate execution due to timeout. Therefore it is recommended not to exceed';
$lang['notice_index_send_method']		= 'Sending method';
$lang['notice_index_message']			= 'Send Message';
$lang['notice_index_email']				= 'Send email';
$lang['notice_index_title']				= 'Notification title';
$lang['notice_index_content']			= 'Notification content';
$lang['notice_index_member_error']		= 'Send by select member, the member name cannot be empty and one member name per line';
$lang['notice_index_help1']				= 'Send in batches, one notification operation is automatically divided into multiple batches, you can set the number of notifications sent in each batch';