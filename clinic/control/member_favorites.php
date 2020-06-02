<?php
/**
 * 会员中心--收藏功能
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class member_favoritesControl extends BaseMemberControl{
	public function __construct(){
        parent::__construct();
        Language::read('member_layout,member_member_favorites');
    }
	/**
	 * 增加商品收藏
	 */
	public function favoritesdoctorsOp(){
		$fav_id = intval($_GET['fid']);
		if ($fav_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
		$favorites_model = Model('favorites');
		//判断是否已经收藏
		$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'doctors','member_id'=>"{$_SESSION['member_id']}"));
		if(!empty($favorites_info)){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_doctors','UTF-8')));
			die;
		}
		//判断商品是否为当前会员所有
		$doctors_model = Model('doctors');
		$doctors_info = $doctors_model->getdoctorsInfo(array('doctors_id' => $fav_id));
		if ($doctors_info['clic_id'] == $_SESSION['clic_id']){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_doc','UTF-8')));
			die;
		}
		//添加收藏
		$insert_arr = array();
		$insert_arr['member_id'] = $_SESSION['member_id'];
		$insert_arr['fav_id'] = $fav_id;
		$insert_arr['fav_type'] = 'doctors';
		$insert_arr['fav_time'] = time();
		$result = $favorites_model->addFavorites($insert_arr);
		if ($result){
			//增加收藏数量
			$doctors_model->editdoctors(array('doctors_collect' => array('exp', 'doctors_collect + 1')), array('doctors_id' => $fav_id));
			echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
	}
	/**
	 * 增加店铺收藏
	 */
	public function favoritesclicOp(){
		$fav_id = intval($_GET['fid']);
		if ($fav_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
		$favorites_model = Model('favorites');
		//判断是否已经收藏
		$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'clic','member_id'=>"{$_SESSION['member_id']}"));
		if(!empty($favorites_info)){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_clic','UTF-8')));
			die;
		}
		//判断店铺是否为当前会员所有
		if ($fav_id == $_SESSION['clic_id']){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_clic','UTF-8')));
			die;
		}
		//添加收藏
		$insert_arr = array();
		$insert_arr['member_id'] = $_SESSION['member_id'];
		$insert_arr['fav_id'] = $fav_id;
		$insert_arr['fav_type'] = 'clic';
		$insert_arr['fav_time'] = time();
		$result = $favorites_model->addFavorites($insert_arr);
		if ($result){
			//增加收藏数量
			$clic_model = Model('clic');
            $clic_model->editclic(array('clic_collect'=>array('exp', 'clic_collect+1')), array('clic_id' => $fav_id));
			echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
	}

	/**
	 * 商品收藏列表
	 *
	 * @param
	 * @return
	 */
	public function fglistOp(){
		$favorites_model = Model('favorites');
		$show_type = 'favorites_doctors_picshowlist';//默认为图片横向显示
		$show = $_GET['show'];
		$clic_array = array('list'=>'favorites_doctors_index','pic'=>'favorites_doctors_picshowlist','clic'=>'favorites_doctors_cliniclist');
		if (array_key_exists($show,$clic_array)) $show_type = $clic_array[$show];

		$favorites_list = $favorites_model->getdoctorsFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 20);
		Tpl::output('show_page',$favorites_model->showpage(2));
		if (!empty($favorites_list) && is_array($favorites_list)){
			$favorites_id = array();//收藏的商品编号
			foreach ($favorites_list as $key=>$favorites){
				$fav_id = $favorites['fav_id'];
				$favorites_id[] = $favorites['fav_id'];
				$favorites_key[$fav_id] = $key;
			}
			$doctors_model = Model('doctors');
			$field = 'doctors.doctors_id,doctors.doctors_name,doctors.clic_id,doctors.doctors_image,doctors.doctors_price,doctors.evaluation_count,doctors.doctors_salenum,doctors.doctors_collect,clic.clic_name,clic.member_id,clic.member_name,clic.clic_qq,clic.clic_ww,clic.clic_domain';
			$doctors_list = $doctors_model->getdoctorsclicList(array('doctors_id' => array('in', $favorites_id)), $field);
			$clic_array = array();//店铺编号
			if (!empty($doctors_list) && is_array($doctors_list)){
				$clic_doctors_list = array();//店铺为分组的商品
				foreach ($doctors_list as $key=>$fav){
					$fav_id = $fav['doctors_id'];
					$fav['doctors_member_id'] = $fav['member_id'];
					$key = $favorites_key[$fav_id];
					$favorites_list[$key]['doctors'] = $fav;
					$clic_id = $fav['clic_id'];
					if (!in_array($clic_id,$clic_array)) $clic_array[] = $clic_id;
					$clic_doctors_list[$clic_id][] = $favorites_list[$key];
				}
			}
			$clic_favorites = array();//店铺收藏信息
			if (!empty($clic_array) && is_array($clic_array)){
				$clic_list = $favorites_model->getclicFavoritesList(array('member_id'=>$_SESSION['member_id'], 'fav_id'=> array('in', $clic_array)));
				if (!empty($clic_list) && is_array($clic_list)){
					foreach ($clic_list as $key=>$val){
						$clic_id = $val['fav_id'];
						$clic_favorites[] = $clic_id;
					}
				}
			}
		}
		//查询会员信息
		$this->get_member_info();
		self::profile_menu('favorites','favorites');
		Tpl::output('menu_key',"fav_doctors");
		Tpl::output('favorites_list',$favorites_list);
		Tpl::output('clic_favorites',$clic_favorites);
		Tpl::output('clic_doctors_list',$clic_doctors_list);
		Tpl::output('menu_sign','collect_list');
		Tpl::showpage($show_type);
	}
	/**
	 * 店铺收藏列表
	 *
	 * @param
	 * @return
	 */
	public function fslistOp(){
		$favorites_model = Model('favorites');
		$favorites_list = $favorites_model->getclicFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 10);
		if (!empty($favorites_list) && is_array($favorites_list)){
			$favorites_id = array();//收藏的店铺编号
			foreach ($favorites_list as $key=>$favorites){
				$fav_id = $favorites['fav_id'];
				$favorites_id[] = $favorites['fav_id'];
				$favorites_key[$fav_id] = $key;
			}
			$clic_model = Model('clic');
			$clic_list = $clic_model->getclicList(array('clic_id'=>array('in', $favorites_id)));
			if (!empty($clic_list) && is_array($clic_list)){
				foreach ($clic_list as $key=>$fav){
					$fav_id = $fav['clic_id'];
					$key = $favorites_key[$fav_id];
					$favorites_list[$key]['clic'] = $fav;
				}
			}
		}
		//查询会员信息
		$this->get_member_info();
		self::profile_menu('favorites','favorites');
		Tpl::output('menu_key',"fav_clic");
		Tpl::output('favorites_list',$favorites_list);
		Tpl::output('show_page',$favorites_model->showpage(2));
		Tpl::output('menu_sign','collect_clic');
		Tpl::showpage("favorites_clic_index");
	}
	/**
	 * 删除收藏
	 *
	 * @param
	 * @return
	 */
	public function delfavoritesOp(){
		if (!$_GET['fav_id'] || !$_GET['type']){
			showDialog(Language::get('member_favorite_del_fail'),'','error');
		}
		if (!preg_match_all('/^[0-9,]+$/',$_GET['fav_id'], $matches)) {
		    showDialog(Language::get('wrong_argument'),'','error');
		}
		$fav_id = trim($_GET['fav_id'],',');
		if (!in_array($_GET['type'], array('doctors', 'clic'))) {
		  showDialog(Language::get('wrong_argument'),'','error');
		}
		$type = $_GET['type'];
		$favorites_model = Model('favorites');
		$fav_arr = explode(',',$fav_id);
		if (!empty($fav_arr) && is_array($fav_arr)){
			//批量删除
			$fav_str = "'".implode("','",$fav_arr)."'";
			$result = $favorites_model->delFavorites(array('fav_id_in'=>"$fav_str",'fav_type'=>"$type",'member_id'=>"{$_SESSION['member_id']}"));
			if ($result){
				//剔除删除失败的记录
				$favorites_list = $favorites_model->getFavoritesList(array('fav_id'=>array('in', $fav_arr),'fav_type'=>"$type",'member_id'=>$_SESSION['member_id']));
				if (!empty($favorites_list)){
					foreach ($favorites_list as $k=>$v){
						unset($fav_arr[array_search($v['fav_id'],$fav_arr)]);
					}
				}
				if (!empty($fav_arr)){
					if ($type=='doctors'){
						//更新收藏数量
						$doctors_model = Model('doctors');
						$doctors_model->editdoctors(array('doctors_collect'=>array('exp', 'doctors_collect - 1')), array('doctors_id' => array('in', $fav_arr)));
						showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorites&op=fglist&show='.$_GET['show'],'succ');
					}else {
						$fav_str = "'".implode("','",$fav_arr)."'";
						//更新收藏数量
						$clic_model = Model('clic');
						$clic_model->editclic(array('clic_collect'=>array('exp', 'clic_collect - 1')),array('clic_id'=>array('in', $fav_str)));
						showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorites&op=fslist','succ');
					}
				}
			}else {
				showDialog(Language::get('favorite_del_fail'),'','error');
			}

		}else {
			showDialog(Language::get('member_favorite_del_fail'),'','error');
		}
	}
	/**
	 * 店铺新上架的商品列表
	 *
	 * @param
	 * @return
	 */
	public function clic_doctorsOp(){
		$clic_id = intval($_GET["clic_id"]);
		if($clic_id > 0) {
			$condition = array();
			$add_time_from = TIMESTAMP-60*60*24*30;//30天
			$condition['clic_id'] = $clic_id;
			$condition['doctors_addtime']	= array('between', $add_time_from.','.TIMESTAMP);
			$doctors_model = Model('doctors');
			$doctors_list = $doctors_model->getdoctorsOnlineList($condition,'doctors_id,doctors_name,clic_id,doctors_image,doctors_price', 0, 'doctors_id desc', 50);
			Tpl::output('doctors_list',$doctors_list);
			Tpl::showpage('favorites_clic_doctors','null_layout');
		}
	}
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'fav_doctors','menu_name'=>Language::get('nc_member_path_collect_list'),	'menu_url'=>'index.php?act=member_favorites&op=fglist'),
			2=>array('menu_key'=>'fav_clic','menu_name'=>Language::get('nc_member_path_collect_clic'), 'menu_url'=>'index.php?act=member_favorites&op=fslist')
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
