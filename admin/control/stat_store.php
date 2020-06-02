<?php
/**
 * 统计管理（店铺）
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class stat_clicControl extends SystemControl{
	private $links = array(
        array('url'=>'act=stat_clic&op=newclic','lang'=>'stat_newclic'),
        array('url'=>'act=stat_clic&op=rank','lang'=>'stat_clicrank'),
        array('url'=>'act=stat_clic&op=degree','lang'=>'stat_clicdegree'),
    );
	public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
    }
    /**
	 * 新增店铺
	 */
    public function newclicOp(){
    	$where = array();
		$field = ' count(*) as allnum ';
		if(!$_REQUEST['search_type']){
			$_REQUEST['search_type'] = 'day';
		}
		//初始化时间
		//天
		if(!$_REQUEST['search_time']){
			$_REQUEST['search_time'] = date('Y-m-d', time());
		}
		$search_time = strtotime($_REQUEST['search_time']);//搜索的时间
		Tpl::output('search_time',$_REQUEST['search_time']);
		//周
		if(!$_REQUEST['search_time_year']){
			$_REQUEST['search_time_year'] = date('Y', time());
		}
		if(!$_REQUEST['search_time_month']){
			$_REQUEST['search_time_month'] = date('m', time());
		}
		if(!$_REQUEST['search_time_week']){
			$_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
		}
		$current_year = $_REQUEST['search_time_year'];
		$current_month = $_REQUEST['search_time_month'];
		$current_week = $_REQUEST['search_time_week'];
		$year_arr = getSystemYearArr();
		$month_arr = getSystemMonthArr();
		$week_arr = getMonthWeekArr($current_year, $current_month);
		
		Tpl::output('current_year', $current_year);
		Tpl::output('current_month', $current_month);
		Tpl::output('current_week', $current_week);
		Tpl::output('year_arr', $year_arr);
		Tpl::output('month_arr', $month_arr);
		Tpl::output('week_arr', $week_arr);
		
    	$model = Model('stat');
		$statlist = array();//统计数据列表
		if($_REQUEST['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				//统计图数据
				$curr_arr[$i] = 0;//今天
				$up_arr[$i] = 0;//昨天
				//统计表数据
				$uplist_arr[$i]['timetext'] = $i;
				$currlist_arr[$i]['timetext'] = $i;
				//方便搜索会员列表，计算开始时间和结束时间
				$currlist_arr[$i]['stime'] = $search_time+$i*3600;
				$currlist_arr[$i]['etime'] = $currlist_arr[$i]['stime']+3600;
				$uplist_arr[$i]['val'] = 0;
				$currlist_arr[$i]['val'] = 0;
				//横轴
				$stat_arr['xAxis']['categories'][] = "$i";
			}
			$stime = $search_time - 86400;//昨天0点
			$etime = $search_time + 86400 - 1;//今天24点
			
			$today_day = @date('d', $search_time);//今天日期
			$yesterday_day = @date('d', $stime);//昨天日期
			
			$where['clic_time'] = array('between',array($stime,$etime));
			$field .= ' ,DAY(FROM_UNIXTIME(clic_time)) as dayval,HOUR(FROM_UNIXTIME(clic_time)) as hourval ';
			$memberlist = $model->getNewclicStatList($where, $field, 0, '', 0, 'dayval,hourval');
			if($memberlist){
				foreach($memberlist as $k => $v){
					if($today_day == $v['dayval']){
						$curr_arr[$v['hourval']] = intval($v['allnum']);
						$currlist_arr[$v['hourval']]['val'] = intval($v['allnum']);
					}
					if($yesterday_day == $v['dayval']){
						$up_arr[$v['hourval']] = intval($v['allnum']);
						$uplist_arr[$v['hourval']]['val'] = intval($v['allnum']);
					}
				}
			}
			$stat_arr['series'][0]['name'] = 'yesterday';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'today';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			
			//统计数据标题
			$statlist['headertitle'] = array('hour','yesterday','today','day on day');
			Tpl::output('actionurl','index.php?act=stat_clic&op=newclic&search_type=day&search_time='.date('Y-m-d',$search_time));
		}
		
		if($_REQUEST['search_type'] == 'week'){
			$current_weekarr = explode('|', $current_week);
			$stime = strtotime($current_weekarr[0])-86400*7;
			$etime = strtotime($current_weekarr[1])+86400-1;
			$up_week = @date('W', $stime);//上周
			$curr_week = @date('W', $etime);//本周
			//构造横轴数据
			for($i=1; $i<=7; $i++){
				//统计图数据
				$up_arr[$i] = 0;
				$curr_arr[$i] = 0;
				$tmp_weekarr = getSystemWeekArr();
				//统计表数据
				$uplist_arr[$i]['timetext'] = $tmp_weekarr[$i];
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
			$where['clic_time'] = array('between', array($stime,$etime));
			$field .= ',WEEKOFYEAR(FROM_UNIXTIME(clic_time)) as weekval,DAYOFWEEK(FROM_UNIXTIME(clic_time)) as dayofweekval ';
			$memberlist = $model->getNewclicStatList($where, $field, 0, '', 0, 'weekval,dayofweekval');
			if($memberlist){
				foreach($memberlist as $k=>$v){
					if ($up_week == $v['weekval']){
						$up_arr[$v['dayofweekval']] = intval($v['allnum']);
						$uplist_arr[$v['dayofweekval']]['val'] = intval($v['allnum']);
					}
					if ($curr_week == $v['weekval']){
						$curr_arr[$v['dayofweekval']] = intval($v['allnum']);
						$currlist_arr[$v['dayofweekval']]['val'] = intval($v['allnum']);
					}
				}
			}
			$stat_arr['series'][0]['name'] = 'last week';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'this week';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			//统计数据标题
			$statlist['headertitle'] = array('week','last week','this week','week on week');
			Tpl::output('actionurl','index.php?act=stat_clic&op=newclic&search_type=week&search_time_year='.$current_year.'&search_time_month='.$current_month.'&search_time_week='.$current_week);
		}
		
		if($_REQUEST['search_type'] == 'month'){
			$stime = strtotime($current_year.'-'.$current_month."-01 -1 month");
			$etime = getMonthLastDay($current_year,$current_month)+86400-1;
			
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
				$uplist_arr[$i]['timetext'] = $i;
				$currlist_arr[$i]['timetext'] = $i;
				//方便搜索会员列表，计算开始时间和结束时间
				$currlist_arr[$i]['stime'] = strtotime($current_year.'-'.$current_month."-01")+($i-1)*86400;
				$currlist_arr[$i]['etime'] = $currlist_arr[$i]['stime']+86400 - 1;
				$uplist_arr[$i]['val'] = 0;
				$currlist_arr[$i]['val'] = 0;
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
			}
			$where['clic_time'] = array('between', array($stime,$etime));
			$field .= ',MONTH(FROM_UNIXTIME(clic_time)) as monthval,day(FROM_UNIXTIME(clic_time)) as dayval ';
			$memberlist = $model->getNewclicStatList($where, $field, 0, '', 0, 'monthval,dayval');
		    if($memberlist){
				foreach($memberlist as $k=>$v){
					if ($up_month == $v['monthval']){
						$up_arr[$v['dayval']] = intval($v['allnum']);
						$uplist_arr[$v['dayval']]['val'] = intval($v['allnum']);
					}
					if ($curr_month == $v['monthval']){
						$curr_arr[$v['dayval']] = intval($v['allnum']);
						$currlist_arr[$v['dayval']]['val'] = intval($v['allnum']);
					}
				}
			}
			$stat_arr['series'][0]['name'] = 'last month';
			$stat_arr['series'][0]['data'] = array_values($up_arr);
			$stat_arr['series'][1]['name'] = 'this month';
			$stat_arr['series'][1]['data'] = array_values($curr_arr);
			//统计数据标题
			$statlist['headertitle'] = array('date','last month','this month','month on month');
			Tpl::output('actionurl','index.php?act=stat_clic&op=newclic&search_type=month&search_time_year='.$current_year.'&search_time_month='.$current_month);
		}
		
		//计算同比
		foreach ((array)$currlist_arr as $k=>$v){
			$tmp = array();
			$tmp['seartime'] = $v['stime'].'|'.$v['etime'];
			$tmp['timetext'] = $v['timetext'];
			$tmp['currentdata'] = $v['val'];
			$tmp['updata'] = $uplist_arr[$k]['val'];
			$tmp['tbrate'] = getTb($tmp['updata'], $tmp['currentdata']);
			$statlist['data'][]  = $tmp;
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
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('new clinic stat',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('new clinic stat',CHARSET).date('Y-m-d-H',time()));
			exit();
		} else {
			//得到统计图数据
    		$stat_arr['title'] = 'new clinic stat';
            $stat_arr['yAxis'] = 'new clinics';
    		$stat_json = getStatData_LineLabels($stat_arr);
    		Tpl::output('stat_json',$stat_json);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('top_link',$this->sublink($this->links, 'newclic'));
			Tpl::showpage('stat.newclic');
		}
    }
	/**
	 * 店铺排行
	 */
    public function rankOp(){
    	$where = array();
    	if(trim($_GET['appointment_type']) != ''){
    		$where['appointment_state'] = trim($_GET['appointment_type']);
    	}
		if(!$_REQUEST['search_type']){
			$_REQUEST['search_type'] = 'day';
		}
		if(trim($_GET['clic_name']) != ''){
			$where['clic_name'] = trim($_GET['clic_name']);
		}
		//初始化时间
		//天
		if(!$_REQUEST['search_time']){
			$_REQUEST['search_time'] = date('Y-m-d', time());
		}
		$search_time = strtotime($_REQUEST['search_time']);//搜索的时间
		Tpl::output('search_time',$_REQUEST['search_time']);
		//周
		if(!$_REQUEST['search_time_year']){
			$_REQUEST['search_time_year'] = date('Y', time());
		}
		if(!$_REQUEST['search_time_month']){
			$_REQUEST['search_time_month'] = date('m', time());
		}
		if(!$_REQUEST['search_time_week']){
			$_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
		}
		$current_year = $_REQUEST['search_time_year'];
		$current_month = $_REQUEST['search_time_month'];
		$current_week = $_REQUEST['search_time_week'];
		$year_arr = getSystemYearArr();
		$month_arr = getSystemMonthArr();
		$week_arr = getMonthWeekArr($current_year, $current_month);
		
		Tpl::output('current_year', $current_year);
		Tpl::output('current_month', $current_month);
		Tpl::output('current_week', $current_week);
		Tpl::output('year_arr', $year_arr);
		Tpl::output('month_arr', $month_arr);
		Tpl::output('week_arr', $week_arr);
    	$model = Model('stat');
    	if($_REQUEST['search_type'] == 'day'){
			$stime = $search_time;//昨天0点
			$etime = $search_time + 86400 - 1;//今天24点
			Tpl::output('actionurl','index.php?act=stat_clic&op=rank&search_type=day&search_time='.date('Y-m-d',$search_time));
		}
		if($_REQUEST['search_type'] == 'week'){
			$current_weekarr = explode('|', $current_week);
			$stime = strtotime($current_weekarr[0])-86400*7;
			$etime = strtotime($current_weekarr[1])+86400-1;
			Tpl::output('actionurl','index.php?act=stat_clic&op=rank&search_type=week&search_time_year='.$current_year.'&search_time_month='.$current_month.'&search_time_week='.$current_week);
		}
		if($_REQUEST['search_type'] == 'month'){
			$stime = strtotime($current_year.'-'.$current_month."-01 0 month");
			$etime = getMonthLastDay($current_year,$current_month)+86400-1;
			Tpl::output('actionurl','index.php?act=stat_clic&op=rank&search_type=month&search_time_year='.$current_year.'&search_time_month='.$current_month);
		}
		$where['add_time'] = array('between',array($stime,$etime));
		$where['appointment_state'] = array('neq',appointment_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(appointment_state = '".appointment_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
    	$where['payment_code'] = array('exp',"!(payment_code='offline' and appointment_state <> '".appointment_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		//得到统计图数据
		if(trim($_GET['stat_type']) == 'sale'){
			$clic_list = $model->getclicSaleRank($where,'sale_amount');
			$statlist['headertitle'] = array('ranking','clinic name','turnover');
			$stat_arr['title'] = 'turnover Top15';
            $stat_arr['yAxis'] = 'turnover';
			$stat_arr['series'][0]['name'] = 'turnover';
		}else{
			$clic_list = $model->getclicSaleRank($where,'sale_num');
			$statlist['headertitle'] = array('ranking','clinic name','appointments');
			$stat_arr['title'] = 'appointment Top15';
            $stat_arr['yAxis'] = 'appointments';
            $stat_arr['series'][0]['name'] = 'appointments';
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
			foreach ($statlist['headertitle'] as $v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
			}
			//data
			foreach ($clic_list as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$k+1);
				$excel_data[$k+1][] = array('data'=>$v['clic_name']);
				$excel_data[$k+1][] = array('data'=>$v['allnum']);
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
	        if(trim($_GET['stat_type']) == 'sale'){
	        	$excel_obj->addWorksheet($excel_obj->charset('clinic turnover Top15',CHARSET));
		    	$excel_obj->generateXML($excel_obj->charset('clinic turnover Top15',CHARSET).date('Y-m-d-H',time()));
			}else{
				$excel_obj->addWorksheet($excel_obj->charset('clinic appointment Top15',CHARSET));
		    	$excel_obj->generateXML($excel_obj->charset('clinic appointment Top15',CHARSET).date('Y-m-d-H',time()));
			}
			exit();
		} else {
			$stat_arr['series'][0]['data'] = array();
			for ($i = 0; $i < 15; $i++){
			    $stat_arr['series'][0]['data'][] = array('name'=>strval($clic_list[$i]['clic_name']),'y'=>floatval($clic_list[$i]['allnum']));
			}
			//构造横轴数据
			for($i=1; $i<=15; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
			}
			$stat_arr['legend']['enabled'] = false;
    		$stat_json = getStatData_Column2D($stat_arr);
    		//总数统计
    		$amount = $model->getclicSaleStatList($where,' count(*) as allnum ');
    		$sale = $model->getclicSaleStatList($where,' sum(appointment_amount) as allnum ');
    		Tpl::output('sum_data',array($amount[0]['allnum'],$sale[0]['allnum']));
    		Tpl::output('stat_json',$stat_json);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('clic_list',$clic_list);
    		Tpl::output('top_link',$this->sublink($this->links, 'rank'));
			Tpl::showpage('stat.clicrank');
		}
    }
    /**
     * 店铺等级
     */
    public function degreeOp(){
    	$where = array();
    	$field = ' count(*) as allnum,grade_id ';
    	$model = Model('stat');
    	$memberlist = $model->getNewclicStatList($where, $field, 0, '', 0, 'grade_id');
    	$sd_list = $model->getclicDegree();
    	$statlist['headertitle'] = array();
    	$statlist['data'] = array();
    	//处理数组数据
    	if(!empty($memberlist)){
    		foreach ($memberlist as $k=>$v){
    			$memberlist[$k]['p_name'] = $sd_list[$v['grade_id']];
    			$memberlist[$k]['allnum'] = intval($v['allnum']);
    			$statlist['headertitle'][] = $sd_list[$v['grade_id']];
    			$statlist['data'][] = $v['allnum'];
    		}
    	}
    	//导出Excel
        if($_GET['exporttype'] == 'excel'){
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
				$excel_data[1][] = $v;
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
			$excel_obj->addWorksheet($excel_obj->charset('clinic level stat',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('clinic level stat',CHARSET).date('Y-m-d-H',time()));
			exit();
		}else{
			Tpl::output('actionurl','index.php?act=stat_clic&op=degree');
			$data = array(
				'title'=>'clinic level stat',
				'name'=>'clinics',
				'label_show'=>true,
				'series'=>$memberlist
			);
			Tpl::output('stat_json',getStatData_Pie($data));
    		Tpl::output('top_link',$this->sublink($this->links, 'degree'));
    		Tpl::showpage('stat.clicdegree');
		}
    }
	/**
	 * 查看店铺列表
	 */
	public function showclicOp(){
		$model = Model('stat');
		$where = array();
		if (in_array($_GET['type'],array('newbyday','newbyweek','newbymonth'))){
		    $actionurl = 'index.php?act=stat_clic&op=showclic&type=newbyday&t='.$_GET['t'];
		    $searchtime_arr = explode('|',$_GET['t']);
		    $where['clic_time'] = array('between',$searchtime_arr);
		}
		if ($_GET['exporttype'] == 'excel'){
		    $clic_list = $model->getNewclicStatList($where);
		} else {
		    $clic_list = $model->getNewclicStatList($where, '', 10);
		}
		//店铺等级
		$model_grade = Model('clic_grade');
		$grade_list = $model_grade->getGradeList();
		if (!empty($grade_list)){
			$search_grade_list = array();
			foreach ($grade_list as $k => $v){
				$search_grade_list[$v['sg_id']] = $v['sg_name'];
			}
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
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'clinic name');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'account');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'manager account');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'level');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'valid until');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'open date');
			//data
			foreach ($clic_list as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['clic_name']);
				$excel_data[$k+1][] = array('data'=>$v['member_name']);
				$excel_data[$k+1][] = array('data'=>$v['clinicer_name']);
				$excel_data[$k+1][] = array('data'=>$search_grade_list[$v['grade_id']]);
				$excel_data[$k+1][] = array('data'=>$v['clic_end_time']?date('Y-m-d', $v['clic_end_time']):'no limit');
				$excel_data[$k+1][] = array('data'=>date('Y-m-d', $v['clic_time']));
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('add clinic',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('add clinic',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
        Tpl::output('search_grade_list', $search_grade_list);
        Tpl::output('actionurl',$actionurl);
		Tpl::output('clic_list',$clic_list);
		Tpl::output('show_page',$model->showpage(2));
		$this->links[] = array('url'=>'act=stat_clic&op=showclic','lang'=>'stat_cliclist');
		Tpl::output('top_link',$this->sublink($this->links, 'showclic'));
	    Tpl::showpage('stat.info.cliclist');
	}
}