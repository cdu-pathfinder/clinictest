<?php
/**
 * SNS我的空间
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_snshomeControl extends BaseSNSControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_sns,sns_home');
		$where = array();
		$where['name']	= !empty($this->member_info['member_truename'])?$this->member_info['member_truename']:$this->member_info['member_name'];
		Model('seo')->type('sns')->param($where)->show();
	}
	/**
	 * SNS首页
	 */
	public function indexOp(){
		$this->get_visitor();	// 获取访客
		$this->sns_messageboard();	// 留言版
		
		$model = Model();
		// 分享的商品
		$where = array();
		$where['share_memberid']	= $this->master_id;
		$where['share_isshare']		= 1;
		switch ($this->relation){
			case 2:
				$where['share_privacy'] = array('in', array(0,1));
				break;
			case 1:
			default:
				$where['share_privacy'] = 0;
				break;
		}
		$doctorslist = $model->table('sns_sharedoctors,sns_doctors')
						->on('sns_sharedoctors.share_doctorsid = sns_doctors.snsdoctors_doctorsid')->join('inner')
						->where($where)->appointment('share_addtime desc')->limit(3)->select();
		if ($_SESSION['is_login'] == '1' && !empty($doctorslist)){
			foreach ($doctorslist as $k=>$v){
				if (!empty($v['snsdoctors_likemember'])){
					$v['snsdoctors_likemember_arr'] = explode(',',$v['snsdoctors_likemember']);
					$v['snsdoctors_havelike'] = in_array($_SESSION['member_id'],$v['snsdoctors_likemember_arr'])?1:0;
				}
				$doctorslist[$k] = $v;
			}
		}
		Tpl::output('doctorslist', $doctorslist);
		
		// 我的图片
		$pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id))->appointment('ap_id desc')->limit(3)->select();
		Tpl::output('pic_list', $pic_list);
		
		// 分享的店铺
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		$condition['limit']	= 1;
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		$shareclic_model = Model("sns_shareclic");
		$cliclist = $shareclic_model->getShareclicList($condition,'','*','detail');
		$cliclist_new = array();
		if (!empty($cliclist)){
			//获得店铺ID
			$clicid_arr = '';
			foreach ($cliclist as $k=>$v){
				$cliclist_new[$v['clic_id']] = $v;
			}
			$clicid_arr = array_keys($cliclist_new);
			//查询店铺推荐商品
			$doctors_model = Model('doctors');
			$doctorslist = $doctors_model->getdoctorsOnlineList(array('clic_id'=> array('in', $clicid_arr)), 'doctors_id,doctors_name,doctors_image,clic_id');
			if (!empty($doctorslist)){
				foreach ($doctorslist as $k=>$v){
					$v['doctorsurl'] = urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));
					$cliclist_new[$v['clic_id']]['doctors'][] = $v;
				}
			}
		}
		//信息输出
		Tpl::output('cliclist',$cliclist_new);
		
		$where = array();
		$where['trace_memberid']	= $this->master_id;
		$where['trace_state']		= 0;
		switch ($this->relation){
			case 2:
				$where['trace_privacy']	= array('in',array(0,1));
				break;
			case 1:
			default:
				$where['trace_privacy']	= 0;
		}
		$tracelist = $model->table('sns_tracelog')->where($where)->appointment('trace_id desc')->limit(4)->select();
		if (!empty($tracelist)){
			foreach ($tracelist as $k=>$v){
				if ($v['trace_title']){
					$v['trace_title'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $v['trace_title']);
					$v['trace_title_forward'] = '|| @'.$v['trace_membername'].Language::get('nc_colon').preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:]|$)/is",'@${4}${5}',$v['trace_title']);
				}
				if(!empty($v['trace_content'])){
					//替换内容中的siteurl
					$v['trace_content'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $v['trace_content']);
				}
				$tracelist[$k] = $v;
			}
		}
		Tpl::output('tracelist',$tracelist);
		
		Tpl::output('type','snshome');
		Tpl::output('menu_sign','snshome');
		Tpl::showpage('sns_home');
	}
	/**
	 * 获取分享和喜欢商品列表
	 */
	public function shareglistOp(){
		//查询分享商品信息
		$page	= new Page();
		$page->setEachNum(20);
		$page->setStyle('admin');		
		//动态列表
		$condition = array();
		$condition['share_memberid'] = $this->master_id;
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		if ($_GET['type'] == 'like'){
			$condition['share_islike'] = "1";//喜欢的商品
			$condition['appointment'] = " share_likeaddtime desc";
		}else {
			$condition['share_isshare'] = "1";//分享的商品
			$condition['appointment'] = " share_addtime desc";
		}
		$sharedoctors_model = Model('sns_sharedoctors');
		$doctorslist = $sharedoctors_model->getSharedoctorsList($condition,$page,'*','detail');
		if($_GET['type'] != 'like' && !empty($doctorslist)){
			$shareid_array = array();
			foreach($doctorslist as $val){
				$shareid_array[]	= $val['share_id'];
			}
			$pic_array = Model()->table('sns_albumpic')->field('count(item_id) as count,item_id,ap_cover')->where(array('ap_type'=>1, 'item_id'=>array('in', $shareid_array)))->group('item_id')->select();
			if(!empty($pic_array)){
				$pic_list = array();
				foreach ($pic_array as $val){
					$val['ap_cover'] = UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.str_ireplace('.', '_1280.', $val['ap_cover']);
					$pic_list[$val['item_id']]	= $val;
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		if ($_SESSION['is_login'] == '1' && !empty($doctorslist)){
			foreach ($doctorslist as $k=>$v){
				if (!empty($v['snsdoctors_likemember'])){
					$v['snsdoctors_likemember_arr'] = explode(',',$v['snsdoctors_likemember']);
					$v['snsdoctors_havelike'] = in_array($_SESSION['member_id'],$v['snsdoctors_likemember_arr'])?1:0;
				}
				$doctorslist[$k] = $v;
			}
		}
		//信息输出
		Tpl::output('doctorslist',$doctorslist);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','sharedoctors');
		if ($_GET['type'] == 'like'){
			Tpl::showpage('sns_likedoctorslist');
		}else {
			Tpl::showpage('sns_sharedoctorslist');
		}
	}
	/**
	 * 分享和喜欢商品详细页面
	 */
	public function doctorsinfoOp(){
		$share_id = intval($_GET['id']);
		if ($share_id <= 0){
			showDialog(Language::get('wrong_argument'),"index.php?act=member_snshome&mid={$this->master_id}",'error');
		}
		//查询分享和喜欢商品信息
		$sharedoctors_model = Model('sns_sharedoctors');
		$condition = array();
		$condition['share_id'] = "$share_id";
		$condition['share_memberid'] = "{$this->master_id}";
		$sharedoctors_list = $sharedoctors_model->getSharedoctorsList($condition,'','','detail');
		unset($condition);
		if (empty($sharedoctors_list)){
			showDialog(Language::get('wrong_argument'),"index.php?act=member_snshome&mid={$this->master_id}",'error');
		}
		$sharedoctors_info = $sharedoctors_list[0];
		if (!empty($sharedoctors_info['snsdoctors_doctorsimage'])){
			$image_arr = explode('_small',$sharedoctors_info['snsdoctors_doctorsimage']);
			$sharedoctors_info['snsdoctors_doctorsimage'] = $image_arr[0];		
		}
		$sharedoctors_info['snsdoctors_doctorsurl'] = urlclinic('doctors', 'index', array('doctors_id'=>$sharedoctors_info['snsdoctors_doctorsid']));
		if ($_SESSION['is_login'] == '1'){
			if (!empty($sharedoctors_info['snsdoctors_likemember'])){
				$sharedoctors_info['snsdoctors_likemember_arr'] = explode(',',$sharedoctors_info['snsdoctors_likemember']);
				$sharedoctors_info['snsdoctors_havelike'] = in_array($_SESSION['member_id'],$sharedoctors_info['snsdoctors_likemember_arr'])?1:0;
			}
		}
		unset($sharedoctors_list);
		
		//查询上一条记录
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		if ($_GET['type'] == 'like'){
			$condition['share_likeaddtime_gt'] = "{$sharedoctors_info['share_likeaddtime']}";
			$condition['share_islike'] = "1";
			$condition['appointment'] = "share_likeaddtime asc";
		}else {
			$condition['share_addtime_gt'] = "{$sharedoctors_info['share_addtime']}";
			$condition['share_isshare'] = "1";
			$condition['appointment'] = "share_addtime asc";
		}
		$condition['limit'] = "1";
		$sharedoctors_list = $sharedoctors_model->getSharedoctorsList($condition);
		unset($condition);
		if (empty($sharedoctors_list)){
			$sharedoctors_info['snsdoctors_isfirst'] = true;
		}else {
			$sharedoctors_info['snsdoctors_isfirst'] = false;
			$sharedoctors_info['snsdoctors_previd'] = $sharedoctors_list[0]['share_id'];
		}
		unset($sharedoctors_list);
		//查询下一条记录
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		if ($_GET['type'] == 'like'){
			$condition['share_likeaddtime_lt'] = "{$sharedoctors_info['share_likeaddtime']}";
			$condition['share_islike'] = "1";
			$condition['appointment'] = "share_likeaddtime desc";
		}else {
			$condition['share_addtime_lt'] = "{$sharedoctors_info['share_addtime']}";
			$condition['share_isshare'] = "1";
			$condition['appointment'] = "share_addtime desc";
		}
		$condition['limit'] = "1";
		
		$sharedoctors_list = $sharedoctors_model->getSharedoctorsList($condition);
		unset($condition);
		if (empty($sharedoctors_list)){
			$sharedoctors_info['snsdoctors_islast'] = true;
		}else {
			$sharedoctors_info['snsdoctors_islast'] = false;
			$sharedoctors_info['snsdoctors_nextid'] = $sharedoctors_list[0]['share_id'];
		}
		unset($sharedoctors_list);
		
		$model = Model();
		
		if ($_GET['type'] != 'like'){
			// 买下秀图片
			$pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id, 'ap_type'=>1, 'item_id'=>$share_id))->select();
			if(!empty($pic_list)) {
				foreach ($pic_list as $key=>$val){
					$pic_list[$key]['ap_cover']	= UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.str_ireplace('.', '_1024.', $val['ap_cover']);
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		
		$where = array();
		$where['share_memberid']	= $this->master_id;
		$where['share_id']			= array('neq', $share_id);
		if ($_GET['type'] == 'like'){
			$where['share_islike']	= 1;
		}else{
			$where['share_isshare']	= 1;
		}
			
		// 更多分享/喜欢商品
		$sharedoctors_list = $model->table('sns_sharedoctors,sns_doctors')->join('inner')->on('sns_sharedoctors.share_doctorsid=sns_doctors.snsdoctors_doctorsid')
							->where($where)->limit(9)->select();
		Tpl::output('sharedoctors_list', $sharedoctors_list);
		Tpl::output('sharedoctors_info',$sharedoctors_info);
		Tpl::output('menu_sign','sharedoctors');
		Tpl::showpage('sns_doctorsinfo');
	}
	/**
	 * 评论前10条记录
	 */
	public function commenttopOp(){
		$comment_model = Model('sns_comment');
		//查询评论总数
		$condition = array();
		$condition['comment_originalid'] = "{$_GET['id']}";
		$condition['comment_originaltype'] = "{$_GET['type']}";//原帖类型 0表示动态信息 1表示分享商品 2表示喜欢商品
		$condition['comment_state'] = "0";//0表示正常，1表示屏蔽
		$countnum = $comment_model->getCommentCount($condition);
		//动态列表
		$condition['limit'] = "10";
		$commentlist = $comment_model->getCommentList($condition);
		$showmore = '0';//是否展示更多的连接
		if ($countnum > count($commentlist)){
			$showmore = '1';
		}
		Tpl::output('countnum',$countnum);
		Tpl::output('showmore',$showmore);
		Tpl::output('showtype',1);//页面展示类型 0表示分页 1表示显示前几条
		Tpl::output('tid',$_GET['id']);
		Tpl::output('type',$_GET['type']);
		//验证码
		Tpl::output('nchash',substr(md5(clinic_SITE_URL.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('commentlist',$commentlist);
		Tpl::showpage('sns_commentlist','null_layout');
	}
	/**
	 * 评论列表
	 */
	public function commentlistOp(){
		$comment_model = Model('sns_comment');
		//查询评论总数
		$condition = array();
		$condition['comment_originalid'] = "{$_GET['id']}";
		$condition['comment_originaltype'] = "{$_GET['type']}";//原帖类型 0表示动态信息 1表示分享商品 
		$condition['comment_state'] = "0";//0表示正常，1表示屏蔽
		$countnum = $comment_model->getCommentCount($condition);
		//评价列表
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$commentlist = $comment_model->getCommentList($condition,$page);
		
		Tpl::output('countnum',$countnum);
		Tpl::output('tid',$_GET['id']);
		Tpl::output('type',$_GET['type']);
		Tpl::output('showtype','0');//页面展示类型 0表示分页 1表示显示前几条
		//验证码
		Tpl::output('nchash',substr(md5(clinic_SITE_URL.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('commentlist',$commentlist);
		Tpl::output('show_page',$page->show());
		Tpl::showpage('sns_commentlist','null_layout');
	}
	/**
	 * 获取店铺列表(不登录就可以查看)
	 */
	public function cliclistOp(){
		//查询分享店铺信息
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');		
		//动态列表
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		$shareclic_model = Model("sns_shareclic");
		$cliclist = $shareclic_model->getShareclicList($condition,$page,'*','detail');
		$cliclist_new = array();
		if (!empty($cliclist)){
			//获得店铺ID
			$clicid_arr = '';
			foreach ($cliclist as $k=>$v){
				$cliclist_new[$v['clic_id']] = $v;
			}			
			$clicid_arr = array_keys($cliclist_new);
			//查询店铺推荐商品
			$doctors_model = Model('doctors');
			$doctorslist = $doctors_model->getdoctorsOnlineList(array('clic_id'=> array('in', $clicid_arr), 'doctors_commend' => 1), 'doctors_id,clic_id,doctors_name,doctors_image');
			if (!empty($doctorslist)){
				foreach ($doctorslist as $k=>$v){
					$v['doctorsurl'] = urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));
					$cliclist_new[$v['clic_id']]['doctors'][] = $v;
				}
			}
            foreach ($clicid_arr as $val) {
                $cliclist_new[$val]['doctors_count'] = $doctors_model->getdoctorsCount(array('clic_id'=> $val));
            }
		}
		//信息输出
		Tpl::output('cliclist',$cliclist_new);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','shareclic');
		Tpl::showpage('sns_cliclist');
	}
	/**
	 * 动态列表页面
	 */
	public function traceOp(){
		$this->get_visitor();	// 获取访客
		$this->sns_messageboard();	// 留言版
		
		Tpl::output('menu_sign','snstrace');
		Tpl::showpage('sns_hometrace');
	}
	/**
	 * 某会员的SNS动态列表
	 */
	public function tracelistOp(){
		$tracelog_model = Model('sns_tracelog');
		$condition = array();
		$condition['trace_memberid'] = $this->master_id;
		switch ($this->relation){
			case 3:
				$condition['trace_privacyin'] = "";
				break;
			case 2:
				$condition['trace_privacyin'] = "0','1";
				break;
			case 1:
				$condition['trace_privacyin'] = "0";
				break;
			default:
				$condition['trace_privacyin'] = "0";
				break;
		}
		$condition['trace_state'] = "0";
		$count = $tracelog_model->countTrace($condition);
		//分页
		$page	= new Page();
		$page->setEachNum(30);
		$page->setStyle('admin');
		$page->setTotalNum($count);
		$delaypage = intval($_GET['delaypage'])>0?intval($_GET['delaypage']):1;//本页延时加载的当前页数
		$lazy_arr = lazypage(10,$delaypage,$count,true,$page->getNowPage(),$page->getEachNum(),$page->getLimitStart());		
		//动态列表
		$condition['limit'] = $lazy_arr['limitstart'].",".$lazy_arr['delay_eachnum'];
		$tracelist = $tracelog_model->getTracelogList($condition);
		if (!empty($tracelist)){
			foreach ($tracelist as $k=>$v){
				if ($v['trace_title']){
					$v['trace_title'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $v['trace_title']);
					$v['trace_title_forward'] = '|| @'.$v['trace_membername'].Language::get('nc_colon').preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:]|$)/is",'@${4}${5}',$v['trace_title']);
				}
				if(!empty($v['trace_content'])){
					//替换内容中的siteurl
					$v['trace_content'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $v['trace_content']);
				}
				$tracelist[$k] = $v;
			}
		}
		Tpl::output('hasmore',$lazy_arr['hasmore']);
		Tpl::output('tracelist',$tracelist);
		Tpl::output('show_page',$page->show());
		Tpl::output('type','home');
		//验证码
		Tpl::output('nchash',substr(md5(clinic_SITE_URL.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('menu_sign', 'snstrace');
		Tpl::showpage('sns_tracelist','null_layout');
	}
	/**
	 * 一条SNS动态及其评论
	 */
	public function traceinfoOp(){
		$id = intval($_GET['id']);
		if ($id<=0){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		//查询动态详细
		$tracelog_model = Model('sns_tracelog');
		$condition = array();
		$condition['trace_id'] = "$id";
		$condition['trace_memberid'] = "{$this->master_id}";
		switch ($this->relation){
			case 3:
				$condition['trace_privacyin'] = "";
				break;
			case 2:
				$condition['trace_privacyin'] = "0','1";
				break;
			case 1:
				$condition['trace_privacyin'] = "0";
				break;
			default:
				$condition['trace_privacyin'] = "0";
				break;
		}
		$condition['trace_state'] = "0";
		$tracelist = $tracelog_model->getTracelogList($condition);
		$traceinfo = array();
		if (!empty($tracelist)){
			$traceinfo = $tracelist[0];
			if ($traceinfo['trace_title']){
				$traceinfo['trace_title'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $traceinfo['trace_title']);
				$traceinfo['trace_title_forward'] = '|| @'.$traceinfo['trace_membername'].':'.preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:]|$)/is",'@${4}${5}',$traceinfo['trace_title']);
			}
			if(!empty($traceinfo['trace_content'])){
				//替换内容中的siteurl
				$traceinfo['trace_content'] = str_replace("%siteurl%", clinic_SITE_URL.DS, $traceinfo['trace_content']);
			}
		}
		Tpl::output('traceinfo',$traceinfo);
		Tpl::output('menu_sign','snshome');
		//验证码
		Tpl::output('nchash',substr(md5(clinic_SITE_URL.$_GET['act'].$_GET['op']),0,8));
		Tpl::showpage('sns_traceinfo');
	}
	/**
	 * 追加买家秀
	 */
	public function add_shareOp(){
		$sid = intval($_GET['sid']);
		$model = Model();
		if($sid > 0){
			// 查询已秀图片
			$where = array();
			$where['member_id']	= $_SESSION['member_id'];
			$where['ap_type']	= 1;
			$where['item_id']	= $sid;
			$pic_list = $model->table('sns_albumpic')->where($where)->select();
			if(!empty($pic_list)) {
				foreach ($pic_list as $key=>$val){
					$pic_list[$key]['ap_cover']	= UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$_SESSION['member_id'].'/'.str_ireplace('.', '_240.', $val['ap_cover']);
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		$sharedoctors_info = $model->table('sns_doctors')->find(intval($_GET['gid']));
		Tpl::output('sharedoctors_info', $sharedoctors_info);
		Tpl::output('sid', $sid);
		Tpl::showpage('sns_addshare', 'null_layout');
	}
	/**
	 * ajax图片上传
	 */
	public function image_uploadOp(){
		$ap_id = intval($_POST['apid']);
		/**
		 * 相册
		 */
		$model = Model();
		$default_class = $model->table('sns_albumclass')->where(array('member_id'=>$_SESSION['member_id'], 'is_default'=>1))->find();
		if(empty($default_class)){	// 验证时候存在买家秀相册，不存在添加。
			$default_class = array();
			$default_class['ac_name']		= Language::get('sns_buyershow');
			$default_class['member_id']		= $this->master_id;
			$default_class['ac_des']		= Language::get('sns_buyershow_album_des');
			$default_class['ac_sort']		= '255';
			$default_class['is_default']	= 1;
			$default_class['upload_time']	= time();
			$default_class['ac_id']			= $model->table('sns_albumclass')->insert($default_class);
		}
		
		// 验证图片数量
		$count = $model->table('sns_albumpic')->where(array('member_id'=>$_SESSION['member_id']))->count();
		if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
			$output	= array();
			$output['error']	= Language::get('sns_upload_img_max_num_error');
			$output = json_encode($output);
			echo $output;die;
		}
		
		/**
		 * 上传图片
		 */
		$upload = new UploadFile();
		if($ap_id > 0){
			$pic_info = $model->table('sns_albumpic')->find($ap_id);
			if(!empty($pic_info)) $upload->set('file_name',$pic_info['ap_cover']);		// 原图存在设置图片名称为原图名称
		}
		$upload_dir = ATTACH_MALBUM.DS.$_SESSION['member_id'].DS;
		
		$upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
		$thumb_width	= '240,1024';
		$thumb_height	= '2048,1024';
		$upload->set('max_size',C('image_max_filesize'));
		$upload->set('thumb_width',	$thumb_width);
		$upload->set('thumb_height',$thumb_height);
		
		$upload->set('fprefix',$_SESSION['member_id']);
		$upload->set('thumb_ext',	'_240,_1024');
		$result = $upload->upfile(trim($_POST['id']));
		if (!$result){
			if (strtoupper(CHARSET) == 'GBK'){
				$upload->error = Language::getUTF8($upload->error);
			}
			$output	= array();
			$output['error']	= $upload->error;
			$output = json_encode($output);
			echo $output;die;
		}
			
		
		if($ap_id <= 0){		// 如果原图存在，则不需要在插入数据库
			$img_path 		= $upload->getSysSetPath().$upload->file_name;
			list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.$_SESSION['member_id'].DS.$img_path);
	
			$image = explode('.', $_FILES[trim($_POST['id'])]["name"]);
	
	
			if(strtoupper(CHARSET) == 'GBK'){
				$image['0'] = Language::getGBK($image['0']);
			}
			$insert = array();
			$insert['ap_name']		= $image['0'];
			$insert['ac_id']		= $default_class['ac_id'];
			$insert['ap_cover']		= $img_path;
			$insert['ap_size']		= intval($_FILES[trim($_POST['id'])]['size']);
			$insert['ap_spec']		= $width.'x'.$height;
			$insert['upload_time']	= time();
			$insert['member_id']	= $_SESSION['member_id'];
			$insert['ap_type']		= 1;
			$insert['item_id']		= intval($_POST['sid']);
			$result = $model->table('sns_albumpic')->insert($insert);
		}
		$data = array();
		$data['file_name']	= $ap_id > 0?$pic_info['ap_cover']:$upload->getSysSetPath().$upload->thumb_image;
		$data['file_id']	= $ap_id > 0?$pic_info['ap_id']:$result;
		
		/**
		 * 整理为json格式
		 */
		$output = json_encode($data);
		echo  $output;die;
	}
	/**
	 * ajax删除图片
	 */
	public function del_sharepicOp(){
		$ap_id = intval($_GET['apid']);
		$data = array();
		if($ap_id > 0){
			Model()->table('sns_albumpic')->where(array('ap_id'=>$ap_id, 'member_id'=>$_SESSION['member_id']))->delete();
			$data['type']	= 'true';
		}else{
			$data['type']	= 'false';
		}
		/**
		 * 整理为json格式
		 */
		$output = json_encode($data);
		echo  $output;die;
	}
	/**
	 * 留言板
	 */
	private function sns_messageboard(){
		$model = Model();
		$where = array();
		$where['from_member_id']	= array('neq',0);
		$where['to_member_id']		= $this->master_id;
		$where['message_state']		= array('neq',2);
		$where['message_parent_id']	= 0;
		$where['message_type']		= 2;
		$message_list = $model->table('message')->where($where)->appointment('message_id desc')->limit(10)->select();
		if(!empty($message_list)){
			$pmsg_id = array();
			foreach ($message_list as $key=>$val){
				$pmsg_id[]	= $val['message_id'];
				$message_list[$key]['message_time'] = $this->formatDate($val['message_time']);
			}
			$where = array();
			$where['message_parent_id'] = array('in',$pmsg_id);
			$rmessage_array = $model->table('message')->where($where)->select();
			$rmessage_list	= array();
			if(!empty($rmessage_array)){
				foreach ($rmessage_array as $key=>$val){
					$val['message_time'] = $this->formatDate($val['message_time']);
					$rmessage_list[$val['message_parent_id']][] = $val;
				}
				foreach ($rmessage_list as $key=>$val){
					$rmessage_list[$key]	 = array_slice($val, -3, 3);
				}
			}
			Tpl::output('rmessage_list', $rmessage_list);
		}
		Tpl::output('message_list', $message_list);
	}
}
