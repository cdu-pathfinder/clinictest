<?php
/**
 * 统计管理
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class statModel extends Model{
    /**
     * 查询新增会员统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @param boolean $lock 是否锁定
     * @return array
     */
    public function statByMember($where, $field = '*', $page = 0, $appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('member')->field($field)->where($where)->page($page[0],$page[1])->appointment($appointment)->group($group)->select();
            } else {
                return $this->table('member')->field($field)->where($where)->page($page[0])->appointment($appointment)->group($group)->select();
            }
        } else {
            return $this->table('member')->field($field)->where($where)->page($page)->appointment($appointment)->group($group)->select();
        }  
    }
    
    /**
     * 查询新增店铺统计
     */
	public function getNewclicStatList($condition, $field = '*', $page = 0, $appointment = 'clic_id desc', $limit = 0, $group = '', $lock = false) {
        return $this->table('clic')->field($field)->where($condition)->group($group)->select();
    }
    
    /**
     * 查询会员列表
     */
    public function getMemberList($where, $field = '*', $page = 0, $appointment = 'member_id desc', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('member')->field($field)->where($where)->page($page[0],$page[1])->group($group)->appointment($appointment)->select();
            } else {
                return $this->table('member')->field($field)->where($where)->page($page[0])->group($group)->appointment($appointment)->select();
            }
        } else {
            return $this->table('member')->field($field)->where($where)->page($page)->group($group)->appointment($appointment)->select();
        }
    }
    
	/**
     * 查询店铺销量统计
     */
	public function getclicSaleStatList($condition, $field = '*', $page = 0, $appointment = 'appointment_id desc', $limit = 0, $group = '', $lock = false) {
        return $this->table('appointment')->field($field)->where($condition)->group($group)->appointment('allnum desc')->select();
    }
    /**
     * 调取店铺等级信息
     */
    public function getclicDegree(){
    	$tmp = $this->table('clic_grade')->field('sg_id,sg_name')->where(true)->select();
    	$sd_list = array();
    	if(!empty($tmp)){
	    	foreach ($tmp as $k=>$v){
	    		$sd_list[$v['sg_id']] = $v['sg_name'];
	    	}
    	}
    	return $sd_list;
    }
    /**
     * 调取订单数据表
     */
	public function getclicappointmentList($condition,$limit=true) {
		if($limit){
        	return $this->field('appointment_id,appointment_sn,clic_name,buyer_name,add_time,appointment_amount,appointment_state')->table('appointment')->where($condition)->appointment('add_time desc')->page(15)->select();
		}else{
			return $this->field('appointment_id,appointment_sn,clic_name,buyer_name,add_time,appointment_amount,appointment_state')->table('appointment')->where($condition)->appointment('add_time desc')->select();
		}
    }
    
    /**
     * 查询会员统计数据记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByStatmember($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('stat_member')->field($field)->where($where)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->group($group)->select();
            } else {
                return $this->table('stat_member')->field($field)->where($where)->page($page[0])->limit($limit)->appointment($appointment)->group($group)->select();
            }
        } else {
            return $this->table('stat_member')->field($field)->where($where)->page($page)->limit($limit)->appointment($appointment)->group($group)->select();
        }
    }
    
    /**
     * 查询商品数量
     */
    public function getdoctorsNum($where){
    	$rs = $this->field('count(*) as allnum')->table('doctors_common')->where($where)->select();
    	return $rs[0]['allnum'];
    }
    /**
     * 获取销售信息
     */
    public function getTradeInfo($type,$stime,$etime){
    	switch ($type){
    		case 'appointment_doctors_num'://下单商品数
    			$data = $this->field('sum(doctors_num) as allnum')->table('appointment_doctors,appointment')->join('left join')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where(array('appointment.add_time'=>array('between', array($stime,$etime)),'appointment.appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment.appointment_state = '".appointment_STATE_CANCEL."' and appointment.refund_state = 0)")))->select();
    			return $data[0]['allnum'];
    			break;
    		case 'appointment_num'://下单单量
    			$data = $this->field('count(*) as allnum')->table('appointment')->where(array('add_time'=>array('between', array($stime,$etime)),'appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)")))->select();
    			return $data[0]['allnum'];
    			break;
    		case 'appointment_buyer_num'://下单客户数
    			$data = $this->field('DISTINCT(buyer_id)')->table('appointment')->where(array('add_time'=>array('between', array($stime,$etime)),'appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)")))->select();
    			return count($data);
    			break;
    		case 'appointment_amount'://合计金额
    			$data = $this->field('sum(appointment_amount) as allnum')->table('appointment')->where(array('add_time'=>array('between', array($stime,$etime)),'appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)")))->select();
    			return $data[0]['allnum'];
    			break;
    	}
    }
    /**
     * 获取商品销售排名
     */
    public function getdoctorsTradeRanking($type,$stime,$etime){
    	switch ($type){
    		case 'trade_num'://按销量
    			return $this->field('sum(doctors_num) as allnum,doctors_name,doctors_id')->table('appointment_doctors,appointment')->join('left join')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where(array('appointment.add_time'=>array('between', array($stime,$etime)),'appointment.appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment.appointment_state = '".appointment_STATE_CANCEL."' and appointment.refund_state = 0)"),'payment_code'=>array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')")))->group('appointment_doctors.doctors_id')->limit(15)->appointment('allnum desc')->select();
    			break;
    		case 'trade_amount'://按销售额
    			return $this->field('sum(doctors_price*doctors_num) as allnum,doctors_name,doctors_id')->table('appointment_doctors,appointment')->join('left join')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where(array('appointment.add_time'=>array('between', array($stime,$etime)),'appointment.appointment_state'=>array('neq',appointment_STATE_NEW),'refund_state'=>array('exp',"!(appointment.appointment_state = '".appointment_STATE_CANCEL."' and appointment.refund_state = 0)"),'payment_code'=>array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')")))->limit(15)->appointment('allnum desc')->group('appointment_doctors.doctors_id')->select();
    			break;
    	}
    }
    /**
     * 查询订单及地区的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByappointmentCommon($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('appointment,appointment_common')->field($field)->join('left')->on('appointment.appointment_id=appointment_common.appointment_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('appointment,appointment_common')->field($field)->join('left')->on('appointment.appointment_id=appointment_common.appointment_id')->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }  
        } else {
            return $this->table('appointment,appointment_common')->field($field)->join('left')->on('appointment.appointment_id=appointment_common.appointment_id')->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
    /**
     * 获取预存款数据
     */
    public function getPredepositInfo($condition, $field = '*', $page = 0, $appointment = 'lg_add_time desc', $limit = 0, $group = '', $lock = false){
    	return $this->table('pd_log')->field($field)->where($condition)->page($page)->group($group)->appointment($appointment)->select();
    }
    /**
     * 获取商品销售明细列表
     */
    public function getdoctorsTradeDetailList($condition,$page=15){
    	$condition = $this->_getRecursiveClass($condition);
    	if(intval($page) > 0){
    		$count_allnum = $this->field('count(DISTINCT doctors.doctors_id) as countnum')->table('doctors,appointment_doctors,doctors_common,appointment')->join('left join')->on('doctors.doctors_id=appointment_doctors.doctors_id,doctors.doctors_commonid=doctors_common.doctors_commonid,appointment_doctors.appointment_id=appointment.appointment_id')->where($condition)->select();
    		$count_allnum = $count_allnum[0]['countnum'];
    		return $this->field('sum(appointment_doctors.doctors_num) as gnum,count(*) as onum,sum(appointment_doctors.doctors_price*appointment_doctors.doctors_num) as pnum,doctors.doctors_name,doctors.clic_name,doctors_common.doctors_addtime,doctors_common.doctors_selltime,doctors.doctors_id')->table('doctors,appointment_doctors,doctors_common,appointment')->join('left join')->on('doctors.doctors_id=appointment_doctors.doctors_id,doctors.doctors_commonid=doctors_common.doctors_commonid,appointment_doctors.appointment_id=appointment.appointment_id')->where($condition)->group('doctors.doctors_id')->appointment('doctors.doctors_addtime desc')->page($page,$count_allnum)->select();
    	}else{
    		return $this->field('sum(appointment_doctors.doctors_num) as gnum,count(*) as onum,sum(appointment_doctors.doctors_price*appointment_doctors.doctors_num) as pnum,doctors.doctors_name,doctors.clic_name,doctors_common.doctors_addtime,doctors_common.doctors_selltime,doctors.doctors_id')->table('doctors,appointment_doctors,doctors_common,appointment')->join('left join')->on('doctors.doctors_id=appointment_doctors.doctors_id,doctors.doctors_commonid=doctors_common.doctors_commonid,appointment_doctors.appointment_id=appointment.appointment_id')->where($condition)->group('doctors.doctors_id')->appointment('doctors.doctors_addtime desc')->select();	
    	}
    }
	/**
      * 获得商品子分类的ID
      * @param array $condition
      * @return array 
      */
    private function _getRecursiveClass($condition){
        if (isset($condition['doctors.gc_id']) && !is_array($condition['doctors.gc_id'])) {
            $gc_list = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
            if (!empty($gc_list[$condition['doctors.gc_id']])) {
                $gc_id[] = $condition['doctors.gc_id'];
                $gcchild_id = empty($gc_list[$condition['doctors.gc_id']]['child']) ? array() : explode(',', $gc_list[$condition['doctors.gc_id']]['child']);
                $gcchildchild_id = empty($gc_list[$condition['doctors.gc_id']]['childchild']) ? array() : explode(',', $gc_list[$condition['doctors.gc_id']]['childchild']);
                $gc_id = array_merge($gc_id, $gcchild_id, $gcchildchild_id);
                $condition['doctors.gc_id'] = array('in', $gc_id);
            }
        }
        if (isset($condition['clic.sc_id']) && !is_array($condition['clic.sc_id'])) {
        	$sc_list = H('clic_class') ? H('clic_class') : H('clic_class', true);
        	if(is_array($sc_list[$condition['clic.sc_id']]['child']) && !empty($sc_list[$condition['clic.sc_id']]['child'])){
        		$sc_child_string = $condition['clic.sc_id'];
        		foreach ($sc_list[$condition['clic.sc_id']]['child'] as $val){
        			$sc_child_string .= ','.$val;
        		} 
        		$condition['clic.sc_id'] = array('in', $sc_child_string);
        	}
        }
        return $condition;
    }
    /**
     * 获取类目销售信息列表
     */
    public function getclicTradeList($condition,$type,$limit=''){
    	$condition = $this->_getRecursiveClass($condition);
    	switch ($type){
    		case 'doctors'://返回商品销售列表
    			return $this->field('sum(appointment_doctors.doctors_num) as gnum,count(*) as onum,sum(appointment_doctors.doctors_price*appointment_doctors.doctors_num) as pnum,doctors.doctors_name,doctors.clic_name,doctors_class.gc_name,doctors.doctors_id')->table('doctors,appointment_doctors,appointment,doctors_class')->join('left join')->on('doctors.doctors_id=appointment_doctors.doctors_id,appointment_doctors.appointment_id=appointment.appointment_id,doctors.gc_id=doctors_class.gc_id')->where($condition)->group('doctors.doctors_id')->appointment('sum(appointment_doctors.doctors_price*appointment_doctors.doctors_num) desc')->limit($limit)->select();
    			break;
    		case 'clic'://返回店铺销售列表
    			return $this->field('count(*) as onum,sum(appointment.appointment_amount) as pnum,clic.clic_name,clic_class.sc_name,clic.member_name')->table('clic,appointment,clic_class')->join('left join')->on('clic.clic_id=appointment.clic_id,clic.sc_id=clic_class.sc_id')->where($condition)->group('clic.clic_id')->appointment('sum(appointment.appointment_amount) desc')->limit($limit)->select();
    			break;
    	}
    }
    /**
     * 获取结算数据
     */
    public function getBillList($condition,$type,$have_page=true){
    	switch ($type){
    		case 'os'://平台
    			return $this->field('sum(os_appointment_totals) as oot,sum(os_appointment_return_totals) as oort,sum(os_commis_totals-os_commis_return_totals) as oct,sum(os_clic_cost_totals) as osct,sum(os_result_totals) as ort')->table('appointment_statis')->where($condition)->select();
    			break;
    		case 'ob'://店铺
    			$page = $have_page?15:'';
    			return $this->field('appointment_bill.*,clic.member_name')->table('appointment_bill,clic')->join('left join')->on('appointment_bill.ob_clic_id=clic.clic_id')->where($condition)->page($page)->appointment('ob_no desc')->select();
    			break;
    	}
    }
	/**
     * 查询订单及订单商品的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByappointmentdoctors($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('appointment_doctors,appointment')->field($field)->join('left')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('appointment_doctors,appointment')->field($field)->join('left')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }  
        } else {
            return $this->table('appointment_doctors,appointment')->field($field)->join('left')->on('appointment_doctors.appointment_id=appointment.appointment_id')->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
	/**
     * 查询订单及订单商品的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByappointmentLog($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('appointment_log,appointment')->field($field)->join('left')->on('appointment_log.appointment_id = appointment.appointment_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('appointment_log,appointment')->field($field)->join('left')->on('appointment_log.appointment_id = appointment.appointment_id')->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }  
        } else {
            return $this->table('appointment_log,appointment')->field($field)->join('left')->on('appointment_log.appointment_id = appointment.appointment_id')->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
	/**
     * 查询退款退货统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByRefundreturn($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }
        } else {
            return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
	/**
     * 查询店铺动态评分统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByclicAndEvaluateclic($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = ''){
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('evaluate_clic,clic')->field($field)->join('left')->on('evaluate_clic.seval_clicid=clic.clic_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('evaluate_clic,clic')->field($field)->join('left')->on('evaluate_clic.seval_clicid=clic.clic_id')->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }
        } else {
            return $this->table('evaluate_clic,clic')->field($field)->join('left')->on('evaluate_clic.seval_clicid=clic.clic_id')->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
    /**
	 * 处理搜索时间
	 */
    public function dealwithSearchTime($search_arr){
	    //初始化时间
		//天
		if(!$search_arr['search_time']){
			$search_arr['search_time'] = date('Y-m-d', time());
		}
		$search_arr['day']['search_time'] = strtotime($search_arr['search_time']);//搜索的时间
		
		//周
		if(!$search_arr['searchweek_year']){
			$search_arr['searchweek_year'] = date('Y', time());
		}
		if(!$search_arr['searchweek_month']){
			$search_arr['searchweek_month'] = date('m', time());
		}
		if(!$search_arr['searchweek_week']){
			$search_arr['searchweek_week'] =  implode('|', getWeek_SdateAndEdate(time()));
		}
		$weekcurrent_year = $search_arr['searchweek_year'];
		$weekcurrent_month = $search_arr['searchweek_month'];
		$weekcurrent_week = $search_arr['searchweek_week'];
		$search_arr['week']['current_year'] = $weekcurrent_year;
		$search_arr['week']['current_month'] = $weekcurrent_month;
		$search_arr['week']['current_week'] = $weekcurrent_week;
		
		//月
		if(!$search_arr['searchmonth_year']){
			$search_arr['searchmonth_year'] = date('Y', time());
		}
		if(!$search_arr['searchmonth_month']){
			$search_arr['searchmonth_month'] = date('m', time());
		}
		$monthcurrent_year = $search_arr['searchmonth_year'];
		$monthcurrent_month = $search_arr['searchmonth_month'];
		$search_arr['month']['current_year'] = $monthcurrent_year;
		$search_arr['month']['current_month'] = $monthcurrent_month;
		return $search_arr;
	}
	/**
	 * 获得查询的开始和结束时间
	 */
	public function getStarttimeAndEndtime($search_arr){
	    if($search_arr['search_type'] == 'day'){
			$stime = $search_arr['day']['search_time'];//今天0点
			$etime = $search_arr['day']['search_time'] + 86400 - 1;//今天24点
		}
	    if($search_arr['search_type'] == 'week'){
	        $current_weekarr = explode('|', $search_arr['week']['current_week']);
			$stime = strtotime($current_weekarr[0]);
			$etime = strtotime($current_weekarr[1])+86400-1;
		}
	    if($search_arr['search_type'] == 'month'){
	        $stime = strtotime($search_arr['month']['current_year'].'-'.$search_arr['month']['current_month']."-01 0 month");
			$etime = getMonthLastDay($search_arr['month']['current_year'],$search_arr['month']['current_month'])+86400-1;
		}
		return array($stime,$etime);
	}
	/**
     * 查询会员统计数据单条记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function getOneStatmember($where, $field = '*', $appointment = '', $group = ''){
        return $this->table('stat_member')->field($field)->where($where)->group($group)->appointment($appointment)->find();
    }
	/**
     * 更新会员统计数据单条记录
     * 
     * @param array $condition 条件
     * @param array $update_arr 更新数组
     * @return array
     */
    public function updateStatmember($where,$update_arr){
        return $this->table('stat_member')->where($where)->update($update_arr);
    }
	/**
     * 查询订单的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByappointment($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('appointment')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('appointment')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }   
        } else {
            return $this->table('appointment')->field($field)->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
	/**
     * 查询积分的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByPointslog($where, $field = '*', $page = 0, $limit = 0,$appointment = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('points_log')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->appointment($appointment)->select();
            } else {
                return $this->table('points_log')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->appointment($appointment)->select();
            }
        } else {
            return $this->table('points_log')->field($field)->where($where)->group($group)->page($page)->limit($limit)->appointment($appointment)->select();
        }
    }
	/**
     * 删除会员统计数据记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $appointment 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function delByStatmember($where = array()) {
        $this->table('stat_member')->where($where)->delete();   
    }
	/**
     * 店铺销售排行
     */
	public function getclicSaleRank($condition,$type) {
		switch ($type){
			case 'sale_amount'://按销售额排行
				return $this->field('sum(appointment_amount) as allnum,clic_name')->table('appointment')->where($condition)->appointment('allnum desc')->group('clic_id')->limit(15)->select();
				break;
			case 'sale_num'://按下单量排行
				return $this->field('count(*) as allnum,clic_name')->table('appointment')->where($condition)->appointment('allnum desc')->group('clic_id')->limit(15)->select();
				break;
		}
    }
}