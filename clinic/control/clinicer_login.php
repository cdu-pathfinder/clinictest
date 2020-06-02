<?php
/**
 * 店铺卖家登录
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

class clinicer_loginControl extends BaseclinicerControl {

	public function __construct() {
		parent::__construct();
        if (!empty($_SESSION['clinicer_id'])) {
            @header('location: index.php?act=clinicer_center');die;
        }
	}

    public function indexOp() {
        $this->show_loginOp();
    }

    public function show_loginOp() {
        Tpl::output('nchash', getNchash());
		Tpl::setLayout('null_layout');
        Tpl::showpage('login');
    }

    public function loginOp() {
        if (!Security::checkToken()) {
            showMessage('登录错误', '', '', 'error');
        }

        if (!checkSeccode($_POST['nchash'], $_POST['captcha'])) {
            showMessage('验证码错误', '', '', 'error');
        }

        $model_clinicer = Model('clinicer');
        $clinicer_info = $model_clinicer->getclinicerInfo(array('clinicer_name' => $_POST['clinicer_name']));
        if($clinicer_info) {

            $model_member = Model('member');
            $member_info = $model_member->infoMember(
                array(
                    'member_id' => $clinicer_info['member_id'],
                    'member_passwd' => md5($_POST['password'])
                )
            );
            if($member_info) {
                // 更新卖家登陆时间
                $model_clinicer->editclinicer(array('last_login_time' => TIMESTAMP), array('clinicer_id' => $clinicer_info['clinicer_id']));

                $model_clinicer_group = Model('clinicer_group');
                $clinicer_group_info = $model_clinicer_group->getclinicerGroupInfo(array('group_id' => $clinicer_info['clinicer_group_id']));

                $model_clic = Model('clic');
                $clic_info = $model_clic->getclicInfoByID($clinicer_info['clic_id']);

                $_SESSION['is_login'] = '1';
                $_SESSION['member_id'] = $member_info['member_id'];
                $_SESSION['member_name'] = $member_info['member_name'];
    			$_SESSION['member_email'] = $member_info['member_email'];
    			$_SESSION['is_buy']	= $member_info['is_buy'];
    			$_SESSION['avatar']	= $member_info['member_avatar'];

                $_SESSION['grade_id'] = $clic_info['grade_id'];
                $_SESSION['clinicer_id'] = $clinicer_info['clinicer_id'];
                $_SESSION['clinicer_name'] = $clinicer_info['clinicer_name'];
                $_SESSION['clinicer_is_admin'] = intval($clinicer_info['is_admin']);
                $_SESSION['clic_id'] = intval($clinicer_info['clic_id']);
                $_SESSION['clic_name']	= $clic_info['clic_name'];
                $_SESSION['clinicer_limits'] = explode(',', $clinicer_group_info['limits']);
                if($clinicer_info['is_admin']) {
                    $_SESSION['clinicer_group_name'] = 'administer';
                } else {
                    $_SESSION['clinicer_group_name'] = $clinicer_group_info['group_name'];
                }
                if(!$clinicer_info['last_login_time']) {
                    $clinicer_info['last_login_time'] = TIMESTAMP;
                }
                $_SESSION['clinicer_last_login_time'] = date('Y-m-d H:i', $clinicer_info['last_login_time']);
                $clinicer_menu = $this->getclinicerMenuList($clinicer_info['is_admin'], explode(',', $clinicer_group_info['limits']));
                $_SESSION['clinicer_menu'] = $clinicer_menu['clinicer_menu'];
                $_SESSION['clinicer_function_list'] = $clinicer_menu['clinicer_function_list'];
                if(!empty($clinicer_info['clinicer_quicklink'])) {
                    $quicklink_array = explode(',', $clinicer_info['clinicer_quicklink']);
                    foreach ($quicklink_array as $value) {
                        $_SESSION['clinicer_quicklink'][$value] = $value ;
                    }
                }
                $this->recordclinicerLog('login success');
                showMessage('login success', 'index.php?act=clinicer_center');
            } else {
                showMessage('Username and password error', '', '', 'error');
            }
        } else {
            showMessage('Username and password error', '', '', 'error');
        }
    }
}
