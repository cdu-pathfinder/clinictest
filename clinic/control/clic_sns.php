<?php
/**
 * 店铺动态
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
class clic_snsControl extends BaseclinicerControl{
    public function __construct() {
        parent::__construct ();
        Language::read('clic_sns,member_sns');
    }
    public function indexOp() {
        $this->addOp();
    }
    
    /**
     * 发布动态
     */
    public function addOp() {
        $model_doctors = Model('doctors');
        // 热销商品
        
        // where条件
        $where = array('clic_id' => $_SESSION['clic_id']);
        $field = 'doctors_id,doctors_name,doctors_image,doctors_price,doctors_salenum,clic_id';
        $appointment = 'doctors_salenum desc';
        $hotsell_list = $model_doctors->getdoctorsOnlineList($where, $field, 0, $appointment, 8);
        Tpl::output('hotsell_list', $hotsell_list);
        
        // 新品
        
        // where条件
        $where = array('clic_id' => $_SESSION['clic_id']);
        $field = 'doctors_id,doctors_name,doctors_image,doctors_price,doctors_salenum,clic_id';
        $appointment = 'doctors_id desc';
        $new_list = $model_doctors->getdoctorsOnlineList($where, $field, 0, $appointment, 8);
        Tpl::output('new_list', $new_list);
        
        $this->profile_menu ( 'clic_sns_add' );
        Tpl::showpage ( 'clic_sns_add' );
    }


    /**
     * 上传图片
     */
    public function image_uploadOp() {
        // 判断图片数量是否超限
        $model_album = Model('album');
        $album_limit = $this->clic_grade['sg_album_limit'];
        $album_count = $model_album->getCount(array('clic_id' => $_SESSION['clic_id']));
        if ($album_count >= $album_limit) {
            $error = L('clic_doctors_album_climit');
            if (strtoupper(CHARSET) == 'GBK') {
                $error = Language::getUTF8($error);
            }
            exit(json_encode(array('error' => $error)));
        }
        $class_info = $model_album->getOne(array('clic_id' => $_SESSION['clic_id'], 'is_default' => 1), 'album_class');
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_doctorS . DS . $_SESSION ['clic_id'] . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));
    
        $upload->set('thumb_width', doctorS_IMAGES_WIDTH);
        $upload->set('thumb_height', doctorS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', doctorS_IMAGES_EXT);
        $upload->set('fprefix', $_SESSION['clic_id']);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($_POST['id']);
        if (!$result) {
            if (strtoupper(CHARSET) == 'GBK') {
                $upload->error = Language::getUTF8($upload->error);
            }
            $output = array();
            $output['error'] = $upload->error;
            $output = json_encode($output);
            exit($output);
        }
    
        $img_path = $upload->getSysSetPath() . $upload->file_name;
        $thumb_page = $upload->getSysSetPath() . $upload->thumb_image;
    
        // 取得图像大小
        list($width, $height, $type, $attr) = getimagesize(UPLOAD_SITE_URL . '/' . ATTACH_doctorS . '/' . $_SESSION ['clic_id'] . DS . $img_path);
    
        // 存入相册
        $image = explode('.', $_FILES[$_POST['id']]["name"]);
        $insert_array = array();
        $insert_array['apic_name'] = $image['0'];
        $insert_array['apic_tag'] = '';
        $insert_array['aclass_id'] = $class_info['aclass_id'];
        $insert_array['apic_cover'] = $img_path;
        $insert_array['apic_size'] = intval($_FILES[$_POST['id']]['size']);
        $insert_array['apic_spec'] = $width . 'x' . $height;
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['clic_id'] = $_SESSION['clic_id'];
        $model_album->addPic($insert_array);
    
        $data = array ();
        $data ['image'] = cthumb($img_path, 240, $_SESSION['clic_id']);
    
        // 整理为json格式
        $output = json_encode($data);
        echo $output;
        exit();
    }
	/**
	 * 保存动态
	 */
	public function clic_sns_saveOp(){
		/**
		 * 验证表单
		 */
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
				array("input"=>$_POST["content"],"require"=>"true","validator"=>"Length","max"=>140,"min"=>1,"message"=>Language::get('clic_sns_center_error')),
				array("input"=>$_POST["doctors_url"],"require"=>"false","validator"=>"url","message"=>Language::get('clic_doctors_index_doctors_price_null')),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			showDialog($error);
		}
		// 实例化模型
		$model = Model();
		
		
		$doctorsdata	= '';
		$content	= '';
		$_POST['type'] = intval($_POST['type']);
		switch ($_POST['type']){
			case '2':
				$sns_image	= trim($_POST['sns_image']);
				if($sns_image != '') $content	= '<div class="fd-media">
									<div class="thumb-image"><a href="javascript:void(0);" nc_type="thumb-image"><img src="'.$sns_image.'" /><i></i></a></div>
									<div class="origin-image"><a href="javascript:void(0);" nc_type="origin-image"></a></div>
								</div>';
				break;
			case '9':
				$data = $this->getdoctorsByUrl(html_entity_decode($_POST['doctors_url']));
				if( CHARSET == 'GBK') {
					foreach ((array)$data as $k=>$v){
						$data[$k] = Language::getUTF8($v);
					}
				}
				$doctorsdata	= addslashes(json_encode($data));
				break;
			case '10':
				if(is_array($_POST['doctors_id'])){
					$doctors_id_array = $_POST['doctors_id'];
				}else{
					showDialog(Language::get('clic_sns_choose_doctors'));
				}
				$field = 'doctors_id,clic_id,doctors_name,doctors_image,doctors_price,doctors_freight';
				$where = array('clic_id'=>$_SESSION['clic_id'],'doctors_id'=>array('in',$doctors_id_array));
				$doctors_array = Model('doctors')->getdoctorsList($where, $field);
				if(!empty($doctors_array) && is_array($doctors_array)){
					$doctorsdata	= array();
					foreach ($doctors_array as $val){
						if( CHARSET == 'GBK') {
							foreach ((array)$val as $k=>$v){
								$val[$k] = Language::getUTF8($v);
							}
						}
						$doctorsdata[]	= addslashes(json_encode($val));
					}
				}
				break;
			case '3':
				if(is_array($_POST['doctors_id'])){
					$doctors_id_array = $_POST['doctors_id'];
				}else{
					showDialog(Language::get('clic_sns_choose_doctors'));
				}
				$field = 'doctors_id,clic_id,doctors_name,doctors_image,doctors_price,doctors_freight';
				$where = array('clic_id'=>$_SESSION['clic_id'],'doctors_id'=>array('in',$doctors_id_array));
				$doctors_array = Model('doctors')->getdoctorsList($where, $field);
				if(!empty($doctors_array) && is_array($doctors_array)){
					$doctorsdata	= array();
					foreach($doctors_array as $val){
						if( CHARSET == 'GBK') {
							foreach ((array)$val as $k=>$v){
								$val[$k] = Language::getUTF8($v);
							}
						}
						$doctorsdata[]	= addslashes(json_encode($val));
					}
				}
				break;
			default:
				showDialog(Language::get('para_error'));
		}

		$model_stracelog = Model('clic_sns_tracelog');
		// 插入数据
		$stracelog_array = array();
		$stracelog_array['strace_clicid']	= $this->clic_info['clic_id'];
		$stracelog_array['strace_clicname']= $this->clic_info['clic_name'];
		$stracelog_array['strace_cliclogo']= empty($this->clic_info['clic_label'])?'':$this->clic_info['clic_label'];
		$stracelog_array['strace_title']	= $_POST['content'];
		$stracelog_array['strace_content']	= $content;
		$stracelog_array['strace_time']		= time();
		$stracelog_array['strace_type']		= $_POST['type'];
		if(isset($doctorsdata) && is_array($doctorsdata)){
			$stracelog	= array();
			foreach($doctorsdata as $val){
				$stracelog_array['strace_doctorsdata']	= $val;
				$stracelog[]	= $stracelog_array;
			}
			$rs	= $model_stracelog->saveclicSnsTracelogAll($stracelog);
		}else{
			$stracelog_array['strace_doctorsdata']	= $doctorsdata;
			$rs	= $model_stracelog->saveclicSnsTracelog($stracelog_array);
		}
		if($rs){
			showDialog(Language::get('nc_common_op_succ'), 'index.php?act=clic_sns', 'succ');
		}else{
			showDialog(Language::get('nc_common_op_fail'));
		}
	}
	
	/**
	 * 动态设置
	 */
	public function settingOp(){
		// 实例化模型
		$model_clicsnssetting = Model('clic_sns_setting');
		if(chksubmit()){
			$update = array();
			$update['sauto_clicid']		= $_SESSION['clic_id'];
			$update['sauto_new']			= isset($_POST['new'])?1:0;
			$update['sauto_newtitle']		= trim($_POST['new_title']);
			$update['sauto_coupon']			= isset($_POST['coupon'])?1:0;
			$update['sauto_coupontitle']	= trim($_POST['coupon_title']);
			$update['sauto_xianshi']		= isset($_POST['xianshi'])?1:0;
			$update['sauto_xianshititle']	= trim($_POST['xianshi_title']);
			$update['sauto_mansong']		= isset($_POST['mansong'])?1:0;
			$update['sauto_mansongtitle']	= trim($_POST['mansong_title']);
			$update['sauto_bundling']		= isset($_POST['bundling'])?1:0;
			$update['sauto_bundlingtitle']	= trim($_POST['bundling_title']);
			$update['sauto_groupbuy']		= isset($_POST['groupbuy'])?1:0;
			$updata['sauto_groupbuytitle']	= trim($_POST['groupbuy_title']);
			$result = $model_clicsnssetting->saveclicSnsSetting($update,true);
			showDialog(Language::get('nc_common_save_succ'), '', 'succ');
		}
		$sauto_info	= $model_clicsnssetting->getclicSnsSettingInfo(array('sauto_clicid' => $_SESSION['clic_id']));
		Tpl::output('sauto_info', $sauto_info);
		$this->profile_menu('clic_sns_setting');
		Tpl::showpage('clic_sns_setting');
	}
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key) {
		$menu_array	= array(
				1=>array('menu_key'=>'clic_sns_add', 'menu_name'=>Language::get('clic_sns_add'), 'menu_url'=>'index.php?act=clic_sns&op=add'),
				2=>array('menu_key'=>'clic_sns_setting', 'menu_name'=>Language::get('clic_sns_setting'), 'menu_url'=>'index.php?act=clic_sns&op=setting'),
				3=>array('menu_key'=>'clic_sns_brower', 'menu_name'=>Language::get('clic_sns_browse'), 'menu_url'=>'index.php?act=clic_snshome&sid='.$_SESSION['clic_id'], 'target'=>'_blank')
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
		Tpl::output('menu_sign','clic_sns');
		Tpl::output('menu_sign_url','index.php?act=clic_sns');
		Tpl::output('menu_sign1',$menu_key);
	}
	
	/**
	 * 根据url取得商品信息
	 */
	private function getdoctorsByUrl($url){
		$array = parse_url($url);
		if(isset($array['query'])){
			// 未开启伪静态
			parse_str($array['query'],$arr);
			$id = $arr['doctors_id'];
		}else{
			// 开启伪静态
			$id = preg_replace('/\/item-(\d+)\.html/i', '$1', $array['path']);
		}
		if(intval($id) > 0){
			// 查询商品信息
			$field = 'doctors_id,clic_id,doctors_name,doctors_image,doctors_price,doctors_freight';
			$where = array('doctors_id' => $id);
			$result = Model('doctors')->getdoctorsInfo($where, $field);
			if(!empty($result) && is_array($result)){
				return $result;
			}else{
				showDialog(Language::get('clic_sns_doctors_url_error'));
			}
		}else{
			showDialog(Language::get('clic_sns_doctors_url_error'));
		}

	}
}
