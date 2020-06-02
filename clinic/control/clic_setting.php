<?php
/**
 * 会员中心——我是卖家
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

class clic_settingControl extends BaseclinicerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_clic_index');
	}
	
	/**
	 * 卖家店铺设置
	 *
	 * @param string	
	 * @param string 	
	 * @return 
	 */
	public function clic_settingOp(){
		/**
		 * 实例化模型
		 */
		$model_class = Model('clic');
		/**
		 * 获取设置
		 */
		$setting_config = $GLOBALS['setting_config'];
		$clic_id = $_SESSION['clic_id'];//当前店铺ID
		/**
		 * 获取店铺信息
		 */
		$clic_info = $model_class->getclicInfoByID($clic_id);
		$subdomain_edit = intval($setting_config['subdomain_edit']);//二级域名是否可修改
		$subdomain_times = intval($setting_config['subdomain_times']);//系统设置二级域名可修改次数
		$clic_domain_times = intval($clic_info['clic_domain_times']);//店铺已修改次数
		$subdomain_length = explode('-',$setting_config['subdomain_length']);
		$subdomain_length[0] = intval($subdomain_length[0]);
		$subdomain_length[1] = intval($subdomain_length[1]);
		if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]){//域名长度
			$subdomain_length[0] = 3;
			$subdomain_length[1] = 12;
		}
		Tpl::output('subdomain_length',$subdomain_length);
		/**
		 * 保存店铺设置
		 */
		if (chksubmit()){
			$_POST['clic_domain'] = trim($_POST['clic_domain']);
			$clic_domain = strtolower($_POST['clic_domain']);
			//判断是否设置二级域名
			if (!empty($clic_domain) && $clic_domain != $clic_info['clic_domain']){
				$clic_domain_count = strlen($clic_domain);
				if ($clic_domain_count < $subdomain_length[0] || $clic_domain_count > $subdomain_length[1]){
					showDialog(Language::get('clic_setting_wrong_uri').': '.$setting_config['subdomain_length'],'reload','error');
				}
				if (!preg_match('/^[\w-]+$/i',$clic_domain)){//判断域名是否正确
					showDialog(Language::get('clic_setting_lack_uri'));
				}
				$clic = $model_class->getclicInfo(array(
					'clic_domain'=>$clic_domain
				));
				//二级域名存在,则提示错误
				if (!empty($clic) && ($clic_id != $clic['clic_id'])){
					showDialog(Language::get('clic_setting_exists_uri'),'reload','error');
				}
				//判断二级域名是否为系统禁止
				$subdomain_reserved = @explode(',',$setting_config['subdomain_reserved']);
				if(!empty($subdomain_reserved) && is_array($subdomain_reserved)){
						if (in_array($clic_domain,$subdomain_reserved)){
							showDialog(Language::get('clic_setting_invalid_uri'));
						}
				}
				if($subdomain_times > $clic_domain_times){//可继续修改
					$param = array();
					$param['clic_domain'] = $clic_domain;
					if (!empty($clic_info['clic_domain'])) $param['clic_domain_times'] = $clic_domain_times+1;//第一次保存不计数
                    $model_class->editclic($param, array('clic_id' => $clic_id));
				}
				$_POST['clic_domain'] = '';//避免重复更新
			}
			$upload = new UploadFile();
			/**
			 * 上传店铺图片
			 */
			if (!empty($_FILES['clic_banner']['name'])){
				$upload->set('default_dir',	ATTACH_clic);
				$upload->set('thumb_ext',	'');
				$upload->set('file_name','');
				$upload->set('ifremove',false);
				$result = $upload->upfile('clic_banner');
				if ($result){
					$_POST['clic_banner'] = $upload->file_name;
				}else {
					showDialog($upload->error);
				}
			}
			/**
			 * 删除旧店铺图片
			 */
			if (!empty($_POST['clic_banner']) && !empty($clic_info['clic_banner'])){
			    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_banner']);
			}

			/**
			 * 上传店铺图片
			 */
			if (!empty($_FILES['clic_label']['name'])){
				$upload->set('default_dir',	ATTACH_clic);
				$upload->set('thumb_ext',	'');
				$upload->set('file_name','');
				$upload->set('ifremove',false);
				$result = $upload->upfile('clic_label');
				if ($result){
					$_POST['clic_label'] = $upload->file_name;
				}else {
					showDialog($upload->error);
				}
			}
			/**
			 * 删除旧店铺图片
			 */
			if (!empty($_POST['clic_label']) && !empty($clic_info['clic_label'])){
			    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_label']);
			}
			
			/**
			 * 更新入库
			 */
            $param = array(
                'clic_label' => empty($_POST['clic_label']) ? $clic_info['clic_label'] : $_POST['clic_label'],
                'clic_banner' => empty($_POST['clic_banner']) ? $clic_info['clic_banner'] : $_POST['clic_banner'],
                'clic_qq' => $_POST['clic_qq'],
                'clic_ww' => $_POST['clic_ww'],
                'clic_zy' => $_POST['clic_zy'],
                'clic_keywords' => $_POST['seo_keywords'],
                'clic_description' => $_POST['seo_description']
            );
            if (!empty($_POST['clic_theme'])){
                $param['clic_theme'] = $_POST['clic_theme'];
            }

            $model_class->editclic($param, array('clic_id' => $clic_id));
            showDialog(Language::get('nc_common_save_succ'),'index.php?act=clic_setting&op=clic_setting','succ');
        }
        /**
         * 实例化店铺等级模型
         */			
        $model_clic_grade	= Model('clic_grade');
        $clic_grade		= $model_clic_grade->getOneGrade($clic_info['grade_id']);
		//编辑器多媒体功能
		$editor_multimedia = false;
		$sg_fun = @explode('|',$clic_grade['sg_function']);
		if(!empty($sg_fun) && is_array($sg_fun)){
			foreach($sg_fun as $fun){
				if ($fun == 'editor_multimedia'){
					$editor_multimedia = true;
				}
			}
		}
		Tpl::output('editor_multimedia',$editor_multimedia);
		if($subdomain_edit == 1 && ($subdomain_times > $clic_domain_times)){//可继续修改二级域名
			Tpl::output('subdomain_edit',$subdomain_edit);
		}
		/**
		 * 输出店铺信息
		 */
		self::profile_menu('clic_setting');
		Tpl::output('clic_info',$clic_info);
		Tpl::output('clic_grade',$clic_grade);
		Tpl::output('subdomain',$setting_config['enabled_subdomain']);
		Tpl::output('subdomain_times',$setting_config['subdomain_times']);
		Tpl::output('menu_sign','clic_setting');
		Tpl::output('menu_sign_url','index.php?act=clic_setting&op=clic_setting');
		Tpl::output('menu_sign1','clic_setting');
		/**
		 * 页面输出
		 */
		Tpl::showpage('clic_setting_form');
	}

	/**
	 * 店铺幻灯片
	 */
	public function clic_slideOp() {
		/**
		 * 模型实例化
		 */
		$model_clic = Model('clic');
		$model_upload = Model('upload');
		/**
		 * 保存店铺信息
		 */
		if ($_POST['form_submit'] == 'ok'){
			// 更新店铺信息
			$update	= array();
			$update['clic_slide']		= implode(',', $_POST['image_path']);
			$update['clic_slide_url']	= implode(',', $_POST['image_url']);
            $model_clic->editclic($update, array('clic_id' => $_SESSION['clic_id']));
			
			// 删除upload表中数据
			$model_upload->delByWhere(array('upload_type'=>7,'clic_id'=>$_SESSION['clic_id']));
			showDialog(Language::get('nc_common_save_succ'),'index.php?act=clic_setting&op=clic_slide','succ');
		}

		// 删除upload中的无用数据
		$upload_info = $model_upload->getUploadList(array('upload_type'=>7,'clic_id'=>$_SESSION['clic_id']),'file_name');
		if(is_array($upload_info) && !empty($upload_info)){
			foreach ($upload_info as $val){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$val['file_name']);
			}
		}
		$model_upload->delByWhere(array('upload_type'=>7,'clic_id'=>$_SESSION['clic_id']));
		
        $clic_info = $model_clic->getclicInfoByID($_SESSION['clic_id']);
		if($clic_info['clic_slide'] != '' && $clic_info['clic_slide'] != ',,,,'){
			Tpl::output('clic_slide', explode(',', $clic_info['clic_slide']));
			Tpl::output('clic_slide_url', explode(',', $clic_info['clic_slide_url']));
		}
		self::profile_menu('clic_slide');
		Tpl::output('menu_sign','clic_setting');
		Tpl::output('menu_sign_url','index.php?act=clic_setting&op=clic_setting');
		Tpl::output('menu_sign1','clic_slide');
		/**
		 * 页面输出
		 */
		Tpl::showpage('clic_slide_form');
	}
	/**
	 * 店铺幻灯片ajax上传
	 */
	public function silde_image_uploadOp(){
		$upload = new UploadFile();
		$upload->set('default_dir',ATTACH_SLIDE);
		$upload->set('max_size',C('image_max_filesize'));
		
		$result = $upload->upfile($_POST['id']);
		
		
		$output	= array();
		if(!$result){
			/**
			 * 转码
			 */
			if (strtoupper(CHARSET) == 'GBK'){
				$upload->error = Language::getUTF8($upload->error);
			}
			$output['error']	= $upload->error;
			echo json_encode($output);die;
		}
		
		$img_path = $upload->file_name;
		
		/**
		 * 模型实例化
		 */
		$model_upload = Model('upload');
		
		if(intval($_POST['file_id']) > 0){
			$file_info = $model_upload->getOneUpload($_POST['file_id']);
			@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$file_info['file_name']);
			
			$update_array	= array();
			$update_array['upload_id']	= intval($_POST['file_id']);
			$update_array['file_name']	= $img_path;
			$update_array['file_size']	= $_FILES[$_POST['id']]['size'];
			$model_upload->update($update_array);

			$output['file_id']	= intval($_POST['file_id']);
			$output['id']		= $_POST['id'];
			$output['file_name']	= $img_path;
			echo json_encode($output);die;
		}else{
			/**
			 * 图片数据入库
			 */
			$insert_array = array();
			$insert_array['file_name']		= $img_path;
			$insert_array['upload_type']	= '7';
			$insert_array['file_size']		= $_FILES[$_POST['id']]['size'];
			$insert_array['clic_id']		= $_SESSION['clic_id'];
			$insert_array['upload_time']	= time();
			
			$result = $model_upload->add($insert_array);
			
			if(!$result){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$img_path);
				$output['error']	= Language::get('clic_slide_upload_fail','UTF-8');
				echo json_encode($output);die;
			}
			
			$output['file_id']	= $result;
			$output['id']		= $_POST['id'];
			$output['file_name']	= $img_path;
			echo json_encode($output);die;
		}
	}

	/**
	 * ajax删除幻灯片图片
	 */
	public function dorp_imgOp(){
		/**
		 * 模型实例化
		 */
		$model_upload = Model('upload');
		$file_info = $model_upload->getOneUpload(intval($_GET['file_id']));
		if(!$file_info){
		}else{
			@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$file_info['file_name']);
			$model_upload->del(intval($_GET['file_id']));
		}
		echo json_encode(array('succeed'=>Language::get('nc_common_save_succ','UTF-8')));die;
	}

	/**
	 * 卖家店铺主题设置
	 *
	 * @param string	
	 * @param string 	
	 * @return 
	 */
	public function themeOp(){
		/**
		 * 店铺信息
		 */
		$clic_class = Model('clic');
		$clic_info = $clic_class->getclicInfoByID($_SESSION['clic_id']);
		/**
		 * 主题配置信息
		 */
		$style_data = array();
		$style_configurl = BASE_ROOT_PATH.DS.DIR_clinic.'/templates/'.TPL_clinic_NAME.DS.'clic'.DS.'style'.DS."styleconfig.php";
		if (file_exists($style_configurl)){
			include_once($style_configurl);
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$style_data = Language::getGBK($style_data);
		}
		/**
		 * 当前店铺主题
		 */
		$curr_clic_theme = !empty($clic_info['clic_theme'])?$clic_info['clic_theme']:'default';
		/**
		 * 当前店铺预览图片
		 */
		$curr_image = clinic_TEMPLATES_URL.'/clic/style/'.$curr_clic_theme.'/images/preview.jpg';
		$curr_theme = array(
		'curr_name'=>$curr_clic_theme,
		'curr_truename'=>$style_data[$curr_clic_theme]['truename'],
		'curr_image'=>$curr_image
		);
		/**
		 * 店铺等级
		 */
		$grade_class = Model('clic_grade');
		$grade = $grade_class->getOneGrade($clic_info['grade_id']);
		/**
		 * 可用主题
		 */
		$themes = explode('|',$grade['sg_template']);
		/**
		 * 可用主题预览图片
		 */
		foreach ($style_data as $key => $val){
			if (in_array($key,$themes)){
				$theme_list[$key] = array(
				'name'=>$key,
				'truename'=>$val['truename'],
				'image'=>clinic_TEMPLATES_URL.'/clic/style/'.$key.'/images/preview.jpg'
				);
			}
		}
		/**
		 * 页面输出
		 */
		self::profile_menu('clic_theme','clic_theme');
		Tpl::output('menu_sign','clic_theme');
		Tpl::output('clic_info',$clic_info);
		Tpl::output('curr_theme',$curr_theme);
		Tpl::output('theme_list',$theme_list);
		Tpl::showpage('clic_theme');
	}
	/**
	 * 卖家店铺主题设置
	 *
	 * @param string	
	 * @param string 	
	 * @return 
	 */
	public function set_themeOp(){
		//读取语言包
		$lang	= Language::getLangContent();
		$style = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;

		if (!empty($style) && file_exists(BASE_TPL_PATH.DS.'/clic/style/'.$style.'/images/preview.jpg')){
			$clic_class = Model('clic');
            $rs = $clic_class->editclic(array('clic_theme'=>$style), array('clic_id' => $_SESSION['clic_id']));
			showDialog($lang['clic_theme_congfig_success'],'reload','succ');
		}else{
			showDialog($lang['clic_theme_congfig_fail'],'','succ');
		}
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
            1=>array('menu_key'=>'clic_setting','menu_name'=>Language::get('nc_member_path_clic_config'),'menu_url'=>'index.php?act=clic_setting&op=clic_setting'),
            4=>array('menu_key'=>'clic_slide','menu_name'=>Language::get('nc_member_path_clic_slide'),'menu_url'=>'index.php?act=clic_setting&op=clic_slide'),
            5=>array('menu_key'=>'clic_theme','menu_name'=>'店铺主题','menu_url'=>'index.php?act=clic_setting&op=theme'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
    
}
