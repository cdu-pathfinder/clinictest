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

class stat_memberControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_member&op=newmember','lang'=>'stat_newmember'),
        array('url'=>'act=stat_member&op=analyze','lang'=>'stat_memberanalyze'),
        array('url'=>'act=stat_member&op=scale','lang'=>'stat_scaleanalyze'),
        array('url'=>'act=stat_member&op=area','lang'=>'stat_areaanalyze'),
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
		if (in_array($_REQUEST['op'],array('newmember','analyze','scale','area'))){
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
	 * 新增会员
	 */
    public function newmemberOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		$statlist = array();//统计数据列表
		//新增总数数组
		$count_arr = array('up'=>0,'curr'=>0);
		$where = array();
		$field = ' count(*) as allnum ';
		if($this->search_arr['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				//统计图数据
				$curr_arr[$i] = 0;//今天
				$up_arr[$i] = 0;//昨天
				//统计表数据
				$currlist_arr[$i]['timetext'] = $i;
				
				//方便搜索会员列表，计算开始时间和结束时间
				$currlist_arr[$i]['stime'] = $this->search_arr['day']['search_time']+$i*3600;
				$currlist_arr[$i]['etime'] = $currlist_arr[$i]['stime']+3600;
				
				$uplist_arr[$i]['val'] = 0;
				$currlist_arr[$i]['val'] = 0;
				//横轴
				$stat_arr['xAxis']['categories'][] = "$i";
			}
			$stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
			$etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
			//总计的查询时间
			$count_arr['seartime'] = ($stime+86400).'|'.$etime;
			
			$today_day = @date('d', $this->search_arr['day']['search_time']);//今天日期
			$yesterday_day = @date('d', $stime);//昨天日期
			
			$where['member_time'] = array('between',array($stime,$etime));
			$field .= ' ,DAY(FROM_UNIXTIME(member_time)) as dayval,HOUR(FROM_UNIXTIME(member_time)) as hourval ';
			$memberlist = $model->statByMember($where, $field, 0, '', 'dayval,hourval');
			if($memberlist){
				foreach($memberlist as $k => $v){
					if($today_day == $v['dayval']){
						$curr_arr[$v['hourval']] = intval($v['allnum']);
						$currlist_arr[$v['hourval']]['val'] = intval($v['allnum']);
						$count_arr['curr'] += intval($v['allnum']);
					}
					if($yesterday_day == $v['dayval']){
						$up_arr[$v['hourval']] = intval($v['allnum']);
						$uplist_arr[$v['hourval']]['val'] = intval($v['allnum']);
						$count_arr['up'] += intval($v['allnum']);
					}
				}
			}
			$stat_arr['series'][0]['name'] = 'yesterday';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'today';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			
			//统计数据标题
			$statlist['headertitle'] = array('hour','yesterday','today','day on day');
			Tpl::output('actionurl','index.php?act=stat_member&op=newmember&search_type=day&search_time='.date('Y-m-d',$this->search_arr['day']['search_time']));
		}
		
		if($this->search_arr['search_type'] == 'week'){
			$current_weekarr = explode('|', $this->search_arr['week']['current_week']);
			$stime = strtotime($current_weekarr[0])-86400*7;
			$etime = strtotime($current_weekarr[1])+86400-1;
			//总计的查询时间
			$count_arr['seartime'] = ($stime+86400*7).'|'.$etime;
			
			$up_week = @date('W', $stime);//上周
			$curr_week = @date('W', $etime);//本周
			
			//构造横轴数据
			for($i=1; $i<=7; $i++){
				//统计图数据
				$up_arr[$i] = 0;
				$curr_arr[$i] = 0;
				$tmp_weekarr = getSystemWeekArr();
				//统计表数据
				$currlist_arr[$i]['timetext'] = $tmp_weekarr[$i];
				//方便搜索会员列表，计算开始时间和结束时间
				$currlist_arr[$i]['stime'] = strtotime($current_weekarr[0])+($i-1)*86400;
				$currlist_arr[$i]['etime'] = $currlist_arr[$i]['stime']+86400 - 1;
				
				$uplist_arr[$i]['val'] = 0;
				$currlist_arr[$i]['val'] = 0;
				//横轴
				$stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
				unset($tmp_weekarr);
			}
			$where['member_time'] = array('between', array($stime,$etime));
			$field .= ',WEEKOFYEAR(FROM_UNIXTIME(member_time)) as weekval,WEEKDAY(FROM_UNIXTIME(member_time))+1 as dayofweekval ';
			$memberlist = $model->statByMember($where, $field, 0, '', 'weekval,dayofweekval');			
			
			if($memberlist){
				foreach($memberlist as $k=>$v){
					if ($up_week == intval($v['weekval'])){
						$up_arr[$v['dayofweekval']] = intval($v['allnum']);
						$uplist_arr[$v['dayofweekval']]['val'] = intval($v['allnum']);
						$count_arr['up'] += intval($v['allnum']);
					}
					if ($curr_week == $v['weekval']){
						$curr_arr[$v['dayofweekval']] = intval($v['allnum']);
						$currlist_arr[$v['dayofweekval']]['val'] = intval($v['allnum']);
						$count_arr['curr'] += intval($v['allnum']);
					}
				}
			}
			
			$stat_arr['series'][0]['name'] = 'last week';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'this week';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			//统计数据标题
			$statlist['headertitle'] = array('week','last week','this week','week on week');
			Tpl::output('actionurl','index.php?act=stat_member&op=newmember&search_type=week&searchweek_year='.$this->search_arr['week']['current_year'].'&searchweek_month='.$this->search_arr['week']['current_month'].'&searchweek_week='.$this->search_arr['week']['current_week']);
		}
		
		if($this->search_arr['search_type'] == 'month'){
			$stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01 -1 month");
			$etime = getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
			//总计的查询时间
			$count_arr['seartime'] = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01").'|'.$etime;
			
			$up_month = date('m',$stime);
			$curr_month = date('m',$etime);
			//计算横轴的最大量（由于每个月的天数不同）
			$up_dayofmonth = date('t',$stime);
			$curr_dayofmonth = date('t',$etime);
			$x_max = $up_dayofmonth > $curr_dayofmonth ? $up_dayofmonth : $curr_dayofmonth;
			
		    //构造横轴数据
			for($i=1; $i<=$x_max; $i++){
				//统计图数据
				$up_arr[$i] = 0;
				$curr_arr[$i] = 0;
				//统计表数据
				$currlist_arr[$i]['timetext'] = $i;
				//方便搜索会员列表，计算开始时间和结束时间
				$currlist_arr[$i]['stime'] = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01")+($i-1)*86400;
				$currlist_arr[$i]['etime'] = $currlist_arr[$i]['stime']+86400 - 1;
				
				$uplist_arr[$i]['val'] = 0;
				$currlist_arr[$i]['val'] = 0;
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
				unset($tmp_montharr);
			}
			$where['member_time'] = array('between', array($stime,$etime));
			$field .= ',MONTH(FROM_UNIXTIME(member_time)) as monthval,day(FROM_UNIXTIME(member_time)) as dayval ';
			$memberlist = $model->statByMember($where, $field, 0, '', 'monthval,dayval');
		    if($memberlist){
				foreach($memberlist as $k=>$v){
					if ($up_month == $v['monthval']){
						$up_arr[$v['dayval']] = intval($v['allnum']);
						$uplist_arr[$v['dayval']]['val'] = intval($v['allnum']);
						$count_arr['up'] += intval($v['allnum']);
					}
					if ($curr_month == $v['monthval']){
						$curr_arr[$v['dayval']] = intval($v['allnum']);
						$currlist_arr[$v['dayval']]['val'] = intval($v['allnum']);
						$count_arr['curr'] += intval($v['allnum']);
					}
				}
			}
			$stat_arr['series'][0]['name'] = 'last month';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'this month';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			//统计数据标题
			$statlist['headertitle'] = array('date','last month','this month','month on month');
			Tpl::output('actionurl','index.php?act=stat_member&op=newmember&search_type=month&searchmonth_year='.$this->search_arr['month']['current_year'].'&searchmonth_month='.$this->search_arr['month']['current_month']);
		}
		
		//计算同比
		foreach ((array)$currlist_arr as $k=>$v){
			$tmp = array();
			$tmp['timetext'] = $v['timetext'];
			$tmp['seartime'] = $v['stime'].'|'.$v['etime'];
			$tmp['currentdata'] = $v['val'];
			$tmp['updata'] = $uplist_arr[$k]['val'];
			$tmp['tbrate'] = getTb($tmp['updata'], $tmp['currentdata']);
			$statlist['data'][]  = $tmp;
		}
		//计算总结同比
		$count_arr['tbrate'] = getTb($count_arr['up'], $count_arr['curr']);
		
		//导出Excel
        if ($_GET['exporttype'] == 'excel'){
			//导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
			foreach ($statlist['headertitle'] as $v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
			}
			//data
			foreach ($statlist['data'] as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['timetext']);
				$excel_data[$k+1][] = array('format'=>'Number','data'=>$v['updata']);
				$excel_data[$k+1][] = array('format'=>'Number','data'=>$v['currentdata']);
				$excel_data[$k+1][] = array('data'=>$v['tbrate']);
			}
			$excel_data[count($statlist['data'])+1][] = array('data'=>'总计');
			$excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['up']);
			$excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['curr']);
			$excel_data[count($statlist['data'])+1][] = array('data'=>$count_arr['tbrate']);
				
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('new member statistics',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('new member statistics',CHARSET).date('Y-m-d-H',time()));
			exit();
		} else {
			//得到统计图数据
    		$stat_arr['title'] = 'new member statistics';
            $stat_arr['yAxis'] = 'new members';
    		$stat_json = getStatData_LineLabels($stat_arr);
    		Tpl::output('stat_json',$stat_json);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('count_arr',$count_arr);
    		Tpl::output('top_link',$this->sublink($this->links, 'newmember'));
    		Tpl::showpage('stat.newmember');
		}
	}
	/**
	 * 会员分析
	 */
	public function analyzeOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
	 	//构造横轴数据
		for($i=1; $i<=15; $i++){
			//横轴
			$stat_arr['xAxis']['categories'][] = $i;
		}
		$stat_arr['title'] = 'patient Top15';
		$stat_arr['legend']['enabled'] = false;
		
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		
		$where = array();
		$where['statm_time'] = array('between',$searchtime_arr);
		//下单量
		$where['statm_appointmentnum'] = array('gt',0);
		$field = ' statm_memberid, statm_membername, sum(statm_appointmentnum) as appointmentnum ';
		$appointmentnum_listtop15 = $model->statByStatmember($where, $field, 0, 15, 'appointmentnum desc,statm_memberid desc', 'statm_memberid');
		$stat_appointmentnum_arr = $stat_arr;
		$stat_appointmentnum_arr['series'][0]['name'] = 'appointments';
		$stat_appointmentnum_arr['series'][0]['data'] = array();
		for ($i = 0; $i < 15; $i++){
		    $stat_appointmentnum_arr['series'][0]['data'][] = array('name'=>strval($appointmentnum_listtop15[$i]['statm_membername']),'y'=>intval($appointmentnum_listtop15[$i]['appointmentnum']));
		}
        $stat_appointmentnum_arr['yAxis'] = 'appointments';
		$statappointmentnum_json = getStatData_Column2D($stat_appointmentnum_arr);
		unset($stat_appointmentnum_arr);
		Tpl::output('statappointmentnum_json',$statappointmentnum_json);
		Tpl::output('appointmentnum_listtop15',$appointmentnum_listtop15);
		
		//下单商品件数
		$where['statm_doctorsnum'] = array('gt',0);
		$field = ' statm_memberid, statm_membername, sum(statm_doctorsnum) as doctorsnum ';
		$doctorsnum_listtop15 = $model->statByStatmember($where, $field, 0, 15, 'doctorsnum desc,statm_memberid desc', 'statm_memberid');
		$stat_doctorsnum_arr = $stat_arr;
		$stat_doctorsnum_arr['series'][0]['name'] = 'doctors booked';
		$stat_doctorsnum_arr['series'][0]['data'] = array();
		for ($i = 0; $i < 15; $i++){
		    $stat_doctorsnum_arr['series'][0]['data'][] = array('name'=>strval($doctorsnum_listtop15[$i]['statm_membername']),'y'=>intval($doctorsnum_listtop15[$i]['doctorsnum']));
		}
        $stat_doctorsnum_arr['yAxis'] = 'doctors booked';
		$statdoctorsnum_json = getStatData_Column2D($stat_doctorsnum_arr);
		unset($stat_doctorsnum_arr);
		Tpl::output('statdoctorsnum_json',$statdoctorsnum_json);
		Tpl::output('doctorsnum_listtop15',$doctorsnum_listtop15);
		
		//下单金额
		$where['statm_appointmentamount'] = array('gt',0);
		$field = ' statm_memberid, statm_membername, sum(statm_appointmentamount) as appointmentamount ';
		$appointmentamount_listtop15 = $model->statByStatmember($where, $field, 0, 15, 'appointmentamount desc,statm_memberid desc', 'statm_memberid');
		$stat_appointmentamount_arr = $stat_arr;
		$stat_appointmentamount_arr['series'][0]['name'] = 'booked price';
		$stat_appointmentamount_arr['series'][0]['data'] = array();
		for ($i = 0; $i < 15; $i++){
		    $stat_appointmentamount_arr['series'][0]['data'][] = array('name'=>strval($appointmentamount_listtop15[$i]['statm_membername']),'y'=>floatval($appointmentamount_listtop15[$i]['appointmentamount']));
		}
        $stat_appointmentamount_arr['yAxis'] = 'booked price';
		$statappointmentamount_json = getStatData_Column2D($stat_appointmentamount_arr);
		unset($stat_appointmentamount_arr);
		Tpl::output('statappointmentamount_json',$statappointmentamount_json);
		Tpl::output('appointmentamount_listtop15',$appointmentamount_listtop15);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'analyze'));
    	Tpl::showpage('stat.memberanalyze');
	}
	
	/**
	 * 会员分析异步详细列表
	 */
	public function analyzeinfoOp(){
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['statm_time'] = array('between',$searchtime_arr);
		$memberlist = array();
		//查询统计数据
		$field = ' statm_memberid, statm_membername ';
		switch ($_GET['type']){
		   case 'appointmentamount':
		       $where['statm_appointmentamount'] = array('gt',0);
		       $field .= ' ,sum(statm_appointmentamount) as appointmentamount ';
		       $caption = 'booked price';
		       break;
		   case 'doctorsnum':
		       $where['statm_doctorsnum'] = array('gt',0);
		       $field .= ' ,sum(statm_doctorsnum) as doctorsnum ';
		       $caption = 'doctors';
		       break;
		   default:
		       $_GET['type'] = 'appointmentnum';
		       $where['statm_appointmentnum'] = array('gt',0);
		       $field .= ' ,sum(statm_appointmentnum) as appointmentnum ';
		       $caption = 'doctors booked';
		       break;
		}
		//查询记录总条数
		$count_arr = $model->statByStatmember($where, 'count(DISTINCT statm_memberid) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
		if ($_GET['exporttype'] == 'excel'){
		    $memberlist = $model->statByStatmember($where, $field, 0, 0, "{$_GET['type']} desc,statm_memberid desc", 'statm_memberid');
		} else {
		    $memberlist = $model->statByStatmember($where, $field, array(10,$countnum), 0, "{$_GET['type']} desc,statm_memberid desc", 'statm_memberid');
		}
		$_REQUEST['curpage'] = $_REQUEST['curpage']?$_REQUEST['curpage']:1;
		foreach ((array)$memberlist as $k=>$v){
		    $v['number'] = ($_REQUEST['curpage'] - 1) * 10 + $k + 1;
		    $memberlist[$k] = $v;
		}
		//导出Excel
        if ($_GET['exporttype'] == 'excel'){
			//导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header		
			$excel_data[0][] = array('styleid'=>'s_title','data'=>'number');
			$excel_data[0][] = array('styleid'=>'s_title','data'=>'membername');
			$excel_data[0][] = array('styleid'=>'s_title','data'=>$caption);
			//data
			foreach ($memberlist as $k=>$v){
				$excel_data[$k+1][] = array('format'=>'Number','data'=>$v['number']);
				$excel_data[$k+1][] = array('data'=>$v['statm_membername']);
				$excel_data[$k+1][] = array('data'=>$v[$_GET['type']]);
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('member'.$caption.'statistics',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('member'.$caption.'statistics',CHARSET).date('Y-m-d-H',time()));
			exit();
		} else {
		    Tpl::output('caption',$caption);
    		Tpl::output('stat_field',$_GET['type']);
    		Tpl::output('memberlist',$memberlist);
    		Tpl::output('show_page',$model->showpage(2));
    		Tpl::showpage('stat.memberanalyze.info','null_layout');   
		}
	}
	
	/**
	 * 查看会员列表
	 */
	public function showmemberOp(){
	    Language::read('member');
		$model = Model('stat');
		$where = array();
		if (in_array($_GET['type'],array('newbyday','newbyweek','newbymonth'))){
		    $actionurl = 'index.php?act=stat_member&op=showmember&type=newbyday&t='.$_GET['t'];
		    $searchtime_arr = explode('|',$_GET['t']);
		    $where['member_time'] = array('between',$searchtime_arr);
		}
		if ($this->search_arr['exporttype'] == 'excel'){
		    $member_list = $model->getMemberList($where);
		} else {
		    $member_list = $model->getMemberList($where, '', 10);
		}
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
			}
		}
		//导出Excel
        if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>L('member_index_name'));
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'register time');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>L('member_index_login_time'));
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>L('member_index_last_login'));
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>L('member_index_points'));
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>L('member_index_preclic'));
			//data
			foreach ($member_list as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['member_name'].'('.L('member_index_true_name,nc_colon').$v['member_truename'].')');
				$excel_data[$k+1][] = array('data'=>$v['member_time']);
				$excel_data[$k+1][] = array('format'=>'Number','data'=>$v['member_login_num']);
				$excel_data[$k+1][] = array('data'=>$v['member_login_time'].'(IP:'.$v['member_login_ip'].')');
				$excel_data[$k+1][] = array('data'=>$v['member_points']);
				$excel_data[$k+1][] = array('data'=>L('member_index_available,nc_colon').$v['available_predeposit'].L('currency_zh').'('.L('member_index_frozen,nc_colon').$v['freeze_predeposit'].L('currency_zh').')');
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('new member',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('new member',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
        Tpl::output('actionurl',$actionurl);
		Tpl::output('member_list',$member_list);
		Tpl::output('show_page',$model->showpage(2));
		$this->links[] = array('url'=>'act=stat_member&op=showmember','lang'=>'stat_memberlist');
		Tpl::output('top_link',$this->sublink($this->links, 'showmember'));
	    Tpl::showpage('stat.info.memberlist');
	}
	
	/**
	 * 会员规模
	 */
	public function scaleOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		$statlist = array();//统计数据列表
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		$where = array();
		$where['statm_time'] = array('between',$searchtime_arr);
		if (trim($this->search_arr['membername'])){
		    $where['statm_membername'] = array('like',"%".trim($this->search_arr['membername'])."%");
		}
		$field = ' statm_memberid, statm_membername, statm_time, sum(statm_appointmentamount) as appointmentamount, sum(statm_predincrease) as predincrease, -sum(statm_predreduce) as predreduce, sum(statm_pointsincrease) as pointsincrease, -sum(statm_pointsreduce) as pointsreduce ';		
		if (trim($this->search_arr['appointmentby'])){
		    $appointmentby = trim($this->search_arr['appointmentby']);
		} else {
		    $appointmentby = 'appointmentamount desc';
		}
		$appointmentby .= ',statm_memberid desc';
	    //查询记录总条数
		$count_arr = $model->statByStatmember($where, 'count(DISTINCT statm_memberid) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
		if ($_GET['exporttype'] == 'excel'){
		    $statlist = $model->statByStatmember($where, $field, 0, 0, $appointmentby, 'statm_memberid');
		} else {
		    $statlist = $model->statByStatmember($where, $field, array(10,$countnum), 0, $appointmentby, 'statm_memberid');
		}
	    //导出Excel
        if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员名称');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'下单金额');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'增预存款');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'减预存款');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'增积分');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'减积分');
			//data
			foreach ($statlist as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['statm_membername']);
				$excel_data[$k+1][] = array('data'=>$v['appointmentamount']);
				$excel_data[$k+1][] = array('data'=>$v['predincrease']);
				$excel_data[$k+1][] = array('data'=>$v['predreduce']);
				$excel_data[$k+1][] = array('data'=>$v['pointsincrease']);
				$excel_data[$k+1][] = array('data'=>$v['pointsreduce']);
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('Membership size analysis',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('Membership size analysis',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
		Tpl::output('statlist',$statlist);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('top_link',$this->sublink($this->links, 'scale'));
		Tpl::showpage('stat.memberscale');
	}

	/**
	 * 区域分析
	 */
	public function areaOp(){
	    if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
		Tpl::output('top_link',$this->sublink($this->links, 'area'));
		Tpl::showpage('stat.memberarea');
	}
	/**
	 * 区域分析之详细列表
	 */
	public function area_listOp(){
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$this->search_arr['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['appointment_state'] = array(array('neq',appointment_STATE_CANCEL),array('neq',appointment_STATE_NEW),'and');
		$where['appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		
		$field = ' appointment_common.reciver_province_id, count(*) as appointmentnum,sum(appointment.appointment_amount) as appointmentamount, count(DISTINCT appointment.buyer_id) as membernum ';
	    if (!trim($this->search_arr['appointmentby'])){
		    $this->search_arr['appointmentby'] = 'membernum desc';
		}
		$appointmentby = trim($this->search_arr['appointmentby']).',appointment_common.reciver_province_id';
		
		$count_arr = $model->statByappointmentCommon($where, 'count(DISTINCT appointment_common.reciver_province_id) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
		if ($this->search_arr['exporttype'] == 'excel'){
		    $statlist_tmp = $model->statByappointmentCommon($where, $field, 0, 0, $appointmentby, 'appointment_common.reciver_province_id');
		} else {
		    $statlist_tmp = $model->statByappointmentCommon($where, $field, array(10,$countnum), 0, $appointmentby, 'appointment_common.reciver_province_id');
		}
		// 地区
        require_once(BASE_DATA_PATH.'/area/area.php');
        $statheader = array();
        $statheader[] = array('text'=>'state','key'=>'provincename');
        $statheader[] = array('text'=>'memeber num','key'=>'membernum','isappointment'=>1);
        $statheader[] = array('text'=>'price booked','key'=>'appointmentamount','isappointment'=>1);
        $statheader[] = array('text'=>'num booked','key'=>'appointmentnum','isappointment'=>1);
        $statlist = array();
		foreach ((array)$statlist_tmp as $k=>$v){
		    $province_id = intval($v['reciver_province_id']);
		    $tmp = array();
		    $tmp['provincename'] = ($t = $area_array[$province_id]['area_name']) ? $t : '其他';
		    $tmp['membernum'] = $v['membernum'];
		    $tmp['appointmentamount'] = $v['appointmentamount'];
		    $tmp['appointmentnum'] = $v['appointmentnum'];
		    $statlist[] = $tmp;
		}
	    //导出Excel
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
		    $excel_obj->addWorksheet($excel_obj->charset('Regional analysis',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('Regional analysis',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
		Tpl::output('statlist',$statlist);
		Tpl::output('statheader',$statheader);
		Tpl::output('appointmentby',$this->search_arr['appointmentby']);
		Tpl::output('actionurl',"index.php?act={$this->search_arr['act']}&op={$this->search_arr['op']}&t={$this->search_arr['t']}");
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('top_link',$this->sublink($this->links, 'area'));
		Tpl::showpage('stat.listandappointment','null_layout');
	}
	/**
	 * 区域分析之地图数据
	 */
	public function area_mapOp(){
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['appointment_state'] = array(array('neq',appointment_STATE_CANCEL),array('neq',appointment_STATE_NEW),'and');
		$where['appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!(appointment.payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		$memberlist = array();
		//查询统计数据
		$field = ' appointment_common.reciver_province_id ';
		switch ($_GET['type']){
		   case 'appointmentamount':
		       $field .= ' ,count(*) as appointmentnum,sum(appointment.appointment_amount) as appointmentamount ';
		       $appointmentby = 'appointmentamount desc';
		       break;
		   case 'appointmentnum':
		       $field .= ' ,count(*) as appointmentnum ';
		       $appointmentby = 'appointmentnum desc';
		       break;
		   default:
		       $_GET['type'] = 'membernum';
		       $field .= ' ,count(DISTINCT appointment.buyer_id) as membernum ';
		       $appointmentby = 'membernum desc';
		       break;
		}
		$appointmentby .= ',appointment_common.reciver_province_id';
		$statlist_tmp = $model->statByappointmentCommon($where, $field, 10, 0, $appointmentby, 'appointment_common.reciver_province_id');
		// 地区
        require_once(BASE_DATA_PATH.'/area/area.php');
        //地图显示等级数组
        $level_arr = array(array(1,2,3),array(4,5,6),array(7,8,9),array(10,11,12));
        $statlist = array();
		foreach ((array)$statlist_tmp as $k=>$v){
		    $v['level'] = 4;//排名
		    foreach ($level_arr as $lk=>$lv){
		        if (in_array($k+1,$lv)){
		            $v['level'] = $lk;//排名
		        }
		    }
		    $province_id = intval($v['reciver_province_id']);
		    $statlist[$province_id] = $v;
		}
		$stat_arr = array();
		foreach ((array)$area_array as $k=>$v){
		    if ($statlist[$k]){
    		    switch ($_GET['type']){
        		   case 'appointmentamount':
        		       $des = "，appointmentamount：{$statlist[$k]['appointmentamount']}";
        		       break;
        		   case 'appointmentnum':
        		       $des = "，appointmentnum：{$statlist[$k]['appointmentnum']}";
        		       break;
        		   default:
        		       $des = "，membernum：{$statlist[$k]['membernum']}";
        		       break;
        		}
		        $stat_arr[] = array('cha'=>$k,'name'=>$v['area_name'],'des'=>$des,'level'=>$statlist[$k]['level']);
		    } else {
		        $des = "，No appointment data";
		        $stat_arr[] = array('cha'=>$k,'name'=>$v['area_name'],'des'=>$des,'level'=>4);
		    }
		}
		$stat_json = getStatData_Map($stat_arr);
		Tpl::output('stat_field',$_GET['type']);
		Tpl::output('stat_json',$stat_json);
		Tpl::showpage('stat.map','null_layout');   
	}
}