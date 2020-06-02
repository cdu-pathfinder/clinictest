<?php
/**
 * 会员店铺
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

class show_clicControl extends BaseclicControl {
	public function __construct(){
		parent::__construct();
	}
	public function indexOp(){
		$doctors_class = Model('doctors');

        $condition = array();
        $condition['clic_id'] = $this->clic_info['clic_id'];

        $model_doctors = Model('doctors'); // 字段
        $fieldstr = "doctors_id,doctors_commonid,doctors_name,doctors_jingle,clic_id,clic_name,doctors_price,doctors_marketprice,doctors_storage,doctors_image,doctors_freight,doctors_salenum,color_id,evaluation_doctor_star,evaluation_count";
		//得到最新12个商品列表
        $new_doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, $fieldstr, 'doctors_id desc', 12);
        Tpl::output('new_doctors_list',$new_doctors_list);

        $condition['doctors_commend'] = 1;
		//得到12个推荐商品列表
        $recommended_doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, $fieldstr, 'doctors_id desc', 12);
        Tpl::output('recommended_doctors_list',$recommended_doctors_list);

		//幻灯片图片
        if($this->clic_info['clic_slide'] != '' && $this->clic_info['clic_slide'] != ',,,,'){
            Tpl::output('clic_slide', explode(',', $this->clic_info['clic_slide']));
            Tpl::output('clic_slide_url', explode(',', $this->clic_info['clic_slide_url']));
		}
		Tpl::output('page','index');
		Tpl::output('recommended_doctors_list',$recommended_doctors_list);
		
		Tpl::showpage('index');
	}

    public function show_articleOp() {
		//判断是否为导航页面
        $model_clic_navigation = Model('clic_navigation');
        $clic_navigation_info = $model_clic_navigation->getclicNavigationInfo(array('sn_id' => intval($_GET['sn_id'])));
        if (!empty($clic_navigation_info) && is_array($clic_navigation_info)){
            Tpl::output('clic_navigation_info',$clic_navigation_info);
            Tpl::showpage('article');
        }
    }

	/**
	 * 全部商品
	 */
	public function doctors_allOp(){

		$condition = array();
        $condition['clic_id'] = $this->clic_info['clic_id'];
        if (trim($_GET['keyword']) != '') {
            $condition['doctors_name'] = array('like', '%'.trim($_GET['keyword']).'%');
        }

		// 排序
        $appointment = $_GET['appointment'] == 1 ? 'asc' : 'desc';
		switch (trim($_GET['key'])){
			case '1':
				$appointment = 'doctors_id '.$appointment;
				break;
			case '2':
				$appointment = 'doctors_price '.$appointment;
				break;
			case '3':
				$appointment = 'doctors_salenum '.$appointment;
				break;
			case '4':
				$appointment = 'doctors_collect '.$appointment;
				break;
			case '5':
				$appointment = 'doctors_click '.$appointment;
				break;
			default:
				$appointment = 'doctors_id desc';
				break;
		}

		//查询分类下的子分类
		if (intval($_GET['stc_id']) > 0){
		    $condition['doctors_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
		}

		$model_doctors = Model('doctors');
		$fieldstr = "doctors_id,doctors_commonid,doctors_name,doctors_jingle,clic_id,clic_name,doctors_price,doctors_marketprice,doctors_storage,doctors_image,doctors_freight,doctors_salenum,color_id,evaluation_doctor_star,evaluation_count";
		
        $recommended_doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, $fieldstr, $appointment, 24);
        loadfunc('search');
        
		//输出分页
		Tpl::output('show_page',$model_doctors->showpage('5'));
		$stc_class = Model('clic_doctors_class');
		$stc_info = $stc_class->getOneById(intval($_GET['stc_id']));
		Tpl::output('stc_name',$stc_info['stc_name']);
		Tpl::output('page','index');
		Tpl::output('recommended_doctors_list',$recommended_doctors_list);
		Tpl::showpage('doctors_list');
	}

	/**
	 * ajax获取动态数量
	 */
	function ajax_clic_trend_countOp(){
		$count = Model('clic_sns_tracelog')->getclicSnsTracelogCount(array('strace_clicid'=>$this->clic_info['clic_id']));
		echo json_encode(array('count'=>$count));exit;
	}
	/**
	 * ajax 店铺流量统计入库
	 */
	public function ajax_flowstat_recordOp(){
		if($_GET['clic_id'] != '' && $_SESSION['clic_id'] != $_GET['clic_id']){
			//确定统计分表名称
			$flow_tableid = 0;
			$len = strlen(strval(intval($_GET['clic_id'])));
			$last_num = substr(strval(intval($_GET['clic_id'])), $len-1,1);
			switch ($last_num){
				case 1:
					$flow_tableid = 1;
					break;
				case 2:
					$flow_tableid = 1;
					break;
				case 3:
					$flow_tableid = 2;
					break;
				case 4:
					$flow_tableid = 2;
					break;
				case 5:
					$flow_tableid = 3;
					break;
				case 6:
					$flow_tableid = 3;
					break;
				case 7:
					$flow_tableid = 4;
					break;
				case 8:
					$flow_tableid = 4;
					break;
				case 9:
					$flow_tableid = 5;
					break;
				case 0:
					$flow_tableid = 5;
					break;
			}
			$flow_tablename = 'flowstat_'.$flow_tableid; 
			//判断是否存在当日数据信息
			$date = date('Ymd',time());
			$model = Model();
			$stat_model = Model('statistics');
			if($_GET['act_param'] == 'show_clic' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'clic_info')){
				$flow_date_array = $model->table($flow_tablename)->where(array('date'=>$date,'clic_id'=>intval($_GET['clic_id'])))->find();
			}else if($_GET['act_param'] == 'doctors' && $_GET['op_param'] == 'index'){
				$flow_date_array = $model->table($flow_tablename)->where(array('date'=>$date,'doctors_id'=>intval($_GET['doctors_id'])))->find();
				$flow_date_array_sub = $model->table($flow_tablename)->where(array('date'=>$date,'clic_id'=>intval($_GET['clic_id'])))->find();
			}
			//向数据库写入访问量数据
			$update_param = array();
			$update_param['table'] = $flow_tablename;
			$update_param['field'] = 'clicknum';
			$update_param['value'] = 1;
			if(is_array($flow_date_array) && !empty($flow_date_array)){//已经存在数据则更新
				if($_GET['act_param'] == 'show_clic' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'clic_info')){
					$update_param['where'] = "WHERE date = '".$date."' AND clic_id = '".intval($_GET['clic_id'])."' AND doctors_id = '0'";
					$stat_model->updatestat($update_param);
				}else if($_GET['act_param'] == 'doctors' && $_GET['op_param'] == 'index'){
					$update_param['where'] = "WHERE date = '".$date."' AND doctors_id = '".intval($_GET['doctors_id'])."'";
					$stat_model->updatestat($update_param);
					$update_param['where'] = "WHERE date = '".$date."' AND clic_id = '".intval($_GET['clic_id'])."' AND doctors_id = '0'";
					$stat_model->updatestat($update_param);
				}
			}else{//未存在数据则插入一行访问量数据
				if($_GET['act_param'] == 'show_clic' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'clic_info')){
					$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'clic_id'=>intval($_GET['clic_id']),'type'=>'sum','doctors_id'=>0));
				}else if($_GET['act_param'] == 'doctors' && $_GET['op_param'] == 'index'){
					if(is_array($flow_date_array_sub) && !empty($flow_date_array_sub)){//已经有店铺数据则只插入一行并更新店铺访问数据
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'clic_id'=>intval($_GET['clic_id']),'type'=>'doctors','doctors_id'=>intval($_GET['doctors_id'])));
						$update_param['where'] = "WHERE date = '".$date."' AND clic_id = '".intval($_GET['clic_id'])."' AND doctors_id = '0'";
						$stat_model->updatestat($update_param);
					}else{//没有店铺访问数据的则建立两行访问数据
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'clic_id'=>intval($_GET['clic_id']),'type'=>'sum','doctors_id'=>0));
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'clic_id'=>intval($_GET['clic_id']),'type'=>'doctors','doctors_id'=>intval($_GET['doctors_id'])));
					}
				}
			}
		}
		echo json_encode(array('done'=>true,'msg'=>'done'));
	}
}
?>
