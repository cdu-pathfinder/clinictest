<?php
/**
 * 积分礼品
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class pointprodControl extends BaseHomeControl {
	private $templatestate_arr;
	public function __construct() {
		parent::__construct();
		//读取语言包
		Language::read('home_pointprod,home_voucher');
		//判断系统是否开启积分和积分中心功能
		if (C('points_isuse') != 1 || C('pointclinic_isuse') != 1){
			showMessage(Language::get('pointclinic_unavailable'),'index.php','html','error');
		}
		//根据op判断积分兑换功能是否开启
		if (in_array($_GET['op'],array('plist','pinfo')) && C('pointprod_isuse') != 1){
			showMessage(Language::get('pointprod_unavailable'),'index.php','html','error');
		}
		Tpl::output('index_sign','pointprod');
		//代金券模板状态
		$this->templatestate_arr = array('usable'=>array(1,Language::get('voucher_templatestate_usable')),'disabled'=>array(2,Language::get('voucher_templatestate_disabled')));
		//领取的代金券状态
		$this->voucherstate_arr = array('unused'=>array(1,Language::get('voucher_voucher_state_unused')),'used'=>array(2,Language::get('voucher_voucher_state_used')),'expire'=>array(3,Language::get('voucher_voucher_state_expire')));
		if($_SESSION['is_login'] == '1'){
			$model = Model();
			if (C('pointprod_isuse') == 1){
				//已选择兑换商品数
				$pcartnum = $model->table('points_cart')->where(array('pmember_id'=>$_SESSION['member_id']))->count();
				Tpl::output('pcartnum',$pcartnum);
			}
			//查询会员信息
			$member_info = $model->table('member')->field('member_points,member_avatar')->where(array('member_id'=>$_SESSION['member_id']))->find();
			Tpl::output('member_info',$member_info);
		}
	}
	public function indexOp(){
		$model = Model();
		//开启代金券功能后查询代金券相应信息
		if (C('voucher_allow') == 1){
			//查询已兑换代金券券数量
			$vouchercount = 0;
			if($_SESSION['is_login'] == '1'){
				$vouchercount = $model->table('voucher')->where(array('voucher_owner_id'=>$_SESSION['member_id'],'voucher_state'=>$this->voucherstate_arr['unused'][0]))->count();
			}
			Tpl::output('vouchercount',$vouchercount);
			//查询代金券面额
			$pricelist =  $model->table('voucher_price')->appointment('voucher_price asc')->select();
			Tpl::output('pricelist',$pricelist);
			//查询代金券列表
			$field = 'voucher_template.*,clic.clic_id,clic.clic_label,clic.clic_name,clic.clic_domain';
			$on = 'voucher_template.voucher_t_clic_id=clic.clic_id';
			$new_voucher = $model->table('voucher_template,clic')->field($field)->join('left')->on($on)->where(array('voucher_t_state'=>$this->templatestate_arr['usable'][0],'voucher_t_end_date'=>array('gt',time())))->limit(16)->select();
			if (!empty($new_voucher)){
				foreach ($new_voucher as $k=>$v){
					if (!empty($v['voucher_t_customimg'])){
						$v['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$v['voucher_t_clic_id'].DS.$v['voucher_t_customimg'];
					}else{
						$v['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.defaultdoctorsImage(240);
					}
					$v['voucher_t_limit'] = intval($v['voucher_t_limit']);
					$new_voucher[$k] = $v;
				}
			}
			Tpl::output('new_voucher',$new_voucher);
		}
		//开启积分兑换功能后查询代金券相应信息
		if (C('pointprod_isuse') == 1){
			//最新积分兑换商品
			$model_pointsprod = Model('pointprod');
			$new_pointsprod = $model_pointsprod->getPointProdListNew('*',array('pdoctors_show'=>1,'pdoctors_state'=>0),'pdoctors_sort asc,pdoctors_id desc',16);
			Tpl::output('new_pointsprod',$new_pointsprod);
			//兑换排行
			$converlist = $model_pointsprod->getPointProdListNew('*',array('pdoctors_show'=>1,'pdoctors_state'=>0),'pdoctors_salenum desc,pdoctors_id desc',3);
			Tpl::output('converlist',$converlist);
		}
		//SEO
		Model('seo')->type('point')->show();
		Tpl::showpage('pointprod');
	}
	/**
	 * 积分商品列表
	 */
	public function plistOp(){
		$model_pointsprod = Model('pointprod');
		$pointprod_list = $model_pointsprod->getPointProdListNew('*',array('pdoctors_show'=>1,'pdoctors_state'=>0),'pdoctors_sort asc,pdoctors_id desc','',16);
		Tpl::output('pointprod_list',$pointprod_list);
		//兑换排行
		$converlist = $model_pointsprod->getPointProdListNew('*',array('pdoctors_show'=>1,'pdoctors_state'=>0),'pdoctors_salenum desc,pdoctors_id desc',3);
		Tpl::output('converlist',$converlist);
		Tpl::showpage('pointprod_list');
	}
	/**
	 * 积分礼品详细
	 */
	public function pinfoOp() {
		$pid = intval($_GET['id']);
		if (!$pid){
			showMessage(Language::get('pointprod_parameter_error'),'index.php?act=pointprod','html','error');
		}
		$model = Model('pointprod');
		$prodinfo = $model->getPointProdInfoNew(array('pdoctors_id'=>$pid));
		if (!is_array($prodinfo) || count($prodinfo)<=0){
			showMessage(Language::get('pointprod_record_error'),'index.php?act=pointprod','html','error');
		}
		//兑换按钮的可用状态
		if ($prodinfo['pdoctors_islimittime'] == 1 && $prodinfo['ex_state'] == 'going'){
			$timediff = intval($prodinfo['pdoctors_endtime'])-time();
			$prodinfo['timediff']['diff_day']  = intval($timediff/86400);
			$prodinfo['timediff']['diff_hour'] = intval($timediff%86400/3600);
			$prodinfo['timediff']['diff_mins'] = intval($timediff%86400%3600/60);
			$prodinfo['timediff']['diff_secs'] = intval($timediff%86400%3600%60);
		}
		//更新礼品浏览次数
		$model->table('points_doctors')->where(array('pdoctors_id'=>$pid))->update(array('pdoctors_view'=>array('exp','pdoctors_view+1')));

		//查询兑换信息
		$appointmentprod_list = $model->table('points_appointmentdoctors,points_appointment')->join('left')->on('points_appointmentdoctors.point_appointmentid = points_appointment.point_appointmentid')->where(array('point_appointmentstate'=>array('gt',10)))->appointment('points_appointmentdoctors.point_recid desc')->limit(5)->select();
		Tpl::output('appointmentprod_list',$appointmentprod_list);
		Tpl::output('prodinfo',$prodinfo);

		$seo_param = array();
		$seo_param['name'] = $prodinfo['pdoctors_name'];
		$seo_param['key'] = $prodinfo['pdoctors_keywords'];
		$seo_param['description'] = $prodinfo['pdoctors_description'];
		Model('seo')->type('point_content')->param($seo_param)->show();
		Tpl::showpage('pointprod_info');
	}
	/**
	 * 推荐礼品
	 */
	private function getCommendPointProd(){
		$condition_arr = array();
		$condition_arr['pdoctors_show'] = '1';
		$condition_arr['pdoctors_state'] = '0';
		$condition_arr['pdoctors_commend'] = '1';
		$condition_arr['appointment'] = ' pdoctors_sort asc,pdoctors_id desc ';
		$condition_arr['limit'] = 4;
		$pointprod_model = Model('pointprod');
		$list = $pointprod_model->getPointProdList($condition_arr,$page);
		return $list;
	}
}
