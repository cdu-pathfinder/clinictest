<?php
/**
 * 执行任务队列
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class scanControl extends BaseHomeControl{

    public function indexOp(){
        $this->cron();
        $this->cron_groupbuy();
        $this->cron_xianshi();
        $this->cron_mansong();
        $this->cron_memberstat();
    }

    private function cron(){
        //查找待执行任务
        $model_cron = Model('cron');
        $cron = $model_cron->getCronList(array('exetime'=>array('elt',TIMESTAMP)));
        if (!is_array($cron)) return ;
        $cron_array = array(); $cronid = array();
        foreach ($cron as $v) {
            $cron_array[$v['type']][$v['exeid']] = $v;
        }
        foreach ($cron_array as $k=>$v) {
            if (!method_exists($this,'cron_'.$k)) {
                $tmp = current($v);
                $cronid[] = $tmp['id'];continue;
            }
            $result = call_user_func_array(array($this,'cron_'.$k),array($v));
            if (is_array($result)){
                $cronid = array_merge($cronid,$result);
            }
        }
        //删除执行完成的cron信息
        if (!empty($cronid) && is_array($cronid)){
            $model_cron->delCron(array('id'=>array('in',$cronid)));
        }
    }

    /**
     * 上架
     *
     * @param array $cron
     */
    private function cron_1($cron = array()){
        $condition = array('doctors_commonid' => array('in',array_keys($cron)));
        $update = Model('doctors')->editProducesOnline($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array_keys($cron);
        }else{
            return false;
        }
        return $cronid;
    }

	/**
	 * 邮件发送
	 *
	 * @param array $cron
	 */
	private function cron_2($cron = array()){
		$cronid = array();$i = 0;$model = Model('cron');
		foreach ($cron as $v) {
			if ($i >= 3) break;//只发送2条
			$content = unserialize($v['content']);
			if (!$content[1]) $content[1] = false;
			$this->send_notice($v['exeid'],$v['code'],$content[0],$content[1]);
			$cronid[] = $v['id'];$i++;
		}
		return $cronid;
	}
	
    /**
     * 优惠套装过期
     * 
     * @param array $cron
     */
    private function cron_3($cron = array()) {
        $condition = array('clic_id' => array('in', array_keys($cron)));
        $update = Model('p_bundling')->editBundlingQuotaClose($condition);
        if ($update) {
            // 返回执行成功的cronid
            $cronid = array_keys($cron);
        } else {
            return false;
        }
        return $cronid;
    }
	
    /**
     * 推荐展位过期
     * 
     * @param array $cron
     */
    private function cron_4($cron = array()) {
        $condition = array('clic_id' => array('in', array_keys($cron)));
        $update = Model('p_booth')->editBoothClose($condition);
        if ($update) {
            // 返回执行成功的cronid
            $cronid = array_keys($cron);
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 团购活动过期
     */
    private function cron_groupbuy() {
        $model_groupbuy = Model('groupbuy');
        $model_groupbuy->editExpireGroupbuy();
    }

    /**
     * 限时折扣过期
     */
    private function cron_xianshi() {
        $model_xianshi = Model('p_xianshi');
        $model_xianshi->editExpireXianshi();
    }

    /**
     * 满即送过期
     */
    private function cron_mansong() {
        $model_mansong = Model('p_mansong');
        $model_mansong->editExpireMansong();
    }
    
    /**
     * 会员相关数据统计
     */
    private function cron_memberstat(){
        $model = Model('stat');
        //查询最后统计的记录
        $latest_record = $model->getOneStatmember(array(), '', 'statm_id desc');
        $stime = 0;
        if ($latest_record){
            $start_time = strtotime(date('Y-m-d',$latest_record['statm_updatetime']));
        } else {
            $start_time = strtotime(date('Y-m-d',strtotime(C('setup_date'))));//从系统的安装时间开始统计
        }
        $j = 1;
        for ($stime = $start_time; $stime < time(); $stime = $stime+86400){
            //数据库更新数据数组
            $insert_arr = array();
            $update_arr = array();
            
            //避免重复统计，开始时间必须大于最后一条记录的记录时间
            $search_stime = $latest_record['statm_updatetime'] > $stime?$latest_record['statm_updatetime']:$stime;
            //统计一天的数据，如果结束时间大于当前时间，则结束时间为当前时间，避免因为查询时间的延迟造成数据遗落
            $search_etime = ($t = ($stime + 86400 - 1)) > time() ? time() : ($stime + 86400 - 1);
            
            //统计订单下单量和下单金额
            $field = ' appointment.appointment_id,add_time,buyer_id,buyer_name,appointment_amount';
            $where = array();
            $where['appointment.appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
            $where['appointment.refund_state'] = array('exp',"!(appointment.appointment_state = '".appointment_STATE_CANCEL."' and appointment.refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
            $where['appointment_log.log_time'] = array('between',array($search_stime,$search_etime));//按照订单付款的操作时间统计
            //货到付款当交易成功进入统计，非货到付款当付款后进入统计
            $where['payment_code'] = array('exp',"(appointment.payment_code='offline' and appointment_log.log_appointmentstate = '".appointment_STATE_SUCCESS."') or (appointment.payment_code<>'offline' and appointment_log.log_appointmentstate = '".appointment_STATE_PAY."' )");
            $appointmentlist_tmp = $model->statByappointmentLog($where, $field, 0, 0, 'appointment_id');//此处由于底层的限制，仅能查询1000条，如果日下单量大于1000，则需要limit的支持
            
            $appointment_list = array();
            $appointmentid_list = array();
            foreach ((array)$appointmentlist_tmp as $k=>$v){
                $addtime = strtotime(date('Y-m-d',$v['add_time']));
                if ($addtime != $stime){//订单如果隔天支付的话，需要进行统计数据更新
                    $update_arr[$addtime][$v['buyer_id']]['statm_membername'] = $v['buyer_name'];
                    $update_arr[$addtime][$v['buyer_id']]['statm_appointmentnum'] = intval($update_arr[$addtime][$v['buyer_id']]['statm_appointmentnum'])+1;
                    $update_arr[$addtime][$v['buyer_id']]['statm_appointmentamount'] = floatval($update_arr[$addtime][$v['buyer_id']]['statm_appointmentamount']) + (($t = floatval($v['appointment_amount'])) > 0?$t:0);
                } else {
                    $appointment_list[$v['buyer_id']]['buyer_name'] = $v['buyer_name'];
                    $appointment_list[$v['buyer_id']]['appointmentnum'] = intval($appointment_list[$v['buyer_id']]['appointmentnum']) + 1;
                    $appointment_list[$v['buyer_id']]['appointmentamount'] = floatval($appointment_list[$v['buyer_id']]['appointmentamount']) + (($t = floatval($v['appointment_amount'])) > 0?$t:0);
                }
                //记录订单ID数组
                $appointmentid_list[] = $v['appointment_id'];
            }
            
            //统计下单商品件数
            if ($appointmentid_list){
                $field = ' add_time,appointment.buyer_id,appointment.buyer_name,doctors_num ';
                $where = array();
                $where['appointment.appointment_id'] = array('in',$appointmentid_list);
                $appointmentdoctors_tmp = $model->statByappointmentdoctors($where, $field, 0, 0, 'appointment.appointment_id');
                $appointmentdoctors_list = array();
                foreach ((array)$appointmentdoctors_tmp as $k=>$v){
                    $addtime = strtotime(date('Y-m-d',$v['add_time']));
                    if ($addtime != $stime){//订单如果隔天支付的话，需要进行统计数据更新
                        $update_arr[$addtime][$v['buyer_id']]['statm_doctorsnum'] = intval($update_arr[$addtime][$v['buyer_id']]['statm_doctorsnum']) + (($t = floatval($v['doctors_num'])) > 0?$t:0);
                    } else {
                        $appointmentdoctors_list[$v['buyer_id']]['doctorsnum'] = $appointmentdoctors_list[$v['buyer_id']]['doctorsnum'] + (($t = floatval($v['doctors_num'])) > 0?$t:0);
                    }
                }
            }
            
            //统计的预存款记录
            $field = ' lg_member_id,lg_member_name,SUM(IF(lg_av_amount>=0,lg_av_amount,0)) as predincrease, SUM(IF(lg_av_amount<=0,lg_av_amount,0)) as predreduce ';
            $where = array();
            $where['lg_add_time'] = array('between',array($stime,$etime));
            $predeposit_tmp = $model->getPredepositInfo($where, $field, 0, 'lg_member_id', 0, 'lg_member_id');
            $predeposit_list = array();
            foreach ((array)$predeposit_tmp as $k=>$v){
                $predeposit_list[$v['lg_member_id']] = $v;
            }
            
            //统计的积分记录
            $field = ' pl_memberid,pl_membername,SUM(IF(pl_points>=0,pl_points,0)) as pointsincrease, SUM(IF(pl_points<=0,pl_points,0)) as pointsreduce ';
            $where = array();
            $where['pl_addtime'] = array('between',array($stime,$etime));
            $points_tmp = $model->statByPointslog($where, $field, 0, 0, '', 'pl_memberid');
            $points_list = array();
            foreach ((array)$points_tmp as $k=>$v){
                $points_list[$v['pl_memberid']] = $v;
            }
            
            //处理需要更新的数据
            foreach ((array)$update_arr as $k=>$v){
                foreach ($v as $m_k=>$m_v){
                    //查询记录是否存在
                    $statmember_info = $model->table('stat_member')->where(array('statm_time'=>$k,'statm_memberid'=>$m_k))->find();
                    if ($statmember_info){
                        $m_v['statm_appointmentnum'] = intval($statmember_info['statm_appointmentnum']) + $m_v['statm_appointmentnum'];
                        $m_v['statm_appointmentamount'] = floatval($statmember_info['statm_appointmentnum']) + $m_v['statm_appointmentamount'];
                        $m_v['statm_updatetime'] = $search_etime;
                        $model->table('stat_member')->where(array('statm_time'=>$k,'statm_memberid'=>$m_k))->update($m_v);
                    } else {
                        $tmp = array();
                        $tmp['statm_memberid'] = $m_k;
                        $tmp['statm_membername'] = $m_v['statm_membername'];
                        $tmp['statm_time'] = $k;
                        $tmp['statm_updatetime'] = $search_etime;
                        $tmp['statm_appointmentnum'] = ($t = intval($m_v['statm_appointmentnum'])) > 0?$t:0;
                        $tmp['statm_appointmentamount'] = ($t = floatval($m_v['statm_appointmentamount']))>0?$t:0;
                        $tmp['statm_doctorsnum'] = ($t = intval($m_v['statm_doctorsnum']))?$t:0;
                        $tmp['statm_predincrease'] = 0;
                        $tmp['statm_predreduce'] = 0;
                        $tmp['statm_pointsincrease'] = 0;
                        $tmp['statm_pointsreduce'] = 0;
                        $insert_arr[] = $tmp;
                    }
                    unset($statmember_info);
                }
            }
            
            //处理获得所有会员ID数组
            $memberidarr_appointment = $appointment_list?array_keys($appointment_list):array();
            $memberidarr_appointmentdoctors = $appointmentdoctors_list?array_keys($appointmentdoctors_list):array();
            $memberidarr_predeposit = $predeposit_list?array_keys($predeposit_list):array();
            $memberidarr_points = $points_list?array_keys($points_list):array();
            $memberid_arr = array_merge($memberidarr_appointment,$memberidarr_appointmentdoctors,$memberidarr_predeposit,$memberidarr_points);
            //查询会员信息
            $memberid_list = Model('member')->getMemberList(array('member_id'=>array('in',$memberid_arr)), '', 0);
            //查询记录是否存在
            $statmemberlist_tmp = $model->statByStatmember(array('statm_time'=>$stime));
            foreach ((array)$statmemberlist_tmp as $k=>$v){
                $statmemberlist[$v['statm_memberid']] = $v;
            }
            foreach ((array)$memberid_list as $k=>$v){
                $tmp = array();
                $tmp['statm_memberid'] = $v['member_id'];
                $tmp['statm_membername'] = $v['member_name'];
                $tmp['statm_time'] = $stime;
                $tmp['statm_updatetime'] = $search_etime;
                //因为记录可能已经存在，所以加上之前的统计记录
                $tmp['statm_appointmentnum'] = intval($statmemberlist[$tmp['statm_memberid']]['statm_appointmentnum']) + (($t = intval($appointment_list[$tmp['statm_memberid']]['appointmentnum'])) > 0?$t:0);
                $tmp['statm_appointmentamount'] = floatval($statmemberlist[$tmp['statm_memberid']]['statm_appointmentamount']) + (($t = floatval($appointment_list[$tmp['statm_memberid']]['appointmentamount']))>0?$t:0);
                $tmp['statm_doctorsnum'] = intval($statmemberlist[$tmp['statm_memberid']]['statm_doctorsnum']) + (($t = intval($appointmentdoctors_list[$tmp['statm_memberid']]['doctorsnum']))?$t:0);
                $tmp['statm_predincrease'] = (($t = floatval($predeposit_list[$tmp['statm_memberid']]['predincrease']))?$t:0);
                $tmp['statm_predreduce'] = (($t = floatval($predeposit_list[$tmp['statm_memberid']]['predreduce']))?$t:0);
                $tmp['statm_pointsincrease'] = (($t = intval($points_list[$tmp['statm_memberid']]['pointsincrease']))?$t:0);
                $tmp['statm_pointsreduce'] = (($t = intval($points_list[$tmp['statm_memberid']]['pointsreduce']))?$t:0);
                $insert_arr[] = $tmp;
            }
            //删除旧的统计数据
            $model->delByStatmember(array('statm_time'=>$stime));
            $model->table('stat_member')->insertAll($insert_arr);
        }
    }
}
