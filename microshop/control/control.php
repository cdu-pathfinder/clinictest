<?php
/**
 * 前台control父类,店铺control父类,会员control父类
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class MircroclinicControl{

    const doctorS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const clic_FLAG = 4;

	/**
	 * 构造函数
	 */
	public function __construct(){
		/**
		 * 读取通用、布局的语言包
		 */
		Language::read('common');
		Language::read('microclinic');
        /**
         * 判断微商城是否关闭
         */
		if (C('microclinic_isuse') != '1'){
            header('location: '.clinic_SITE_URL);die;
		}
		/**
		 * 设置布局文件内容
		 */
		Tpl::setLayout('microclinic_layout');
		/**
		 * 转码
		 */
		if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
			$_GET = Language::getGBK($_GET);
		}
		/**
		 * 获取导航
		 */
		Tpl::output('nav_list',($nav = F('nav'))? $nav :H('nav',true,'file'));
        /**
         * 搜索类型列表
         */
        $search_type = array();
        $search_type['doctors'] = Language::get('nc_microclinic_doctors');
        $search_type['personal'] = Language::get('nc_microclinic_personal');
        $search_type['clic'] = Language::get('nc_microclinic_clic');
        //$search_type['album'] = Language::get('nc_microclinic_album');
        Tpl::output('search_type',$search_type);
        /**
         * 登录后读取用户头像
         */
        if(!empty($_SESSION['member_id']) && intval($_SESSION['member_id']) > 0) {
            self::get_member_avatar($_SESSION['member_id']);
        }
		/**
		 * 系统状态检查
		 */
		if(!C('site_status')) halt(C('closed_reason'));

        /**
         * seo
         */
        Tpl::output('html_title',Language::get('nc_microclinic').'-'.C('site_name').' - Powered by clinicNC');
        Tpl::output('seo_keywords',C('microclinic_seo_keywords'));
        Tpl::output('seo_description',C('microclinic_seo_description'));

	}

    protected function check_login() {
        if(!isset($_SESSION['is_login'])) {
            $ref_url = MICROclinic_SITE_URL.request_uri();
            header('location: '.clinic_SITE_URL.'/index.php?act=login&ref_url='.getRefUrl());die;
        }
    }

    protected function get_channel_type($channel_name) {
        $result = array();
        switch ($channel_name) {
            case 'doctors':
                $result['type_id'] = self::doctorS_FLAG;
                $result['type_key'] = 'commend_id';
                break;
            case 'personal':
                $result['type_id'] = self::PERSONAL_FLAG;
                $result['type_key'] = 'personal_id';
                break;
            case 'clic':
                $result['type_id'] = self::clic_FLAG;
                $result['type_key'] = 'microclinic_clic_id';
                break;
            default:
                break;
        }
        return $result;
    }

    protected function get_member_avatar($member_id) {
        if(!isset($_SESSION['member_avatar'])) {
            $model_member = Model('member');
            $member_info = $model_member->infoMember(array('member_id'=>$member_id));
            $_SESSION['member_avatar'] = $member_info['member_avatar'];
        }
    }

    protected function get_personal_class_list() {
        $model_class = Model("micro_personal_class");
        $list = $model_class->getList(TRUE);
        Tpl::output('personal_class_list',$list);
    }

    //获取商品列表
    protected function get_doctors_list($condition,$appointment='commend_time desc') {
        $model_microclinic_doctors = Model('micro_doctors');
        $page_number = 35;
        $field = 'micro_doctors.*,member.member_name,member.member_avatar';
        $list = $model_microclinic_doctors->getListWithUserInfo($condition,$page_number,$appointment,$field);
        Tpl::output('show_page',$model_microclinic_doctors->showpage(2));
        Tpl::output('list',$list);
    }

    //获取个人秀列表
    protected function get_personal_list($condition,$appointment='commend_time desc') {
        $model_personal = Model('micro_personal');
        $page_number = 35;
        $field = 'micro_personal.*,member.member_name,member.member_avatar';
        $list = $model_personal->getListWithUserInfo($condition,$page_number,$appointment,$field);
        Tpl::output('show_page',$model_personal->showpage(2));
        Tpl::output('list',$list);
    }

    //获得分享列表
    protected function get_share_app_list() {
        $app_clinic = array();
        $app_array = array();
		if (C('share_isuse') == 1 && isset($_SESSION['member_id'])){
			//站外分享接口
			$model = Model('sns_binding');
			$app_array = $model->getUsableApp($_SESSION['member_id']);
		}
        Tpl::output('app_arr',$app_array);
    }

    protected function share_app_publish($type,$publish_info=array()) {
        $param = array();
        switch ($type) {
        case 'comment':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_comment').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_comment').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            break;
        case 'publish':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_commend').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_commend').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            break;
        case 'share':
            $param['comment'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_share').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            $param['title'] = "'".$_SESSION['member_name']."'".Language::get('microclinic_text_zai').Language::get('nc_microclinic').Language::get('microclinic_text_share').Language::get('microclinic_text_le').Language::get("nc_microclinic_{$publish_info['type']}_content");
            break;
        }
        $param['url'] = $publish_info['url'];
        $function_name = "get_share_app_{$publish_info['type']}_content";
        $param['content'] = self::$function_name($publish_info,$param);
        $param['images'] = '';

        //分享应用
        $app_items = array();
        foreach ($_POST['share_app_items'] as $val) {
            if($val != '') {
                $app_items[$val] = TRUE;
            }
        }

        if (C('share_isuse') == 1 && !empty($app_items)){
            $model = Model('sns_binding');
            //查询该用户的绑定信息
            $bind_list = $model->getUsableApp($_SESSION['member_id']);
            //商城
            if (isset($app_items['clinic'])){

                $model_member = Model('member');
                $member_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));

				$tracelog_model = Model('sns_tracelog');
				$insert_arr = array();
				$insert_arr['trace_originalid'] = '0';
				$insert_arr['trace_originalmemberid'] = '0';
				$insert_arr['trace_memberid'] = $_SESSION['member_id'];
				$insert_arr['trace_membername'] = $_SESSION['member_name'];
				$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];
				$insert_arr['trace_title'] = $publish_info['commend_message'];
                $insert_arr['trace_content'] = $param['content'];
				$insert_arr['trace_addtime'] = time();
				$insert_arr['trace_state'] = '0';
				$insert_arr['trace_privacy'] = 0;
				$insert_arr['trace_commentcount'] = 0;
				$insert_arr['trace_copycount'] = 0;
				$insert_arr['trace_from'] = '3';
				$result = $tracelog_model->tracelogAdd($insert_arr);

            }
            //腾讯微博
            if (isset($app_items['qqweibo']) && $bind_list['qqweibo']['isbind'] == true){
                $model->addQQWeiboPic($bind_list['qqweibo'],$param);
            }
            //新浪微博
            if (isset($app_items['sinaweibo']) && $bind_list['sinaweibo']['isbind'] == true){
                $model->addSinaWeiboUpload($bind_list['sinaweibo'],$param);
            }
        }
    }

    //商品sns内容结构
    protected function get_share_app_doctors_content($doctors_info,$param) {
        $content_str = "
            <div class='fd-media'>
            <div class='doctorsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".cthumb($doctors_info['commend_doctors_image'], 240, $doctors_info['commend_doctors_clic_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" title=\"{$doctors_info['commend_doctors_name']}\" alt=\"{$doctors_info['commend_doctors_name']}\"></a></div>
            <div class='doctorsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$param['url']}\">{$doctors_info['commend_doctors_name']}</a></dt>
            <dd>".Language::get('nc_common_price').Language::get('nc_colon').Language::get('currency').$doctors_info['commend_doctors_price']."</dd>
            <dd>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('nc_common_goto')."</a></dd>
            </dl>
            </div>
            </div>
            ";
        return $content_str;
    }

    //个人秀sns内容结构
    protected function get_share_app_personal_content($personal_info,$param) {
        $personal_image_array = getMicroclinicPersonalImageUrl($personal_info,'list');
        $personal_image_array_tiny = getMicroclinicPersonalImageUrl($personal_info,'tiny');
        $content_str = "
            <div class='fd-media'>
            <div class='doctorsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array[0]."\" onload=\"javascript:DrawImage(this,120,120);\"></a></div>
            <div class='doctorsinfo'>
            <ul>
            ";
        if(!empty($personal_image_array_tiny[1])) {
            $content_str .= "<li><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array_tiny[1]."\" onload=\"javascript:DrawImage(this,60,60);\"></a></li>";
        }
        if(!empty($personal_image_array_tiny[2])) {
            $content_str .= "<li><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".$personal_image_array_tiny[2]."\" onload=\"javascript:DrawImage(this,60,60);\"></a></li>";
        }
        $content_str .= "</ul><p>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('nc_common_goto')."</a></p>
            </div>
            </div>
            ";
        return $content_str;
    }

    //个人秀sns内容结构
    protected function get_share_app_clic_content($clic_info,$param) {
        $content_str = "
            <div class='fd-media'>
            <div class='doctorsimg'><a target=\"_blank\" href=\"{$param['url']}\"><img src=\"".getclicLogo($clic_info['clic_label'])."\" onload=\"javascript:DrawImage(this,120,120);\"></a></div>
            <div class='doctorsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$param['url']}\">{$clic_info['clic_name']}</a></dt>
            <dd>{$param['comment']}<a target=\"_blank\" href=\"{$param['url']}\">".Language::get('nc_common_goto')."</a></dd>
            </dl>
            </div>
            </div>
            ";
        return $content_str;
    }

    /**
     * 微商城详细页侧栏
     */
    protected function get_sidebar_list($member_id) {
        //随心看
        $model_microclinic_doctors = Model('micro_doctors');
        $sidebar_doctors_list = $model_microclinic_doctors->getList(array('commend_member_id'=>$member_id),null,'commend_time desc','*',9);
        Tpl::output('sidebar_doctors_list',$sidebar_doctors_list);
        //个人秀
        $model_microclinic_personal = Model('micro_personal');
        $sidebar_personal_list = $model_microclinic_personal->getList(array('commend_member_id'=>$member_id),null,'commend_time desc','*',9);
        Tpl::output('sidebar_personal_list',$sidebar_personal_list);
    }

    /**
     * 用户详细信息
     */
	protected function get_member_detail_info($member_info) {
		//生成缓存的键值
		$member_id  = $member_info['member_id'];
		if($member_id <= 0){
            return null;
		}
		//写入缓存的数据
		$cachekey_arr = array('member_name','clic_id','member_avatar','member_qq','member_email','member_msn','member_ww','member_points',
				'available_predeposit','member_snsvisitnum','credit_arr','fan_count','attention_count');
		//先查找$member_id缓存
		if ($_cache = rcache($member_id,'sns_member')){
			foreach ($_cache as $k=>$v){
				$member_info[$k] = $v;
			}
		} else {
			$model = Model();
			//粉丝数
			$fan_count = $model->table('sns_friend')->where(array('friend_tomid'=>$member_id))->count();
			$member_info['fan_count'] = $fan_count;
			//关注数
			$attention_count = $model->table('sns_friend')->where(array('friend_frommid'=>$member_id))->count();
			$member_info['attention_count'] = $attention_count;
			//兴趣标签
			$mtag_list = $model->table('sns_membertag,sns_mtagmember')->field('mtag_name')->on('sns_membertag.mtag_id = sns_mtagmember.mtag_id')->join('inner')->where(array('sns_mtagmember.member_id'=>$member_id))->select();
			$tagname_array = array();
			if(!empty($mtag_list)){
				foreach ($mtag_list as $val){
					$tagname_array[] = $val['mtag_name'];
				}
			}
			$member_info['tagname'] = $tagname_array;

			wcache($member_id,$member_info,'sns_member');
		}
        return $member_info;
	}

    /**
     * 删除个人秀图片
     */
    protected function drop_personal_image($commend_image) {
        $image_array = explode(',',$commend_image);
        foreach ($image_array as $image_name) {
            list($name, $ext) = explode(".", $image_name);
            $name = str_replace('/', '', $name);
            $name = str_replace('.', '', $name);
            $image = array();
            $image['src'] = $name.'.'.$ext;
            $image['list'] = $image['src'].'_list.'.$ext;
            $image['tiny'] = $image['src'].'_tiny.'.$ext;

            foreach ($image as $value) {
                $image_name = BasePath.DS.ATTACH_MICROclinic.DS.$_SESSION['member_id'].DS.$value;
                if(is_file($image_name)) {
                    unlink($image_name);
                }
            }
        }
    }

    /**
     * 返回json状态
     */
	protected function return_json($message,$result='true') {
        $data = array();
        $data['result'] = $result;
        $data['message'] = $message;
        self::echo_json($data);
    }

    protected function echo_json($data) {
		if (strtoupper(CHARSET) == 'GBK'){
			$data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		}
        echo json_encode($data);
    }

    /**
     * 微商城广告
     */
    protected function get_microclinic_adv($type='index') {
        $model = Model('micro_adv');
        $adv_list = $model->getList(array('adv_type'=>$type),null,'adv_sort asc');
        Tpl::output($type.'_adv_list',$adv_list);
    }

    /**
     * 获取主域名
     */
    protected function get_url_domain($url) {
        $url_parse_array = parse_url($url);
        $host = $url_parse_array['host'];
        $host_names = explode(".", $host);
        $bottom_host_name = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
        return $bottom_host_name;
    }
}

