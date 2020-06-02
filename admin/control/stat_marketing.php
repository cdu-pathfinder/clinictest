<?php
/**
 * 营销分析
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class stat_marketingControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_marketing&op=promotion','lang'=>'stat_promotion'),
        array('url'=>'act=stat_marketing&op=group','lang'=>'stat_group'),
    );
    private $search_arr;//处理后的参数
    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($this->search_arr['op'],array('promotion','group'))){
		    $this->search_arr = $model->dealwithSearchTime($this->search_arr);
    		//获得系统年份
    		$year_arr = getSystemYearArr();
    		//获得系统月份
    		$month_arr = getSystemMonthArr();
    		//获得本月的周时间段
    		$week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
    		Tpl::output('year_arr', $year_arr);
    		Tpl::output('month_arr', $month_arr);
    		Tpl::output('week_arr', $week_arr);
		}
        Tpl::output('search_arr', $this->search_arr);
    }
	/**
	 * 促销分析
	 */
	public function promotionOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		$where = array();
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['appointment_state'] = array(array('neq',appointment_STATE_CANCEL),array('neq',appointment_STATE_NEW),'and');
		$where['appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		$where['doctors_type'] = array('in',array(2,3,4));
		//下单量
		$field = ' doctors_type,count(DISTINCT appointment.appointment_id) as appointmentnum,SUM(doctors_num) as doctorsnum,SUM(doctors_pay_price) as appointmentamount';
		$statlist_tmp = $model->statByappointmentdoctors($where, $field, 0, 0, 'doctors_type', 'doctors_type');
		//优惠类型数组
		$doctorstype_arr = array(2=>'团购',3=>'限时折扣',4=>'优惠套装');
		$statlist = array();
		$statcount = array('appointmentnum'=>0,'doctorsnum'=>0,'appointmentamount'=>0.00);
		$stat_arr = array();
		$stat_json = array('appointmentnum'=>'','doctorsnum'=>'','appointmentamount'=>'');
		if ($statlist_tmp){
		    foreach((array)$statlist_tmp as $k=>$v){
    		    $statcount['appointmentnum'] += intval($v['appointmentnum']);
    		    $statcount['doctorsnum'] += intval($v['doctorsnum']);
    		    $statcount['appointmentamount'] += floatval($v['appointmentamount']);
    		}
    	    foreach((array)$statlist_tmp as $k=>$v){
    	        $v['appointmentnumratio'] = round($v['appointmentnum']/$statcount['appointmentnum'],4)*100;
    	        $v['doctorsnumratio'] = round($v['doctorsnum']/$statcount['doctorsnum'],4)*100;
    	        $v['appointmentamountratio'] = round($v['appointmentamount']/$statcount['appointmentamount'],4)*100;
    	        $statlist_tmp2[$v['doctors_type']] = $v;
    	        $stat_arr['appointmentnum'][] = array('p_name'=>$doctorstype_arr[$v['doctors_type']],'allnum'=>$v['appointmentnumratio']);
    	        $stat_arr['doctorsnum'][] = array('p_name'=>$doctorstype_arr[$v['doctors_type']],'allnum'=>$v['doctorsnumratio']);
    	        $stat_arr['appointmentamount'][] = array('p_name'=>$doctorstype_arr[$v['doctors_type']],'allnum'=>$v['appointmentamountratio']);
    		}
    		foreach ($doctorstype_arr as $k=>$v){
    		    if ($statlist_tmp2[$k]){
    		        $statlist_tmp2[$k]['doctorstype_text'] = $v;
    		        $statlist[] = $statlist_tmp2[$k];    		        
    		    } else {
    		        $statlist[] = array('doctorstype_text'=>$k,'doctorstype_text'=>$v,'appointmentnum'=>0,'doctorsnum'=>0,'appointmentamount'=>0.00);
    		    }
    		}
    		$stat_json['appointmentnum'] = getStatData_Pie(array('title'=>'下单量','name'=>'下单量(%)','label_show'=>false,'series'=>$stat_arr['appointmentnum']));
    		$stat_json['doctorsnum'] = getStatData_Pie(array('title'=>'下单商品数','name'=>'下商品数(%)','label_show'=>false,'series'=>$stat_arr['doctorsnum']));
    		$stat_json['appointmentamount'] = getStatData_Pie(array('title'=>'下单金额','name'=>'下单金额(%)','label_show'=>false,'series'=>$stat_arr['appointmentamount']));
		}
		Tpl::output('statcount',$statcount);
		Tpl::output('statlist',$statlist);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'promotion'));
    	Tpl::showpage('stat.marketing.promotion');
	}
	/**
	 * 促销销售趋势分析
	 */
	public function promotiontrendOp(){
	    //优惠类型数组
		$doctorstype_arr = array(2=>'团购',3=>'限时折扣',4=>'优惠套装');
		
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['appointment_state'] = array(array('neq',appointment_STATE_CANCEL),array('neq',appointment_STATE_NEW),'and');
		$where['appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		$where['doctors_type'] = array('in',array(2,3,4));
		$field = ' doctors_type';
		switch ($this->search_arr['stattype']){
		    case 'appointmentamount':
		        $field .= " ,SUM(doctors_pay_price) as appointmentamount";
		        $caption = '下单金额';
		        break;
		    case 'doctorsnum':
		        $field .= " ,SUM(doctors_num) as doctorsnum";
		        $caption = '下单商品数';
		        break;
		    default:
		        $field .= " ,count(DISTINCT appointment.appointment_id) as appointmentnum";
		        $caption = '下单量';
		        break;
		}
		if($this->search_arr['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = "$i";
				foreach ($doctorstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
			}
			$field .= ' ,HOUR(FROM_UNIXTIME(add_time)) as timeval ';
		}
	    if($this->search_arr['search_type'] == 'week'){
	        //构造横轴数据
	        for($i=1; $i<=7; $i++){
	            $tmp_weekarr = getSystemWeekArr();
				//横轴
				$stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
				unset($tmp_weekarr);
				foreach ($doctorstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
			}
			$field .= ' ,WEEKDAY(FROM_UNIXTIME(add_time))+1 as timeval ';
		}
		if($this->search_arr['search_type'] == 'month'){
		    //计算横轴的最大量（由于每个月的天数不同）
			$dayofmonth = date('t',$searchtime_arr[0]);
		    //构造横轴数据
			for($i=1; $i<=$dayofmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
				foreach ($doctorstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
			}
			$field .= ' ,day(FROM_UNIXTIME(add_time)) as timeval ';
		}
		//查询数据
		$statlist_tmp = $model->statByappointmentdoctors($where, $field, 0, '', 'timeval','doctors_type,timeval');
		//整理统计数组
	    if($statlist_tmp){
			foreach($statlist_tmp as $k => $v){
			    //将数据按照不同的促销方式分组
			    foreach ($doctorstype_arr as $t_k=>$t_v){
			        if ($t_k == $v['doctors_type']){
				        switch ($this->search_arr['stattype']){
                		    case 'appointmentamount':
                		        $statlist[$t_k][$v['timeval']] = round($v[$this->search_arr['stattype']],2);
                		        break;
                		    case 'doctorsnum':
                		        $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                		        break;
                		    default:
                		        $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                		        break;
                		}
			        }
			    }
			}
		}
	    foreach ($doctorstype_arr as $k=>$v){
		    $tmp = array();
		    $tmp['name'] = $v;
		    $tmp['data'] = array_values($statlist[$k]);
		    $stat_arr['series'][] = $tmp;    
		}
		//得到统计图数据
		$stat_arr['title'] = $caption.'统计';
        $stat_arr['yAxis'] = $caption;
		$stat_json = getStatData_LineLabels($stat_arr);
		Tpl::output('stat_json',$stat_json);
		Tpl::showpage('stat.linelabels','null_layout');
	}

	/**
	 * 团购统计
	 */
	public function groupOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
	    Tpl::output('statcount',$statcount);
		Tpl::output('statlist',$statlist);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'group'));
    	Tpl::showpage('stat.marketing.group');
	}
	/**
	 * 团购统计
	 */
	public function grouplistOp(){
	    $model = Model('groupbuy');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['start_time'] = array('exp',"!(end_time < {$searchtime_arr[0]} or start_time > {$searchtime_arr[1]})");
		$where['state'] = array('in',array(10,20,30));
		$gname = trim($_GET['gname']);
		if ($gname){
		    $where['groupbuy_name'] = array('like',"%{$gname}%");
		}
		$grouplist = $model->getGroupbuyList($where,10,'start_time asc');
		Tpl::output('grouplist',$grouplist);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('searchtime',$_GET['t']);
    	Tpl::showpage('stat.marketing.grouplist','null_layout');
	}
	/**
	 * 团购商品统计
	 */
	public function groupdoctorsOp(){
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		$where['doctors_type'] = 2;
		$doctorsname = trim($_GET['doctorsname']);
		if ($doctorsname){
		    $where['doctors_name'] = array('like',"%{$doctorsname}%");
		}
		$field = " doctors_id,doctors_name";
		$field .= " ,SUM(appointment_doctors.doctors_num) as doctorsnum";
		$field .= " ,SUM(appointment_doctors.doctors_pay_price) as doctorsamount";
		$field .= " ,SUM(IF(appointment.appointment_state='".appointment_STATE_CANCEL."',doctors_num,0)) as canceldoctorsnum";
		$field .= " ,SUM(IF(appointment.appointment_state='".appointment_STATE_CANCEL."',doctors_pay_price,0)) as canceldoctorsamount";
		$field .= " ,SUM(IF(appointment.appointment_state<>'".appointment_STATE_CANCEL."' and appointment.appointment_state<>'".appointment_STATE_NEW."',doctors_num,0)) as finishdoctorsnum";
		$field .= " ,SUM(IF(appointment.appointment_state<>'".appointment_STATE_CANCEL."' and appointment.appointment_state<>'".appointment_STATE_NEW."',doctors_pay_price,0)) as finishdoctorsamount";
	    if (!trim($this->search_arr['appointmentby'])){
		    $this->search_arr['appointmentby'] = 'doctorsnum desc';
		}
		$appointmentby = trim($this->search_arr['appointmentby']).',doctors_id desc';
		//统计记录总条数
		$count_arr = $model->statByappointmentdoctors($where, 'count(DISTINCT doctors_id) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
		if ($this->search_arr['exporttype'] == 'excel'){
		    $statlist_tmp = $model->statByappointmentdoctors($where, $field, 0, 0, $appointmentby, 'doctors_id');
		} else {
		    $statlist_tmp = $model->statByappointmentdoctors($where, $field, array(10,$countnum), 0, $appointmentby, 'doctors_id');
		}
		$statheader = array();
        $statheader[] = array('text'=>'商品名称','key'=>'doctors_name','class'=>'alignleft');
        $statheader[] = array('text'=>'下单商品数','key'=>'doctorsnum','isappointment'=>1);
        $statheader[] = array('text'=>'下单金额','key'=>'doctorsamount','isappointment'=>1);
        $statheader[] = array('text'=>'取消商品数','key'=>'canceldoctorsnum','isappointment'=>1);
        $statheader[] = array('text'=>'取消金额','key'=>'canceldoctorsamount','isappointment'=>1);
        $statheader[] = array('text'=>'完成商品数','key'=>'finishdoctorsnum','isappointment'=>1);
        $statheader[] = array('text'=>'完成金额','key'=>'finishdoctorsamount','isappointment'=>1);
        foreach ((array)$statlist_tmp as $k=>$v){
            $tmp = $v;
            foreach ($statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
                if ($h_v['key'] == 'doctors_name'){
                    $tmp[$h_v['key']] = '<a href="'.urlclinic('doctors', 'index', array('doctors_id' => $v['doctors_id'])).'" target="_blank">'.$v['doctors_name'].'</a>';
                }
            }
            $statlist[] = $tmp;
        }
	    if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
			foreach ($statheader as $k=>$v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);			    
			}
			//data
			foreach ($statlist as $k=>$v){
    			foreach ($statheader as $h_k=>$h_v){
    			    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);			    
    			}
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('团购商品统计',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('团购商品统计',CHARSET).date('Y-m-d-H',time()));
			exit();
        } else {
            Tpl::output('statheader',$statheader);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('show_page',$model->showpage(2));
    		Tpl::output('searchtime',$_GET['t']);
    		Tpl::output('appointmentby',$this->search_arr['appointmentby']);
    		Tpl::output('actionurl',"index.php?act={$this->search_arr['act']}&op={$this->search_arr['op']}&t={$this->search_arr['t']}");
        	Tpl::showpage('stat.listandappointment','null_layout');
        }
	}
}