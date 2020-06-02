<?php
/**
 * cms调用接口
 *
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class apiControl extends CMSHomeControl{

	public function __construct() {
		parent::__construct();
    }

    /**
     * 商品列表
     */
	public function doctors_listOp() {
		$model_doctors = Model('doctors');
		$page	= new Page();
		$page->setEachNum(6);
		$page->setStyle('1');
		$condition = array();
        if($_GET['search_type'] == 'doctors_url') {
            $condition['doctors_id'] = intval($_GET['search_keyword']);
        } else {
            $condition['doctors_name'] = trim($_GET['search_keyword']);
        }
		$condition['doctors_show'] = '1';//上架:1是,0否
		$doctors_list = $model_doctors->getdoctors($condition,$page,'doctors.doctors_id,doctors.doctors_name,doctors.clic_id,doctors.doctors_image,doctors.doctors_clic_price','doctors');
		Tpl::output('show_page',$page->show());
		Tpl::output('doctors_list',$doctors_list);
		Tpl::showpage('api_doctors_list','null_layout');
	}

    /**
     * 文章列表
     */
	public function article_listOp() {
        //获取文章列表
		$condition = array();
        if($_GET['search_type'] == 'article_id') {
            $condition['article_id'] = intval($_GET['search_keyword']);
        } else {
            $condition['article_title'] = array('like','%'.trim($_GET['search_keyword']).'%');
        }
        $condition['article_state'] = self::ARTICLE_STATE_PUBLISHED;

        $model_article = Model('cms_article');
        $article_list = $model_article->getList($condition , 10, 'article_id desc');
        Tpl::output('show_page',$model_article->showpage(1));	
        Tpl::output('article_list', $article_list);
		Tpl::showpage('api_article_list','null_layout');
	}

    /**
     * 图片商品添加
     */
    public function doctors_info_by_urlOp() {
        $url = urldecode($_GET['url']);
        if(empty($url)) {
            self::return_json(Language::get('doctors_not_exist'), 'false');
        }
        $model_doctors_info = Model('doctors_info_by_url');
        $result = $model_doctors_info->get_doctors_info_by_url($url);
        if($result) {
            self::echo_json($result);
        } else {
            self::return_json(Language::get('doctors_not_exist'), 'false');
        }
    }

}
