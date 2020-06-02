<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * 交易管理 语言包
 */

//订单管理
$lang['appointment_manage']              = 'appointment manage';
$lang['appointment_help1']			   = 'Click the view action to display the details of the appointment';
$lang['appointment_help2']			   = 'Click the cancel operation to cancel the appointment (online payment but unpaid appointments and delivery appointments not confirmed)';
$lang['appointment_help3']			   = 'If the platform has confirmed receipt of the payment, but the payment status of the system has not changed, you can click the payment received operation and fill in the relevant information to change the payment status of the appointment';
$lang['manage']                    = 'manage';
$lang['clic_name']                = 'clinic';
$lang['buyer_name']                = 'patient';
$lang['payment']                   = 'payment';
$lang['appointment_number']              = 'appointment No.';
$lang['appointment_state']               = 'appointment state';
$lang['appointment_state_new']           = 'to pay';
$lang['appointment_state_pay']           = 'to confirm';
$lang['appointment_state_send']          = 'to service';
$lang['appointment_state_success']       = 'finish';
$lang['appointment_state_cancel']        = 'cancel';
$lang['type']					   = 'type';
$lang['pended_payment']            = 'Submitted, to be confirmed';//增加
$lang['appointment_time_from']           = 'time';
$lang['appointment_price_from']          = 'price';
$lang['cancel_search']             = '撤销检索';
$lang['appointment_time']                = 'appointment time';
$lang['appointment_total_price']         = 'total price';
$lang['appointment_total_transport']     = '运费';
$lang['miss_appointment_number']         = 'need appointment No.';

$lang['appointment_state_paid'] = 'paid';
$lang['appointment_admin_operator'] = 'admin operator';
$lang['appointment_state_null'] = 'null';
$lang['appointment_handle_history']	= 'handle history';
$lang['appointment_admin_cancel'] = '未付款，系统管理员取消订单。';
$lang['appointment_admin_pay'] = '系统管理员确认收款完成。';
$lang['appointment_confirm_cancel']	= '您确实要取消该订单吗？';
$lang['appointment_confirm_received']	= '您确定已经收到货款了吗？';
$lang['appointment_change_cancel']	= 'cancel';
$lang['appointment_change_received']	= '收到货款';
$lang['appointment_log_cancel']	= 'cancel appointment';

//订单详情
$lang['appointment_detail']              = 'rder detail';
$lang['offer']                     = '优惠了';
$lang['appointment_info']                = 'rder info';
$lang['clinicer_name']               = 'clinic clinicer';
$lang['pay_message']               = 'pay message';
$lang['payment_time']              = 'payment time';
$lang['ship_time']                 = '发货时间';
$lang['complate_time']             = '完成时间';
$lang['buyer_message']             = '买家附言';
$lang['consignee_ship_appointment_info'] = '收货人及发货信息';
$lang['consignee_name']            = '收货人姓名';
$lang['region']                    = '所在地区';
$lang['zip']                       = '邮政编码';
$lang['tel_phone']                 = 'tel_phone';
$lang['mob_phone']                 = 'mob_phone';
$lang['address']                   = 'address';
$lang['ship_method']               = '配送方式';
$lang['ship_code']                 = '发货单号';
$lang['doc_info']              = '商品信息';
$lang['doc_type']              = '促销';
$lang['doc_price']             = 'price';
$lang['doc_num']               = 'number';
$lang['doc_shipping_mfee']     = '免运费';
$lang['nc_promotion']				= '促销活动';
$lang['nc_groupbuy_flag']			= '团';
$lang['nc_groupbuy']				= '团购活动';
$lang['nc_groupbuy_view']			= '查看';
$lang['nc_mansong_flag']			= '满';
$lang['nc_mansong']					= '满即送';
$lang['nc_xianshi_flag']			= '折';
$lang['nc_xianshi']					= '限时折扣';
$lang['nc_bundling_flag']			= '组';
$lang['nc_bundling']				= '优惠套装';


$lang['pay_bank_user']			= 'remitter';
$lang['pay_bank_bank']			= 'bank';
$lang['pay_bank_account']		= 'account';
$lang['pay_bank_num']			= 'remittance amount';
$lang['pay_bank_date']			= 'Remittance date';
$lang['pay_bank_extend']		= 'other';
$lang['pay_bank_appointment']			= 'Remittance No.';

$lang['appointment_refund']			= 'refund';
$lang['appointment_return']			= 'reback';

$lang['appointment_show_system']				= 'system';
$lang['appointment_show_at']				= 'at';
$lang['appointment_show_cur_state']			= 'state';
$lang['appointment_show_next_state']		= 'next state';
$lang['appointment_show_reason']			= 'reason';