<?php
/**
 * 购买
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

class member_buyControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        $cart_id = explode(',', $_POST['cart_id']);

        $model_buy = Model('buy');

        $result = $model_buy->buyStep1($cart_id, $_POST['ifcart'], $_POST['invalid_cart'], $this->member_info['member_id'], $this->member_info['clic_id']);
        if(isset($result['error'])) {
            output_error($result['error']);
        }

        //整理数据
        $clic_cart_list = array();
        foreach ($result['clic_cart_list'] as $key => $value) {
            $clic_cart_list[$key]['doctors_list'] = $value;
            $clic_cart_list[$key]['clic_doctors_total'] = $result['clic_doctors_total'][$key];
            if(!empty($result['clic_premiums_list'][$key])) {
                $result['clic_premiums_list'][$key][0]['premiums'] = true;
                $result['clic_premiums_list'][$key][0]['doctors_total'] = 0.00;
                $clic_cart_list[$key]['doctors_list'][] = $result['clic_premiums_list'][$key][0];
            }
            $clic_cart_list[$key]['clic_mansong_rule_list'] = $result['clic_mansong_rule_list'][$key];
            $clic_cart_list[$key]['clic_voucher_list'] = $result['clic_voucher_list'][$key];
            if(!empty($result['cancel_calc_sid_list'][$key])) {
                $clic_cart_list[$key]['freight'] = '0';
                $clic_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];
            } else {
                $clic_cart_list[$key]['freight'] = '1';
            }
            $clic_cart_list[$key]['clic_name'] = $value[0]['clic_name'];
        }

        $buy_list = array();
        $buy_list['clic_cart_list'] = $clic_cart_list;
        $buy_list['freight_hash'] = $result['freight_list'];
        $buy_list['address_info'] = $result['address_info'];
        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];
        $buy_list['vat_hash'] = $result['vat_hash'];
        $buy_list['inv_info'] = $result['inv_info'];
        $buy_list['available_predeposit'] = $result['available_predeposit'];
        output_data($buy_list);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {
        $param = array();
        $param['ifcart'] = $_POST['ifcart'];
        $param['cart_id'] = explode(',', $_POST['cart_id']);
        $param['address_id'] = $_POST['address_id'];
        $param['vat_hash'] = $_POST['vat_hash'];
        $param['offpay_hash'] = $_POST['offpay_hash'];
        $param['pay_name'] = $_POST['pay_name'];
        $param['invoice_id'] = $_POST['invoice_id'];
        $param['voucher'] = $_POST['voucher'];
        //手机端暂时不做支付留言，页面内容太多了
        //$param['pay_message'] = json_decode($_POST['pay_message']);
        $param['pd_pay'] = $_POST['pd_pay'];
        $param['password'] = $_POST['password'];

        $model_buy = Model('buy');

        $pay_sn = $model_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email']);
        if(!empty($pay_sn['error'])) {
            output_error($pay_sn['error']);
        }

        output_data(array('pay_sn' => $pay_sn));
    }

    /**
     * 验证密码
     */
    public function check_passwordOp() {
        if(empty($_POST['password'])) {
            output_error('参数错误');
        }

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfo(array('member_id' => $this->member_info['member_id']));
        if($member_info['member_passwd'] == md5($_POST['password'])) {
            output_data('1');
        } else {
            output_error('密码错误');
        }
    }

    /**
     * 更换收货地址
     */
    public function change_addressOp() {
        $model_buy = Model('buy');

        $data = $model_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);
        if(!empty($data) && $data['state'] == 'success' ) {
            output_data($data);
        } else {
            output_error('地址修改失败');
        }
    }


}

