<?php
/**
 * 控制台
 *
 * 
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class dashboardControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('dashboard');
	}
	/**
	 * Welcome页面
	 */
	public function welcomeOp(){
		/**
		 * 管理员信息
		 */
		$model_admin = Model('admin');
		$tmp = $this->getAdminInfo();
		$condition['admin_id'] = $tmp['id'];
		$admin_info = $model_admin->infoAdmin($condition);
		$admin_info['admin_login_time'] = date('Y-m-d H:i:s',($admin_info['admin_login_time'] == '' ? time() : $admin_info['admin_login_time']));
		/**
		 * 系统信息
		 */
		$version = C('version');
		$setup_date = C('setup_date');
		$statistics['os'] = PHP_OS;
		$statistics['web_server'] = $_SERVER['SERVER_SOFTWARE'];
		$statistics['php_version'] = PHP_VERSION;
		$statistics['sql_version'] = Db::getServerInfo();
		$statistics['clinic_version'] = $version;
		$statistics['setup_date'] = substr($setup_date,0,10);
		Tpl::output('statistics',$statistics);
		Tpl::output('admin_info',$admin_info);
		Tpl::showpage('welcome');
	}
	
	/**
	 * 关于我们
	 */
	public function aboutusOp(){
		
		Tpl::showpage('aboutus');
	}
	
	/**
	 * 统计
	 */
	public function statisticsOp(){
        $statistics = array();
        // 本周开始时间点
        $tmp_time = mktime(0,0,0,date('m'),date('d'),date('Y'))-(date('w')==0?7:date('w')-1)*24*60*60;
        /**
         * 会员
         */
        $model_member = Model('member');
        // 会员总数
        $statistics['member'] = $model_member->getMemberCount(array());
        // 新增会员数
        $statistics['week_add_member'] = $model_member->getMemberCount(array('member_time' => array('egt', $tmp_time)));
        // 预存款提现
        $statistics['cashlist'] = Model('predeposit')->getPdCashCount(array('pdc_payment_state'=>0));

        /**
         * 店铺
         */
        $model_clic = Model('clic');
        // 店铺总数
        $statistics['clic'] = Model('clic')->getclicCount(array());
        // 店铺申请数
        $statistics['clic_joinin'] = Model('clic_joinin')->getclicJoininCount(array('joinin_state' => array('in', array(10, 11))));
        // 即将到期
        $statistics['clic_expire'] = $model_clic->getclicCount(array('clic_state'=>1, 'clic_end_time'=>array('between', array(TIMESTAMP, TIMESTAMP + 864000))));
        // 已经到期
        $statistics['clic_expired'] = $model_clic->getclicCount(array('clic_state'=>1, 'clic_end_time'=>array('between', array(1, TIMESTAMP))));
        
        /**
         * 商品
         */
        $model_doctors = Model('doctors');
        // 商品总数
        $statistics['doctors'] = $model_doctors->getdoctorsCommonCount(array());
        // 新增商品数
        $statistics['week_add_doc'] = $model_doctors->getdoctorsCommonCount(array('doctors_addtime' => array('egt', $tmp_time)));
        // 等待审核
        $statistics['doc_verify'] = $model_doctors->getdoctorsCommonWaitVerifyCount(array());
        // 举报
        $statistics['inform_list'] = Model('inform')->getInformCount(array('inform_state'=>1));
        // 品牌申请
        $statistics['brand_apply'] = Model('brand')->getBrandCount(array('brand_apply' => '0'));
		
        /**
         * 交易
         */
        $model_appointment = Model('appointment');
        $model_refund_return = Model('refund_return');
        $model_complain = Model('complain');
        // 订单总数
        $statistics['appointment'] = $model_appointment->getappointmentCount(array());
        // 退款
        $statistics['refund'] = $model_refund_return->getRefundReturn(array('refund_type' => 1, 'refund_state' => 2));
        // 退货
        $statistics['return'] = $model_refund_return->getRefundReturn(array('refund_type' => 2, 'refund_state' => 2));
        // 投诉
        $statistics['complain_new_list'] = $model_complain->getComplainCount(array('complain_state'=>10));
        // 带仲裁
		$statistics['complain_handle_list'] = $model_complain->getComplainCount(array('complain_state'=>40));

		/**
         * 运营
		 */
		// 团购数量
		$statistics['groupbuy_verify_list'] = Model('groupbuy')->getGroupbuyCount(array('state'=>10));
		// 积分订单
		$statistics['points_appointment'] = Model()->cls()->table('points_appointment')->where(array('point_appointmentstate'=>array('in',array(11,20))))->count();
		//待审核账单
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_state'] = BILL_STATE_clic_COFIRM;
		$statistics['check_billno'] = $model_bill->getappointmentBillCount($condition);
		//待支付账单
		$condition = array();
		$condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
		$statistics['pay_billno'] = $model_bill->getappointmentBillCount($condition);		
        /**
         * CMS
         */
        if (C('cms_isuse')) {
            // 文章审核
            $statistics['cms_article_verify'] = Model('cms_article')->getCmsArticleCount(array('article_state' => 2));
            // 画报审核
            $statistics['cms_picture_verify'] = Model('cms_picture')->getCmsPictureCount(array('picture_state' => 2));
        }
        /**
         * 圈子
         */
        if (C('circle_isuse')) {
            $statistics['circle_verify'] = Model('circle')->getCircleUnverifiedCount();
        }
        echo json_encode($statistics);
		exit;
	}
}
