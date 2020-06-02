<?php
/**
 * 购物车操作
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class cartControl extends BaseBuyControl {
    const MANSONG_STATE_PUBLISHED = 2;

	public function __construct() {
		parent::__construct();
		Language::read('home_cart_index');

		$op = isset($_GET['op']) ? $_GET['op'] : $_POST['op'];

		//允许不登录就可以访问的op
		$op_arr = array('ajax_load','add','del');
		if (!in_array($op,$op_arr) && !$_SESSION['member_id'] ){
			$current_url = request_uri();
			redirect('index.php?act=login&ref_url='.urlencode($current_url));
		}
	}

	/**
	 * 购物车首页
	 */
	public function indexOp() {
        $model_cart	= Model('cart');

        //取出购物车信息
        $cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));

        //取商品最新的在售信息
        $cart_list = $model_cart->getOnlineCartList($cart_list);

        //得到限时折扣信息
        $cart_list = $model_cart->getXianshiCartList($cart_list);

        //得到优惠套装状态,并取得组合套装商品列表
        $cart_list = $model_cart->getBundlingCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $clic_cart_list = array();
        foreach ($cart_list as $cart) {
            $cart['doctors_total'] = ncPriceFormat($cart['doctors_price'] * $cart['doctors_num']);
            $clic_cart_list[$cart['clic_id']][] = $cart;
        }
        Tpl::output('clic_cart_list',$clic_cart_list);

        //店铺信息
        $clic_list = Model('clic')->getclicMemberIDList(array_keys($clic_cart_list));
        Tpl::output('clic_list',$clic_list);

        //取得店铺级活动 - 可用的满即送活动
	    $mansong_rule_list = $model_cart->getMansongRuleList(array_keys($clic_cart_list));
	    Tpl::output('mansong_rule_list',$mansong_rule_list);

	    //取得哪些店铺有满免运费活动
        $free_freight_list = $model_cart->getFreeFreightActiveList(array_keys($clic_cart_list));
        Tpl::output('free_freight_list',$free_freight_list);

        //标识 购买流程执行第几步
	    Tpl::output('buy_step','step1');
        Tpl::showpage(empty($cart_list) ? 'cart_empty' : 'cart');
	}

	/**
	 * 异步查询购物车
	 */
	public function ajax_loadOp() {
	    $model_cart	= Model('cart');
		if ($_SESSION['member_id']){
		    //登录后
			$cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
			$cart_array	= array();
			if(!empty($cart_list)){
			    $k = 0;
				foreach ($cart_list as $cart){
					$cart_array['list'][$k]['cart_id'] = $cart['cart_id'];
					$cart_array['list'][$k]['doctors_id'] = $cart['doctors_id'];
					$cart_array['list'][$k]['doctors_name'] = $cart['doctors_name'];
					$cart_array['list'][$k]['doctors_price'] 	= $cart['doctors_price'];
					$cart_array['list'][$k]['doctors_image']	= thumb($cart,60);
					$cart_array['list'][$k]['doctors_num'] = $cart['doctors_num'];
					$cart_array['list'][$k]['doctors_url'] = urlclinic('doctors', 'index', array('doctors_id' => $cart['doctors_id']));
					$k++;
				}
			}
		} else {
		    //登录前
		    $save_type = C('cache.type') == 'file' ? 'cookie' : 'cache';
			$cart_list = $model_cart->listCart($save_type);
			foreach ($cart_list as $key => $cart){
			    $value = array();
			    $value['cart_id'] = $key;
				$value['doctors_id'] = $cart['doctors_id'];
				$value['doctors_name'] = $cart['doctors_name'];
				$value['doctors_price'] = $cart['doctors_price'];
				$value['doctors_num'] = $cart['doctors_num'];
				$value['doctors_image'] = thumb($cart,60);
				$value['doctors_url'] = urlclinic('doctors', 'index', array('doctors_id' => $cart['doctors_id']));
				$cart_array['list'][] = $value;
			}
		}
		setNcCookie('cart_doctors_num',$model_cart->cart_doctors_num,2*3600);
		$cart_array['cart_all_price'] = ncPriceFormat($model_cart->cart_all_price);
		$cart_array['cart_doctors_num'] = $model_cart->cart_doctors_num;
		$cart_array = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($cart_array) : $cart_array;
        $json_data = json_encode($cart_array);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}

	/**
	 * 加入购物车，登录后存入购物车表
	 * 登录前，如果开启缓存，存入缓存，否则存入COOKIE，由于COOKIE长度限制，最多保存5个商品
	 * 未登录不能将优惠套装商品加入购物车，登录前保存的信息以doctors_id为下标
	 *
	 */
	public function addOp() {
	    $model_doctors = Model('doctors');
        if (is_numeric($_GET['doctors_id'])) {

            //商品加入购物车(默认)
            $doctors_id = intval($_GET['doctors_id']);
            $quantity = intval($_GET['quantity']);
            if ($doctors_id <= 0) return ;
            $doctors_info	= $model_doctors->getdoctorsOnlineInfo(array('doctors_id'=>$doctors_id));

            //判断是不是在限时折扣中，如果是返回折扣信息
            $xianshi_info = Model('cart')->getXianshiInfo($doctors_info,$quantity);
            if (!empty($xianshi_info)) {
                $doctors_info = $xianshi_info;
            }

            $this->_check_doctors($doctors_info,$_GET['quantity']);

        } elseif (is_numeric($_GET['bl_id'])) {

            //优惠套装加入购物车(单套)
            if (!$_SESSION['member_id']) {
                exit(json_encode(array('msg'=>'请先登录','UTF-8')));
            }
            $bl_id = intval($_GET['bl_id']);
            if ($bl_id <= 0) return ;
            $model_bl = Model('p_bundling');
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
            if (empty($bl_info) || $bl_info['bl_state'] == '0') {
                exit(json_encode(array('msg'=>'该优惠套装已不存在，建议您单独购买','UTF-8')));
            }

            //检查每个商品是否符合条件,并重新计算套装总价
            $bl_doctors_list = $model_bl->getBundlingdoctorsList(array('bl_id'=>$bl_id));
            $doctors_id_array = array();
            $bl_amount = 0;
            foreach ($bl_doctors_list as $doctors) {
            	$doctors_id_array[] = $doctors['doctors_id'];
            	$bl_amount += $doctors['bl_doctors_price'];
            }
            $model_doctors = Model('doctors');
            $doctors_list = $model_doctors->getdoctorsOnlineList(array('doctors_id'=>array(in,$doctors_id_array)));
            foreach ($doctors_list as $doctors) {
                $this->_check_doctors($doctors,1);
            }

            //优惠套装作为一条记录插入购物车，图片取套装内的第一个商品图
            $doctors_info    = array();
            $doctors_info['clic_id']	= $bl_info['clic_id'];
            $doctors_info['doctors_id']	= $doctors_list[0]['doctors_id'];
            $doctors_info['doctors_name'] = $bl_info['bl_name'];
            $doctors_info['doctors_price'] = $bl_amount;
            $doctors_info['doctors_num']   = 1;
            $doctors_info['doctors_image'] = $doctors_list[0]['doctors_image'];
            $doctors_info['clic_name'] = $bl_info['clic_name'];
            $doctors_info['bl_id'] = $bl_id;
            $quantity = 1;
        }

        //已登录状态，存入数据库,未登录时，优先存入缓存，否则存入COOKIE
        if($_SESSION['member_id']) {
            $save_type = 'db';
            $doctors_info['buyer_id'] = $_SESSION['member_id'];
        } else {
            $save_type = C('cache.type') != 'file' ? 'cache' : 'cookie';
        }
        $model_cart	= Model('cart');
        $insert = $model_cart->addCart($doctors_info,$save_type,$quantity);
        if ($insert) {
            //购物车商品种数记入cookie
            setNcCookie('cart_doctors_num',$model_cart->cart_doctors_num,2*3600);
            $data = array('state'=>'true', 'num' => $model_cart->cart_doctors_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
        } else {
            $data = array('state'=>'false');
        }
	    exit(json_encode($data));
	}

	/**
	 * 检查商品是否符合加入购物车条件
	 * @param unknown $doctors
	 * @param number $quantity
	 */
	private function _check_doctors($doctors_info, $quantity) {
		if(empty($quantity)) {
			exit(json_encode(array('msg'=>Language::get('wrong_argument','UTF-8'))));
		}
		if(empty($doctors_info)) {
			exit(json_encode(array('msg'=>Language::get('cart_add_doctors_not_exists','UTF-8'))));
		}
		if ($doctors_info['clic_id'] == $_SESSION['clic_id']) {
			exit(json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8'))));
		}
		if(intval($doctors_info['doctors_storage']) < 1) {
			exit(json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8'))));
		}
		if(intval($doctors_info['doctors_storage']) < $quantity) {
			exit(json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8'))));
		}
	}

	/**
	 * 购物车更新商品数量
	 */
	public function updateOp() {
		$cart_id	= intval(abs($_GET['cart_id']));
		$quantity	= intval(abs($_GET['quantity']));

		if(empty($cart_id) || empty($quantity)) {
			exit(json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8'))));
		}

		$model_cart = Model('cart');
		$model_doctors= Model('doctors');

		//存放返回信息
		$return = array();

		$cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		if ($cart_info['bl_id'] == '0') {

		    //普通商品
		    $doctors_id = intval($cart_info['doctors_id']);
		    $doctors_info	= $model_doctors->getdoctorsOnlineInfo(array('doctors_id'=>$doctors_id));
		    if(empty($doctors_info)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '商品已被下架';
		        $return['subtotal'] = 0;
		        $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		    //如果是是在限时折扣中,json返回价格，重新计算
		    $xianshi_info = Model('cart')->getXianshiInfo($doctors_info,$quantity);
		    if (!empty($xianshi_info)) {
		        $cart_info['doctors_price'] = $xianshi_info['doctors_price'];
		    }

		    if(intval($doctors_info['doctors_storage']) < $quantity) {
		        $return['state'] = 'shortage';
		        $return['msg'] = '库存不足';
		        $return['doctors_num'] = $doctors_info['doctors_storage'];
		        $return['doctors_price'] = $cart_info['doctors_price'];
		        $return['subtotal'] = $cart_info['doctors_price'] * $quantity;
		        $model_cart->editCart(array('doctors_num'=>$doctors_info['doctors_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		} else {

		    //优惠套装商品
		    $model_bl = Model('p_bundling');
		    $bl_doctors_list = $model_bl->getBundlingdoctorsList(array('bl_id'=>$cart_info['bl_id']));
		    $doctors_id_array = array();
		    foreach ($bl_doctors_list as $doctors) {
		        $doctors_id_array[] = $doctors['doctors_id'];
		    }
		    $cart_list[$key]['bl_doctors_list'] = $model_doctors->getdoctorsOnlineList(array('doctors_id'=>array(in,$doctors_id_array)));

		    //如果其中有商品下架，删除
		    if (count($cart_list[$key]['bl_doctors_list']) != count($doctors_id_array)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '该优惠套装已经无效，建议您购买单个商品';
		        $return['subtotal'] = 0;
		        $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($cart_list[$key]['bl_doctors_list'] as $doctors_info) {
		        if ($quantity > $doctors_info['doctors_storage']) {
		            $return['state'] = 'shortage';
		            $return['msg'] = '该优惠套装部分商品库存不足，<br/>建议您降低购买数量或购买库存足够的单个商品';
		            $return['doctors_num'] = $doctors_info['doctors_storage'];
		            $return['doctors_price'] = $cart_info['doctors_price'];
		            $return['subtotal'] = $cart_info['doctors_price'] * $quantity;
		            $model_cart->editCart(array('doctors_num'=>$doctors_info['doctors_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		            exit(json_encode($return));
		            break;
		        }
		    }
		}

		$data = array();
        $data['doctors_num'] = $quantity;
        $data['doctors_price'] = $cart_info['doctors_price'];
        $update = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		if ($update) {
		    $return = array();
			$return['state'] = 'true';
			$return['subtotal'] = $cart_info['doctors_price'] * $quantity;
			$return['doctors_price'] = $cart_info['doctors_price'];
			$return['doctors_num'] = $quantity;
		} else {
			$return = array('msg'=>Language::get('cart_update_buy_fail','UTF-8'));
		}
		exit(json_encode($return));
	}

	/**
	 * 购物车删除单个商品，未登录前使用doctors_id，此时cart_id可能为0，登录后使用cart_id
	 */
	public function delOp() {
		$cart_id = intval($_GET['cart_id']);
		$doctors_id = intval($_GET['doctors_id']);
		if($cart_id < 0 || $doctors_id < 0) return ;
		$model_cart	= Model('cart');
		$data = array();
		if ($_SESSION['member_id']) {
		    //登录状态下删除数据库内容
			$delete	= $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_doctors_num;
			    $data['amount'] = $model_cart->cart_all_price;
			} else {
				$data['msg'] = Language::get('cart_drop_del_fail','UTF-8');
			}
		} else {
			//未登录时删除cookie或缓存的购物车信息
			$save_type = C('cache.type') == 'file' ? 'cookie' : 'cache';
			$delete	= $model_cart->delCart($save_type,array('doctors_id'=>$doctors_id));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_doctors_num;
			    $data['amount'] = $model_cart->cart_all_price;
			}
		}
		setNcCookie('cart_doctors_num',$model_cart->cart_doctors_num,2*3600);
		$json_data = json_encode($data);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}
}
