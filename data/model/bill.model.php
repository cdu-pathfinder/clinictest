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

//以下是定义结算单状态
//默认
define('BILL_STATE_CREATE',1);
//店铺已确认
define('BILL_STATE_clic_COFIRM',2);
//平台已审核
define('BILL_STATE_SYSTEM_CHECK',3);
//结算完成
define('BILL_STATE_SUCCESS',4);

class billModel extends Model {

    /**
     * 取得平台月结算单
     * @param unknown $condition
     * @param unknown $fields
     * @param unknown $pagesize
     * @param unknown $appointment
     * @param unknown $limit
     */
    public function getappointmentStatisList($condition = array(), $fields = '*', $pagesize = null, $appointment = '', $limit = null) {
        return $this->table('appointment_statis')->where($condition)->field($fields)->appointment($appointment)->page($pagesize)->limit($limit)->select();
    }

    /**
     * 取得平台月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getappointmentStatisInfo($condition = array(), $fields = '*',$appointment = null) {
        return $this->table('appointment_statis')->where($condition)->field($fields)->appointment($appointment)->find();
    }

    /**
     * 取得店铺月结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $appointment
     * @param string $limit
     */
    public function getappointmentBillList($condition = array(), $fields = '*', $pagesize = null, $appointment = '', $limit = null) {
        return $this->table('appointment_bill')->where($condition)->field($fields)->appointment($appointment)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 取得店铺月结算单单条
     * @param unknown $condition
     * @param string $fields
     */
    public function getappointmentBillInfo($condition = array(), $fields = '*') {
        return $this->table('appointment_bill')->where($condition)->field($fields)->find();
    }
    
    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getappointmentBillCount($condition) {
        return $this->table('appointment_bill')->where($condition)->count();
    }
    
    public function addappointmentStatis($data) {
        return $this->table('appointment_statis')->insertAll($data);
    }

    public function addappointmentBill($data) {
        return $this->table('appointment_bill')->insert($data);
    }

    public function editappointmentBill($data, $condition = array()) {
        return $this->table('appointment_bill')->where($condition)->update($data);
    }
}