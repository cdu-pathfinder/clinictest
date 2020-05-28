<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 预存款功能公用
 */
$lang['predeposit_no_record']	 			= 'No record of eligibility';
$lang['predeposit_unavailable']	 			= 'The pre-deposit function is not enabled in the system';
$lang['predeposit_parameter_error']			= 'Parameter error';
$lang['predeposit_record_error']			= 'Recording error';
$lang['predeposit_userrecord_error']		= 'Member information error';
$lang['predeposit_payment']					= 'Method of payment';
$lang['predeposit_addtime']					= 'predeposit_addtime';
$lang['predeposit_apptime']					= 'apply time';
$lang['predeposit_checktime']					= 'checktime';
$lang['predeposit_paytime']					= 'paytime';
$lang['predeposit_addtime_to']				= 'to';
$lang['predeposit_trade_no']				= 'trade No.';
$lang['predeposit_adminremark']				= 'adminremark';
$lang['predeposit_recordstate']				= 'recordstate';
$lang['predeposit_paystate']				= 'paystate';
$lang['predeposit_backlist']				= 'backlist';
$lang['predeposit_pricetype']				= 'predeposit type';
$lang['predeposit_pricetype_available']		= 'predeposit available';
$lang['predeposit_pricetype_freeze']		= 'pricet freeze';
$lang['predeposit_price']					= 'predeposit';
$lang['predeposit_payment_error']			= 'payment error';
/**
 * 充值功能公用
 */
$lang['predeposit_rechargesn']					= 'recharge No.';
$lang['predeposit_rechargewaitpaying']			= 'wait to pay';
$lang['predeposit_rechargepaysuccess']			= 'paied';
$lang['predeposit_rechargestate_auditing']		= 'auditing';
$lang['predeposit_rechargestate_completed']		= 'completed';
$lang['predeposit_rechargestate_closed']		= 'closed';
$lang['predeposit_recharge_price']				= 'recharge price';
$lang['predeposit_recharge_huikuanname']		= 'name';
$lang['predeposit_recharge_huikuanbank']		= 'bank';
$lang['predeposit_recharge_huikuandate']		= 'date';
$lang['predeposit_recharge_memberremark']		= 'remark';
$lang['predeposit_recharge_success']			= 'successful';
$lang['predeposit_recharge_fail']				= 'faild';
$lang['predeposit_recharge_pay']				= 'pay';
$lang['predeposit_recharge_view']				= 'view details';
$lang['predeposit_recharge_paydesc']			= 'predeposit recharge No.';
$lang['predeposit_recharge_pay_offline']		= 'to confirm';
/**
 * 充值添加
 */
$lang['predeposit_recharge_add_pricenull_error']			= 'Please add the recharge amount';
$lang['predeposit_recharge_add_pricemin_error']				= 'The number of recharged amount is greater than or equal to 0.01';
/**
 * 充值信息删除
 */
$lang['predeposit_recharge_del_success']		= 'The recharge information was deleted successfully';
$lang['predeposit_recharge_del_fail']		= 'Failed to delete the recharge information';
/**
 * 提现功能公用
 */
$lang['predeposit_cashsn']				= 'cash No.';
$lang['predeposit_cashmanage']			= 'cash manage';
$lang['predeposit_cashwaitpaying']		= 'wait to pay';
$lang['predeposit_cashpaysuccess']		= 'successfully';
$lang['predeposit_cashstate_auditing']	= 'aduiting';
$lang['predeposit_cashstate_completed']	= 'completed';
$lang['predeposit_cashstate_closed']		= 'closed';
$lang['predeposit_cash_price']				= 'cash price';
$lang['predeposit_cash_shoukuanname']			= 'name';
$lang['predeposit_cash_shoukuanbank']			= 'bank';
$lang['predeposit_cash_shoukuanaccount']		= 'account';
$lang['predeposit_cash_shoukuanname_tip']	= 'It is strongly recommended to give priority to large Banks';
$lang['predeposit_cash_shoukuanaccount_tip']	= 'Bank account';
$lang['predeposit_cash_shoukuanauser_tip']	= 'The name of the person who opened the account';
$lang['predeposit_cash_shortprice_error']		= 'The amount of predeposit is insufficient';
$lang['predeposit_cash_price_tip']				= 'Currently available amount';

$lang['predeposit_cash_availablereducedesc']	=  '会员申请提现减少预存款金额';
$lang['predeposit_cash_freezeadddesc']	=  '会员申请提现增加冻结预存款金额';
$lang['predeposit_cash_availableadddesc']	=  '会员删除提现增加预存款金额';
$lang['predeposit_cash_freezereducedesc']	=  '会员删除提现减少冻结预存款金额';

/**
 * 提现添加
 */
$lang['predeposit_cash_add_shoukuannamenull_error']		= 'Please fill in the name of the payee';
$lang['predeposit_cash_add_shoukuanbanknull_error']		= 'Please fill in the receiving bank';
$lang['predeposit_cash_add_pricemin_error']				= 'The number of cash amount is greater than or equal to 0.01';
$lang['predeposit_cash_add_enough_error']				= 'Insufficient account balance';
$lang['predeposit_cash_add_pricenull_error']			= 'Please fill in the cash amount';
$lang['predeposit_cash_add_shoukuanaccountnull_error']	= 'Please fill in the account number';
$lang['predeposit_cash_add_success']					= 'Your cash application has been successfully submitted, please wait for the system to process';
$lang['predeposit_cash_add_fail']						= 'Failed to add cash information';
/**
 * 提现信息删除
 */
$lang['predeposit_cash_del_success']	= 'The cash information was deleted successfully';
$lang['predeposit_cash_del_fail']		= 'cash information deletion failed';
/**
 * 支付接口
 */
$lang['predeposit_payment_pay_fail']		= 'recharge failure';
$lang['predeposit_payment_pay_success']		= 'recharge is successful, is heading to my order';
$lang['predepositrechargedesc']	=  'recharge';
/**
 * 出入明细 
 */
$lang['predeposit_log_stage'] 			= 'type';
$lang['predeposit_log_stage_recharge']	= 'recharge';
$lang['predeposit_log_stage_cash']		= 'cash';
$lang['predeposit_log_stage_order']		= 'order';
$lang['predeposit_log_stage_artificial']= 'Manually modify';
$lang['predeposit_log_stage_system']	= 'system';
$lang['predeposit_log_stage_income']	= 'income';
$lang['predeposit_log_desc']			= 'Change description';
?>