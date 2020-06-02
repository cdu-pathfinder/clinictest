<?php
/**
 * 我的购物车
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

class member_cartControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 购物车列表
     */
    public function cart_listOp() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list	= $model_cart->listCart('db', $condition);
        $sum = 0;
        foreach ($cart_list as $key => $value) {
            $cart_list[$key]['doctors_image_url'] = cthumb($value['doctors_image'], $value['clic_id']);
            $cart_list[$key]['doctors_sum'] = ncPriceFormat($value['doctors_price'] * $value['doctors_num']);
            $sum += $cart_list[$key]['doctors_sum'];
        }

        output_data(array('cart_list' => $cart_list, 'sum' => ncPriceFormat($sum)));
    }

    /**
     * 购物车添加
     */
    public function cart_addOp() {
        $doctors_id = intval($_POST['doctors_id']);
        $quantity = intval($_POST['quantity']);
        if($doctors_id <= 0 || $quantity <= 0) {
            output_error('参数错误');
        }

        $model_doctors = Model('doctors');
        $model_cart	= Model('cart');

        $doctors_info = $model_doctors->getdoctorsOnlineInfo(array('doctors_id' => $doctors_id));
        //判断是不是在限时折扣中，如果是返回折扣信息
        $xianshi_info = $model_cart->getXianshiInfo($doctors_info, $quantity);
        if (!empty($xianshi_info)) {
            $doctors_info = $xianshi_info;
        }

        //验证是否可以购买
		if(empty($doctors_info)) {
            output_error('商品不存在');
		}
        if ($doctors_info['clic_id'] == $this->member_info['clic_id']) {
            output_error('不能购买自己发布的商品');
		}
		if(intval($doctors_info['doctors_storage']) < 1 || intval($doctors_info['doctors_storage']) < $quantity) {
            output_error('库存不足');
		}

        $param = array();
        $param['buyer_id']	= $this->member_info['member_id'];
        $param['clic_id']	= $doctors_info['clic_id'];
        $param['doctors_id']	= $doctors_info['doctors_id'];
        $param['doctors_name'] = $doctors_info['doctors_name'];
        $param['doctors_price'] = $doctors_info['doctors_price'];
        $param['doctors_image'] = $doctors_info['doctors_image'];
        $param['clic_name'] = $doctors_info['clic_name'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            output_data('1');
        } else {
            output_error('收藏失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = intval($_POST['cart_id']);
        
        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
		$cart_id = intval(abs($_POST['cart_id']));
		$quantity = intval(abs($_POST['quantity']));
		if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
		}

		$model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        if(!$this->_check_doctors_storage($cart_info, $quantity, $this->member_info['member_id'])) {
            output_error('库存不足');
        }

		$data = array();
        $data['doctors_num'] = $quantity;
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
		if ($update) {
		    $return = array();
            $return['quantity'] = $quantity;
			$return['doctors_price'] = ncPriceFormat($cart_info['doctors_price']);
			$return['total_price'] = ncPriceFormat($cart_info['doctors_price'] * $quantity);
            output_data($return);
		} else {
            output_error('修改失败');
		}
    }

    /**
     * 检查库存是否充足 
     */
    private function _check_doctors_storage($cart_info, $quantity, $member_id) {
		$model_doctors= Model('doctors');
        $model_bl = Model('p_bundling');

		if ($cart_info['bl_id'] == '0') {
            //普通商品
		    $doctors_info	= $model_doctors->getdoctorsOnlineInfo(array('doctors_id' => $cart_info['doctors_id']));

		    if(intval($doctors_info['doctors_storage']) < $quantity) {
                return false;
		    }
		} else {
		    //优惠套装商品
		    $bl_doctors_list = $model_bl->getBundlingdoctorsList(array('bl_id' => $cart_info['bl_id']));
		    $doctors_id_array = array();
		    foreach ($bl_doctors_list as $doctors) {
		        $doctors_id_array[] = $doctors['doctors_id'];
		    }
		    $bl_doctors_list = $model_doctors->getdoctorsOnlineList(array('doctors_id' => array('in', $doctors_id_array)));

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($bl_doctors_list as $doctors_info) {
		        if (intval($doctors_info['doctors_storage']) < $quantity) {
                    return false;
		        }
		    }
		}
        return true;
    }

}
