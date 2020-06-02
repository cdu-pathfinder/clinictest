<?php
/**
 * 购物车管理
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
class cartModel extends Model {

    /**
     * 购物车商品总金额
     */
    private $cart_all_price = 0;

    /**
     * 购物车商品总数
     */
    private $cart_doctors_num = 0;

    public function __construct() {
       parent::__construct('cart'); 
    }

    /**
     * 取属性值魔术方法
     *
     * @param string $name
     */
    public function __get($name) {
        return $this->$name;
    }

	/**
	 * 检查购物车内商品是否存在
	 *
	 * @param
	 */
	public function checkCart($condition = array()) {
	    return $this->where($condition)->find();
	}
	
	/**
	 * 取得 单条购物车信息
	 * @param unknown $condition
	 * @param string $field
	 */
	public function getCartInfo($condition = array(), $field = '*') {
	   return $this->field($field)->where($condition)->find();    
	}

	/**
	 * 将商品添加到购物车中
	 *
	 * @param array	$data	商品数据信息
	 * @param string $save_type 保存类型，可选值 db,cookie,cache
	 * @param int $quantity 购物数量
	 */	
	public function addCart($data = array(), $save_type = '', $quantity = null) {
        $method = '_addCart'.ucfirst($save_type);
	    $insert = $this->$method($data,$quantity);
	    //更改购物车总商品数和总金额，传递数组参数只是给DB使用
	    $this->getCartNum($save_type,array('buyer_id'=>$data['buyer_id']));
	    return $insert;
	}

	/**
	 * 添加数据库购物车
	 *
	 * @param unknown_type $doctors_info
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	private function _addCartDb($doctors_info = array(),$quantity) {
	    //验证购物车商品是否已经存在
	    $condition = array();
	    $condition['doctors_id'] = $doctors_info['doctors_id'];
	    $condition['buyer_id'] = $doctors_info['buyer_id'];
	    if (isset($doctors_info['bl_id'])) {
	        $condition['bl_id'] = $doctors_info['bl_id'];   
	    } else {
	        $condition['bl_id'] = 0;
	    }
    	$check_cart	= $this->checkCart($condition);
    	if (!empty($check_cart)) return true;     

		$array    = array();
		$array['buyer_id']	= $doctors_info['buyer_id'];
		$array['clic_id']	= $doctors_info['clic_id'];
		$array['doctors_id']	= $doctors_info['doctors_id'];
		$array['doctors_name'] = $doctors_info['doctors_name'];
		$array['doctors_price'] = $doctors_info['doctors_price'];
		$array['doctors_num']   = $quantity;
		$array['doctors_image'] = $doctors_info['doctors_image'];
		$array['clic_name'] = $doctors_info['clic_name'];
		$array['bl_id'] = isset($doctors_info['bl_id']) ? $doctors_info['bl_id'] : 0;
		return $this->insert($array);
	}

	/**
	 * 添加到缓存购物车
	 *
	 * @param unknown_type $doctors_info
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	private function _addCartCache($doctors_info = array(), $quantity = null) {
        $obj_cache = Cache::getInstance(C('cache.type'));
        $cart_array = $obj_cache->get($_COOKIE['PHPSESSID'],'cart_');
        $cart_array = @unserialize($cart_array);
    	$cart_array = !is_array($cart_array) ? array() : $cart_array;
    	if (count($cart_array) >= 5) return true;
        if (in_array($doctors_info['doctors_id'],array_keys($cart_array))) return true;
		$cart_array[$doctors_info['doctors_id']] = array(
		  'clic_id' => $doctors_info['clic_id'],
		  'doctors_id' => $doctors_info['doctors_id'],
		  'doctors_name' => $doctors_info['doctors_name'],
		  'doctors_price' => $doctors_info['doctors_price'],
		  'doctors_image' => $doctors_info['doctors_image'],
		  'doctors_num' => $quantity
		);
        $obj_cache->set($_COOKIE['PHPSESSID'], serialize($cart_array), 'cart_', 24*3600);
        return true;
	}

	/**
	 * 添加到cookie购物车,最多保存5个商品
	 *
	 * @param unknown_type $doctors_info
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	private function _addCartCookie($doctors_info = array(), $quantity = null) {
    	//去除斜杠
    	$cart_str = get_magic_quotes_gpc() ? stripslashes(cookie('cart')) : cookie('cart');
    	$cart_str = base64_decode(decrypt($cart_str));
    	$cart_array = @unserialize($cart_str);
    	$cart_array = !is_array($cart_array) ? array() : $cart_array;
    	if (count($cart_array) >= 5) return false;

    	if (in_array($doctors_info['doctors_id'],array_keys($cart_array))) return true;
		$cart_array[$doctors_info['doctors_id']] = array(
		  'clic_id' => $doctors_info['clic_id'],
		  'doctors_id' => $doctors_info['doctors_id'],
		  'doctors_name' => $doctors_info['doctors_name'],
		  'doctors_price' => $doctors_info['doctors_price'],
		  'doctors_image' => $doctors_info['doctors_image'],
		  'doctors_num' => $quantity
		);
		setNcCookie('cart',encrypt(base64_encode(serialize($cart_array))),24*3600);
		return true;
	}

	/**
	 * 更新购物车 
	 *
	 * @param	array	$param 商品信息
	 */	
	public function editCart($data,$condition) {
		$result	= $this->where($condition)->update($data);
		if ($result) {
		    $this->getCartNum('db',array('buyer_id'=>$condition['buyer_id']));
		}
		return $result;
	}

	/**
	 * 购物车列表 
	 *
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */	
	public function listCart($type, $condition = array()) {
        if ($type == 'db') {
    		$cart_list = $this->where($condition)->select(array('cache'=>false));
        } elseif ($type == 'cache') {
            $obj_cache = Cache::getInstance(C('cache.type'));
            $cart_list = $obj_cache->get($_COOKIE['PHPSESSID'],'cart_');
            $cart_list = @unserialize($cart_list);
        } elseif ($type == 'cookie') {
        	//去除斜杠
        	$cart_str = get_magic_quotes_gpc() ? stripslashes(cookie('cart')) : cookie('cart');
        	$cart_str = base64_decode(decrypt($cart_str));
        	$cart_list = @unserialize($cart_str);
        }
        $cart_list = is_array($cart_list) ? $cart_list : array();
        //顺便设置购物车商品数和总金额
		$this->cart_doctors_num =  count($cart_list);
	    $cart_all_price = 0;
		if(is_array($cart_list)) {
			foreach ($cart_list as $val) {
				$cart_all_price	+= $val['doctors_price'] * $val['doctors_num'];
			}
		}
        $this->cart_all_price = ncPriceFormat($cart_all_price);
		return !is_array($cart_list) ? array() : $cart_list;
	}

	/**
	 * 删除购物车商品
	 * 
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */
	public function delCart($type, $condition = array()) {
	    if ($type == 'db') {
    		$result =  $this->where($condition)->delete();
	    } elseif ($type == 'cache') {
	        $obj_cache = Cache::getInstance(C('cache.type'));
	        $cart_array = $obj_cache->get($_COOKIE['PHPSESSID'],'cart_');
	        $cart_array = @unserialize($cart_array);
	        if (!is_array($cart_array)) return true;
	        if (key_exists($condition['doctors_id'],$cart_array)) {
	            unset($cart_array[$condition['doctors_id']]);
                $obj_cache = Cache::getInstance(C('cache.type'));
                $obj_cache->set($_COOKIE['PHPSESSID'], serialize($cart_array), 'cart_', 24*3600);
	            $result = true;
	        }
	    } elseif ($type == 'cookie') {        	
        	$cart_str = get_magic_quotes_gpc() ? stripslashes(cookie('cart')) : cookie('cart');
        	$cart_str = base64_decode(decrypt($cart_str));
        	$cart_array = @unserialize($cart_str);
            if (key_exists($condition['doctors_id'],(array)$cart_array)) {
                unset($cart_array[$condition['doctors_id']]);
            }
            setNcCookie('cart',encrypt(base64_encode(serialize($cart_array))),24*3600);
            $result = true;
	    }
	    //重新计算购物车商品数和总金额
		if ($result) {
		    $this->getCartNum($type,array('buyer_id'=>$condition['buyer_id']));
		}
		return $result;
	}

	/**
	 * 清空购物车
	 *
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */
	public function clearCart($type, $condition = array()) {
	    if ($type == 'cache') {
            $obj_cache = Cache::getInstance(C('cache.type'));
            $obj_cache->rm($_COOKIE['PHPSESSID'],'cart_');
	    } elseif ($type == 'cookie') {
            setNcCookie('cart','',-3600);
	    } else if ($type == 'db') {
	        //数据库暂无浅清空操作
	    }
	}

	/**
	 * 计算购物车总商品数和总金额 
	 * @param string $type 购物车信息保存类型 db,cookie,cache
	 * @param array $condition 只有登录后操作购物车表时才会用到该参数
	 */		
	public function getCartNum($type, $condition = array()) {
	    if ($type == 'db') {
    	    $cart_all_price = 0;
    		$cart_doctors	= $this->listCart('db',$condition);
    		$this->cart_doctors_num = count($cart_doctors);
    		if(!empty($cart_doctors) && is_array($cart_doctors)) {
    			foreach ($cart_doctors as $val) {
    				$cart_all_price	+= $val['doctors_price'] * $val['doctors_num'];
    			}
    		}
		  $this->cart_all_price = ncPriceFormat($cart_all_price);
	        
	    } elseif ($type == 'cache') {
            $obj_cache = Cache::getInstance(C('cache.type'));
            $cart_array = $obj_cache->get($_COOKIE['PHPSESSID'],'cart_');
            $cart_array = @unserialize($cart_array);
        	$cart_array = !is_array($cart_array) ? array() : $cart_array;
    		$this->cart_doctors_num = count($cart_array);
    		$cart_all_price = 0;
    		if (!empty($cart_array)){
    			foreach ($cart_array as $v){
    				$cart_all_price += floatval($v['doctors_price'])*intval($v['doctors_num']);
    			}
    		}
    		$this->cart_all_price = $cart_all_price;

	    } elseif ($type == 'cookie') {
        	$cart_str = get_magic_quotes_gpc() ? stripslashes(cookie('cart')) : cookie('cart');
        	$cart_str = base64_decode(decrypt($cart_str));
        	$cart_array = @unserialize($cart_str);
        	$cart_array = !is_array($cart_array) ? array() : $cart_array;
    		$this->cart_doctors_num = count($cart_array);
    		$cart_all_price = 0;
    		foreach ($cart_array as $v){
    			$cart_all_price += floatval($v['doctors_price'])*intval($v['doctors_num']);
    		}
    		$this->cart_all_price = $cart_all_price;
	    }
	    setNcCookie('cart_doctors_num',$this->cart_doctors_num,2*3600);
	    return $this->cart_doctors_num;
	}

	/**
	 * 直接购买/加入购物车时，判断商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
	 * @param unknown $buy_doctors_list
	 * @param number $quantity 购买数量
	 * @return array,如果该商品未正在进行限时折扣，返回空数组
	 */
	public function getXianshiInfo($buy_doctors_info, $quantity) {
	    if (!C('promotion_allow') || empty($buy_doctors_info) || !is_array($buy_doctors_info)) return $buy_doctors_info;
	    //定义返回数组
	    $xianshi_info = Model('p_xianshi_doctors')->getXianshidoctorsInfoBydoctorsID($buy_doctors_info['doctors_id']);
	    if (!empty($xianshi_info)) {
	        if ($quantity >= $xianshi_info['lower_limit']) {
	            $buy_doctors_info['doctors_price'] = $xianshi_info['xianshi_price'];
	            $buy_doctors_info['promotions_id'] = $xianshi_info['xianshi_id'];
	            $buy_doctors_info['ifxianshi'] = true;
	        }
	    }
	    return $buy_doctors_info;
	}

	/**
	 * 直接购买时，判断商品是不是正在团购中，如果是，按团购价格计算，购买数量若超过团购规定的上限，则按团购上限计算
	 * @param unknown $buy_doctors_info
	 * @return array,如果该商品未正在进行团购，返回空数组
	 */
	public function getGroupbuyInfo($buy_doctors_info = array()) {
	    if (!C('groupbuy_allow') || empty($buy_doctors_info) || !is_array($buy_doctors_info)) return $buy_doctors_info;
	    $groupbuy_info = Model('groupbuy')->getGroupbuyInfoBydoctorsCommonID($buy_doctors_info['doctors_commonid']);
	    if (!empty($groupbuy_info)) {
	        $buy_doctors_info['doctors_price'] = $groupbuy_info['groupbuy_price'];
	        if ($groupbuy_info['upper_limit'] && $buy_doctors_info['doctors_num'] > $groupbuy_info['upper_limit']) {
	            $buy_doctors_info['doctors_num'] = $groupbuy_info['upper_limit'];
	        }
	        $buy_doctors_info['promotions_id'] = $buy_doctors_info['groupbuy_id'] = $groupbuy_info['groupbuy_id'];
	        $buy_doctors_info['ifgroupbuy'] = true;
	    }
	    return $buy_doctors_info;
	}

	/**
	 * 直接购买时返回最新的在售商品信息（需要在售）
	 *
	 * @param int $doctors_id 所购商品ID
	 * @param int $quantity 购买数量
	 * @return array
	 */
	public function getdoctorsOnlineInfo($doctors_id,$quantity) {
	    //取目前在售商品
	    $doctors_info = Model('doctors')->getdoctorsOnlineInfo(array('doctors_id'=>$doctors_id));
	    if(empty($doctors_info)){
            return null;
	    }
	    $new_array = array();
	    $new_array['doctors_num'] = $quantity;
	    $new_array['doctors_id'] = $doctors_id;
	    $new_array['doctors_commonid'] = $doctors_info['doctors_commonid'];
	    $new_array['gc_id'] = $doctors_info['gc_id'];
	    $new_array['clic_id'] = $doctors_info['clic_id'];
	    $new_array['doctors_name'] = $doctors_info['doctors_name'];
	    $new_array['doctors_price'] = $doctors_info['doctors_price'];
	    $new_array['clic_name'] = $doctors_info['clic_name'];
	    $new_array['doctors_image'] = $doctors_info['doctors_image'];
	    $new_array['transport_id'] = $doctors_info['transport_id'];
	    $new_array['doctors_freight'] = $doctors_info['doctors_freight'];
	    $new_array['doctors_vat'] = $doctors_info['doctors_vat'];
	    $new_array['doctors_storage'] = $doctors_info['doctors_storage'];
	    $new_array['state'] = true;
	    $new_array['storage_state'] = intval($doctors_info['doctors_storage']) < intval($quantity) ? false : true;

	    //填充必要下标，方便后面统一使用购物车方法与模板
	    //cart_id=doctors_id,优惠套装目前只能进购物车,不能立即购买
	    $new_array['cart_id'] = $doctors_id;
	    $new_array['bl_id'] = 0;
	    return $new_array;
	}

	/**
	 * 取商品最新的在售信息
	 * @param unknown $cart_list
	 * @return array
	 */
	public function getOnlineCartList($cart_list) {
	    if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
	    //验证商品是否有效
	    $doctors_id_array = array();
	    foreach ($cart_list as $key => $cart_info) {
	        if (!intval($cart_info['bl_id'])) {
	            $doctors_id_array[] = $cart_info['doctors_id'];
	        }
	    }
	    $model_doctors = Model('doctors');
	    $doctors_online_list = $model_doctors->getdoctorsOnlineList(array('doctors_id'=>array(in,$doctors_id_array)));
	    $doctors_online_array = array();
	    foreach ($doctors_online_list as $doctors) {
	        $doctors_online_array[$doctors['doctors_id']] = $doctors;
	    }
	    foreach ((array)$cart_list as $key => $cart_info) {
	        if (intval($cart_info['bl_id'])) continue;
	        $cart_list[$key]['state'] = true;
	        $cart_list[$key]['storage_state'] = true;
	        if (in_array($cart_info['doctors_id'],array_keys($doctors_online_array))) {
                $doctors_online_info = $doctors_online_array[$cart_info['doctors_id']];
                $cart_list[$key]['doctors_name'] = $doctors_online_info['doctors_name'];
                $cart_list[$key]['gc_id'] = $doctors_online_info['gc_id'];
                $cart_list[$key]['doctors_image'] = $doctors_online_info['doctors_image'];
                $cart_list[$key]['doctors_price'] = $doctors_online_info['doctors_price'];
                $cart_list[$key]['transport_id'] = $doctors_online_info['transport_id'];
                $cart_list[$key]['doctors_freight'] = $doctors_online_info['doctors_freight'];
                $cart_list[$key]['doctors_vat'] = $doctors_online_info['doctors_vat'];
                $cart_list[$key]['doctors_storage'] = $doctors_online_info['doctors_storage'];
                if ($cart_info['doctors_num'] > $doctors_online_info['doctors_storage']) {
                    $cart_list[$key]['storage_state'] = false;
                }
	        } else {
	            //如果商品下架
	            $cart_list[$key]['state'] = false;
	            $cart_list[$key]['storage_state'] = false;
	        }
	    }
	    return $cart_list;
	}

	/**
	 * 批量判断购物车内的商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
	 * 并标识该商品为限时商品
	 * @param unknown $cart_list
	 * @return array
	 */
	public function getXianshiCartList($cart_list) {
	    if (!C('promotion_allow') || empty($cart_list) || !is_array($cart_list)) return $cart_list;
	    $model_xianshi = Model('p_xianshi_doctors');
	    $model_doctors = Model('doctors');
        foreach ($cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id'])) continue;
            $xianshi_info = $model_xianshi->getXianshidoctorsInfoBydoctorsID($cart_info['doctors_id']);
            if (!empty($xianshi_info)) {
                if ($cart_info['doctors_num'] >= $xianshi_info['lower_limit']) {
                    $cart_list[$key]['doctors_price'] = $xianshi_info['xianshi_price'];
                    $cart_list[$key]['promotions_id'] = $xianshi_info['xianshi_id'];
                    $cart_list[$key]['ifxianshi'] = true;
                }
                $cart_list[$key]['xianshi_info']['lower_limit'] = $xianshi_info['lower_limit'];
                $cart_list[$key]['xianshi_info']['xianshi_price'] = $xianshi_info['xianshi_price'];
                $cart_list[$key]['xianshi_info']['down_price'] = ncPriceFormat($cart_info['doctors_price'] - $xianshi_info['xianshi_price']);
            }
        }
	    return $cart_list;
	}

	/**
	 * 取得购买车内组合销售信息以及包含的商品及有效状态
	 * @param unknown $cart_list
	 * @return array
	 */
	public function getBundlingCartList($cart_list) {
	    if (!C('promotion_allow') || empty($cart_list) || !is_array($cart_list)) return $cart_list;
	    $model_bl = Model('p_bundling');
	    $model_doctors = Model('doctors');
        foreach ($cart_list as $key => $cart_info) {
            if (!intval($cart_info['bl_id'])) continue;
            $cart_list[$key]['state'] = true;
            $cart_list[$key]['storage_state'] = true;
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$cart_info['bl_id']));

            //标志优惠套装是否处于有效状态
            if (empty($bl_info) || !intval($bl_info['bl_state'])) {
                $cart_list[$key]['state'] = false;
            }

            //取得优惠套装商品列表
            $cart_list[$key]['bl_doctors_list'] = $model_bl->getBundlingdoctorsList(array('bl_id'=>$cart_info['bl_id']));

            //取最新在售商品信息
            $doctors_id_array = array();
            foreach ($cart_list[$key]['bl_doctors_list'] as $doctors_info) {
                $doctors_id_array[] = $doctors_info['doctors_id'];
            }
            $doctors_list = $model_doctors->getdoctorsOnlineList(array('doctors_id'=>array(in,$doctors_id_array)));
            $doctors_online_list = array();
            foreach ($doctors_list as $doctors_info) {
                $doctors_online_list[$doctors_info['doctors_id']] = $doctors_info;
            }
            unset($doctors_list);

            //使用最新的商品名称、图片,如果一旦有商品下架，则整个套装置置为无效状态
            foreach ($cart_list[$key]['bl_doctors_list'] as $k => $doctors_info) {
                if (array_key_exists($doctors_info['doctors_id'],$doctors_online_list)) {
                    $doctors_online_info = $doctors_online_list[$doctors_info['doctors_id']];
                    //如果库存不足，标识false
                    if ($cart_info['doctors_num'] > $doctors_online_info['doctors_storage']) {
                        $cart_list[$key]['storage_state'] = false;
                    }
                    $cart_list[$key]['bl_doctors_list'][$k]['doctors_name'] = $doctors_online_info['doctors_name'];
                    $cart_list[$key]['bl_doctors_list'][$k]['doctors_image'] = $doctors_online_info['doctors_image'];
                    $cart_list[$key]['bl_doctors_list'][$k]['doctors_storage'] = $doctors_online_info['doctors_storage'];
                } else {
                    //商品已经下架
                    $cart_list[$key]['state'] = false;
                    $cart_list[$key]['storage_state'] = false;
                }
            }
        }
	    return $cart_list;
	}

	/**
	 * 从购物车数组中得到商品列表
	 * @param unknown $cart_list
	 */
	public function getdoctorsList($cart_list) {
	    if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
	    $doctors_list = array();
	    $i = 0;
	    foreach ($cart_list as $key => $cart) {
	        if (!$cart['state'] || !$cart['storage_state']) continue;
	        //购买数量
	        $quantity = $cart['doctors_num'];
	        if (!intval($cart['bl_id'])) {
	            //如果是普通商品
	            $doctors_list[$i]['doctors_num'] = $quantity;
	            $doctors_list[$i]['doctors_id'] = $cart['doctors_id'];
	            $doctors_list[$i]['clic_id'] = $cart['clic_id'];
	            $doctors_list[$i]['gc_id'] = $cart['gc_id'];
	            $doctors_list[$i]['doctors_name'] = $cart['doctors_name'];
	            $doctors_list[$i]['doctors_price'] = $cart['doctors_price'];
	            $doctors_list[$i]['clic_name'] = $cart['clic_name'];
	            $doctors_list[$i]['doctors_image'] = $cart['doctors_image'];
	            $doctors_list[$i]['transport_id'] = $cart['transport_id'];
	            $doctors_list[$i]['doctors_freight'] = $cart['doctors_freight'];
	            $doctors_list[$i]['doctors_vat'] = $cart['doctors_vat'];
	            $doctors_list[$i]['bl_id'] = 0;
	            $i++;
	        } else {
	            //如果是优惠套装商品
	            foreach ($cart['bl_doctors_list'] as $bl_doctors) {
	                $doctors_list[$i]['doctors_num'] = $quantity;
	                $doctors_list[$i]['doctors_id'] = $bl_doctors['doctors_id'];
	                $doctors_list[$i]['clic_id'] = $cart['clic_id'];
	                $doctors_list[$i]['gc_id'] = $bl_doctors['gc_id'];
	                $doctors_list[$i]['doctors_name'] = $bl_doctors['doctors_name'];
	                $doctors_list[$i]['doctors_price'] = $bl_doctors['doctors_price'];
	                $doctors_list[$i]['clic_name'] = $bl_doctors['clic_name'];
	                $doctors_list[$i]['doctors_image'] = $bl_doctors['doctors_image'];
	                $doctors_list[$i]['transport_id'] = $bl_doctors['transport_id'];
	                $doctors_list[$i]['doctors_freight'] = $bl_doctors['doctors_freight'];
	                $doctors_list[$i]['doctors_vat'] = $bl_doctors['doctors_vat'];
	                $doctors_list[$i]['bl_id'] = $cart['bl_id'];
	                $i++;
	            }
	        }
	    }
	    return $doctors_list;
	}

	/**
	 * 将下单商品列表转换为以店铺ID为下标的数组
	 *
	 * @param array $cart_list
	 * @return array
	 */
	public function getclicCartList($cart_list) {
	    if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
	    $new_array = array();
	    foreach ($cart_list as $cart) {
	        $new_array[$cart['clic_id']][] = $cart;
	    }
	    return $new_array;
	}

	/**
	 * 商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
	 * @param unknown $clic_cart_list 以店铺ID分组的购物车商品信息
	 * @return array
	 */
	public function calcCartList($clic_cart_list) {
	    if (empty($clic_cart_list) || !is_array($clic_cart_list)) return array($clic_cart_list,array(),0);
	
	    //存放每个店铺的商品总金额
	    $clic_doctors_total = array();
	    //存放本次下单所有店铺商品总金额
	    $appointment_doctors_total = 0;
	
	    foreach ($clic_cart_list as $clic_id => $clic_cart) {
	        $tmp_amount = 0;
	        foreach ($clic_cart as $key => $cart_info) {
	            $clic_cart[$key]['doctors_total'] = ncPriceFormat($cart_info['doctors_price'] * $cart_info['doctors_num']);
	            $clic_cart[$key]['doctors_image_url'] = cthumb($clic_cart[$key]['doctors_image']);
	            $tmp_amount += $clic_cart[$key]['doctors_total'];
	        }
	        $clic_cart_list[$clic_id] = $clic_cart;
	        $clic_doctors_total[$clic_id] = ncPriceFormat($tmp_amount);
	    }
	    return array($clic_cart_list,$clic_doctors_total);
	}

	/**
	 * 取得店铺级活动 - 每个店铺可用的满即送活动规则列表
	 * @param unknown $clic_id_array 店铺ID数组
	 */
	public function getMansongRuleList($clic_id_array) {
	    if (!C('promotion_allow') || empty($clic_id_array) || !is_array($clic_id_array)) return array();
        $model_mansong = Model('p_mansong');
        $mansong_rule_list = array();
        foreach ($clic_id_array as $clic_id) {
            $clic_mansong_rule = $model_mansong->getMansongInfoByclicID($clic_id);
            if (!empty($clic_mansong_rule['rules']) && is_array($clic_mansong_rule['rules'])) {
                foreach ($clic_mansong_rule['rules'] as $rule_info) {
                    //如果减金额 或 有赠品(在售且有库存)
                    if (!empty($rule_info['discount']) || (!empty($rule_info['mansong_doctors_name']) && !empty($rule_info['doctors_storage']))) {
                        $mansong_rule_list[$clic_id][] = $this->_parseMansongRuleDesc($rule_info);
                    }
                }
            }
        }
        return $mansong_rule_list;
    }

	/**
	 * 取得店铺级优惠 - 跟据商品金额返回每个店铺当前符合的一条活动规则，如果有赠品，则自动追加到购买列表，价格为0
	 * @param unknown $clic_doctors_total 每个店铺的商品金额小计，以店铺ID为下标
	 * @return array($premiums_list,$mansong_rule_list) 分别为赠品列表[下标自增]，店铺满送规则列表[店铺ID为下标]
	 */
	public function getMansongRuleCartListByTotal($clic_doctors_total) {
	    if (!C('promotion_allow') || empty($clic_doctors_total) || !is_array($clic_doctors_total)) return array(array(),array());

        $model_mansong = Model('p_mansong');
        $model_doctors = Model('doctors');

        //定义赠品数组，下标为店铺ID
        $premiums_list = array();
        //定义满送活动数组，下标为店铺ID
        $mansong_rule_list = array();

        foreach ($clic_doctors_total as $clic_id => $doctors_total) {
            $rule_info = $model_mansong->getMansongRuleByclicID($clic_id,$doctors_total);
            if (is_array($rule_info) && !empty($rule_info)) {
                //即不减金额，也找不到促销商品时(已下架),此规则无效
                if (empty($rule_info['discount']) && empty($rule_info['mansong_doctors_name'])) {
                    continue;
                }
                $rule_info['desc'] = $this->_parseMansongRuleDesc($rule_info);
                $rule_info['discount'] = ncPriceFormat($rule_info['discount']);
                $mansong_rule_list[$clic_id] = $rule_info;
                //如果赠品在售,有库存,则追加到购买列表
                if (!empty($rule_info['mansong_doctors_name']) && !empty($rule_info['doctors_storage'])) {
                    $data = array();
                    $data['doctors_id'] = $rule_info['doctors_id'];
                    $data['doctors_name'] = $rule_info['mansong_doctors_name'];
                    $data['doctors_num'] = 1;
                    $data['doctors_price'] = 0.00;
                    $data['doctors_image'] = $rule_info['doctors_image'];
                    $data['doctors_image_url'] = cthumb($rule_info['doctors_image']);
                    $data['doctors_storage'] = $rule_info['doctors_storage'];
                    $premiums_list[$clic_id][] = $data;
                }
            }
        }
        return array($premiums_list,$mansong_rule_list);
	}

	/**
	 * 拼装单条满即送规则页面描述信息
	 * @param array $rule_info 满即送单条规则信息 
	 * @return string
	 */
	private function _parseMansongRuleDesc($rule_info) {
	    if (empty($rule_info) || !is_array($rule_info)) return;
	    $discount_desc = !empty($rule_info['discount']) ? '减'.$rule_info['discount'] : '';
	    $doctors_desc = (!empty($rule_info['mansong_doctors_name']) && !empty($rule_info['doctors_storage'])) ?
	    " 送<a href='".urlclinic('doctors','index',array('doctors_id'=>$rule_info['doctors_id']))."' title='{$rule_info['mansong_doctors_name']}' target='_blank'>[赠品]</a>" : '';
	     return sprintf('满%s%s%s',$rule_info['price'],$discount_desc,$doctors_desc);	    
	}

	/**
	 * 重新计算每个店铺最终商品总金额(最初计算金额减去各种优惠/加运费)
	 * @param array $clic_doctors_total 店铺商品总金额
	 * @param array $preferential_array 店铺优惠活动内容
	 * @param string $preferential_type 优惠类型，目前只有一个 'mansong'
	 * @return array 返回扣除优惠后的店铺商品总金额
	 */
	public function reCalcdoctorsTotal($clic_doctors_total, $preferential_array, $preferential_type) {
	   $deny = empty($clic_doctors_total) || !is_array($clic_doctors_total) || empty($preferential_array) || !is_array($preferential_array);
	   if ($deny) return $clic_doctors_total;

        switch ($preferential_type) {
            case 'mansong':
                if (!C('promotion_allow')) return $clic_doctors_total;
                foreach ($preferential_array as $clic_id => $rule_info) {
                    if (is_array($rule_info) && $rule_info['discount'] > 0) {
                        $clic_doctors_total[$clic_id] -= $rule_info['discount'];
                    }
                }
                break;

            case 'voucher':
                if (!C('voucher_allow')) return $clic_doctors_total;
                foreach ($preferential_array as $clic_id => $voucher_info) {
                    $clic_doctors_total[$clic_id] -= $voucher_info['voucher_price'];
                }
                break;

            case 'freight':
                foreach ($preferential_array as $clic_id => $freight_total) {
                    $clic_doctors_total[$clic_id] += $freight_total;
                }
                break;
            }
	    return $clic_doctors_total;
	}

	/**
	 * 取得哪些店铺有满免运费活动
	 * @param array $clic_id_array 店铺ID数组
	 * @return array
	 */
	public function getFreeFreightActiveList($clic_id_array) {
	    if (empty($clic_id_array) || !is_array($clic_id_array)) return array();

	    //定义返回数组
	    $clic_free_freight_active = array();

	    //如果商品金额未达到免运费设置下线，则需要计算运费
	    $condition = array('clic_id' => array('in',$clic_id_array));
	    $clic_list = Model('clic')->getclicOnlineList($condition,null,'','clic_id,clic_free_price');
	    foreach ($clic_list as $clic_info) {
	        $limit_price = floatval($clic_info['clic_free_price']);
	        if ($limit_price > 0) {
	            $clic_free_freight_active[$clic_info['clic_id']] = sprintf('满%s免运费',$limit_price);
	        }
	    }
	    return $clic_free_freight_active;
	}

	/**
	 * 验证传过来的代金券是否可用有效，如果无效，直接删除
	 * @param array $input_voucher_list 代金券列表
	 * @param array $clic_doctors_total (店铺ID=>商品总金额)
	 * @return array
	 */
	public function reParseVoucherList($input_voucher_list = array(), $clic_doctors_total = array(), $member_id) {
	    if (empty($input_voucher_list) || !is_array($input_voucher_list)) return array();
        $clic_voucher_list = $this->getclicAvailableVoucherList($clic_doctors_total, $member_id);
        foreach ($input_voucher_list as $clic_id => $voucher) {
            $tmp = $clic_voucher_list[$clic_id];
            if (is_array($tmp) && isset($tmp[$voucher['voucher_t_id']])) {
                $input_voucher_list[$clic_id]['voucher_id'] = $tmp[$voucher['voucher_t_id']]['voucher_id'];
                $input_voucher_list[$clic_id]['voucher_code'] = $tmp[$voucher['voucher_t_id']]['voucher_code'];
            } else {
                unset($input_voucher_list[$clic_id]);
            }
        }
        return $input_voucher_list;
	}

	/**
	 * 取得店铺可用的代金券
	 * @param array $clic_doctors_total array(店铺ID=>商品总金额)
	 * @return array
	 */
    public function getclicAvailableVoucherList($clic_doctors_total, $member_id) {
        if (!C('voucher_allow')) return $clic_doctors_total;
        $voucher_list = array();
        $model_voucher = Model('voucher');
        foreach ($clic_doctors_total as $clic_id => $doctors_total) {
            $condition = array();
            $condition['voucher_clic_id'] = $clic_id;
            $condition['voucher_owner_id'] = $member_id;
            $voucher_list[$clic_id] = $model_voucher->getCurrentAvailableVoucher($condition,$doctors_total);
        }
        return $voucher_list;
    }
}
