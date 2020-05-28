<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 导出语言包，只有在执行导出行为时，才会调用
 */

//品牌
$lang['exp_brandid']		= 'Brand Id';
$lang['exp_brand']			= 'Brand';
$lang['exp_brand_cate']		= 'Type';
$lang['exp_brand_img']		= 'Brand image';

//商品
$lang['exp_product']		= 'Doctor';
$lang['exp_pr_cate']		= 'Type';
$lang['exp_pr_brand']		= 'Brand';
$lang['exp_pr_price']		= 'Time';
$lang['exp_pr_serial']		= 'Date';
$lang['exp_pr_state']		= 'Status';
$lang['exp_pr_type']		= 'Type';
$lang['exp_pr_addtime']		= 'Available time';
$lang['exp_pr_store']		= 'Clinic';
$lang['exp_pr_storeid']		= 'Clinic id';
$lang['exp_pr_wgxj']		= '违规下架';
$lang['exp_pr_sj']			= '上架';
$lang['exp_pr_xj']			= '下架';
$lang['exp_pr_new']			= '全新';
$lang['exp_pr_old']			= '二手';

//类型
$lang['exp_type_name']		= 'Type';

//规格
$lang['exp_spec']			= 'Standard';
$lang['exp_sp_content']		= 'Standard content';

//店铺
$lang['exp_store']			= 'Clinic';
$lang['exp_st_name']		= 'Clinic Account';
$lang['exp_st_sarea']		= 'Address';
$lang['exp_st_grade']		= 'Level';
$lang['exp_st_adtime']		= 'Trading time';
$lang['exp_st_yxq']			= '有效期';
$lang['exp_st_state']		= 'Status';
$lang['exp_st_xarea']		= 'Address';
$lang['exp_st_post']		= 'Postcode';
$lang['exp_st_tel']			= 'Contact number';
$lang['exp_st_kq']			= 'Open';
$lang['exp_st_shz']			= 'Pending';
$lang['exp_st_close']		= 'Close';

//会员
$lang['exp_member']			= 'Member';
$lang['exp_mb_name']		= 'Name';
$lang['exp_mb_jf']			= 'Point';
$lang['exp_mb_yck']			= '预存款';
$lang['exp_mb_jbs']			= '金币数';
$lang['exp_mb_sex']			= 'gender';
$lang['exp_mb_ww']			= '旺旺';
$lang['exp_mb_dcs']			= 'Logintimes';
$lang['exp_mb_rtime']		= 'Registration time';
$lang['exp_mb_ltime']		= 'Last login';
$lang['exp_mb_storeid']		= 'Clinic Id';
$lang['exp_mb_nan']			= 'Male';
$lang['exp_mb_nv']			= 'Female';

//积分明细
$lang['exp_pi_member']		= '会员';
$lang['exp_pi_system']		= '管理员';
$lang['exp_pi_point']		= '积分值';
$lang['exp_pi_time']		= '发生时间';
$lang['exp_pi_jd']			= '操作阶段';
$lang['exp_pi_ms']			= '描述';
$lang['exp_pi_jfmx']		= '积分明细';

//预存款充值
$lang['exp_yc_no']			= '充值编号';
$lang['exp_yc_member']		= '会员名';
$lang['exp_yc_money']		= '充值金额';
$lang['exp_yc_pay']			= '支付方式';
$lang['exp_yc_ctime']		= '创建时间';
$lang['exp_yc_ptime']		= '付款时间';
$lang['exp_yc_paystate']	= '支付状态';
$lang['exp_yc_memberid']	= '会员ID';
$lang['exp_yc_yckcz']		= '预存款充值';

//预存款提现
$lang['exp_tx_no']			= '提现编号';
$lang['exp_tx_member']		= '会员名';
$lang['exp_tx_money']		= '提现金额';
$lang['exp_tx_type']		= '提现方式';
$lang['exp_tx_ctime']		= '申请时间';
$lang['exp_tx_state']		= '提现状态';
$lang['exp_tx_memberid']	= '会员ID';
$lang['exp_tx_title']		= '预存款提现';

//预存款明细
$lang['exp_mx_member']		= '会员';
$lang['exp_mx_ctime']		= '变更时间';
$lang['exp_mx_money']		= '金额';
$lang['exp_mx_av_money']	= '可用金额';
$lang['exp_mx_freeze_money']= '冻结金额';
$lang['exp_mx_type']		= '金额类型';
$lang['exp_mx_system']		= '管理员';
$lang['exp_mx_stype']		= '事件类型';
$lang['exp_mx_mshu']		= '描述';
$lang['exp_mx_rz']			= '预存款变更日志';

//订单
$lang['exp_od_no']			= '订单号';
$lang['exp_od_store']		= '店铺';
$lang['exp_od_buyer']		= '买家';
$lang['exp_od_xtimd']		= '下单时间';
$lang['exp_od_count']		= '订单总额';
$lang['exp_od_yfei']		= '运费';
$lang['exp_od_paytype']		= '支付方式';
$lang['exp_od_state']		= '订单状态';
$lang['exp_od_storeid']		= '店铺ID';
$lang['exp_od_selerid']		= '卖家ID';
$lang['exp_od_buyerid']		= '买家ID';
$lang['exp_od_bemail']		= '买家Email';
$lang['exp_od_sta_qx']		= '已取消';
$lang['exp_od_sta_dfk']		= '待付款';
$lang['exp_od_sta_dqr']		= '已付款、待确认';
$lang['exp_od_sta_yfk']		= '已付款';
$lang['exp_od_sta_yfh']		= '已发货';
$lang['exp_od_sta_yjs']		= '已结算';
$lang['exp_od_sta_dsh']		= '待审核';
$lang['exp_od_sta_yqr']		= '已确认';
$lang['exp_od_order']		= '订单';

//金币购买记录
$lang['exp_jbg_member']		= '会员名';
$lang['exp_jbg_store']		= '店铺';
$lang['exp_jbg_jbs']		= '购买金币数';
$lang['exp_jbg_money']		= '所需金额';
$lang['exp_jbg_gtime']		= '购买时间';
$lang['exp_jbg_paytype']	= '支付方式';
$lang['exp_jbg_paystate']	= '支付状态';
$lang['exp_jbg_storeid']	= '店铺ID';
$lang['exp_jbg_memberid']	= '会员ID';
$lang['exp_jbg_wpay']		= '未支付';
$lang['exp_jbg_ypay']		= '已支付';
$lang['exp_jbg_jbgm']		= '金币购买';

//金币日志
$lang['exp_jb_member']		= '会员';
$lang['exp_jb_store']		= '店铺';
$lang['exp_jb_jbs']			= '金币数';
$lang['exp_jb_type']		= '变更类型';
$lang['exp_jb_btime']		= '变更时间';
$lang['exp_jb_mshu']		= '描述';
$lang['exp_jb_storeid']		= '店铺ID';
$lang['exp_jb_memberid']	= '会员ID';
$lang['exp_jb_add']			= '增加';
$lang['exp_jb_del']			= '减少';
$lang['exp_jb_log']			= '金币日志';


?>