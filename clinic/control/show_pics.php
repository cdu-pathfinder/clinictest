<?php
/**
 * 显示图片 
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

class show_picsControl extends BaseMemberControl {

	public function indexOp(){

        $type = trim($_GET['type']);
        if(empty($_GET['pics'])) {
            $this->goto_index();
        }
        $pics = explode('|',trim($_GET['pics']));
        $pic_path = '';
        switch ($type) {
            case 'inform':
                $pic_path = UPLOAD_SITE_URL.DS.'clinic/inform/'; 
                break;
            case 'complain':
                $pic_path = UPLOAD_SITE_URL.DS.'clinic/complain/';
                break;
            default:
                $this->goto_index();
                break;
        }

        Tpl::output('pic_path',$pic_path);
		Tpl::output('pics',$pics);
		//输出页面
		Tpl::showpage('show_pics','null_layout');
	}

    private function goto_index() {
	    @header("Location: index.php?act=member_snsindex");
		exit;
    }
}
