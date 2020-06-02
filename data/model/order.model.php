<?php
/**
 * 订单管理
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
class appointmentModel extends Model {

    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('appointment_common','appointment_doctors','clic')
     * @return unknown
     */
    public function getappointmentInfo($condition = array(), $extend = array(), $fields = '*', $appointment = '',$group = '') {
        $appointment_info = $this->table('appointment')->field($fields)->where($condition)->group($group)->appointment($appointment)->find();
        if (empty($appointment_info)) {
            return array();
        }
        $appointment_info['state_desc'] = appointmentState($appointment_info);
        $appointment_info['payment_name'] = appointmentPaymentName($appointment_info['payment_code']);

        //追加返回订单扩展表信息
        if (in_array('appointment_common',$extend)) {
            $appointment_info['extend_appointment_common'] = $this->getappointmentCommonInfo(array('appointment_id'=>$appointment_info['appointment_id']));
            $appointment_info['extend_appointment_common']['reciver_info'] = unserialize($appointment_info['extend_appointment_common']['reciver_info']);
            $appointment_info['extend_appointment_common']['invoice_info'] = unserialize($appointment_info['extend_appointment_common']['invoice_info']);
        }

        //追加返回店铺信息
        if (in_array('clic',$extend)) {
            $appointment_info['extend_clic'] = Model('clic')->getclicInfo(array('clic_id'=>$appointment_info['clic_id']));
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            $appointment_info['extend_member'] = Model('member')->getMemberInfo(array('member_id'=>$appointment_info['buyer_id']));
        }

        //追加返回商品信息
        if (in_array('appointment_doctors',$extend)) {
            //取商品列表
            $appointment_doctors_list = $this->getappointmentdoctorsList(array('appointment_id'=>$appointment_info['appointment_id']));
            foreach ($appointment_doctors_list as $value) {
            	$appointment_info['extend_appointment_doctors'][] = $value;
            }
        }

        return $appointment_info;
    }

    public function getappointmentCommonInfo($condition = array(), $field = '*') {
        return $this->table('appointment_common')->where($condition)->find();
    }

    public function getappointmentPayInfo($condition = array()) {
        return $this->table('appointment_pay')->where($condition)->find();
    }

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $pagesize
     * @param unknown_type $filed
     * @param unknown_type $appointment
     * @param string $key 以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getappointmentPayList($condition, $pagesize = '', $filed = '*', $appointment = '', $key = '') {
        return $this->table('appointment_pay')->field($filed)->where($condition)->appointment($appointment)->page($pagesize)->key($key)->select();
    }

    /**
     * 取得订单列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $appointment
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('appointment_common','appointment_doctors','clic')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getappointmentList($condition, $pagesize = '', $field = '*', $appointment = 'appointment_id desc', $limit = '', $extend = array()){
        $list = $this->table('appointment')->field($field)->where($condition)->page($pagesize)->appointment($appointment)->limit($limit)->select();
        if (empty($list)) return array();
        $appointment_list = array();
        foreach ($list as $appointment) {
        	$appointment['state_desc'] = appointmentState($appointment);
        	$appointment['payment_name'] = appointmentPaymentName($appointment['payment_code']);
        	if (!empty($extend)) $appointment_list[$appointment['appointment_id']] = $appointment;
        }
        if (empty($appointment_list)) $appointment_list = $list;

        //追加返回订单扩展表信息
        if (in_array('appointment_common',$extend)) {
            $appointment_common_list = $this->getappointmentCommonList(array('appointment_id'=>array('in',array_keys($appointment_list))));
            foreach ($appointment_common_list as $value) {
                $appointment_list[$value['appointment_id']]['extend_appointment_common'] = $value;
                $appointment_list[$value['appointment_id']]['extend_appointment_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $appointment_list[$value['appointment_id']]['extend_appointment_common']['invoice_info'] = @unserialize($value['invoice_info']);
            }
        }
        //追加返回店铺信息
        if (in_array('clic',$extend)) {
            $clic_id_array = array();
            foreach ($appointment_list as $value) {
            	if (!in_array($value['clic_id'],$clic_id_array)) $clic_id_array[] = $value['clic_id'];
            }
            $clic_list = Model('clic')->getclicList(array('clic_id'=>array('in',$clic_id_array)));
            $clic_new_list = array();
            foreach ($clic_list as $clic) {
            	$clic_new_list[$clic['clic_id']] = $clic;
            }
            foreach ($appointment_list as $appointment_id => $appointment) {
                $appointment_list[$appointment_id]['extend_clic'] = $clic_new_list[$appointment['clic_id']];
            }
        }

        //追加返回买家信息
        if (in_array('member',$extend)) {
            $member_id_array = array();
            foreach ($appointment_list as $value) {
            	if (!in_array($value['buyer_id'],$member_id_array)) $member_id_array[] = $value['buyer_id'];
            }
            $member_list = Model()->table('member')->where(array('member_id'=>array('in',$member_id_array)))->limit($pagesize)->key('member_id')->select();
            foreach ($appointment_list as $appointment_id => $appointment) {
                $appointment_list[$appointment_id]['extend_member'] = $member_list[$appointment['buyer_id']];
            }
        }

        //追加返回商品信息
        if (in_array('appointment_doctors',$extend)) {
            //取商品列表
            $appointment_doctors_list = $this->getappointmentdoctorsList(array('appointment_id'=>array('in',array_keys($appointment_list))));
            foreach ($appointment_doctors_list as $value) {
                $value['doctors_image_url'] = cthumb($value['doctors_image'], 240, $value['clic_id']);
            	$appointment_list[$value['appointment_id']]['extend_appointment_doctors'][] = $value;
            }
        }

        return $appointment_list;
    }

    /**
     * 待付款订单数量
     * @param unknown $condition
     */
    public function getappointmentStateNewCount($condition = array()) {
        $condition['appointment_state'] = appointment_STATE_NEW;
        return $this->getappointmentCount($condition);
    }

    /**
     * 待发货订单数量
     * @param unknown $condition
     */
    public function getappointmentStatePayCount($condition = array()) {
        $condition['appointment_state'] = appointment_STATE_PAY;
        return $this->getappointmentCount($condition);
    }

    /**
     * 待收货订单数量
     * @param unknown $condition
     */
    public function getappointmentStateSendCount($condition = array()) {
        $condition['appointment_state'] = appointment_STATE_SEND;
        return $this->getappointmentCount($condition);
    }

    /**
     * 待评价订单数量
     * @param unknown $condition
     */
    public function getappointmentStateEvalCount($condition = array()) {
        $condition['appointment_state'] = appointment_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        $condition['finnshed_time'] = array('gt',TIMESTAMP - appointment_EVALUATE_TIME);
        return $this->getappointmentCount($condition);
    }

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getappointmentCount($condition) {
        return $this->table('appointment')->where($condition)->count();
    }

    /**
     * 取得订单商品表详细信息
     * @param unknown $condition
     * @param string $fields
     * @param string $appointment
     */
    public function getappointmentdoctorsInfo($condition = array(), $fields = '*', $appointment = '') {
        return $this->table('appointment_doctors')->where($condition)->field($fields)->appointment($appointment)->find();
    }

    /**
     * 取得订单商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $appointment
     * @param string $group
     * @param string $key
     */
    public function getappointmentdoctorsList($condition = array(), $fields = '*', $limit = null, $page = null, $appointment = 'rec_id desc', $group = null, $key = null) {
        return $this->table('appointment_doctors')->field($fields)->where($condition)->limit($limit)->appointment($appointment)->group($group)->key($key)->page($page)->select();
    }

    /**
     * 取得订单扩展表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     */
    public function getappointmentCommonList($condition = array(), $fields = '*', $limit = null) {
        return $this->table('appointment_common')->field($fields)->where($condition)->limit($limit)->select();
    }

    /**
     * 插入订单支付表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addappointmentPay($data) {
        return $this->table('appointment_pay')->insert($data);
    }

    /**
     * 插入订单表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addappointment($data) {
        return $this->table('appointment')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addappointmentCommon($data) {
        return $this->table('appointment_common')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addappointmentdoctors($data) {
        return $this->table('appointment_doctors')->insertAll($data);
    }

	/**
	 * 添加订单日志
	 */
	public function addappointmentLog($data) {
	    $data['log_role'] = str_replace(array('buyer','seller','system'),array('买家','商家','系统'), $data['log_role']);
	    $data['log_time'] = TIMESTAMP;
	    return $this->table('appointment_log')->insert($data);
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editappointment($data,$condition) {
		return $this->table('appointment')->where($condition)->update($data);
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editappointmentCommon($data,$condition) {
	    return $this->table('appointment_common')->where($condition)->update($data);
	}

	/**
	 * 更改订单支付信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editappointmentPay($data,$condition) {
		return $this->table('appointment_pay')->where($condition)->update($data);
	}

	/**
	 * 订单操作历史列表
	 * @param unknown $appointment_id
	 * @return Ambigous <multitype:, unknown>
	 */
    public function getappointmentLogList($condition) {
        return $this->table('appointment_log')->where($condition)->select();
    }

    /**
     * 返回是否允许某些操作
     * @param unknown $operate
     * @param unknown $appointment_info
     */
    public function getappointmentOperateState($operate,$appointment_info){

        if (!is_array($appointment_info) || empty($appointment_info)) return false;

        switch ($operate) {

            //买家取消订单
        	case 'buyer_cancel':
        	   $state = ($appointment_info['appointment_state'] == appointment_STATE_NEW) ||
        	       ($appointment_info['payment_code'] == 'offline' && $appointment_info['appointment_state'] == appointment_STATE_PAY);
        	   break;

    	   //买家取消订单
    	   case 'refund_cancel':
    	       $state = $appointment_info['refund'] == 1 && !intval($appointment_info['lock_state']);
    	       break;

    	   //商家取消订单
    	   case 'clic_cancel':
    	       $state = ($appointment_info['appointment_state'] == appointment_STATE_NEW) ||
    	       ($appointment_info['payment_code'] == 'offline' &&
    	       in_array($appointment_info['appointment_state'],array(appointment_STATE_PAY,appointment_STATE_SEND)));
    	       break;

           //平台取消订单
           case 'system_cancel':
               $state = ($appointment_info['appointment_state'] == appointment_STATE_NEW) ||
               ($appointment_info['payment_code'] == 'offline' && $appointment_info['appointment_state'] == appointment_STATE_PAY);
               break;

           //平台收款
           case 'system_receive_pay':
               $state = $appointment_info['appointment_state'] == appointment_STATE_NEW && $appointment_info['payment_code'] == 'online';
               break;

	       //买家投诉
	       case 'complain':
	           $state = in_array($appointment_info['appointment_state'],array(appointment_STATE_PAY,appointment_STATE_SEND)) ||
	               intval($appointment_info['finnshed_time']) > (TIMESTAMP - C('complain_time_limit'));
	           break;

            //调整运费
        	case 'modify_price':
        	    $state = ($appointment_info['appointment_state'] == appointment_STATE_NEW) ||
        	       ($appointment_info['payment_code'] == 'offline' && $appointment_info['appointment_state'] == appointment_STATE_PAY);
        	    $state = floatval($appointment_info['shipping_fee']) > 0 && $state;
        	   break;

        	//发货
        	case 'send':
        	    $state = !$appointment_info['lock_state'] && $appointment_info['appointment_state'] == appointment_STATE_PAY;
        	    break;

        	//收货
    	    case 'receive':
    	        $state = !$appointment_info['lock_state'] && $appointment_info['appointment_state'] == appointment_STATE_SEND;
    	        break;

    	    //评价
    	    case 'evaluation':
    	        $state = !$appointment_info['lock_state'] && !intval($appointment_info['evaluation_state']) && $appointment_info['appointment_state'] == appointment_STATE_SUCCESS &&
    	         TIMESTAMP - intval($appointment_info['finnshed_time']) < appointment_EVALUATE_TIME;
    	        break;

        	//锁定
        	case 'lock':
        	    $state = intval($appointment_info['lock_state']) ? true : false;
        	    break;

        	//快递跟踪
        	case 'deliver':
        	    $state = !empty($appointment_info['shipping_code']) && in_array($appointment_info['appointment_state'],array(appointment_STATE_SEND,appointment_STATE_SUCCESS));
        	    break;

        	//分享
        	case 'share':
        	    $state = $appointment_info['appointment_state'] == appointment_STATE_SUCCESS;
        	    break;

        }
        return $state;

    }
    
    /**
     * 联查订单表订单商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $appointment
     * @return array
     */
    public function getappointmentAndappointmentdoctorsList($condition, $field = '*', $page = 0, $appointment = 'rec_id desc') {
        return $this->table('appointment_doctors,appointment')->join('inner')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where($condition)->field($field)->page($page)->appointment($appointment)->select();
    }
    
    /**
     * 订单销售记录 订单状态为20、30、40时
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $appointment
     */
    public function getappointmentAndappointmentdoctorsSalesRecordList($condition, $field="*", $page = 0, $appointment = 'rec_id desc') {
        $condition['appointment_state'] = array('in', array(appointment_STATE_PAY, appointment_STATE_SEND, appointment_STATE_SUCCESS));
        return $this->getappointmentAndappointmentdoctorsList($condition, $field, $page, $appointment);
    }

	/**
	 * 买家订单状态操作
	 *
	 */
	public function memberChangeState($state_type, $appointment_info, $member_id, $member_name, $extend_msg) {
		try {

		    $this->beginTransaction();

		    if ($state_type == 'appointment_cancel') {
		        $this->_memberChangeStateappointmentCancel($appointment_info, $member_id, $member_name, $extend_msg);
		        $message = '成功取消了订单';
		    } elseif ($state_type == 'appointment_receive') {
		        $this->_memberChangeStateappointmentReceive($appointment_info, $member_id, $member_name, $extend_msg);
		        $message = '订单交易成功,您可以评价本次交易';
		    }

		    $this->commit();
            return array('success' => $message);

		} catch (Exception $e) {
		    $this->rollback();
            return array('error' => $message);
		}

	}

	/**
	 * 取消订单操作
	 * @param unknown $appointment_info
	 */
	private function _memberChangeStateappointmentCancel($appointment_info, $member_id, $member_name, $extend_msg) {
        $appointment_id = $appointment_info['appointment_id'];
        $if_allow = $this->getappointmentOperateState('buyer_cancel',$appointment_info);
        if (!$if_allow) {
            throw new Exception('非法访问');
        }

        $doctors_list = $this->getappointmentdoctorsList(array('appointment_id'=>$appointment_id));
        $model_doctors= Model('doctors');
        if(is_array($doctors_list) && !empty($doctors_list)) {
            $data = array();
            foreach ($doctors_list as $doctors) {
                $data['doctors_storage'] = array('exp','doctors_storage+'.$doctors['doctors_num']);
                $data['doctors_salenum'] = array('exp','doctors_salenum-'.$doctors['doctors_num']);
                $update = $model_doctors->editdoctors($data,array('doctors_id'=>$doctors['doctors_id']));
                if (!$update) {
                    throw new Exception('保存失败');
                }
            }
        }
        
        //解冻预存款
        $pd_amount = floatval($appointment_info['pd_amount']);
        if ($pd_amount > 0) {
            $model_pd = Model('predeposit');
            $data_pd = array();
            $data_pd['member_id'] = $member_id;
            $data_pd['member_name'] = $member_name;
            $data_pd['amount'] = $pd_amount;
            $data_pd['appointment_sn'] = $appointment_info['appointment_sn'];
            $model_pd->changePd('appointment_cancel',$data_pd);
        }

        //更新订单信息
        $update_appointment = array('appointment_state' => appointment_STATE_CANCEL, 'pd_amount' => 0);
        $update = $this->editappointment($update_appointment,array('appointment_id'=>$appointment_id));
        if (!$update) {
            throw new Exception('保存失败');
        }

        //添加订单日志
        $data = array();
        $data['appointment_id'] = $appointment_id;
        $data['log_role'] = 'buyer';
        $data['log_msg'] = '取消了订单';
        if ($extend_msg) {
            $data['log_msg'] .= ' ( '.$extend_msg.' )';
        }
        $data['log_appointmentstate'] = appointment_STATE_CANCEL;
        $this->addappointmentLog($data);
	}

	/**
	 * 收货操作
	 * @param unknown $appointment_info
	 */
	private function _memberChangeStateappointmentReceive($appointment_info, $member_id, $member_name, $extend_msg) {
	    $appointment_id = $appointment_info['appointment_id'];

	    //更新订单状态
        $update_appointment = array();
        $update_appointment['finnshed_time'] = TIMESTAMP;
	    $update_appointment['appointment_state'] = appointment_STATE_SUCCESS;
	    $update = $this->editappointment($update_appointment,array('appointment_id'=>$appointment_id));
	    if (!$update) {
	        throw new Exception('保存失败');
	    }

	    //添加订单日志
	    $data = array();
	    $data['appointment_id'] = $appointment_id;
	    $data['log_role'] = 'buyer';
	    $data['log_msg'] = '签收了货物';
	    if ($extend_msg) {
	        $data['log_msg'] .= ' ( '.$extend_msg.' )';
	    }
	    $data['log_appointmentstate'] = appointment_STATE_SUCCESS;
	    $this->addappointmentLog($data);

	    //确认收货时添加会员积分
	    if (C('points_isuse') == 1){
	        $points_model = Model('points');
	        $points_model->savePointsLog('appointment',array('pl_memberid'=>$member_id,'pl_membername'=>$member_name,'appointmentprice'=>$appointment_info['appointment_amount'],'appointment_sn'=>$appointment_info['appointment_sn'],'appointment_id'=>$appointment_info['appointment_id']),true);
	    }
	}
}
