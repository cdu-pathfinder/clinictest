<?php
/**
 * 商品
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class doctorsControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function doctors_listOp() {
        $model_doctors = Model('doctors');

        //查询条件
        $condition = array();
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) { 
            $condition['doctors_name|doctors_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
        }

        //所需字段
        $fieldstr = "doctors_id,doctors_commonid,clic_id,doctors_name,doctors_price,doctors_marketprice,doctors_image,doctors_salenum,evaluation_doctor_star,evaluation_count";

        //Sorting method
        $appointment = $this->_doctors_list_appointment($_GET['key'], $_GET['appointment']);

        $doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, $fieldstr, $appointment, $this->page);
        $page_count = $model_doctors->gettotalpage();

        //处理商品列表(团购、限时折扣、商品图片)
        $doctors_list = $this->_doctors_list_extend($doctors_list);

        output_data(array('doctors_list' => $doctors_list), mobile_page($page_count));
    }

    /**
     * 商品列表Sorting method
     */
    private function _doctors_list_appointment($key, $appointment) {
        $result = 'doctors_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($appointment == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
                case '1' :
                    $result = 'doctors_salenum' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' : 
                    $result = 'doctors_click' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'doctors_price' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 处理商品列表(团购、限时折扣、商品图片)
     */
    private function _doctors_list_extend($doctors_list) {
        //获取商品列表编号数组
        $commonid_array = array();
        $doctorsid_array = array();
        foreach($doctors_list as $key => $value) {
            $commonid_array[] = $value['doctors_commonid'];
            $doctorsid_array[] = $value['doctors_id'];
        }

        //促销
        $groupbuy_list = Model('groupbuy')->getGroupbuyListBydoctorsCommonIDString(implode(',', $commonid_array));
        $xianshi_list = Model('p_xianshi_doctors')->getXianshidoctorsListBydoctorsString(implode(',', $doctorsid_array));
        foreach ($doctors_list as $key => $value) {
            //团购
            if (isset($groupbuy_list[$value['doctors_commonid']])) {
                $doctors_list[$key]['doctors_price'] = $groupbuy_list[$value['doctors_commonid']]['groupbuy_price'];
                $doctors_list[$key]['group_flag'] = true;
            } else {
                $doctors_list[$key]['group_flag'] = false;
            }

            //限时折扣
            if (isset($xianshi_list[$value['doctors_id']]) && !$doctors_list[$key]['group_flag']) {
                $doctors_list[$key]['doctors_price'] = $xianshi_list[$value['doctors_id']]['xianshi_price'];
                $doctors_list[$key]['xianshi_flag'] = true;
            } else {
                $doctors_list[$key]['xianshi_flag'] = false;
            }

            //商品图片url
            $doctors_list[$key]['doctors_image_url'] = cthumb($value['doctors_image'], 360, $value['clic_id']); 

            unset($doctors_list[$key]['clic_id']);
            unset($doctors_list[$key]['doctors_commonid']);
            unset($doctors_list[$key]['nc_distinct']);
        }

        return $doctors_list;
    }

    /**
     * 商品详细页
     */
    public function doctors_detailOp() {
        $doctors_id = intval($_GET ['doctors_id']);
        
        // 商品详细信息
        $model_doctors = Model('doctors');
        $doctors_detail = $model_doctors->getdoctorsDetail($doctors_id, '*');
        if (empty($doctors_detail)) {
            output_error('商品不存在');
        }

        //推荐商品
        $model_clic = Model('clic');
        $hot_sales = $model_clic->getHotSalesList($doctors_detail['doctors_info']['clic_id'], 6);
        $doctors_commend_list = array();
        foreach($hot_sales as $value) {
            $doctors_commend = array();
            $doctors_commend['doctors_id'] = $value['doctors_id'];
            $doctors_commend['doctors_name'] = $value['doctors_name'];
            $doctors_commend['doctors_price'] = $value['doctors_price'];
            $doctors_commend['doctors_image_url'] = cthumb($value['doctors_image'], 240);
            $doctors_commend_list[] = $doctors_commend;
        }
        $doctors_detail['doctors_commend_list'] = $doctors_commend_list;

        //商品详细信息处理
        $doctors_detail = $this->_doctors_detail_extend($doctors_detail);

        output_data($doctors_detail);
    }

    /**
     * 商品详细信息处理
     */
    private function _doctors_detail_extend($doctors_detail) {
        //整理商品规格
        unset($doctors_detail['spec_list']);
        $doctors_detail['spec_list'] = $doctors_detail['spec_list_mobile'];
        unset($doctors_detail['spec_list_mobile']);

        //整理商品图片
        unset($doctors_detail['doctors_image']);
        $doctors_detail['doctors_image'] = implode(',', $doctors_detail['doctors_image_mobile']);
        unset($doctors_detail['doctors_image_mobile']);

        //整理数据
        unset($doctors_detail['doctors_info']['doctors_commonid']);
        unset($doctors_detail['doctors_info']['gc_id']);
        unset($doctors_detail['doctors_info']['gc_name']);
        unset($doctors_detail['doctors_info']['clic_id']);
        unset($doctors_detail['doctors_info']['clic_name']);
        unset($doctors_detail['doctors_info']['brand_id']);
        unset($doctors_detail['doctors_info']['brand_name']);
        unset($doctors_detail['doctors_info']['type_id']);
        unset($doctors_detail['doctors_info']['doctors_image']);
        unset($doctors_detail['doctors_info']['doctors_body']);
        unset($doctors_detail['doctors_info']['doctors_state']);
        unset($doctors_detail['doctors_info']['doctors_stateremark']);
        unset($doctors_detail['doctors_info']['doctors_verify']);
        unset($doctors_detail['doctors_info']['doctors_verifyremark']);
        unset($doctors_detail['doctors_info']['doctors_lock']);
        unset($doctors_detail['doctors_info']['doctors_addtime']);
        unset($doctors_detail['doctors_info']['doctors_edittime']);
        unset($doctors_detail['doctors_info']['doctors_selltime']);
        unset($doctors_detail['doctors_info']['doctors_show']);
        unset($doctors_detail['doctors_info']['doctors_commend']);
        unset($doctors_detail['groupbuy_info']);
        unset($doctors_detail['xianshi_info']);

        return $doctors_detail;
    }

    /**
     * 商品详细页
     */
    public function doctors_bodyOp() {
        $doctors_id = intval($_GET ['doctors_id']);

        $model_doctors = Model('doctors');

        $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id' => $doctors_id));
        $doctors_common_info = $model_doctors->getdoctoreCommonInfo(array('doctors_commonid' => $doctors_info['doctors_commonid']));

        Tpl::output('doctors_common_info', $doctors_common_info);
        Tpl::showpage('doctors_body');
    }
}
