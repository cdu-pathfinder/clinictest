<?php
/**
 * 下单业务模型
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class buyModel {

    /**
     * 输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
     * @param $buy_list 商品列表
     * @return 返回 以支付方式为下标分组的商品列表
     */
    public function getOfflinedoctorsPay($buy_list) {
        //以支付方式为下标，存放购买商品
        $buy_doctors_list = array();        
        $offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code'=>'offline'));
        if ($offline_pay) {
            //下单里包括平台自营商品并且平台已开户货到付款，则显示货到付款项及对应商品数量,取出支持货到付款的店铺ID组成的数组，目前就一个，DEFAULT_PLATFORM_clic_ID
            $offline_clic_id_array = array(DEFAULT_PLATFORM_clic_ID);
            foreach ($buy_list as $value) {
                if (in_array($value['clic_id'],$offline_clic_id_array)) {
                    $buy_doctors_list['offline'][] = $value;
                } else {
                    $buy_doctors_list['online'][] = $value;
                }
            }
        }
        return $buy_doctors_list;
    }

    /**
     * 计算每个店铺(所有店铺级优惠活动)总共优惠多少金额
     * @param array $clic_doctors_total 最初店铺商品总金额 
     * @param array $clic_final_doctors_total 去除各种店铺级促销后，最终店铺商品总金额(不含运费)
     * @return array
     */
    public function getclicPromotionTotal($clic_doctors_total, $clic_final_doctors_total) {
        if (!is_array($clic_doctors_total) || !is_array($clic_final_doctors_total)) return array();
        $clic_promotion_total = array();
        foreach ($clic_doctors_total as $clic_id => $doctors_total) {
            $clic_promotion_total[$clic_id] = abs($doctors_total - $clic_final_doctors_total[$clic_id]);
        }
        return $clic_promotion_total;
    }

    /**
     * 返回需要计算运费的店铺ID组成的数组 和 免运费店铺ID及免运费下限金额描述
     * @param array $clic_doctors_total 每个店铺的商品金额小计，以店铺ID为下标
     * @return array
     */
    public function getclicFreightDescList($clic_doctors_total) {
        if (empty($clic_doctors_total) || !is_array($clic_doctors_total)) return array(array(),array());

        //定义返回数组
        $need_calc_sid_array = array();
        $cancel_calc_sid_array = array();

        //如果商品金额未达到免运费设置下线，则需要计算运费
        $condition = array('clic_id' => array('in',array_keys($clic_doctors_total)));
        $clic_list = Model('clic')->getclicOnlineList($condition,null,'','clic_id,clic_free_price');
        foreach ($clic_list as $clic_info) {
            $limit_price = floatval($clic_info['clic_free_price']);
            if ($limit_price == 0 || $limit_price > $clic_doctors_total[$clic_info['clic_id']]) {
                //需要计算运费
                $need_calc_sid_array[] = $clic_info['clic_id'];
            } else {
                //返回免运费金额下限
                $cancel_calc_sid_array[$clic_info['clic_id']]['free_price'] = $limit_price;
                $cancel_calc_sid_array[$clic_info['clic_id']]['desc'] = sprintf('满%s免运费',$limit_price);
            }
        }
        return array($need_calc_sid_array,$cancel_calc_sid_array);
    }

    /**
     * 取得店铺运费(使用运费模板的商品运费不会计算，但会返回模板信息)
     * 先将免运费的店铺运费置0，然后算出店铺里没使用运费模板的商品运费之和 ，存到iscalced下标中
     * 然后再计算使用运费模板的信息(array(店铺ID=>array(运费模板ID=>购买数量))，放到nocalced下标里
     * @param array $buy_list 购买商品列表
     * @param array $free_freight_sid_list 免运费的店铺ID数组
     */
    public function getclicFreightList($buy_list = array(), $free_freight_sid_list) {
        //定义返回数组
        $return = array();
        //先将免运费的店铺运费置0(格式:店铺ID=>0)
        $freight_list = array();
        if (!empty($free_freight_sid_list) && is_array($free_freight_sid_list)) {
            foreach ($free_freight_sid_list as $clic_id) {
                $freight_list[$clic_id] = 0;
            }
        }

        //然后算出店铺里没使用运费模板(优惠套装商品除外)的商品运费之和(格式:店铺ID=>运费)
        //定义数组，存放店铺优惠套装商品运费总额 clic_id=>运费
        $clic_bl_doctors_freight = array();
        foreach ($buy_list as $key => $doctors_info) {
            //免运费店铺的商品不需要计算
            if (array_key_exists($doctors_info['clic_id'], $freight_list)) {
                unset($buy_list[$key]);
            }
            //优惠套装商品运费另算
            if (intval($doctors_info['bl_id'])) {
                unset($buy_list[$key]);
                $clic_bl_doctors_freight[$doctors_info['clic_id']] = $doctors_info['bl_id'];
                continue;
            }
            if (!intval($doctors_info['transport_id']) &&  !in_array($doctors_info['clic_id'],$free_freight_sid_list)) {
                $freight_list[$doctors_info['clic_id']] += $doctors_info['doctors_freight'];
                unset($buy_list[$key]);
            }
        }
        //计算优惠套装商品运费
        if (!empty($clic_bl_doctors_freight)) {
            $model_bl = Model('p_bundling');
            foreach (array_unique($clic_bl_doctors_freight) as $clic_id => $bl_id) {
                $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
                if (!empty($bl_info)) {
                    $freight_list[$clic_id] += $bl_info['bl_freight'];
                }
            }
        }

        $return['iscalced'] = $freight_list;

        //最后再计算使用运费模板的信息(店铺ID，运费模板ID，购买数量),使用使用相同运费模板的商品数量累加
        $freight_list = array();
        foreach ($buy_list as $doctors_info) {
            $freight_list[$doctors_info['clic_id']][$doctors_info['transport_id']] += $doctors_info['doctors_num'];
        }
        $return['nocalced'] = $freight_list;

        return $return;
    }

    /**
     * 根据地区选择计算出所有店铺最终运费
     * @param array $freight_list 运费信息(店铺ID，运费，运费模板ID，购买数量)
     * @param int $city_id 市级ID
     * @return array 返回店铺ID=>运费
     */
    public function calcclicFreight($freight_list, $city_id) {
		if (!is_array($freight_list) || empty($freight_list) || empty($city_id)) return;

		//免费和固定运费计算结果
		$return_list = $freight_list['iscalced'];

		//使用运费模板的信息(array(店铺ID=>array(运费模板ID=>购买数量))
		$nocalced_list = $freight_list['nocalced'];

		//然后计算使用运费运费模板的在该$city_id时的运费值
		if (!empty($nocalced_list) && is_array($nocalced_list)) {
		    //如果有商品使用的运费模板，先计算这些商品的运费总金额
            $model_transport = Model('transport');
            foreach ($nocalced_list as $clic_id => $value) {
                if (is_array($value)) {
                    foreach ($value as $transport_id => $buy_num) {
                        $freight_total = $model_transport->calc_transport($transport_id,$buy_num, $city_id);
                        if (empty($return_list[$clic_id])) {
                            $return_list[$clic_id] = $freight_total;
                        } else {
                            $return_list[$clic_id] += $freight_total;
                        }
                    }
                }
            }
		}

		return $return_list;
    }

    /**
     * 取得店铺下商品分类佣金比例
     * @param array $doctors_list
     * @return array 店铺ID=>array(分类ID=>佣金比例)
     */
    public function getclicGcidCommisRateList($doctors_list) {
        if (empty($doctors_list) || !is_array($doctors_list)) return array();

        //定义返回数组
        $clic_gc_id_commis_rate = array();

        //取得每个店铺下有哪些商品分类
        $clic_gc_id_list = array();
        foreach ($doctors_list as $doctors) {
            if (!intval($doctors['gc_id'])) continue;
            if (!in_array($doctors['gc_id'],(array)$clic_gc_id_list[$doctors['clic_id']])) {
                if (in_array($doctors['clic_id'],array(DEFAULT_PLATFORM_clic_ID))) {
                    //平台店铺佣金为0
                    $clic_gc_id_commis_rate[$doctors['clic_id']][$doctors['gc_id']] = 0;
                } else {
                    $clic_gc_id_list[$doctors['clic_id']][] = $doctors['gc_id'];
                }
            }
        }

        if (empty($clic_gc_id_list)) return array();

        $model_bind_class = Model('clic_bind_class');
        $condition = array();
        foreach ($clic_gc_id_list as $clic_id => $gc_id_list) {
            $condition['clic_id'] = $clic_id;
            $condition['class_1|class_2|class_3'] = array('in',$gc_id_list);
            $bind_list = $model_bind_class->getclicBindClassList($condition);
            if (!empty($bind_list) && is_array($bind_list)) {
                foreach ($bind_list as $bind_info) {
                    if ($bind_info['clic_id'] != $clic_id) continue;
                    //如果class_1,2,3有一个字段值匹配，就有效
                    $bind_class = array($bind_info['class_3'],$bind_info['class_2'],$bind_info['class_1']);
                    foreach ($gc_id_list as $gc_id) {
                        if (in_array($gc_id,$bind_class)) {
                            $clic_gc_id_commis_rate[$clic_id][$gc_id] = $bind_info['commis_rate'];
                        }
                    }
                }
            }
        }
        return $clic_gc_id_commis_rate;

    }

    /**
     * 追加赠品到下单列表,并更新购买数量
     * @param array $clic_cart_list 购买列表
     * @param array $clic_premiums_list 赠品列表
     * @param array $clic_mansong_rule_list 满退送规则
     */
    public function appendPremiumsToCartList($clic_cart_list, $clic_premiums_list = array(), $clic_mansong_rule_list = array(), $member_id) {
        if (empty($clic_cart_list)) return array();

        //取得每种商品的库存
        $doctors_storage_quantity = $this->_getEachdoctorsStorageQuantity($clic_cart_list,$clic_premiums_list);

        //取得每种商品的购买量
        $doctors_buy_quantity = $this->_getEachdoctorsBuyQuantity($clic_cart_list);

        //本次购买后，余库存为0的，则后面不再送赠品
        $last_storage = array();
        foreach ($doctors_buy_quantity as $doctors_id => $quantity) {
            $doctors_storage_quantity[$doctors_id] -= $quantity;
            if ($doctors_storage_quantity[$doctors_id] < 0) {
                return array('error' => '抱歉，您购买的商品库存不足，请重购买'); 
            }
        }
        //将赠品追加到购买列表
        if(is_array($clic_premiums_list)) {
            foreach ($clic_premiums_list as $clic_id => $doctors_list) {
                foreach ($doctors_list as $doctors_info) {
                    //如果没有库存了，则不再送赠品
                    if (!intval($doctors_storage_quantity[$doctors_id])) {
                        $clic_mansong_rule_list[$clic_id]['desc'] .= ' ( 抱歉，库存不足，系统未送赠品 )';
                        continue;
                    }
                    $new_data = array();
                    $new_data['buyer_id'] = $member_id;
                    $new_data['clic_id'] = $clic_id;
                    $new_data['clic_name'] = $clic_cart_list[$clic_id][0]['clic_name'];
                    $new_data['doctors_id'] = $doctors_info['doctors_id'];
                    $new_data['doctors_name'] = $doctors_info['doctors_name'];
                    $new_data['doctors_num'] = 1;
                    $new_data['doctors_price'] = 0;
                    $new_data['doctors_image'] = $doctors_info['doctors_image'];
                    $new_data['bl_id'] = 0;
                    $new_data['state'] = true;
                    $new_data['storage_state'] = true;
                    $new_data['gc_id'] = 0;
                    $new_data['transport_id'] = 0;
                    $new_data['doctors_freight'] = 0;
                    $new_data['doctors_vat'] = 0;
                    $new_data['doctors_total'] = 0;
                    $new_data['ifzengpin'] = true;
                    $clic_cart_list[$clic_id][] = $new_data;
                    $doctors_buy_quantity[$doctors_info['doctors_id']] += 1;
                }
            }
        }
        return array($clic_cart_list,$doctors_buy_quantity,$clic_mansong_rule_list);
    }

    /**
     * 取得每种商品的库存
     * @param array $clic_cart_list 购买列表
     * @param array $clic_premiums_list 赠品列表
     * @return array 商品ID=>库存
     */
    private function _getEachdoctorsStorageQuantity($clic_cart_list, $clic_premiums_list = array()) {
        if(empty($clic_cart_list) || !is_array($clic_cart_list)) return array();
        $doctors_storage_quangity = array();
        foreach ($clic_cart_list as $clic_cart) {
            foreach ($clic_cart as $cart_info) {
                if (!intval($cart_info['bl_id'])) {
                    //正常商品
                    $doctors_storage_quangity[$cart_info['doctors_id']] = $cart_info['doctors_storage'];
                } elseif (!empty($cart_info['bl_doctors_list']) && is_array($cart_info['bl_doctors_list'])) {
                    //优惠套装
                    foreach ($cart_info['bl_doctors_list'] as $doctors_info) {
                        $doctors_storage_quangity[$doctors_info['doctors_id']] = $doctors_info['doctors_storage'];
                    }
                }
            }
        }
        //取得赠品商品的库存
        if (is_array($clic_premiums_list)) {
            foreach ($clic_premiums_list as $clic_id => $doctors_list) {
                foreach($doctors_list as $doctors_info) {
                    if (!isset($doctors_storage_quangity[$doctors_info['doctors_id']])) {
                        $doctors_storage_quangity[$doctors_info['doctors_id']] = $doctors_info['doctors_storage'];
                    }
                }
            }
        }
        return $doctors_storage_quangity;
    }

    /**
     * 取得每种商品的购买量
     * @param array $clic_cart_list 购买列表
     * @return array 商品ID=>购买数量
     */
    private function _getEachdoctorsBuyQuantity($clic_cart_list) {
        if(empty($clic_cart_list) || !is_array($clic_cart_list)) return array();
        $doctors_buy_quangity = array();
        foreach ($clic_cart_list as $clic_cart) {
            foreach ($clic_cart as $cart_info) {
                if (!intval($cart_info['bl_id'])) {
                    //正常商品
                    $doctors_buy_quangity[$cart_info['doctors_id']] += $cart_info['doctors_num'];
                } elseif (!empty($cart_info['bl_doctors_list']) && is_array($cart_info['bl_doctors_list'])) {
                    //优惠套装
                    foreach ($cart_info['bl_doctors_list'] as $doctors_info) {
                        $doctors_buy_quangity[$doctors_info['doctors_id']] += $cart_info['doctors_num'];
                    }
                }   
            }
        }
        return $doctors_buy_quangity;
    }

    /**
     * 生成订单
     * @param array $input
     * @throws Exception
     * @return array array(支付单sn,订单列表)
     */
    public function createappointment($input, $member_id, $member_name, $member_email) {

        extract($input);
        $model_appointment = Model('appointment');
        //存储生成的订单,函数会返回该数组
        $appointment_list = array();

        //每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[在线支付/货到付款]
        $clic_pay_type_list    = $this->_getclicPayTypeList(array_keys($clic_cart_list), $if_offpay, $pay_name);

        $pay_sn = $this->makePaySn($member_id);
        $appointment_pay = array();
        $appointment_pay['pay_sn'] = $pay_sn;
        $appointment_pay['buyer_id'] = $member_id;
        $appointment_pay_id = $model_appointment->addappointmentPay($appointment_pay);
        if (!$appointment_pay_id) {
            throw new Exception('订单保存失败');
        }

        //收货人信息
        $reciver_info = array();
        $reciver_info['address'] = $address_info['area_info'].'&nbsp;'.$address_info['address'];
        $reciver_info['phone'] = $address_info['mob_phone'].($address_info['tel_phone'] ? ','.$address_info['tel_phone'] : null);
        $reciver_info = serialize($reciver_info);
        $reciver_name = $address_info['true_name'];

        foreach ($clic_cart_list as $clic_id => $doctors_list) {

            //取得本店优惠额度(后面用来计算每件商品实际支付金额，结算需要)
            $promotion_total = !empty($clic_promotion_total[$clic_id]) ? $clic_promotion_total[$clic_id] : 0; 

            //本店总的优惠比例,保留3位小数
            $should_doctors_total = $clic_final_appointment_total[$clic_id]-$clic_freight_total[$clic_id]+$promotion_total;
            $promotion_rate = abs($promotion_total/$should_doctors_total);
            if ($promotion_rate <= 1) {
                $promotion_rate = floatval(substr($promotion_rate,0,5));
            } else {
                $promotion_rate = 0;
            }

            //每种商品的优惠金额累加保存入 $promotion_sum
            $promotion_sum = 0;

            $appointment = array();
            $appointment_common = array();
            $appointment_doctors = array();

            $appointment['appointment_sn'] = $this->makeappointmentSn($appointment_pay_id);
            $appointment['pay_sn'] = $pay_sn;
            $appointment['clic_id'] = $clic_id;
            $appointment['clic_name'] = $doctors_list[0]['clic_name'];
            $appointment['buyer_id'] = $member_id;
            $appointment['buyer_name'] = $member_name;
            $appointment['buyer_email'] = $member_email;
            $appointment['add_time'] = TIMESTAMP;
            $appointment['payment_code'] = $clic_pay_type_list[$clic_id];
            $appointment['appointment_state'] = $clic_pay_type_list[$clic_id] == 'online' ? appointment_STATE_NEW : appointment_STATE_PAY;
            $appointment['appointment_amount'] = $clic_final_appointment_total[$clic_id];
            $appointment['shipping_fee'] = $clic_freight_total[$clic_id];
            $appointment['doctors_amount'] = $appointment['appointment_amount'] - $appointment['shipping_fee'];
            $appointment['appointment_from'] = 1;
            $appointment_id = $model_appointment->addappointment($appointment);
            if (!$appointment_id) {
                throw new Exception('订单保存失败');
            }
            $appointment['appointment_id'] = $appointment_id;
            $appointment_list[$appointment_id] = $appointment;

            $appointment_common['appointment_id'] = $appointment_id;
            $appointment_common['clic_id'] = $clic_id;
            $appointment_common['appointment_message'] = $pay_message[$clic_id];

            //代金券
            if (isset($voucher_list[$clic_id])){
                $appointment_common['voucher_price'] = $voucher_list[$clic_id]['voucher_price'];
                $appointment_common['voucher_code'] = $voucher_list[$clic_id]['voucher_code'];
            }

            $appointment_common['reciver_info']= $reciver_info;
            $appointment_common['reciver_name'] = $reciver_name;

            //发票信息
            $appointment_common['invoice_info'] = $this->_createInvoiceData($invoice_info);

            //保存促销信息
            if(is_array($clic_mansong_rule_list[$clic_id])) {
                $appointment_common['promotion_info'] = addslashes($clic_mansong_rule_list[$clic_id]['desc']);
            }

            //取得省ID
            require_once(BASE_DATA_PATH.'/area/area.php');
            $appointment_common['reciver_province_id'] = intval($area_array[$input_city_id]['area_parent_id']);
            $appointment_id = $model_appointment->addappointmentCommon($appointment_common);
            if (!$appointment_id) {
                throw new Exception('订单保存失败');
            }

            //生成appointment_doctors订单商品数据
            $i = 0;
            foreach ($doctors_list as $doctors_info) {
                if (!$doctors_info['state'] || !$doctors_info['storage_state']) {
                    throw new Exception('部分商品已经下架或库存不足，请重新选择');
                }
                if (!intval($doctors_info['bl_id'])) {
                    //如果不是优惠套装
                    $appointment_doctors[$i]['appointment_id'] = $appointment_id;
                    $appointment_doctors[$i]['doctors_id'] = $doctors_info['doctors_id'];
                    $appointment_doctors[$i]['clic_id'] = $clic_id;
                    $appointment_doctors[$i]['doctors_name'] = $doctors_info['doctors_name'];
                    $appointment_doctors[$i]['doctors_price'] = $doctors_info['doctors_price'];
                    $appointment_doctors[$i]['doctors_num'] = $doctors_info['doctors_num'];
                    $appointment_doctors[$i]['doctors_image'] = $doctors_info['doctors_image'];
                    $appointment_doctors[$i]['buyer_id'] = $member_id;
                    if ($doctors_info['ifgroupbuy']) {
                        $appointment_doctors[$i]['doctors_type'] = 2;
                    }elseif ($doctors_info['ifxianshi']) {
                        $appointment_doctors[$i]['doctors_type'] = 3;
                    }elseif ($doctors_info['ifzengpin']) {
                        $appointment_doctors[$i]['doctors_type'] = 5;
                    }else {
                        $appointment_doctors[$i]['doctors_type'] = 1;
                    }
                    $appointment_doctors[$i]['promotions_id'] = $doctors_info['promotions_id'] ? $doctors_info['promotions_id'] : 0;
                    $appointment_doctors[$i]['commis_rate'] = floatval($clic_gc_id_commis_rate_list[$clic_id][$doctors_info['gc_id']]);
                    //计算商品金额
                    $doctors_total = $doctors_info['doctors_price'] * $doctors_info['doctors_num'];
                    //计算本件商品优惠金额
                    $promotion_value = floor($doctors_total*($promotion_rate));
                    $appointment_doctors[$i]['doctors_pay_price'] = $doctors_total - $promotion_value;
                    $promotion_sum += $promotion_value;
                    $i++;

                } elseif (!empty($doctors_info['bl_doctors_list']) && is_array($doctors_info['bl_doctors_list'])) {

                    //优惠套装
                    foreach ($doctors_info['bl_doctors_list'] as $bl_doctors_info) {
                        $appointment_doctors[$i]['appointment_id'] = $appointment_id;
                        $appointment_doctors[$i]['doctors_id'] = $bl_doctors_info['doctors_id'];
                        $appointment_doctors[$i]['clic_id'] = $clic_id;
                        $appointment_doctors[$i]['doctors_name'] = $bl_doctors_info['doctors_name'];
                        $appointment_doctors[$i]['doctors_price'] = $bl_doctors_info['bl_doctors_price'];
                        $appointment_doctors[$i]['doctors_num'] = $doctors_info['doctors_num'];
                        $appointment_doctors[$i]['doctors_image'] = $bl_doctors_info['doctors_image'];
                        $appointment_doctors[$i]['buyer_id'] = $member_id;
                        $appointment_doctors[$i]['doctors_type'] = 4;
                        $appointment_doctors[$i]['promotions_id'] = $bl_doctors_info['bl_id'];
                        $appointment_doctors[$i]['commis_rate'] = floatval($clic_gc_id_commis_rate_list[$clic_id][$doctors_info['gc_id']]);

                        //计算商品实际支付金额(doctors_price减去分摊优惠金额后的值)
                        $doctors_total = $bl_doctors_info['bl_doctors_price'] * $doctors_info['doctors_num'];
                        //计算本件商品优惠金额
                        $promotion_value = floor($doctors_total*($promotion_rate));
                        $appointment_doctors[$i]['doctors_pay_price'] = $doctors_total - $promotion_value;
                        $promotion_sum += $promotion_value;
                        $i++;
                    }
                }
            }

            //将因舍出小数部分出现的差值补到最后一个商品的实际成交价中(商品doctors_price=0时不给补，可能是赠品)
            if ($promotion_total > $promotion_sum) {
                $i--;
                for($i;$i>=0;$i--) {
                    if (floatval($appointment_doctors[$i]['doctors_price']) > 0) {
                        $appointment_doctors[$i]['doctors_pay_price'] -= $promotion_total - $promotion_sum;
                        break;
                    }
                }
            }
            $insert = $model_appointment->addappointmentdoctors($appointment_doctors);
            if (!insert) {
                throw new Exception('订单保存失败');
            }
        }
        return array($pay_sn,$appointment_list);
    }

    /**
     * 记录订单日志
     * @param array $appointment_list
     */
    public function addappointmentLog($appointment_list = array()) {
        if (empty($appointment_list) || !is_array($appointment_list)) return;
        $model_appointment = Model('appointment');
        foreach ($appointment_list as $appointment_id => $appointment) {
            $data = array();
            $data['appointment_id'] = $appointment_id;
            $data['log_role'] = 'buyer';
            $data['log_msg'] = L('appointment_log_new');
            $data['log_appointmentstate'] = $appointment['payment_code'] == 'offline' ? appointment_STATE_PAY : appointment_STATE_NEW;
            $model_appointment->addappointmentLog($data);
        }
    }

    /**
     * 店铺购买列表
     * @param array $doctors_buy_quantity 商品ID与购买数量数组
     * @throws Exception
     */
    public function updatedoctorsStorageNum($doctors_buy_quantity) {
        if (empty($doctors_buy_quantity) || !is_array($doctors_buy_quantity)) return;
        $model_doctors = Model('doctors');
        foreach ($doctors_buy_quantity as $doctors_id => $quantity) {
            $doctors_info = $cart_info;
            $data = array();
            $data['doctors_storage'] = array('exp','doctors_storage-'.$quantity);
            $data['doctors_salenum'] = array('exp','doctors_salenum+'.$quantity);
            $result = $model_doctors->editdoctors($data,array('doctors_id'=>$doctors_id));
            if (!$result) throw new Exception('更新库存失败');
        }
    }

    /**
     * 更新使用的代金券状态
     * @param $input_voucher_list
     * @throws Exception
     */
    public function updateVoucher($voucher_list) {
        if (empty($voucher_list) || !is_array($voucher_list)) return;
        $model_voucher = Model('voucher');
        foreach ($voucher_list as $clic_id => $voucher_info) {
            $update = $model_voucher->editVoucher(array('voucher_state'=>2),array('voucher_id'=>$voucher_info['voucher_id']));
            if (!$update) throw new Exception('代金券更新失败');
        }
    }

    /**
     * 更新团购信息
     * @param unknown $groupbuy_info
     * @throws Exception
     */
    public function updateGroupbuy($groupbuy_info) {
        if (empty($groupbuy_info) || !is_array($groupbuy_info)) return;
        $model_groupbuy = Model('groupbuy');
        $data = array();
        $data['buyer_count'] = array('exp','buyer_count+1');
        $data['buy_quantity'] = array('exp','buy_quantity+'.$groupbuy_info['quantity']);
        $update = $model_groupbuy->editGroupbuy($data,array('groupbuy_id'=>$groupbuy_info['groupbuy_id']));
        if (!$update) throw new Exception('团购信息更新失败');
    }

    /**
     * 预存款支付,依次循环每个订单
     * 如果预存款足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
     */
    public function pdPay($appointment_list, $input, $member_id, $member_name) {
        if (empty($input['pd_pay']) || empty($input['password'])) return;

        $model_payment = Model('payment');
        $pd_payment_info = $model_payment->getPaymentOpenInfo(array('payment_code'=>'predeposit'));
        if (empty($pd_payment_info)) return;

        $buyer_info	= Model('member')->infoMember(array('member_id' => $member_id));
        if ($buyer_info['member_passwd'] != md5($input['password'])) return ;
        $available_pd_amount = floatval($buyer_info['available_predeposit']);
        if ($available_pd_amount <= 0) return;

        $model_appointment = Model('appointment');
        $model_pd = Model('predeposit');
        foreach ($appointment_list as $appointment_info) {

            //货到付款的订单跳过
            if ($appointment_info['payment_code'] == 'offline') continue;

            $appointment_amount = floatval($appointment_info['appointment_amount']);
            $data_pd = array();
            $data_pd['member_id'] = $member_id;
            $data_pd['member_name'] = $member_name;
            $data_pd['amount'] = $appointment_info['appointment_amount'];
            $data_pd['appointment_sn'] = $appointment_info['appointment_sn'];

            if ($available_pd_amount >= $appointment_amount) {
                //预存款立即支付，订单支付完成
                $model_pd->changePd('appointment_pay',$data_pd);
                $available_pd_amount -= $appointment_amount;

                //记录订单日志(已付款)
                $data = array();
                $data['appointment_id'] = $appointment_info['appointment_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = L('appointment_log_pay');
                $data['log_appointmentstate'] = appointment_STATE_PAY;
                $insert = $model_appointment->addappointmentLog($data);
                if (!$insert) {
                    throw new Exception('记录订单日志出现错误');
                }

                //订单状态 置为已支付
                $data_appointment = array();
                $data_appointment['appointment_state'] = appointment_STATE_PAY;
                $data_appointment['payment_time'] = TIMESTAMP;
                $data_appointment['payment_code'] = 'predeposit';
                $data_appointment['pd_amount'] = $appointment_amount;
                $result = $model_appointment->editappointment($data_appointment,array('appointment_id'=>$appointment_info['appointment_id']));
                if (!$result) {
                    throw new Exception('订单更新失败');
                }

            } else {
                //暂冻结预存款,后面还需要 API彻底完成支付
                if ($available_pd_amount > 0) {
                    $data_pd['amount'] = $available_pd_amount;
                    $model_pd->changePd('appointment_freeze',$data_pd);
                    //预存款支付金额保存到订单
                    $data_appointment = array();
                    $data_appointment['pd_amount'] = $available_pd_amount;
                    $result = $model_appointment->editappointment($data_appointment,array('appointment_id'=>$appointment_info['appointment_id']));
                    $available_pd_amount = 0;
                    if (!$result) {
                        throw new Exception('订单更新失败');
                    }
                }
            }
        }
    }

    /**
     * 整理发票信息
     * @param array $invoice_info 发票信息数组
     * @return string
     */
    private function _createInvoiceData($invoice_info){
        //发票信息
        $inv = array();
        if ($invoice_info['inv_state'] == 1) {
            $inv['类型'] = '普通发票 ';
            $inv['抬头'] = $invoice_info['inv_title_select'] == 'person' ? '个人' : $invoice_info['inv_title'];
            $inv['内容'] = $invoice_info['inv_content'];
        } elseif (!empty($invoice_info)) {
            $inv['单位名称'] = $invoice_info['inv_company'];
            $inv['纳税人识别号'] = $invoice_info['inv_code'];
            $inv['注册地址'] = $invoice_info['inv_reg_addr'];
            $inv['注册电话'] = $invoice_info['inv_reg_phone'];
            $inv['开户银行'] = $invoice_info['inv_reg_bname'];
            $inv['银行帐户'] = $invoice_info['inv_reg_baccount'];
            $inv['收票人姓名'] = $invoice_info['inv_rec_name'];
            $inv['收票人手机号'] = $invoice_info['inv_rec_mobphone'];
            $inv['收票人省份'] = $invoice_info['inv_rec_province'];
            $inv['送票地址'] = $invoice_info['inv_goto_addr'];
        }
        return !empty($inv) ? serialize($inv) : serialize(array());        
    }

    /**
     * 计算本次下单中每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[online在线支付offline货到付款]
     * @param array $clic_id_array 店铺ID数组
     * @param boolean $if_offpay 是否支持货到付款 true/false
     * @param string $pay_name 付款方式 online/offline
     * @return array
     */
    private function _getclicPayTypeList($clic_id_array, $if_offpay, $pay_name) {
        $clic_pay_type_list = array();
        if ($_POST['pay_name'] == 'online') {
            foreach ($clic_id_array as $clic_id) {
                $clic_pay_type_list[$clic_id] = 'online';
            }
        } else {
            $offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code'=>'offline'));
            if ($offline_pay) {
                //下单里包括平台自营商品并且平台已开启货到付款
                $offline_clic_id_array = array(DEFAULT_PLATFORM_clic_ID);
                foreach ($clic_id_array as $clic_id) {
                    if (in_array($clic_id,$offline_clic_id_array)) {
                        $clic_pay_type_list[$clic_id] = 'offline';
                    } else {
                        $clic_pay_type_list[$clic_id] = 'online';
                    }
                }
            }
        }
        return $clic_pay_type_list;
    }

	/**
	 * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
	 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
	 * 1000个会员同一微秒提订单，重复机率为1/100
	 * @return string
	 */
	public function makePaySn($member_id) {
		return mt_rand(10,99)
		      . sprintf('%010d',time() - 946656000)
		      . sprintf('%03d', (float) microtime() * 1000)
		      . sprintf('%03d', (int) $member_id % 1000);
	}

	/**
	 * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
	 * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
	 * 1000个会员同一微秒提订单，重复机率为1/100
	 * @param $pay_id 支付表自增ID
	 * @return string
	 */
	public function makeappointmentSn($pay_id) {
	    //记录生成子订单的个数，如果生成多个子订单，该值会累加
	    static $num;
	    if (empty($num)) {
	        $num = 1;
	    } else {
	        $num ++;
	    }
		return (date('y',time()) % 9+1) . sprintf('%013d', $pay_id) . sprintf('%02d', $num);
	}

	/**
	 * 更新库存与销量
	 *
	 * @param array $buy_items 商品ID => 购买数量
	 */
	public function editdoctorsNum($buy_items) {
        $model = Model()->table('doctors');
        foreach ($buy_items as $doctors_id => $buy_num) {
        	$data = array('doctors_storage'=>array('exp','doctors_storage-'.$buy_num),'doctors_salenum'=>array('exp','doctors_salenum+'.$buy_num));
        	$result = $model->where(array('doctors_id'=>$doctors_id))->update($data);
        	if (!$result) throw new Exception(L('cart_step2_submit_fail'));
        }
	}

    /**
     * 购买第一步
     *
	 * @param array $cart_id 购物车
	 * @param int $ifcart 是否为购物车
     * @param int $invalid_cart
     * @param int $member_id 会员编号
     * @param int $clic_id 店铺编号
     */
    public function buyStep1($cart_id, $ifcart, $invalid_cart, $member_id, $clic_id) {
        $model_cart = Model('cart');

        $result = array();

        //取得POST ID和购买数量
        $buy_items = $this->_parseItems($cart_id);

        if ($ifcart) {

            //来源于购物车

            //取购物车列表
            $condition = array('cart_id'=>array('in',array_keys($buy_items)), 'buyer_id'=>$member_id);
            $cart_list	= $model_cart->listCart('db', $condition);

            //取商品最新的在售信息
            $cart_list = $model_cart->getOnlineCartList($cart_list);

            //得到限时折扣信息
            $cart_list = $model_cart->getXianshiCartList($cart_list);

            //得到优惠套装状态,并取得组合套装商品列表
            $cart_list = $model_cart->getBundlingCartList($cart_list);

            //到得商品列表
            $doctors_list = $model_cart->getdoctorsList($cart_list);

            //购物车列表以店铺ID分组显示
            $clic_cart_list = $model_cart->getclicCartList($cart_list);

            //标识来源于购物车
            $result['ifcart'] = 1;

        } else {

            //来源于直接购买

            //取得购买的商品ID和购买数量,只有一个下标 ，只会循环一次
            foreach ($buy_items as $doctors_id => $quantity) {break;}

            //取得商品最新在售信息
            $doctors_info = $model_cart->getdoctorsOnlineInfo($doctors_id,intval($quantity));
            if(empty($doctors_info)) {
                return array('error' => '商品不存在');
            }

            //不能购买自己店铺的商品
            if ($doctors_info['clic_id'] == $clic_id) {
                return array('error' => '不能购买自己店铺的商品' );
            }

            //判断是不是正在团购中，如果是则按团购价格计算，购买数量若超过团购规定的上限，则按团购上限计算
            $doctors_info = $model_cart->getGroupbuyInfo($doctors_info);

            //如果未进行团购，则再判断是否限时折扣中
            if (!$doctors_info['ifgroupbuy']) {
                $doctors_info = $model_cart->getXianshiInfo($doctors_info,$quantity);
            }

            //转成多维数组，方便纺一使用购物车方法与模板
            $clic_cart_list = array();
            $doctors_list = array();
            $doctors_list[0] = $clic_cart_list[$doctors_info['clic_id']][0] = $doctors_info;
        }

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($clic_cart_list,$clic_doctors_total) = $model_cart->calcCartList($clic_cart_list);
        $result['clic_cart_list'] = $clic_cart_list;
        $result['clic_doctors_total'] = $clic_doctors_total;

        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        list($clic_premiums_list,$clic_mansong_rule_list) = $model_cart->getMansongRuleCartListByTotal($clic_doctors_total);
        $result['clic_premiums_list'] = $clic_premiums_list;
        $result['clic_mansong_rule_list'] = $clic_mansong_rule_list;

        //重新计算优惠后(满即送)的店铺实际商品总金额
        $clic_doctors_total = $model_cart->reCalcdoctorsTotal($clic_doctors_total,$clic_mansong_rule_list,'mansong');

        //返回店铺可用的代金券
        $clic_voucher_list = $model_cart->getclicAvailableVoucherList($clic_doctors_total, $member_id);
        $result['clic_voucher_list'] = $clic_voucher_list;

        //返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
        list($need_calc_sid_list,$cancel_calc_sid_list) = $this->getclicFreightDescList($clic_doctors_total);
        $result['need_calc_sid_list'] = $need_calc_sid_list;
        $result['cancel_calc_sid_list'] = $cancel_calc_sid_list;

        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        $freight_list = $this->getclicFreightList($doctors_list,array_keys($cancel_calc_sid_list));
        $result['freight_list'] = $this->buyEncrypt($freight_list, $member_id);

        //输出用户默认收货地址
        $result['address_info'] = Model('address')->getDefaultAddressInfo(array('member_id'=>$member_id));

        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        $pay_doctors_list = $this->getOfflinedoctorsPay($doctors_list);
        if (!empty($pay_doctors_list['offline'])) {
            $result['pay_doctors_list'] = $pay_doctors_list;
            $result['ifshow_offpay'] = true;
        } else {
            //如果所购商品只支持线上支付，支付方式不允许修改
            $result['deny_edit_payment'] = true;
        }

        //发票 :只有所有商品都支持增值税发票才提供增值税发票
        foreach ($doctors_list as $doctors) {
        	if (!intval($doctors['doctors_vat'])) {
        	    $vat_deny = true;break;
        	}
        }
        //不提供增值税发票时抛出true(模板使用)
        $result['vat_deny'] = $vat_deny;
        $result['vat_hash'] = $this->buyEncrypt($result['vat_deny'] ? 'deny_vat' : 'allow_vat', $member_id);

        //输出默认使用的发票信息
        $inv_info = Model('invoice')->getDefaultInvInfo(array('member_id'=>$member_id));
        if ($inv_info['inv_state'] == '2' && !$vat_deny) {
            $inv_info['content'] = '增值税发票 '.$inv_info['inv_company'].' '.$inv_info['inv_code'].' '.$inv_info['inv_reg_addr'];
        } elseif ($inv_info['inv_state'] == '2' && $vat_deny) {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        } elseif (!empty($inv_info)) {
            $inv_info['content'] = '普通发票 '.$inv_info['inv_title'].' '.$inv_info['inv_content'];
        } else {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        }
        $result['inv_info'] = $inv_info;

        //删除购物车中无效商品
        if ($ifcart) {
            if (is_array($invalid_cart)) {
                $cart_id_str = implode(',',$invalid_cart);
                if (preg_match_all('/^[\d,]+$/',$cart_id_str,$matches)) {
                    $model_cart->delCart('db',array('buyer_id'=>$member_id,'cart_id'=>array('in',$cart_id_str)));
                }
            }
        }

        //显示使用预存款支付及会员预存款
        $model_payment = Model('payment');
        $pd_payment_info = $model_payment->getPaymentOpenInfo(array('payment_code'=>'predeposit'));
        if (!empty($pd_payment_info)) {
            $buyer_info	= Model('member')->infoMember(array('member_id' => $member_id));
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $result['available_predeposit'] = $buyer_info['available_predeposit'];
            }
        }

        return $result;
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buyStep2($post, $member_id, $member_name, $member_email) {
        $model_cart = Model('cart');

        //取得商品ID和购买数量
        $input_buy_items = $this->_parseItems($post['cart_id']);

        //验证收货地址
        $input_address_id = intval($post['address_id']);
        if ($input_address_id <= 0) {
            return array('error' => '请选择收货地址');
        } else {
            $input_address_info = Model('address')->getAddressInfo(array('address_id'=>$input_address_id));
            if ($input_address_info['member_id'] != $member_id) {
                return array('error' => '请选择收货地址');
            }
        }
        //收货地址城市编号
        $input_city_id = intval($input_address_info['city_id']);

        //是否开增值税发票
        $input_if_vat = $this->buyDecrypt($post['vat_hash'], $member_id);
        if (!in_array($input_if_vat,array('allow_vat','deny_vat'))) {
            return array('error' => '订单保存出现异常，请重试');
        }
        $input_if_vat = ($input_if_vat == 'allow_vat') ? true : false;

        //是否支持货到付款
        $input_if_offpay = $this->buyDecrypt($post['offpay_hash'], $member_id);
        if (!in_array($input_if_offpay,array('allow_offpay','deny_offpay'))) {
            return array('error' => '订单保存出现异常，请重试');
        }
        $input_if_offpay = ($input_if_offpay == 'allow_offpay') ? true : false;

        //付款方式:在线支付/货到付款(online/offline)
        if (!in_array($post['pay_name'],array('online','offline'))) {
            return array('error' => '付款方式错误，请重新选择');
        }
        $input_pay_name = $post['pay_name'];

        //验证发票信息
        if (!empty($post['invoice_id'])) {
            $input_invoice_id = intval($post['invoice_id']);
            if ($input_invoice_id > 0) {
                $input_invoice_info = Model('invoice')->getinvInfo(array('inv_id'=>$input_invoice_id));
                if ($input_invoice_info['member_id'] != $member_id) {
                    return array('error' => '请正确填写发票信息');
                }
            }
        }

        //验证代金券
        $input_voucher_list = array();
        if (is_array($post['voucher'])) {
            foreach ($post['voucher'] as $clic_id => $voucher) {
                if (preg_match_all('/^(\d+)\|(\d+)\|([\d.]+)$/',$voucher,$matchs)) {
                    if (floatval($matchs[3][0]) > 0) {
                        $input_voucher_list[$clic_id]['voucher_t_id'] = $matchs[1][0];
                        $input_voucher_list[$clic_id]['voucher_price'] = $matchs[3][0];                        
                    }
                }
            }
        }

        if ($post['ifcart']) {

            //取购物车列表
            $condition = array('cart_id'=>array('in',array_keys($input_buy_items)),'buyer_id'=>$member_id);
            $cart_list	= $model_cart->listCart('db',$condition);

            //取商品最新的在售信息
            $cart_list = $model_cart->getOnlineCartList($cart_list);

            //得到限时折扣信息
            $cart_list = $model_cart->getXianshiCartList($cart_list);

            //得到优惠套装状态,并取得组合套装商品列表
            $cart_list = $model_cart->getBundlingCartList($cart_list);

            //到得商品列表
            $doctors_list = $model_cart->getdoctorsList($cart_list);

            //购物车列表以店铺ID分组显示
            $clic_cart_list = $model_cart->getclicCartList($cart_list);
        } else {
            //来源于直接购买
            //取得购买的商品ID和购买数量,只有有一个下标 ，只会循环一次
            foreach ($input_buy_items as $doctors_id => $quantity) {break;}

            //取得商品最新在售信息
            $doctors_info = $model_cart->getdoctorsOnlineInfo($doctors_id,$quantity);
            if(empty($doctors_info)) {
                return array('error' => '商品不存在');
            }

            //判断是不是正在团购中，如果是则按团购价格计算，购买数量若超过团购规定的上限，则按团购上限计算
            $doctors_info = $model_cart->getGroupbuyInfo($doctors_info);

            //如果未进行团购，则再判断是否限时折扣中
            if (!$doctors_info['ifgroupbuy']) {
                $doctors_info = $model_cart->getXianshiInfo($doctors_info,$quantity);
            } else {
                //这里记录一下团购数量，订单完成后需要更新一下团购表信息
                $groupbuy_info = array();
                $groupbuy_info['groupbuy_id'] = $doctors_info['groupbuy_id'];
                $groupbuy_info['quantity'] = $quantity;
            }

            //转成多维数组，方便纺一使用购物车方法与模板
            $clic_cart_list = array();
            $doctors_list = array();
            $doctors_list[0] = $clic_cart_list[$doctors_info['clic_id']][0] = $doctors_info;
        }

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($clic_cart_list,$clic_doctors_total) = $model_cart->calcCartList($clic_cart_list);

        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        list($clic_premiums_list,$clic_mansong_rule_list) = $model_cart->getMansongRuleCartListByTotal($clic_doctors_total);

        //重新计算店铺扣除满即送后商品实际支付金额
        $clic_final_doctors_total = $model_cart->reCalcdoctorsTotal($clic_doctors_total,$clic_mansong_rule_list,'mansong');

        //得到有效的代金券
        $input_voucher_list = $model_cart->reParseVoucherList($input_voucher_list,$clic_doctors_total,$member_id);

        //重新计算店铺扣除优惠券送商品实际支付金额
        $clic_final_doctors_total = $model_cart->reCalcdoctorsTotal($clic_final_doctors_total,$input_voucher_list,'voucher');

        //计算每个店铺(所有店铺级优惠活动)总共优惠多少
        $clic_promotion_total = $this->getclicPromotionTotal($clic_doctors_total, $clic_final_doctors_total);

        //计算每个店铺运费
        list($need_calc_sid_list,$cancel_calc_sid_list) = $this->getclicFreightDescList($clic_final_doctors_total);
        $freight_list = $this->getclicFreightList($doctors_list,array_keys($cancel_calc_sid_list));
        $clic_freight_total = $this->calcclicFreight($freight_list,$input_city_id);

        //计算店铺最终订单实际支付金额(加上运费)
        $clic_final_appointment_total = $model_cart->reCalcdoctorsTotal($clic_final_doctors_total,$clic_freight_total,'freight');

        //计算店铺分类佣金
        $clic_gc_id_commis_rate_list = $this->getclicGcidCommisRateList($doctors_list);

        //将赠品追加到购买列表(如果库存不足，则不送赠品)
        $append_premiums_to_cart_list = $this->appendPremiumsToCartList($clic_cart_list,$clic_premiums_list,$clic_mansong_rule_list,$member_id);
        if(!empty($append_premiums_to_cart_list['error'])) {
            return array('error' => $append_premiums_to_cart_list['error']);
        } else {
            list($clic_cart_list,$doctors_buy_quantity,$clic_mansong_rule_list) = $append_premiums_to_cart_list;
        }

        //整理已经得出的固定数据，准备下单
        $input = array();
        $input['pay_name'] = $input_pay_name;
        $input['if_offpay'] = $input_if_offpay;
        $input['if_vat'] = $input_if_vat;
        $input['pay_message'] = $post['pay_message'];
        $input['address_info'] = $input_address_info;
        $input['invoice_info'] = $input_invoice_info;
        $input['voucher_list'] = $input_voucher_list;
        $input['clic_doctors_total'] = $clic_doctors_total;
        $input['clic_final_appointment_total'] = $clic_final_appointment_total;
        $input['clic_freight_total'] = $clic_freight_total;
        $input['clic_promotion_total'] = $clic_promotion_total;
        $input['clic_gc_id_commis_rate_list'] = $clic_gc_id_commis_rate_list;
        $input['clic_mansong_rule_list'] = $clic_mansong_rule_list;
        $input['clic_cart_list'] = $clic_cart_list;
        $input['input_city_id'] = $input_city_id;

        try {

            //开始事务
            $model_cart->beginTransaction();

            //生成订单
            list($pay_sn,$appointment_list) = $this->createappointment($input, $member_id, $member_name, $member_email);

            //记录订单日志
            $this->addappointmentLog($appointment_list);

            //变更库存和销量
            $this->updatedoctorsStorageNum($doctors_buy_quantity);

            //更新使用的代金券状态
            $this->updateVoucher($input_voucher_list);

            //更新团购购买人数和数量
            $this->updateGroupbuy($groupbuy_info);

            //使用预存款支付
            $this->pdPay($appointment_list, $post, $member_id, $member_name);

            //提交事务
            $model_cart->commit();

        }catch (Exception $e){

            //回滚事务
            $model_cart->rollback();
            return array('error' => $e->getMessage());
        }

        //删除购物车中的商品
        if ($post['ifcart']) {
            $model_cart->delCart('db',array('buyer_id'=>$member_id,'cart_id'=>array('in',array_keys($input_buy_items))));
        }

        //下单完成后，需要更新销量统计
        $this->_complateappointment($doctors_list);

        return array('pay_sn' => $pay_sn);
    }

    /**
     * 加密
     * @param array/string $string
     * @param int $member_id
     * @return mixed arrray/string
     */
    public function buyEncrypt($string, $member_id) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
	    if (is_array($string)) {
	       $string = serialize($string);
	    } else {
	        $string = strval($string);
	    }
	    return encrypt(base64_encode($string), $buy_key);
    }

	/**
	 * 解密
	 * @param string $string
     * @param int $member_id
	 * @param number $ttl
     */
    public function buyDecrypt($string, $member_id, $ttl = 0) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
	    if (empty($string)) return;
	    $string = base64_decode(decrypt(strval($string), $buy_key, $ttl));
	    return ($tmp = @unserialize($string)) ? $tmp : $string;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }

    /**
     * 下单完成后，更新销量统计
     *
     */
    private function _complateappointment($doctors_list = array()) {
        if (empty($doctors_list) || !is_array($doctors_list)) return;
        foreach ($doctors_list as $doctors_info) {
            //更新销量统计
            $model = Model();
            $date = date('Ymd',time());
            $stat_model = Model('statistics');
            $sale_date_array = $model->table('salenum')->where(array('date'=>$date,'doctors_id'=>$doctors_info['doctors_id']))->find();
            if(is_array($sale_date_array) && !empty($sale_date_array)){
                $update_param = array();
                $update_param['table'] = 'salenum';
                $update_param['field'] = 'salenum';
                $update_param['value'] = $doctors_info['doctors_num'];
                $update_param['where'] = "WHERE date = '".$date."' AND doctors_id = '".$doctors_info['doctors_id']."'";
                $stat_model->updatestat($update_param);
            }else{
                $model->table('salenum')->insert(array('date'=>$date,'salenum'=>$doctors_info['doctors_num'],'clic_id'=>$doctors_info['clic_id'],'doctors_id'=>$doctors_info['doctors_id']));
            }            
        }
    }

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function changeAddr($freight_hash, $city_id, $area_id, $member_id) {
    	//$city_id计算运费模板,$area_id计算货到付款
        $city_id = intval($city_id);
        $area_id = intval($area_id);
        if ($city_id <= 0 || $area_id <= 0) return null;

    	//将hash解密，得到运费信息(店铺ID，运费,运费模板ID,购买数量),hash内容有效期为1小时
    	$freight_list = $this->buyDecrypt($freight_hash, $member_id);

    	//算运费
    	$clic_freight_list = $this->calcclicFreight($freight_list, $city_id);
    	$data = array();
    	$data['state'] = empty($clic_freight_list) ? 'fail' : 'success';
    	$data['content'] = $clic_freight_list;

    	//是否能使用货到付款(只有包含平台店铺的商品才会判断)
    	$if_include_platform_clic = array_key_exists(DEFAULT_PLATFORM_clic_ID,$freight_list['iscalced']) || array_key_exists(DEFAULT_PLATFORM_clic_ID,$freight_list['nocalced']);
    	if ($if_include_platform_clic) {
    	    $allow_offpay = Model('offpay_area')->checkSupportOffpay($area_id,DEFAULT_PLATFORM_clic_ID);
    	}
    	//JS验证使用
    	$data['allow_offpay'] = $allow_offpay ? '1' : '0';
        //PHP验证使用
        $data['offpay_hash'] = $this->buyEncrypt($allow_offpay ? 'allow_offpay' : 'deny_offpay', $member_id);

        return $data;
    }

}
