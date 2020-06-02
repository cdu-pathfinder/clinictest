<?php
/**
 * 店铺模型管理
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
class clicModel extends Model {
    public function __construct(){
        parent::__construct('clic');
    }

	/**
	 * 查询店铺列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getclicList($condition, $page = null, $appointment = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->appointment($appointment)->limit($limit)->page($page)->select();
        return $result;
    }

	/**
	 * 查询有效店铺列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getclicOnlineList($condition, $page = null, $appointment = '', $field = '*') {
        $condition['clic_state'] = 1;
        return $this->getclicList($condition, $page, $appointment, $field);
    }

    /**
     * 店铺数量
     * @param array $condition
     * @return int
     */
    public function getclicCount($condition) {
        return $this->where($condition)->count();
    }

    /**
	 * 按店铺编号查询店铺的开店信息
     *
	 * @param array $clicid_array 店铺编号
     * @return array
	 */
    public function getclicMemberIDList($clicid_array) {
        $clic_list = $this->table('clic')->where(array('clic_id'=> array('in', $clicid_array)))->field('clic_id,member_id,clic_domain')->key('clic_id')->select();
        return $clic_list;
    }

    /**
	 * 查询店铺信息
     *
	 * @param array $condition 查询条件
     * @return array
	 */
    public function getclicInfo($condition) {
        $clic_info = $this->where($condition)->find();
        if(!empty($clic_info)) {
            if(!empty($clic_info['clic_presales'])) $clic_info['clic_presales'] = unserialize($clic_info['clic_presales']);
            if(!empty($clic_info['clic_aftersales'])) $clic_info['clic_aftersales'] = unserialize($clic_info['clic_aftersales']);

            //商品数
            $model_doctors = Model('doctors');
            $clic_info['doctors_count'] = $model_doctors->getdoctorsCommonOnlineCount(array('clic_id' => $clic_info['clic_id']));

            //店铺评价
            $model_evaluate_clic = Model('evaluate_clic');
            $clic_evaluate_info = $model_evaluate_clic->getEvaluateclicInfoByclicID($clic_info['clic_id'], $clic_info['sc_id']);

            $clic_info = array_merge($clic_info, $clic_evaluate_info);
        }
        return $clic_info;
    }

    /**
	 * 通过店铺编号查询店铺信息
     *
	 * @param int $clic_id 店铺编号
     * @return array
	 */
    public function getclicInfoByID($clic_id) {
        $clic_info = rcache($clic_id, 'clic_info');
        if(empty($clic_info)) {
            $clic_info = $this->getclicInfo(array('clic_id' => $clic_id));
            wcache($clic_id, $clic_info, 'clic_info');
        }
        return $clic_info;
    }

    public function getclicOnlineInfoByID($clic_id) {
        $clic_info = $this->getclicInfoByID($clic_id);
        if(empty($clic_info) || $clic_info['clic_state'] == '0') {
            return null;
        } else {
            return $clic_info;
        }
    }

    public function getclicIDString($condition) {
        $condition['clic_state'] = 1;
        $clic_list = $this->getclicList($condition);
        $clic_id_string = '';
        foreach ($clic_list as $value) {
            $clic_id_string .= $value['clic_id'].',';
        }
        return $clic_id_string;
    }

	/*
	 * 添加店铺
     *
	 * @param array $param 店铺信息
	 * @return bool
	 */
    public function addclic($param){
        return $this->insert($param);
    }

	/*
	 * 编辑店铺
     *
	 * @param array $update 更新信息
	 * @param array $condition 条件
	 * @return bool
	 */
    public function editclic($update, $condition){
        //清空缓存
        $clic_list = $this->getclicList($condition);
        foreach ($clic_list as $value) {
            wcache($value['clic_id'], array(), 'clic_info');
        }

        return $this->where($condition)->update($update);
    }

	/*
	 * 删除店铺
     *
	 * @param array $condition 条件
	 * @return bool
	 */
    public function delclic($condition){
        $clic_info = $this->getclicInfo($condition);
        //删除店铺相关图片
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_label']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$clic_info['clic_banner']);
        if($clic_info['clic_slide'] != ''){
            foreach(explode(',', $clic_info['clic_slide']) as $val){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$val);
            }
        }

        //清空缓存
        wcache($clic_info['clic_id'], array(), 'clic_info');

        return $this->where($condition)->delete();
    }

    /**
     * 获取商品销售排行
     *
     * @param int $clic_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotSalesList($clic_id, $limit = 5) {
        $prefix = 'clic_hot_sales_list_' . $limit;
        $hot_sales_list = rcache($clic_id, $prefix);
        if(empty($hot_sales_list)) {
            $model_doctors = Model('doctors');
            $hot_sales_list = $model_doctors->getdoctorsOnlineList(array('clic_id' => $clic_id), '*', 0, 'doctors_salenum desc', $limit);
            wcache($clic_id, $hot_sales_list, $prefix);
        }
        return $hot_sales_list;
    }

    /**
     * 获取商品收藏排行
     *
     * @param int $clic_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotCollectList($clic_id, $limit = 5) {
        $prefix = 'clic_collect_sales_list_' . $limit;
        $hot_collect_list = rcache($clic_id, $prefix);
        if(empty($hot_collect_list)) {
            $model_doctors = Model('doctors');
            $hot_collect_list = $model_doctors->getdoctorsOnlineList(array('clic_id' => $clic_id), '*', 0, 'doctors_collect desc', $limit);
            wcache($clic_id, $hot_collect_list, $prefix);
        }
        return $hot_collect_list;
    }

}
