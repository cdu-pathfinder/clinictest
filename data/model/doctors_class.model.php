<?php
/**
 * 商品类别模型
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

class doctors_classModel extends Model{
    public function __construct(){
        parent::__construct('doctors_class');
    }
    
    /**
     * 类别列表
     * 
     * @param  array   $condition  检索条件
     * @return array   返回二位数组
     */
    public function getdoctorsClassList($condition, $field = '*') {
        $result = $this->table('doctors_class')->field($field)->where($condition)->appointment('gc_parent_id asc,gc_sort asc,gc_id asc')->limit(10000)->select();
        return $result;
    }

    /**
     * 类别详细
     * 
     * @param   array   $condition  条件
     * $param   string  $field  字段
     * @return  array   返回一维数组
     */
    public function getdoctorsClassInfo($condition, $field = '*') {
        $result = $this->field($field)->where($condition)->find();
        return $result;
    }
    
    /**
     * 取得店铺绑定的分类
     * 
     * @param   number  $clic_id   店铺id
     * @param   number  $pid        父级分类id
     * @param   number  $deep       深度
     * @return  array   二维数组
     */
    public function getdoctorsClass($clic_id, $pid = 0, $deep = 1) {
        // 读取商品分类
        $gc_list = $this->getdoctorsClassList(array('gc_parent_id' => $pid), 'gc_id, gc_name, type_id');
        // 如果店铺ID不为商城店铺的话，读取绑定分类
        if (!checkPlatformclic()) {
            $gc_list = array_under_reset($gc_list, 'gc_id');
            $model_clicbindclass = Model('clic_bind_class');
            $gcid_array = $model_clicbindclass->getclicBindClassList(array('clic_id' => $clic_id), '', "class_{$deep} asc", "distinct class_{$deep}");
            if (!empty($gcid_array)) {
                $tmp_gc_list = array();
                foreach ($gcid_array as $value) {
                    if (isset($gc_list[$value["class_{$deep}"]])) {
                        $tmp_gc_list[] = $gc_list[$value["class_{$deep}"]];
                    }
                }
                $gc_list = $tmp_gc_list;
            } else {
                return array();
            }
        }
        return $gc_list;
    }
    
    /**
     * 删除商品分类
     * @param unknown $condition
     * @return boolean
     */
    public function deldoctorsClass($condition) {
        return $this->where($condition)->delete();
    }
    
    /**
     * 删除商品分类
     * 
     * @param array $gcids
     * @return boolean
     */
    public function deldoctorsClassByGcIdString($gcids) {
        $gcids = explode(',', $gcids);
        if (empty($gcids)) {
            return false;
        }
        $doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        $gcid_array = array();
        foreach ($gcids as $gc_id) {
            $child = (!empty($doctors_class[$gc_id]['child'])) ? explode(',', $doctors_class[$gc_id]['child']) : array();
            $childchild = (!empty($doctors_class[$gc_id]['childchild'])) ? explode(',', $doctors_class[$gc_id]['childchild']) : array();
            $gcid_array = array_merge($gcid_array, array($gc_id), $child, $childchild);
        }
        // 删除商品分类
        $this->deldoctorsClass(array('gc_id' => array('in', $gcid_array)));
        // 删除常用商品分类
        Model('doctors_class_staple')->delStaple(array('gc_id_1|gc_id_2|gc_id_3' => array('in', $gcid_array)));
        // 删除分类tag表
        Model('doctors_class_tag')->deldoctorsClassTag(array('gc_id_1|gc_id_2|gc_id_3' => array('in', $gcid_array)));
        // 删除店铺绑定分类
        Model('clic_bind_class')->delclicBindClass(array('class_1|class_2|class_3' => array('in', $gcid_array)));
        // 商品下架
        Model('doctors')->editProducesLockUp(array('doctors_stateremark' => '商品分类被删除，需要重新选择分类'), array('gc_id' => array('in', $gcid_array)));
        return true;
    }

	/**
	 * 前台头部的商品分类
	 *
     * @param   number  $update_all   更新
     * @return  array   数组
	 */
	public function get_all_category($update_all = 0) {
	    $file_name = BASE_DATA_PATH.'/cache/index/category.php';
		if (!file_exists($file_name) || $update_all == 1) {//文件不存在时更新或者强制更新时执行
			$class_list = $this->getdoctorsClassList(array(), 'gc_id, gc_name, type_id, gc_parent_id, gc_sort');
			$gc_list = array();
			$class1_deep = array();//第1级关联第3级数组
			$class2_ids = array();//第2级关联第1级ID数组
			$type_ids = array();//第2级分类关联类型
			if (is_array($class_list) && !empty($class_list)) {
    			foreach ($class_list as $key => $value) {
    			    $p_id = $value['gc_parent_id'];//父级ID
    			    $gc_id = $value['gc_id'];
    			    $sort = $value['gc_sort'];
    			    if ($p_id == 0) {//第1级分类
    			        $gc_list[$gc_id] = $value;
    			    } elseif (array_key_exists($p_id,$gc_list)) {//第2级
    			        $class2_ids[$gc_id] = $p_id;
    			        $type_ids[] = $value['type_id'];
    			        $gc_list[$p_id]['class2'][$gc_id] = $value;
    			    } elseif (array_key_exists($p_id,$class2_ids)) {//第3级
    			        $parent_id = $class2_ids[$p_id];//取第1级ID
    			        $gc_list[$parent_id]['class2'][$p_id]['class3'][$gc_id] = $value;
    			        $class1_deep[$parent_id][$sort][] = $value;
    			    }
    			}
    			$type_brands = $this->get_type_brands($type_ids);//类型关联品牌
    			foreach ($gc_list as $key => $value) {
    			    $gc_id = $value['gc_id'];
    			    $pic_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
    			    if (file_exists($pic_name)) {
    			        $gc_list[$gc_id]['pic'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
    			    }
    			    $class3s = $class1_deep[$gc_id];
    			    
    			    if (is_array($class3s) && !empty($class3s)) {//取关联的第3级
    			        $class3_n = 0;//已经找到的第3级分类个数
    			        ksort($class3s);//排序取到分类
        			    foreach ($class3s as $k3 => $v3) {
			                if ($class3_n >= 5) {//最多取5个
			                    break;
			                }
            			    foreach ($v3 as $k => $v) {
    			                if ($class3_n >= 5) {
    			                    break;
    			                }
        			            if (is_array($v) && !empty($v)) {
        			                $p_id = $v['gc_parent_id'];
        			                $gc_id = $v['gc_id'];
        			                $parent_id = $class2_ids[$p_id];//取第1级ID
        			                $gc_list[$parent_id]['class3'][$gc_id] = $v;
        			                $class3_n += 1;
        			            }
    			            }
			            }
			        }
    			    $class2s = $value['class2'];
    			    if (is_array($class2s) && !empty($class2s)) {//第2级关联品牌
        			    foreach ($class2s as $k2 => $v2) {
        			        $p_id = $v2['gc_parent_id'];
        			        $gc_id = $v2['gc_id'];
        			        $type_id = $v2['type_id'];
        			        $gc_list[$p_id]['class2'][$gc_id]['brands'] = $type_brands[$type_id];
			            }
			        }
    			}
    			F('category', $gc_list, 'cache/index');
			}
		} else {
		    $gc_list = include $file_name;
		}
	    return $gc_list;
    }

	/**
	 * 类型关联品牌
	 *
     * @param   array  $type_ids   类型
     * @return  array   数组
	 */
	public function get_type_brands($type_ids = array()) {	
	    $brands = array();//品牌
	    $type_brands = array();//类型关联品牌
		if (is_array($type_ids) && !empty($type_ids)) {
		    $type_ids = array_unique($type_ids);
		    $type_list = $this->table('type_brand')->where(array('type_id'=>array('in',$type_ids)))->limit(10000)->select();
		    if (is_array($type_list) && !empty($type_list)) {
		        $brand_list = $this->table('brand')->field('brand_id,brand_name,brand_pic')->where(array('brand_apply'=>1))->limit(10000)->select();
		        if (is_array($brand_list) && !empty($brand_list)) {
			        foreach ($brand_list as $key => $value) {
			            $brand_id = $value['brand_id'];
			            $brands[$brand_id] = $value;
			        }
			        foreach ($type_list as $key => $value) {
			            $type_id = $value['type_id'];
			            $brand_id = $value['brand_id'];
			            $brand = $brands[$brand_id];
			            if (is_array($brand) && !empty($brand)) {
			                $type_brands[$type_id][$brand_id] = $brand;
			            }
			        }
		        }
		    }
		    
		}
		return $type_brands;
	}

	/**
	 * 类别列表
	 *
	 * @param array $condition 检索条件
	 * @return array 数组结构的返回结果
	 */
	public function getClassList($condition ,$field='*'){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'doctors_class';
		$param['field'] = $field;
		$param['where'] = $condition_str;
		$param['appointment'] = $condition['appointment'] ? $condition['appointment'] : 'gc_parent_id asc,gc_sort asc,gc_id asc';
		$result = Db::select($param);

		return $result;
	}

	/**
	 * 构造检索条件
	 *
	 * @param int $id 记录ID
	 * @return string 字符串类型的返回结果
	 */
	private function _condition($condition){
		$condition_str = '';

		if (!is_null($condition['gc_parent_id'])){
			$condition_str .= " and gc_parent_id = '". intval($condition['gc_parent_id']) ."'";
		}
		if (!is_null($condition['no_gc_id'])){
			$condition_str .= " and gc_id != '". intval($condition['no_gc_id']) ."'";
		}
		if ($condition['in_gc_id'] != ''){
			$condition_str .= " and gc_id in (". $condition['in_gc_id'] .")";
		}
		if ($condition['gc_name'] != ''){
			$condition_str .= " and gc_name = '". $condition['gc_name'] ."'";
		}
		if (isset($condition['un_type_name'])) {
			$condition_str .= " and type_name <> ''";
		}
		if ($condition['un_type_id'] != '') {
			$condition_str .= " and type_id <> '". $condition['un_type_id'] ."'";
		}
		if ($condition['in_type_id'] != '') {
			$condition_str .= " and type_id in (".$condition['in_type_id'].")";
		}

		return $condition_str;
	}
    
    /**
     * 取单个分类的内容
     *
     * @param int $id 分类ID
     * @return array 数组类型的返回结果
     */
    public function getOnedoctorsClass($id) {
        if (intval ( $id ) > 0) {
            $result = $this->where(array('gc_id' => $id))->find();
            return $result;
        } else {
            return false;
        }
    }

	/**
	 * 新增
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('doctors_class',$tmp);
			return $result;
		}else {
			return false;
		}
	}

	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function doctorsClassUpdate($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " gc_id = '". $param['gc_id'] ."'";
			$result = Db::update('doctors_class',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function updateWhere($param, $condition){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = $this->_condition($condition);
			$result = Db::update('doctors_class',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}

	/**
	 * 删除分类
	 *
	 * @param int $id 记录ID
	 * @return bool 布尔类型的返回结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " gc_id = '". intval($id) ."'";
			$result = Db::delete('doctors_class',$where);
			return $result;
		}else {
			return false;
		}
	}

	/**
	 * 取分类列表，最多为三级
	 *
	 * @param int $show_deep 显示深度
	 * @param array $condition 检索条件
	 * @return array 数组类型的返回结果
	 */
	public function getTreeClassList($show_deep='3',$condition=array()){
		$class_list = $this->getClassList($condition);
		$doctors_class = array();//分类数组
		if(is_array($class_list) && !empty($class_list)) {
			$show_deep = intval($show_deep);
			if ($show_deep == 1){//只显示第一级时用循环给分类加上深度deep号码
				foreach ($class_list as $val) {
					if($val['gc_parent_id'] == 0) {
						$val['deep'] = 1;
						$doctors_class[] = $val;
					} else {
						break;//父类编号不为0时退出循环
					}
				}
			} else {//显示第二和三级时用递归
				$doctors_class = $this->_getTreeClassList($show_deep,$class_list);
			}
		}
		return $doctors_class;
	}

	/**
	 * 递归 整理分类
	 *
	 * @param int $show_deep 显示深度
	 * @param array $class_list 类别内容集合
	 * @param int $deep 深度
	 * @param int $parent_id 父类编号
	 * @param int $i 上次循环编号
	 * @return array $show_class 返回数组形式的查询结果
	 */
	private function _getTreeClassList($show_deep,$class_list,$deep=1,$parent_id=0,$i=0){
		static $show_class = array();//树状的平行数组
		if(is_array($class_list) && !empty($class_list)) {
			$size = count($class_list);
			if($i == 0) $show_class = array();//从0开始时清空数组，防止多次调用后出现重复
			for ($i;$i < $size;$i++) {//$i为上次循环到的分类编号，避免重新从第一条开始
				$val = $class_list[$i];
				$gc_id = $val['gc_id'];
				$gc_parent_id	= $val['gc_parent_id'];
				if($gc_parent_id == $parent_id) {
					$val['deep'] = $deep;
					$show_class[] = $val;
					if($deep < $show_deep && $deep < 3) {//本次深度小于显示深度时执行，避免取出的数据无用
						$this->_getTreeClassList($show_deep,$class_list,$deep+1,$gc_id,$i+1);
					}
				}
				if($gc_parent_id > $parent_id) break;//当前分类的父编号大于本次递归的时退出循环
			}
		}
		return $show_class;
	}

	/**
	 * 取指定分类ID下的所有子类
	 *
	 * @param int/array $parent_id 父ID 可以单一可以为数组
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function getChildClass($parent_id){
		$condition = array('appointment'=>'gc_parent_id asc,gc_sort asc,gc_id asc');
		$all_class = $this->getClassList($condition);
		if (is_array($all_class)){
			if (!is_array($parent_id)){
				$parent_id = array($parent_id);
			}
			$result = array();
			foreach ($all_class as $k => $v){
				$gc_id	= $v['gc_id'];//返回的结果包括父类
				$gc_parent_id	= $v['gc_parent_id'];
				if (in_array($gc_id,$parent_id) || in_array($gc_parent_id,$parent_id)){
					$parent_id[] = $v['gc_id'];
					$result[] = $v;
				}
			}
			return $result;
		}else {
			return false;
		}
	}
    
    /**
     * 取指定分类ID的导航链接
     *
     * @param int $id 父类ID/子类ID
     * @param int $sign 1、0 1为最后一级不加超链接，0为加超链接
     * @return array $nav_link 返回数组形式类别导航连接
     */
    public function getdoctorsClassNav($id = 0, $sign = 1) {
        if (intval ( $id ) > 0) {
            $data = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
            
            // 当前分类不加超链接
            if ($sign == 1) {
                $nav_link [] = array(
                        'title' => $data[$id]['gc_name']
                );
            } else {
                $nav_link [] = array(
                        'title' => $data[$id]['gc_name'],
                        'link' => urlclinic('search', 'index', array('cate_id' => $data[$id]['gc_id']))
                );
            }
            
            // 最多循环4层
            for($i = 1; $i < 5; $i ++) {
                if ($data[$id]['gc_parent_id'] == '0') {
                    break;
                }
                $id = $data[$id]['gc_parent_id'];
                $nav_link[] = array(
                        'title' => $data[$id]['gc_name'],
                        'link' => urlclinic('search', 'index', array('cate_id' => $data[$id]['gc_id']))
                );
            }
        } else {
            // 加上 首页 商品分类导航
            $nav_link[] = array('title' => Language::get('doctors_class_index_search_results'));
        }
        // 首页导航
        $nav_link[] = array('title' => Language::get('homepage'), 'link' => clinic_SITE_URL);
        
        krsort ( $nav_link );
        return $nav_link;
    }
    
    /**
     * 取指定分类ID的所有父级分类
     *
     * @param int $id 父类ID/子类ID
     * @return array $nav_link 返回数组形式类别导航连接
     */
    public function getdoctorsClassLineForTag($id = 0) {
        if (intval($id)> 0) {
            $gc_line = array();
            /**
             * 取当前类别信息
             */
            $class = $this->getOnedoctorsClass(intval($id));
            /**
             * 是否是子类
             */
            if ($class['gc_parent_id'] != 0) {
                $parent_1 = $this->getOnedoctorsClass($class['gc_parent_id']);
                if ($parent_1['gc_parent_id'] != 0) {
                    $parent_2 = $this->getOnedoctorsClass($parent_1['gc_parent_id']);
                    $gc_line['gc_id'] = $parent_2['gc_id'];
                    $gc_line['type_id'] = $parent_2['type_id'];
                    $gc_line['gc_id_1'] = $parent_2 ['gc_id'];
                    $gc_line['gc_tag_name'] = trim($parent_2['gc_name']) . ' >';
                    $gc_line['gc_tag_value'] = trim($parent_2['gc_name']) . ',';
                }
                $gc_line['gc_id'] = $parent_1['gc_id'];
                $gc_line['type_id'] = $parent_1['type_id'];
                if (!isset($gc_line['gc_id_1'])) {
                    $gc_line['gc_id_1'] = $parent_1['gc_id'];
                } else {
                    $gc_line['gc_id_2'] = $parent_1['gc_id'];
                }
                $gc_line['gc_tag_name'] .= trim($parent_1['gc_name']) . ' >';
                $gc_line['gc_tag_value'] .= trim($parent_1['gc_name']) . ',';
            }
            $gc_line['gc_id'] = $class['gc_id'];
            $gc_line['type_id'] = $class['type_id'];
            if (!isset($gc_line['gc_id_1'])) {
                $gc_line['gc_id_1'] = $class['gc_id'];
            } else if (!isset($gc_line['gc_id_2'])) {
                $gc_line['gc_id_2'] = $class['gc_id'];
            } else {
                $gc_line['gc_id_3'] = $class['gc_id'];
            }
            $gc_line['gc_tag_name'] .= trim($class['gc_name']) . ' >';
            $gc_line['gc_tag_value'] .= trim($class['gc_name']) . ',';
        }
        $gc_line['gc_tag_name'] = trim($gc_line['gc_tag_name'], ' >');
        $gc_line['gc_tag_value'] = trim($gc_line['gc_tag_value'], ',');
        return $gc_line;
    }

    /**
     * 取得分类关键词，方便SEO
     *
     */
	public function getKeyWords($gc_id = null){
		if (empty($gc_id)) return false;
		$keywrods = ($seo_info = H('doctors_class_seo')) ? $seo_info : H('doctors_class_seo',true);
		$seo_title = $keywrods[$gc_id]['title'];
		$seo_key = '';
		$seo_desc = '';
		if ($gc_id > 0){
			if (isset($keywrods[$gc_id])){
				$seo_key .= $keywrods[$gc_id]['key'].',';
				$seo_desc .= $keywrods[$gc_id]['desc'].',';
			}
			$doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
			if(($gc_id = $doctors_class[$gc_id]['gc_parent_id']) > 0){
				if (isset($keywrods[$gc_id])){
					$seo_key .= $keywrods[$gc_id]['key'].',';
					$seo_desc .= $keywrods[$gc_id]['desc'].',';
				}
			}
			if(($gc_id = $doctors_class[$gc_id]['gc_parent_id']) > 0){
				if (isset($keywrods[$gc_id])){
					$seo_key .= $keywrods[$gc_id]['key'].',';
					$seo_desc .= $keywrods[$gc_id]['desc'].',';
				}
			}
		}
		return array(1=>$seo_title,2=>trim($seo_key,','),3=>trim($seo_desc,','));
	}

}
