<?php
/**
 * 前台分类
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

class categoryControl extends BaseHomeControl {
	/**
	 * 分类列表
	 */
	public function indexOp(){
		Language::read('home_category_index');
		$lang	= Language::getLangContent();
		//导航
		$nav_link = array(
			'0'=>array('title'=>$lang['homepage'],'link'=>clinic_SITE_URL.'/index.php'),
			'1'=>array('title'=>$lang['category_index_doctors_class'])
		);
		Tpl::output('nav_link_list',$nav_link);
		
		Tpl::output('html_title',C('site_name').' - '.Language::get('category_index_doctors_class'));
		Tpl::showpage('category');
	}
}
