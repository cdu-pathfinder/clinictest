<?php
/**
 * 卖家活动管理
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
class clic_activityControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_clic_index');
    }

    public function indexOp() {
        $this->clic_activityOp();
    }

 	/**
	 * 活动管理
	 */
	public function clic_activityOp(){
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$activity	= Model('activity');
		//活动为商品活动，并且为开启状态
		$list	= $activity->getList(array('activity_type'=>'1','opening'=>true,'appointment'=>'activity.activity_sort asc'),$page);
		/**
		 * 页面输出
		 */
		Tpl::output('list',$list);
		Tpl::output('show_page',$page->show());
		self::profile_menu('clic_activity');
		Tpl::output('menu_sign','clic_activity');
		Tpl::output('menu_sign_url','index.php?act=clic_activity&op=clic_activity');
		Tpl::output('menu_sign1','activity_list');
		Tpl::showpage('clic_activity.list');
	}
	/**
	 * 参与活动
	 */
	public function activity_applyOp(){
		//根据活动编号查询活动信息
		$activity_id = intval($_GET['activity_id']);
		if($activity_id <= 0){
			showMessage(Language::get('para_error'),'index.php?act=clic_activity&op=clic_activity','html','error');
		}
		$activity_model	= Model('activity');
		$activity_info	= $activity_model->getOneById($activity_id);
		//活动类型必须是商品并且活动没有关闭并且活动进行中
		if(empty($activity_info) || $activity_info['activity_type'] != '1' || $activity_info['activity_state'] != 1 || $activity_info['activity_start_date']>time() || $activity_info['activity_end_date']<time()){
			showMessage(Language::get('clic_activity_not_exists'),'index.php?act=clic_activity&op=clic_activity','html','error');
		}
		Tpl::output('activity_info',$activity_info);
		$list	= array();//声明存放活动细节的数组
		//查询商品分类列表
		$gc	= Model('doctors_class');
		$gc_list	= $gc->getTreeClassList(3,array('appointment'=>'gc_parent_id asc,gc_sort asc,gc_id asc'));
		foreach($gc_list as $k=>$gc){
			$gc_list[$k]['gc_name']	= '';
			$gc_list[$k]['gc_name']	= str_repeat("&nbsp;",$gc['deep']*2).$gc['gc_name'];
		}
		Tpl::output('gc_list',$gc_list);
		//查询品牌列表
		$brand	= Model('brand');
		$brand_list	= $brand->getBrandList(array());
		Tpl::output('brand_list',$brand_list);
		//查询活动细节信息
		$activity_detail_model	= Model('activity_detail');
		$list	= $activity_detail_model->getdoctorsJoinList(array('activity_id'=>"$activity_id",'clic_id'=>"{$_SESSION['clic_id']}",'activity_detail_state_in'=>"'0','1','3'",'group'=>'activity_detail_state asc'));
		//构造通过与审核中商品的编号数组,以便在下方待选列表中,不显示这些内容
		$item_ids	= array();
		if(is_array($list) and !empty($list)){
			foreach($list as $k=>$v){
				$item_ids[]	= $v['item_id'];
			}
		}
		Tpl::output('list',$list);
		
		//根据查询条件查询商品列表
		$condition	= array();
		if($_GET['gc_id']!=''){
			$condition['gc_id']	= intval($_GET['gc_id']);
		}
		if($_GET['brand_id']!=''){
			$condition['brand_id']	= intval($_GET['brand_id']);
		}
		if(trim($_GET['name'])!=''){
			$condition['doctors_name'] = array('like' ,'%'.trim($_GET['name']).'%');
		}
		$condition['clic_id']		= $_SESSION['clic_id'];
		if (!empty($item_ids)){
			$condition['doctors_id']	= array('not in', $item_ids);
		}
		$model_doctors	= Model('doctors');
		$doctors_list	= $model_doctors->getdoctorsOnlineList($condition,'*', 10);
		Tpl::output('doctors_list',$doctors_list);
		Tpl::output('show_page',$model_doctors->showpage());
		Tpl::output('search',$_GET);
		/**
		 * 页面输出
		 */
		self::profile_menu('activity_apply');
		Tpl::output('menu_sign','clic_activity');
		Tpl::output('menu_sign_url','index.php?act=clic_activity&op=clic_activity');
		Tpl::output('menu_sign1','activity_apply');
		Tpl::showpage('clic_activity.apply');
	}

 	/**
	 * 活动申请保存
	 */
	public function activity_apply_saveOp(){
		//判断页面参数
		if(empty($_POST['item_id'])){
			showDialog(Language::get('clic_activity_choose_doctors'),'index.php?act=clic_activity&op=clic_activity');
		}
		$activity_id = intval($_POST['activity_id']);
		if($activity_id <= 0){
			showDialog(Language::get('para_error'),'index.php?act=clic_activity&op=clic_activity');
		}
		//根据页面参数查询活动内容信息，如果不存在则添加，存在则根据状态进行修改
		$activity_model	= Model('activity');
		$activity	= $activity_model->getOneByid($activity_id);
		//活动类型必须是商品并且活动没有关闭并且活动进行中
		if(empty($activity) || $activity['activity_type'] != '1' || $activity['activity_state'] != '1' || $activity['activity_start_date']>time() || $activity['activity_end_date']<time()){
			showDialog(Language::get('clic_activity_not_exists'),'index.php?act=clic_activity&op=clic_activity');
		}
		$activity_detail	= Model('activity_detail');
		$list	= $activity_detail->getList(array('clic_id'=>"{$_SESSION['clic_id']}",'activity_id'=>"$activity_id"));
		$ids	= array();//已经存在的活动内容编号
		$ids_state2	= array();//已经存在的被拒绝的活动编号
		if(is_array($list) and !empty($list)){
			foreach ($list as $ad){
				$ids[]	= $ad['item_id'];
				if($ad['activity_detail_state']=='2'){
					$ids_state2[]	= $ad['item_id'];
				}	
			}
		}
		//根据查询条件查询商品列表
		$condition_doctors	= array();
		$condition_doctors['clic_id']	= $_SESSION['clic_id'];
		foreach ($_POST['item_id'] as $item_id){
			$item_id = intval($item_id);
			if(!in_array($item_id,$ids)){
				$condition_doctors['doctors_id'] = $item_id;
				$input	= array();
				$input['activity_id']	= $activity_id;
				$doctors	= Model('doctors');
				$item	= $doctors->getdoctorsOnlineInfo($condition_doctors, 'doctors_name,clic_id,clic_name');
				if(empty($item)){
					continue;	
				}
				$input['item_name']	= $item['doctors_name'];
				$input['item_id']	= $item_id;
				$input['clic_id']	= $item['clic_id'];
				$input['clic_name']= $item['clic_name'];
				$activity_detail->add($input);
			}elseif(in_array($item_id,$ids_state2)){
				$input	= array();
				$input['activity_detail_state']= '0';//将重新审核状态去除
				$activity_detail->updateList($input,array('item_id'=>$item_id));
			}
		}
		showDialog(Language::get('clic_activity_submitted'),'reload','succ');
	}   

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_key='') {
        Language::read('member_layout');
        $menu_array = array(
				1=>array('menu_key'=>'clic_activity','menu_name'=>Language::get('nc_member_path_activity_list'),'menu_url'=>'index.php?act=clic_activity&op=clic_activity'),
				2=>array('menu_key'=>'activity_apply','menu_name'=>Language::get('nc_member_path_join_activity'),'menu_url'=>'')
        );
        if($menu_key == 'clic_activity') {
            unset($menu_array[2]);
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}   
