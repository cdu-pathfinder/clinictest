<?php
/**
 * 商品列表
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class searchControl extends BaseHomeControl {


    //每页显示商品数
    const PAGESIZE = 24;

    //模型对象
    private $_model_search;

    public function indexOp() {
        Language::read('home_doctors_class_index');
        $this->_model_search = Model('search');

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $this->_indexer_search();
        $data_attr = $this->_get_attr_list();

        //处理排序
        $appointment = 'doctors_id desc';
        if (in_array($_GET['key'],array('1','2','3'))) {
            $sequence = $_GET['appointment'] == '1' ? 'asc' : 'desc';
            $appointment = str_replace(array('1','2','3'), array('doctors_salenum','doctors_click','doctors_price'), $_GET['key']);
            $appointment .= ' '.$sequence;
        }
        $model_doctors = Model('doctors');
        if (!isset($data_attr['sign']) || $data_attr['sign'] === true) {
            // 字段
            $fields = "doctors_id,doctors_commonid,doctors_name,doctors_jingle,gc_id,clic_id,clic_name,doctors_price,doctors_marketprice,doctors_storage,doctors_image,doctors_freight,doctors_salenum,color_id,evaluation_doctor_star,evaluation_count";

            $condition = array();
            if (is_array($indexer_ids)) {

                //商品主键搜索
                $condition['doctors_id'] = array('in',$indexer_ids);
                $doctors_list = $model_doctors->getdoctorsOnlineList($condition, $fields, 0, $appointment, self::PAGESIZE, null, false);
                pagecmd('setEachNum',self::PAGESIZE);
                pagecmd('setTotalNum',$indexer_count);

            } else {

                //执行正常搜索
                if (isset($data_attr['gcid_array'])) {
                    $condition['gc_id'] = array('in', $data_attr['gcid_array']);
                }
                if (intval($_GET['b_id']) > 0) {
                    $condition['brand_id'] = intval($_GET['b_id']);
                }
                if ($_GET['keyword'] != '') {
                    $condition['doctors_name|doctors_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
                }
                if (intval($_GET['area_id']) > 0) {
                    $condition['areaid_1'] = intval($_GET['area_id']);
                }
                if (in_array($_GET['type'], array(1,2))) {
                    if ($_GET['type'] == 1) {
                        $condition['clic_id'] = DEFAULT_PLATFORM_clic_ID;
                    } else if ($_GET['type'] == 2) {
                        $condition['clic_id'] = array('neq', DEFAULT_PLATFORM_clic_ID);
                    }
                }
                if (isset($data_attr['doctorsid_array'])){
                    $condition['doctors_id'] = array('in', $data_attr['doctorsid_array']);
                }
                $doctors_list = $model_doctors->getdoctorsListByColorDistinct($condition, $fields, $appointment, self::PAGESIZE);
            }

            Tpl::output('show_page1', $model_doctors->showpage(4));
            Tpl::output('show_page', $model_doctors->showpage(5));

            // 商品多图
            if (!empty($doctors_list)) {
                $doctorsid_array = array();       // 商品id数组
                $commonid_array = array(); // 商品公共id数组
                $clicid_array = array();       // 店铺id数组
                foreach ($doctors_list as $value) {
                    $doctorsid_array[] = $value['doctors_id'];
                    $commonid_array[] = $value['doctors_commonid'];
                    $clicid_array[] = $value['clic_id'];
                }
                $doctorsid_array = array_unique($doctorsid_array);
                $commonid_array = array_unique($commonid_array);
                $clicid_array = array_unique($clicid_array);

                // 商品多图
                $doctorsimage_more = Model('doctors')->getdoctorsImageList(array('doctors_commonid' => array('in', $commonid_array)));

                // 店铺
                $clic_list = Model('clic')->getclicMemberIDList($clicid_array);

                // 团购
                if (C('groupbuy_allow')) {
                    $groupbuy_list = Model('groupbuy')->getGroupbuyListBydoctorsCommonIDString(implode(',', $commonid_array));
                }

                if (C('promotion_allow')) {
                    // 限时折扣
                    $xianshi_list = Model('p_xianshi_doctors')->getXianshidoctorsListBydoctorsString(implode(',', $doctorsid_array));
                }

                foreach ($doctors_list as $key => $value) {
                    // 商品多图
                    foreach ($doctorsimage_more as $v) {
                        if ($value['doctors_commonid'] == $v['doctors_commonid'] && $value['clic_id'] == $v['clic_id'] && $value['color_id'] == $v['color_id']) {
                            $doctors_list[$key]['image'][] = $v;
                        }
                    }
                    // 店铺的开店会员编号
                    $clic_id = $value['clic_id'];
                    $doctors_list[$key]['member_id'] = $clic_list[$clic_id]['member_id'];
                    $doctors_list[$key]['clic_domain'] = $clic_list[$clic_id]['clic_domain'];
                    // 团购
                    if (isset($groupbuy_list[$value['doctors_commonid']])) {
                        $doctors_list[$key]['doctors_price'] = $groupbuy_list[$value['doctors_commonid']]['groupbuy_price'];
                        $doctors_list[$key]['group_flag'] = true;
                    }
                    if (isset($xianshi_list[$value['doctors_id']]) && !$doctors_list[$key]['group_flag']) {
                        $doctors_list[$key]['doctors_price'] = $xianshi_list[$value['doctors_id']]['xianshi_price'];
                        $doctors_list[$key]['xianshi_flag'] = true;
                    }
                }
            }
            Tpl::output('doctors_list', $doctors_list);
        }
        Tpl::output('class_name',  @$data_attr['gc_name']);

        //显示左侧分类
        if (intval($_GET['cate_id']) > 0) {
            $doctors_class_array = $this->_model_search->getLeftCategory(array($_GET['cate_id']));
        } elseif ($_GET['keyword'] != '') {
            $doctors_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
        }
        Tpl::output('doctors_class_array', $doctors_class_array);

        if ($_GET['keyword'] == ''){
            //不显示无商品的搜索项
            if (C('fullindexer.open')) {
                $data_attr['brand_array'] = $this->_model_search->delInvalidBrand($data_attr['brand_array']);
                $data_attr['attr_array'] = $this->_model_search->delInvalidAttr($data_attr['attr_array']);   
            }
        }

        //抛出搜索属性
        Tpl::output('brand_array',$data_attr['brand_array']);
        Tpl::output('attr_array',$data_attr['attr_array']);
//         Tpl::output('cate_array',$data_attr['cate_array']);
        Tpl::output('checked_brand', $data_attr['checked_brand']);
        Tpl::output('checked_attr', $data_attr['checked_attr']);

        $model_doctors_class = Model('doctors_class');

        // SEO
        if ($_GET['keyword'] == '') {
            $seo_class_name = @$data_attr['gc_name'];
            if (is_numeric($_GET['cate_id']) && empty($_GET['keyword'])) {
                $seo_info = $model_doctors_class->getKeyWords(intval($_GET['cate_id']));
                if (empty($seo_info[1])) {
                    $seo_info[1] = C('site_name') . ' - ' . $seo_class_name;
                }
                Model('seo')->type($seo_info)->param(array('name' => $seo_class_name))->show();
            } elseif ($_GET['keyword'] != '') {
                Tpl::output('html_title', (empty($_GET['keyword']) ? '' : $_GET['keyword'] . ' - ') . C('site_name') . L('nc_common_search'));
            }
        }

        // 当前位置导航
        $nav_link_list = $model_doctors_class->getdoctorsClassNav(intval($_GET['cate_id']));
        Tpl::output('nav_link_list', $nav_link_list );

        // 得到自定义导航信息
        $nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
        Tpl::output('index_sign', $nav_id);

        // 地区
        require(BASE_DATA_PATH.'/area/area.php');
        Tpl::output('area_array', $area_array);

        loadfunc('search');

        // 浏览过的商品
        $viewed_doctors = $model_doctors->getVieweddoctorsList();
        Tpl::output('viewed_doctors',$viewed_doctors);

        Tpl::showpage('search');

    }

    /**
     * 全文搜索
     * @return array 商品主键，搜索结果总数
     */
    private function _indexer_search() {
        if (!C('fullindexer.open')) return array(null,0);

        $condition = array();

        //拼接条件
        if (intval($_GET['cate_id']) > 0) {
            $cate_id = intval($_GET['cate_id']);
            $doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
            $depth = $doctors_class[$cate_id]['depth'];
            $cate_field = 'cate_'.$depth;
            $condition['cate']['key'] = $cate_field;
            $condition['cate']['value'] = $cate_id;
        }
        if ($_GET['keyword'] != '') {
            $condition['keyword'] = $_GET['keyword'];
        }
        if (intval($_GET['b_id']) > 0) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }
        if (preg_match('/^[\d_]+$/',$_GET['a_id'])) {
            $attr_ids = explode('_',$_GET['a_id']);
            if (is_array($attr_ids)){
                foreach ($attr_ids as $v) {
                    if (intval($v) > 0) {
                        $condition['attr_id'][] = intval($v);
                    }
                }
            }
        }
        if (in_array($_GET['type'],array('1','2'))) {
            $condition['clic_id'] = $_GET['type'];
        }
        if (intval($_GET['area_id']) > 0) {
            $condition['area_id'] = intval($_GET['area_id']);
        }

        //拼接排序(销量,浏览量,价格)
        $appointment = array();
        $appointment['key'] = 'doctors_id';
        $appointment['value'] = false;
        if (in_array($_GET['key'],array('1','2','3'))) {
            $appointment['value'] = $_GET['appointment'] == '1' ? true : false;
            $appointment['key'] = str_replace(array('1','2','3'), array('doctors_salenum','doctors_click','doctors_price'), $_GET['key']);
        }

        //取得商品主键等信息
        $result = $this->_model_search->getIndexerList($condition,$appointment,self::PAGESIZE);
        if ($result !== false) {
            list($indexer_ids,$indexer_count) = $result;
            //如果全文搜索发生错误，后面会再执行数据库搜索
        } else {
            $indexer_ids = null;
            $indexer_count = 0;
        }

        return array($indexer_ids,$indexer_count);
    }

    /**
     * 取得商品属性
     */
    private function _get_attr_list() {
        if (intval($_GET['cate_id']) > 0) {
            $data = $this->_model_search->getAttrList();
        } else {
            $data = array();
        }
        return $data;
    }

    /**
     * 获得推荐商品
     */
    public function get_booth_doctorsOp() {
        $gc_id = $_GET['cate_id'];
        if ($gc_id <= 0) {
            return false;
        }
        // 获取分类id及其所有子集分类id
        $doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        if (empty($doctors_class[$gc_id])) {
            return false;
        }
        $child = (!empty($doctors_class[$gc_id]['child'])) ? explode(',', $doctors_class[$gc_id]['child']) : array();
        $childchild = (!empty($doctors_class[$gc_id]['childchild'])) ? explode(',', $doctors_class[$gc_id]['childchild']) : array();
        $gcid_array = array_merge(array($gc_id), $child, $childchild);
        // 查询添加到推荐展位中的商品id
        $boothdoctors_list = Model('p_booth')->getBoothdoctorsList(array('gc_id' => array('in', $gcid_array)), 'doctors_id', 0, 4, 'rand()');
        if (empty($boothdoctors_list)) {
            return false;
        }

        $doctorsid_array = array();
        foreach ($boothdoctors_list as $val) {
            $doctorsid_array[] = $val['doctors_id'];
        }

        $fieldstr = "doctors_id,doctors_commonid,doctors_name,doctors_jingle,clic_id,clic_name,doctors_price,doctors_marketprice,doctors_storage,doctors_image,doctors_freight,doctors_salenum,color_id,evaluation_count";
        $doctors_list = Model('doctors')->getdoctorsOnlineList(array('doctors_id' => array('in', $doctorsid_array)), $fieldstr);
        if (empty($doctors_list)) {
            return false;
        }
        $commonid_array = array();
        foreach ($doctors_list as $val) {
            $commonid_array[] = $val['doctors_commonid'];
        }
        $groupbuy_list = Model('groupbuy')->getGroupbuyListBydoctorsCommonIDString(implode(',', $commonid_array));
        $xianshi_list = Model('p_xianshi_doctors')->getXianshidoctorsListBydoctorsString(implode(',', $doctorsid_array));
        foreach ($doctors_list as $key => $value) {
            // 团购
            if (isset($groupbuy_list[$value['doctors_commonid']])) {
                $doctors_list[$key]['doctors_price'] = $groupbuy_list[$value['doctors_commonid']]['groupbuy_price'];
                $doctors_list[$key]['group_flag'] = true;
            }
            if (isset($xianshi_list[$value['doctors_id']]) && !$doctors_list[$key]['group_flag']) {
                $doctors_list[$key]['doctors_price'] = $xianshi_list[$value['doctors_id']]['xianshi_price'];
                $doctors_list[$key]['xianshi_flag'] = true;
            }
        }
        Tpl::output('doctors_list', $doctors_list);
        Tpl::output('groupbuy_list', $groupbuy_list);
        Tpl::output('xianshi_list', $xianshi_list);
        Tpl::showpage('doctors.booth', 'null_layout');
    }

	public function auto_completeOp() {
	    require(BASE_DATA_PATH.'/xs/lib/XS.php');
	    $obj_doc = new XSDocument();
	    $obj_xs = new XS('2014');
	    $obj_index = $obj_xs->index;
	    $obj_search = $obj_xs->search;
	    $obj_search->setCharset(CHARSET);
        try {
            $corrected = $obj_search->getExpandedQuery($_GET['term']);
            if (count($corrected) !== 0) {
                $data = array();
                foreach ($corrected as $word)
                {
                    $row['id'] = $word;
                    $row['label'] = $word;
                    $row['value'] = $word;
                    $data[] = $row;
                }
                exit(json_encode($data));
            }
        } catch (XSException $e) {
            print_R($e->getMessage());exit;
        }
	}

}