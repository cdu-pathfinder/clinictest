<?php
/**
 * SNS首页
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_snsindexControl extends BaseCircleControl {
	const MAX_RECORDNUM = 20;//允许插入新记录的最大条数(注意在sns中该常量是一样的，注意与member_snshome中的该常量一致)
	public function __construct(){
		parent::__construct();
		Tpl::output('relation','3');//为了跟home页面保持一致所以输出此变量
		Language::read('member_sns');
		//允许插入新记录的最大条数
		Tpl::output('max_recordnum',self::MAX_RECORDNUM);
	}
	/**
	 * 喜欢商品(访客登录后操作)
	 */
	public function editlikeOp(){
		$obj_validate = new Validate();
		$validate_arr[] = array("input"=>$_GET["id"], "require"=>"true","message"=>Language::get('sns_likedoctors_choose'));			
		$obj_validate -> validateparam = $validate_arr;
		$error = $obj_validate->validate();			
		if ($error != ''){
			showDialog($error,'','error');
		}
		//查询会员信息
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));
		if (empty($member_info)){
			showDialog(Language::get('sns_member_error'),'','error');
		}
		//查询商品信息			
		$doctors_model = Model('doctors');
		$condition = array();
		$condition['doctors_id'] = intval($_GET["id"]);
		//$condition['doctors_state'] = '0';
		$doctors_info = $doctors_model->getdoctorsInfo($condition,'doctors_id,doctors_name,doctors_image,doctors_price,doctors_freight,doctors_collect,clic_id,clic_name');
		if (empty($doctors_info)){
			showDialog(Language::get('sns_doctors_error'),'','error');
		}
		$sharedoctors_model = Model('sns_sharedoctors');
		//判断该商品是否已经存在分享记录
		$sharedoctors_info = $sharedoctors_model->getSharedoctorsInfo(array('share_memberid'=>"{$_SESSION['member_id']}",'share_doctorsid'=>"{$doctors_info['doctors_id']}"));
		if (!empty($sharedoctors_info) && $sharedoctors_info['share_islike'] == 1){
			showDialog(Language::get('sns_likedoctors_exist'),'','error');
		}
		if (empty($sharedoctors_info)){
			//添加分享商品信息
			$insert_arr = array();
			$insert_arr['share_doctorsid'] = $doctors_info['doctors_id'];
			$insert_arr['share_memberid'] = $_SESSION['member_id'];
			$insert_arr['share_membername'] = $_SESSION['member_name'];
			$insert_arr['share_content'] = '';
			$insert_arr['share_likeaddtime'] = time();
			$insert_arr['share_privacy'] = 0;
			$insert_arr['share_commentcount'] = 0;
			$insert_arr['share_islike'] = 1;
			$result = $sharedoctors_model->sharedoctorsAdd($insert_arr);
			unset($insert_arr);
		}else {
			//更新分享商品信息
			$update_arr = array();
			$update_arr['share_likeaddtime'] = time();
			$update_arr['share_islike'] = 1;
			$result = $sharedoctors_model->editSharedoctors($update_arr,array('share_id'=>"{$sharedoctors_info['share_id']}"));
			unset($update_arr);
		}
		if ($result){
			//商品缓存数据更新
			//生成缓存的键值
			$hash_key = $doctors_info['doctors_id'];
			//先查找$hash_key缓存
			if ($_cache = rcache($hash_key,'doc')){
				$_cache['likenum'] = intval($_cache['likenum'])+1;
				//缓存商品信息
				wcache($hash_key,$_cache,'doc');
			}
			//更新SNS商品表信息
			$snsdoctors_model = Model('sns_doctors');
			$snsdoctors_info = $snsdoctors_model->getdoctorsInfo(array('snsdoctors_doctorsid'=>"{$doctors_info['doctors_id']}"));
			if (empty($snsdoctors_info)){
				//添加SNS商品
				$insert_arr = array();
				$insert_arr['snsdoctors_doctorsid'] = $doctors_info['doctors_id'];
				$insert_arr['snsdoctors_doctorsname'] = $doctors_info['doctors_name'];
				$insert_arr['snsdoctors_doctorsimage'] = $doctors_info['doctors_image'];
				$insert_arr['snsdoctors_doctorsprice'] = $doctors_info['doctors_price'];
				$insert_arr['snsdoctors_clicid'] = $doctors_info['clic_id'];
				$insert_arr['snsdoctors_clicname'] = $doctors_info['clic_name'];
				$insert_arr['snsdoctors_addtime'] = time();
				$insert_arr['snsdoctors_likenum'] = 1;
				$insert_arr['snsdoctors_likemember'] = "{$_SESSION['member_id']}";
				$insert_arr['snsdoctors_sharenum'] = 0;
				$snsdoctors_model->doctorsAdd($insert_arr);
				unset($insert_arr);
			}else {
				//更新SNS商品
				$update_arr = array();
				$update_arr['snsdoctors_likenum'] = intval($snsdoctors_info['snsdoctors_likenum'])+1;
				$likemember_arr = array();
				if (!empty($snsdoctors_info['snsdoctors_likemember'])){
					$likemember_arr = explode(',',$snsdoctors_info['snsdoctors_likemember']);
				}
				$likemember_arr[] = $_SESSION['member_id'];
				$update_arr['snsdoctors_likemember'] = implode(',',$likemember_arr);
				$snsdoctors_model->editdoctors($update_arr,array('snsdoctors_doctorsid'=>"{$doctors_info['doctors_id']}"));
			}
			//添加喜欢动态
			$tracelog_model = Model('sns_tracelog');
			$insert_arr = array();
			$insert_arr['trace_originalid'] = '0';
			$insert_arr['trace_originalmemberid'] = '0';
			$insert_arr['trace_memberid'] = $_SESSION['member_id'];
			$insert_arr['trace_membername'] = $_SESSION['member_name'];
			$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];
			$insert_arr['trace_title'] = Language::get('sns_likedoctors_title');
			$content_str = '';
			$content_str .= "<div class=\"fd-media\">
				<div class=\"doctorsimg\"><a target=\"_blank\" href=\"".urlclinic('doctors', 'index', array('doctors_id'=>$doctors_info['doctors_id']))."\"><img src=\"".thumb($doctors_info, 240)."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$doctors_info['doctors_name']}\"></a></div>
				<div class=\"doctorsinfo\">
					<dl>
						<dt><a target=\"_blank\" href=\"".urlclinic('doctors', 'index', array('doctors_id'=>$doctors_info['doctors_id']))."\">".$doctors_info['doctors_name']."</a></dt>
						<dd>".Language::get('sns_sharedoctors_price').Language::get('nc_colon').Language::get('currency').$doctors_info['doctors_clic_price']."</dd>
						<dd>".Language::get('sns_sharedoctors_freight').Language::get('nc_colon').Language::get('currency').$doctors_info['py_price']."</dd>
                  		<dd nctype=\"collectbtn_{$doctors_info['doctors_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_doctors(\'{$doctors_info['doctors_id']}\',\'succ\',\'collectbtn_{$doctors_info['doctors_id']}\');\">".Language::get('sns_sharedoctors_collect')."</a>&nbsp;&nbsp;(".$doctors_info['doctors_collect'].Language::get('sns_collecttip').")</dd>
                  	</dl>
                  </div>
             </div>";
			$insert_arr['trace_content'] = $content_str;
			$insert_arr['trace_addtime'] = time();
			$insert_arr['trace_state'] = '0';
			$insert_arr['trace_privacy'] = 0;
			$insert_arr['trace_commentcount'] = 0;
			$insert_arr['trace_copycount'] = 0;
			$result = $tracelog_model->tracelogAdd($insert_arr);
			$js = "var obj = $(\"#likestat_{$doctors_info['doctors_id']}\"); $(\"#likestat_{$doctors_info['doctors_id']}\").find('i').addClass('noaction');$(obj).find('a').addClass('noaction'); var countobj=$('[nc_type=\'likecount_{$doctors_info['doctors_id']}\']');$(countobj).html(parseInt($(countobj).text())+1);";
			showDialog(Language::get('nc_common_op_succ'),'','succ',$js);
		}else {
			showDialog(Language::get('nc_common_op_fail'),'','error');		
		}
	}
}
