<?php
/**
 * 商户中心
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class clinicer_centerControl extends BaseclinicerControl {

    /**
     * 构造方法
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 商户中心首页
     *
     */
    public function indexOp() {
		Language::read('member_home_index');
        // 店铺信息
        $clic_info = $this->clic_info;
        if(intval($clic_info['clic_end_time']) > 0) {
            $clic_info['clic_end_time_text']	= date('Y-m-d', $clic_info['clic_end_time']);
        } else {
            $clic_info['clic_end_time_text'] = L('clic_no_limit');
        }

        // 店铺等级信息
        $clic_info['grade_name'] = $this->clic_grade['sg_name'];
        $clic_info['grade_doctorslimit'] = $this->clic_grade['sg_doctors_limit'];
        $clic_info['grade_albumlimit'] = $this->clic_grade['sg_album_limit'];

        Tpl::output('clic_info',$clic_info);
        // 文章分类
        $article_class_info = Model('article_class')->getOneClass(3);
        Tpl::output('article_class_info', $article_class_info);
        // 文章列表
        $model_article	= Model('article');
        $condition	= array();
        $condition['article_show'] = '1';
        $condition['ac_id'] = '3';
        $condition['appointment'] = 'article_sort asc,article_time desc';
        $condition['limit'] = '5';
        $show_article	= $model_article->getArticleList($condition);
        Tpl::output('show_article',$show_article);

        // 销售情况统计
        $model_appointment = Model('appointment');
        $field = 'count(appointment_id) as count, sum(appointment_amount) sum';
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        // 日销量
        $condition['add_time'] = array('gt', strtotime(date('Y-m-d')));
        $daily_sales = $model_appointment->getappointmentList($condition, 0, $field);

        Tpl::output('daily_sales', $daily_sales[0]);
        // 月销量
        $condition['add_time'] = array('gt', strtotime(date('Y-m')));
        $monthly_sales = $model_appointment->getappointmentList($condition, 0, $field);
        Tpl::output('monthly_sales', $monthly_sales[0]);

        // 单品销售排行
        $doctors_list = Model('doctors')->getdoctorsList(array('clic_id' => $_SESSION['clic_id']), 'doctors_id,doctors_name,doctors_image,clic_id,doctors_salenum', '', 'doctors_salenum desc', 8);
        Tpl::output('doctors_list', $doctors_list);
        if (C('groupbuy_allow') == 1){
            // 团购套餐
            $groupquota_info = Model('groupbuy_quota')->getGroupbuyQuotaCurrent($_SESSION['clic_id']);
            Tpl::output('groupquota_info', $groupquota_info);
        }
        if (intval(C('promotion_allow')) == 1){
            // 限时折扣套餐
            $xianshiquota_info = Model('p_xianshi_quota')->getXianshiQuotaCurrent($_SESSION['clic_id']);
            Tpl::output('xianshiquota_info', $xianshiquota_info);
            // 满即送套餐
            $mansongquota_info = Model('p_mansong_quota')->getMansongQuotaCurrent($_SESSION['clic_id']);
            Tpl::output('mansongquota_info', $mansongquota_info);
            // 优惠套装套餐
            $binglingquota_info = Model('p_bundling')->getBundlingQuotaInfoCurrent(array('clic_id' => $_SESSION['clic_id']));
            Tpl::output('binglingquota_info', $binglingquota_info);
            // 推荐展位套餐
            $boothquota_info = Model('p_booth')->getBoothQuotaInfoCurrent(array('clic_id' => $_SESSION['clic_id']));
            Tpl::output('boothquota_info', $boothquota_info);
        }
        if (C('voucher_allow') == 1){
            $voucherquota_info = Model('voucher')->getCurrentQuota($_SESSION['clic_id']);
            Tpl::output('voucherquota_info', $voucherquota_info);
        }
        $phone_array = explode(',',C('site_phone'));
        Tpl::output('phone_array',$phone_array);

        Tpl::output('menu_sign','index');
        Tpl::showpage('index');
    }
    /**
     * 异步取得卖家统计类信息
     *
     */
    public function statisticsOp() {
        $add_time_to = strtotime(date("Y-m-d")+60*60*24);   //当前日期 ,从零点来时
        $add_time_from = strtotime(date("Y-m-d",(strtotime(date("Y-m-d"))-60*60*24*30)));   //30天前
        $doctors_online = 0;      // 出售中商品
        $doctors_waitverify = 0;  // 等待审核
        $doctors_verifyfail = 0;  // 审核失败
        $doctors_offline = 0;     // 仓库待上架商品
        $doctors_lockup = 0;      // 违规下架商品
        $consult = 0;           // 待回复商品咨询
        $no_payment = 0;        // 待付款
        $no_delivery = 0;       // 待发货
        $no_receipt = 0;        // 待收货
        $refund_lock  = 0;      // 售前退款
        $refund = 0;            // 售后退款
        $return_lock  = 0;      // 售前退货
        $return = 0;            // 售后退货
        $complain = 0;          //进行中投诉

        $model_doctors = Model('doctors');
        // 全部商品数
        $doctorscount = $model_doctors->getdoctorsCommonCount(array('clic_id' => $_SESSION['clic_id']));
        // 出售中的商品
        $doctors_online = $model_doctors->getdoctorsCommonOnlineCount(array('clic_id' => $_SESSION['clic_id']));
        if (C('doctors_verify')) {
            // 等待审核的商品
            $doctors_waitverify = $model_doctors->getdoctorsCommonWaitVerifyCount(array('clic_id' => $_SESSION['clic_id']));
            // 审核失败的商品
            $doctors_verifyfail = $model_doctors->getdoctorsCommonVerifyFailCount(array('clic_id' => $_SESSION['clic_id']));
        }
        // 仓库待上架的商品
        $doctors_offline = $model_doctors->getdoctorsCommonOfflineCount(array('clic_id' => $_SESSION['clic_id']));
        // 违规下架的商品
        $doctors_lockup = $model_doctors->getdoctorsCommonLockUpCount(array('clic_id' => $_SESSION['clic_id']));
        // 等待回复商品咨询
        $consult = Model('consult')->getConsultCount('clic_id='.$_SESSION['clic_id'].' and consult_reply is null');

        // 商品图片数量
        $imagecount = Model('album')->getAlbumPicCount(array('clic_id' => $_SESSION['clic_id']));

        $model_appointment = Model('appointment');
        // 交易中的订单
        $progressing = $model_appointment->getappointmentCount(array('clic_id'=>$_SESSION['clic_id'],'appointment_state'=>array(array('neq',0),array('neq',40),'and')));
        // 待付款
        $no_payment = $model_appointment->getappointmentStateNewCount(array('clic_id'=>$_SESSION['clic_id']));
        // 待发货
        $no_delivery = $model_appointment->getappointmentStatePayCount(array('clic_id'=>$_SESSION['clic_id']));

        $model_refund_return = Model('refund_return');
        // 售前退款
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['refund_type'] = 1;
        $condition['appointment_lock'] = 2;
        $condition['refund_state'] = array('lt', 3);
        $refund_lock = $model_refund_return->getRefundReturnCount($condition);
        // 售后退款
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['refund_type'] = 1;
        $condition['appointment_lock'] = 1;
        $condition['refund_state'] = array('lt', 3);
        $refund = $model_refund_return->getRefundReturnCount($condition);
        // 售前退货
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['refund_type'] = 2;
        $condition['appointment_lock'] = 2;
        $condition['refund_state'] = array('lt', 3);
        $return_lock = $model_refund_return->getRefundReturnCount($condition);
        // 售后退货
        $condition = array();
        $condition['clic_id'] = $_SESSION['clic_id'];
        $condition['refund_type'] = 2;
        $condition['appointment_lock'] = 1;
        $condition['refund_state'] = array('lt', 3);
        $return = $model_refund_return->getRefundReturnCount($condition);

		$condition = array();
		$condition['accused_id'] = $_SESSION['clic_id'];
		$condition['complain_state'] = array(array('gt',10),array('lt',90),'and');
		$complain = Model()->table('complain')->where($condition)->count();

		//待确认的结算账单
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_clic_id'] = $_SESSION['clic_id'];
		$condition['ob_state'] = BILL_STATE_CREATE;
		$bill_confirm_count = $model_bill->getappointmentBillCount($condition);

        //统计数组
        $statistics = array(
            'doctorscount' => $doctorscount,
            'online' => $doctors_online,
            'waitverify' => $doctors_waitverify,
            'verifyfail' => $doctors_verifyfail,
            'offline' => $doctors_offline,
            'lockup' => $doctors_lockup,
            'imagecount' => $imagecount,
            'consult' => $consult,
            'progressing' => $progressing,
            'payment' => $no_payment,
            'delivery' => $no_delivery,
            'refund_lock' => $refund_lock,
            'refund' => $refund,
            'return_lock' => $return_lock,
            'return' => $return,
            'complain' => $complain,
            'bill_confirm' => $bill_confirm_count
        );
        exit(json_encode($statistics));
    }
    /**
     * 添加快捷操作
     */
    function quicklink_addOp() {
        if(!empty($_POST['item'])) {
            $_SESSION['clinicer_quicklink'][$_POST['item']] = $_POST['item'];
        }
        $this->_update_quicklink();
        echo 'true';
    }

    /**
     * 删除快捷操作
     */
    function quicklink_delOp() {
        if(!empty($_POST['item'])) {
            unset($_SESSION['clinicer_quicklink'][$_POST['item']]);
        }
        $this->_update_quicklink();
        echo 'true';
    }

    private function _update_quicklink() {
        $quicklink = implode(',', $_SESSION['clinicer_quicklink']);
        $update_array = array('clinicer_quicklink' => $quicklink);
        $condition = array('clinicer_id' => $_SESSION['clinicer_id']);
        $model_clinicer = Model('clinicer');
        $model_clinicer->editclinicer($update_array, $condition);
    }

}
