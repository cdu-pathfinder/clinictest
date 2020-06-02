<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 交易管理 语言包
 */

//订单管理
$lang['order_manage']              = 'order manage';
$lang['order_help1']			   = 'Click the view action to display the details of the order';
$lang['order_help2']			   = 'Click the cancel operation to cancel the order (online payment but unpaid orders and delivery orders not confirmed)';
$lang['order_help3']			   = 'If the platform has confirmed receipt of the payment, but the payment status of the system has not changed, you can click the payment received operation and fill in the relevant information to change the payment status of the order';
$lang['manage']                    = 'manage';
$lang['store_name']                = 'clinic';
$lang['buyer_name']                = 'patient';
$lang['payment']                   = 'payment';
$lang['order_number']              = 'order No.';
$lang['order_state']               = 'order state';
$lang['order_state_new']           = 'to pay';
$lang['order_state_pay']           = 'to confirm';
$lang['order_state_send']          = 'to service';
$lang['order_state_success']       = 'finish';
$lang['order_state_cancel']        = 'cancel';
$lang['type']					   = 'type';
$lang['pended_payment']            = 'Submitted, to be confirmed';//增加
$lang['order_time_from']           = 'time';
$lang['order_price_from']          = 'price';
$lang['cancel_search']             = '撤销检索';
$lang['order_time']                = 'order time';
$lang['order_total_price']         = 'total price';
$lang['order_total_transport']     = '运费';
$lang['miss_order_number']         = 'need order No.';

$lang['order_state_paid'] = 'paid';
$lang['order_admin_operator'] = 'admin operator';
$lang['order_state_null'] = 'null';
$lang['order_handle_history']	= 'handle history';
$lang['order_admin_cancel'] = '未付款，系统管理员取消订单。';
$lang['order_admin_pay'] = '系统管理员确认收款完成。';
$lang['order_confirm_cancel']	= '您确实要取消该订单吗？';
$lang['order_confirm_received']	= '您确定已经收到货款了吗？';
$lang['order_change_cancel']	= 'cancel';
$lang['order_change_received']	= '收到货款';
$lang['order_log_cancel']	= 'cancel order';

//订单详情
$lang['order_detail']              = 'rder detail';
$lang['offer']                     = '优惠了';
$lang['order_info']                = 'rder info';
$lang['seller_name']               = 'clinic seller';
$lang['pay_message']               = 'pay message';
$lang['payment_time']              = 'payment time';
$lang['ship_time']                 = '发货时间';
$lang['complate_time']             = '完成时间';
$lang['buyer_message']             = '买家附言';
$lang['consignee_ship_order_info'] = '收货人及发货信息';
$lang['consignee_name']            = '收货人姓名';
$lang['region']                    = '所在地区';
$lang['zip']                       = '邮政编码';
$lang['tel_phone']                 = 'tel_phone';
$lang['mob_phone']                 = 'mob_phone';
$lang['address']                   = 'address';
$lang['ship_method']               = '配送方式';
$lang['ship_code']                 = '发货单号';
$lang['product_info']              = '商品信息';
$lang['product_type']              = '促销';
$lang['product_price']             = 'price';
$lang['product_num']               = 'number';
$lang['product_shipping_mfee']     = '免运费';
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
$lang['pay_bank_order']			= 'Remittance No.';

$lang['order_refund']			= 'refund';
$lang['order_return']			= 'reback';

$lang['order_show_system']				= 'system';
$lang['order_show_at']				= 'at';
$lang['order_show_cur_state']			= 'state';
$lang['order_show_next_state']		= 'next state';
$lang['order_show_reason']			= 'reason';