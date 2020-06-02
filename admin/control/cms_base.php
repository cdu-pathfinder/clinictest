<?php
/**
 * cms公用
 *
 * 
 *
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class cms_baseControl extends SystemControl{

	public function __construct(){
		parent::__construct();
		Language::read('cms');
	}

    /**
     * 获取文章列表
     */
    public function get_article_listOp() {
        //获取文章列表
		$condition = array();
        if($_GET['search_type'] == 'article_id') {
            $condition['article_id'] = intval($_GET['search_keyword']);
        } else {
            $condition['article_title'] = array('like','%'.trim($_GET['search_keyword']).'%');
        }
        $condition['article_state'] = 3;

        $model_article = Model('cms_article');
        $article_list = $model_article->getListWithClassName($condition, 5, 'article_id desc');
        Tpl::output('show_page',$model_article->showpage(1));	
        Tpl::output('article_list', $article_list);
		Tpl::showpage('cms_widget_article_list','null_layout');
    }

    /**
     * 获取店铺列表
     */
    public function get_clic_listOp() {
        //获取店铺列表
		$condition = array();
        $condition['clic_name'] = array('like', $_GET['search_keyword']);

        $model_clic = Model('clic');
        $clic_list = $model_clic->getclicOnlineList($condition, 5);
        Tpl::output('show_page',$model_clic->showpage());	
        Tpl::output('clic_list', $clic_list);
		Tpl::showpage('cms_widget_clic_list', 'null_layout');
    }

    /**
     * 获取会员列表
     */
    public function get_member_listOp() {
        //获取店铺列表
		$condition = array();
        $condition['member_name'] = array('like', '%' . trim($_GET['search_keyword']) . '%');
        $condition['member_state'] = 1;
        
        $model_member = Model('member');
        $member_list = $model_member->getMemberList($condition, '*', 5);
        Tpl::output('show_page',$model_member->showpage());
        Tpl::output('member_list', $member_list);
		Tpl::showpage('cms_widget_member_list', 'null_layout');
    }

    /**
     * 获取品牌列表
     */
    public function get_brand_listOp() {
		$model_brand = Model('brand');
		$condition = array();
		$condition['brand_apply']	= '1';
		$condition['appointment']	= ' brand_sort desc ';
		$page	= new Page();
		$page->setEachNum(6);
		$page->setStyle('admin');
		$brand_list = $model_brand->getBrandList($condition, $page);
		Tpl::output('show_page',$page->show());
		Tpl::output('brand_list',$brand_list);
		Tpl::showpage('cms_widget_brand_list','null_layout');
    }

    /**
     * 商品分类列表
     */
    public function get_doctors_class_list_jsonOp() {
        $model_class = Model('doctors_class');
        $doctors_class_list = $model_class->getTreeClassList(2);//商品分类父类列表，只取到第二级
        $result = array();
        if (is_array($doctors_class_list) && !empty($doctors_class_list)){
            $i = 0;
            foreach ($doctors_class_list as $key => $value){
                $result[$i]['gc_name'] = str_repeat("&nbsp;",$value['deep']*2).$value['gc_name'];
                $result[$i]['gc_id'] = $value['gc_id']; 
                $i++;
            }
        }
        echo json_encode($result);
    }

    /**
     * 商品分类详细列表
     */
	public function get_doctors_class_detailOp(){
		$model_class = Model('doctors_class');
		$gc_parent_id = intval($_GET["class_id"]);
		$gc_parent = $model_class->getOnedoctorsClass($gc_parent_id);
		$doctors_class = $model_class->getClassList(array('gc_parent_id'=>$gc_parent_id));
		Tpl::output('gc_parent',$gc_parent);
		Tpl::output('doctors_class',$doctors_class);
		Tpl::showpage('cms_widget_doctors_class_list','null_layout');
	}

    /**
     * 图片商品添加
     */
    public function doctors_info_by_urlOp() {
        $url = urldecode($_GET['url']);
        if(empty($url)) {
            self::return_json(Language::get('param_error'),'false');
        }
        $model_doctors_info = Model('doctors_info_by_url');
        $result = $model_doctors_info->get_doctors_info_by_url($url);
        if($result) {
            self::echo_json($result);
        } else {
            self::return_json(Language::get('param_error'),'false');
        }
    }

	private function return_json($message,$result='true') {		
        $data = array();
        $data['result'] = $result;
        $data['message'] = $message;
        self::echo_json($data);
    }

    private function echo_json($data) {
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($data);die;
    }

}
