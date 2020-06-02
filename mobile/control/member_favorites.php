<?php
/**
 * 我的收藏
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

class member_favoritesControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 收藏列表
     */
    public function favorites_listOp() {
		$model_favorites = Model('favorites');

        $favorites_list = $model_favorites->getdoctorsFavoritesList(array('member_id'=>$this->member_info['member_id']), '*', $this->page);
        $page_count = $model_favorites->gettotalpage();
        $favorites_id = '';
        foreach ($favorites_list as $value){
            $favorites_id .= $value['fav_id'] . ',';
        }
        $favorites_id = rtrim($favorites_id, ',');

        $model_doctors = Model('doctors');
        $field = 'doctors_id,doctors_name,doctors_price,doctors_image,clic_id';
        $doctors_list = $model_doctors->getdoctorsList(array('doctors_id' => array('in', $favorites_id)), $field);
        foreach ($doctors_list as $key=>$value) {
            $doctors_list[$key]['fav_id'] = $value['doctors_id'];
            $doctors_list[$key]['doctors_image_url'] = cthumb($value['doctors_image'], 240, $value['clic_id']);
        }

        output_data(array('favorites_list' => $doctors_list), mobile_page($page_count));
    }

    /**
     * 添加收藏
     */
    public function favorites_addOp() {
		$doctors_id = intval($_POST['doctors_id']);
		if ($doctors_id <= 0){
            output_error('参数错误');
		}

		$favorites_model = Model('favorites');

		//判断是否已经收藏
        $favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>$doctors_id,'fav_type'=>'doctors','member_id'=>$this->member_info['member_id']));
		if(!empty($favorites_info)) {
            output_error('您已经收藏了该商品');
		}

		//判断商品是否为当前会员所有
		$doctors_model = Model('doctors');
		$doctors_info = $doctors_model->getdoctorsInfo(array('doctors_id' => $doctors_id));
		$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
		if ($doctors_info['clic_id'] == $seller_info['clic_id']) {
            output_error('您不能收藏自己发布的商品');
		}

		//添加收藏		
		$insert_arr = array();
		$insert_arr['member_id'] = $this->member_info['member_id'];
		$insert_arr['fav_id'] = $doctors_id;
		$insert_arr['fav_type'] = 'doctors';
		$insert_arr['fav_time'] = TIMESTAMP;
		$result = $favorites_model->addFavorites($insert_arr);

		if ($result){
			//增加收藏数量
			$doctors_model->editdoctors(array('doctors_collect' => array('exp', 'doctors_collect + 1')), array('doctors_id' => $doctors_id));
            output_data('1');
		}else{
            output_error('收藏失败');
		}
    }
    
    /**
     * 删除收藏
     */
    public function favorites_delOp() {
		$fav_id = intval($_POST['fav_id']);
		if ($fav_id <= 0){
            output_error('参数错误');
		}

		$model_favorites = Model('favorites');

        $condition = array();
        $condition['fav_id'] = $fav_id;
        $condition['member_id'] = $this->member_info['member_id'];
        $model_favorites->delFavorites($condition);
        output_data('1');
    }

}
