<?php
/**
 * 店铺管理界面
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class clicControl extends SystemControl{
	const EXPORT_SIZE = 5000;
	public function __construct(){
		parent::__construct();
		Language::read('clic,clic_grade');
	}

	/**
	 * 店铺
	 */
	public function clicOp(){
		$lang = Language::getLangContent();

		$model_clic = Model('clic');

		if(trim($_GET['owner_and_name']) != ''){
			$condition['member_name']	= array('like', '%'.trim($_GET['owner_and_name']).'%');
			Tpl::output('owner_and_name',trim($_GET['owner_and_name']));
		}
		if(trim($_GET['clic_name']) != ''){
			$condition['clic_name']	= array('like', '%'.trim($_GET['clic_name']).'%');
			Tpl::output('clic_name',trim($_GET['clic_name']));
		}
		if(intval($_GET['grade_id']) > 0){
			$condition['grade_id']		= intval($_GET['grade_id']);
			Tpl::output('grade_id',intval($_GET['grade_id']));
		}

        switch ($_GET['clic_type']) {
            case 'close':
                $condition['clic_state'] = 0;
                break;
            case 'open':
                $condition['clic_state'] = 1;
                break;
            case 'expired':
                $condition['clic_end_time'] = array('between', array(1, TIMESTAMP));
                $condition['clic_state'] = 1;
                break;
            case 'expire':
                $condition['clic_end_time'] = array('between', array(TIMESTAMP, TIMESTAMP + 864000));
                $condition['clic_state'] = 1;
                break;
        }
		//店铺列表
		$clic_list = $model_clic->getclicList($condition, 10);

		//店铺等级
		$model_grade = Model('clic_grade');
		$grade_list = $model_grade->getGradeList($condition);
		if (!empty($grade_list)){
			$search_grade_list = array();
			foreach ($grade_list as $k => $v){
				$search_grade_list[$v['sg_id']] = $v['sg_name'];
			}
		}
        Tpl::output('search_grade_list', $search_grade_list);

		Tpl::output('grade_list',$grade_list);
		Tpl::output('clic_list',$clic_list);
        Tpl::output('clic_type', $this->_get_clic_type_array());
		Tpl::output('page',$model_clic->showpage('2'));
		Tpl::showpage('clic.index');
	}

    private function _get_clic_type_array() {
        return array(
            'open' => 'open',
            'close' => 'close',
            'expire' => 'expire',
            'expired' => 'expired'
        );
    }
	/**
	 * 店铺编辑
	 */
	public function clic_editOp(){
		$lang = Language::getLangContent();

		$model_clic = Model('clic');
		//保存
		if (chksubmit()){
			//取店铺等级的审核
			$model_grade = Model('clic_grade');
			$grade_array = $model_grade->getOneGrade(intval($_POST['grade_id']));
			if (empty($grade_array)){
				showMessage($lang['please_input_clic_level']);
			}
			//结束时间
			$time	= '';
			if(trim($_POST['end_time']) != ''){
				$time = strtotime($_POST['end_time']);
			}
			$update_array = array();
			$update_array['clic_name'] = trim($_POST['clic_name']);
			$update_array['sc_id'] = intval($_POST['sc_id']);
			$update_array['grade_id'] = intval($_POST['grade_id']);
			$update_array['clic_end_time'] = $time;
			$update_array['clic_state'] = intval($_POST['clic_state']);
			if ($_POST['clic_state'] == '0'){
				//根据店铺状态修改该店铺所有商品状态
				$model_doctors = Model('doctors');
				$model_doctors->editProducesOffline(array('clic_id' => $update_array['clic_id']));
				$update_array['clic_close_info'] = trim($_POST['clic_close_info']);
				$update_array['clic_recommend'] = 0;
			}else {
				//店铺开启后商品不在自动上架，需要手动操作
				$update_array['clic_close_info'] = '';
				$update_array['clic_recommend'] = intval($_POST['clic_recommend']);
			}
            $result = $model_clic->editclic($update_array, array('clic_id' => $_POST['clic_id']));
			if ($result){
				$url = array(
				array(
				'url'=>'index.php?act=clic&op=clic',
				'msg'=>$lang['back_clic_list'],
				),
				array(
				'url'=>'index.php?act=clic&op=clic_edit&clic_id='.intval($_POST['clic_id']),
				'msg'=>$lang['countinue_add_clic'],
				),
				);
				$this->log(L('nc_edit,clic').'['.$_POST['clic_name'].']',1);
				showMessage($lang['nc_common_save_succ'],$url);
			}else {
				$this->log(L('nc_edit,clic').'['.$_POST['clic_name'].']',1);
				showMessage($lang['nc_common_save_fail']);
			}
		}
		//取店铺信息
		$clic_array = $model_clic->getclicInfoByID($_GET['clic_id']);
		if (empty($clic_array)){
			showMessage($lang['clic_no_exist']);
		}
		//整理店铺内容
		$clic_array['clic_end_time'] = $clic_array['clic_end_time']?date('Y-m-d',$clic_array['clic_end_time']):'';
		//店铺分类
		$model_clic_class = Model('clic_class');
		$parent_list = $model_clic_class->getTreeClassList(2);
		if (is_array($parent_list)){
			foreach ($parent_list as $k => $v){
				$parent_list[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
			}
		}
		//店铺等级
		$model_grade = Model('clic_grade');
		$grade_list = $model_grade->getGradeList();
		Tpl::output('grade_list',$grade_list);
		Tpl::output('class_list',$parent_list);
		Tpl::output('clic_array',$clic_array);
		Tpl::showpage('clic.edit');
	}

    /**
     * 店铺经营类目管理
     */
    public function clic_bind_classOp() {
        $clic_id = intval($_GET['clic_id']);

        $model_clic = Model('clic');
        $model_clic_bind_class = Model('clic_bind_class');
        $model_doctors_class = Model('doctors_class');

        $gc_list = $model_doctors_class->getClassList(array('gc_parent_id'=>'0'));
        Tpl::output('gc_list',$gc_list);

        $clic_info = $model_clic->getclicInfoByID($clic_id);
        if(empty($clic_info)) {
            showMessage(L('param_error'),'','','error');
        }
        Tpl::output('clic_info', $clic_info);

        $clic_bind_class_list = $model_clic_bind_class->getclicBindClassList(array('clic_id'=>$clic_id), null);
        $doctors_class = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        for($i = 0, $j = count($clic_bind_class_list); $i < $j; $i++) {
            $clic_bind_class_list[$i]['class_1_name'] = $doctors_class[$clic_bind_class_list[$i]['class_1']]['gc_name'];
            $clic_bind_class_list[$i]['class_2_name'] = $doctors_class[$clic_bind_class_list[$i]['class_2']]['gc_name'];
            $clic_bind_class_list[$i]['class_3_name'] = $doctors_class[$clic_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('clic_bind_class_list', $clic_bind_class_list);

        Tpl::showpage('clic_bind_class');
    }

    /**
     * 添加经营类目
     */
    public function clic_bind_class_addOp() {
        $clic_id = intval($_POST['clic_id']);
        $commis_rate = intval($_POST['commis_rate']);
        if($commis_rate < 0 || $commis_rate > 100) {
            showMessage(L('param_error'), '');
        }
        list($class_1, $class_2, $class_3) = explode(',', $_POST['doctors_class']);

        $model_clic_bind_class = Model('clic_bind_class');

        $param = array();
        $param['clic_id'] = $clic_id;
        $param['class_1'] = $class_1;
        if(!empty($class_2)) {
            $param['class_2'] = $class_2;
        }
        if(!empty($class_3)) {
            $param['class_3'] = $class_3;
        }

        // 检查类目是否已经存在
        $clic_bind_class_info = $model_clic_bind_class->getclicBindClassInfo($param);
        if(!empty($clic_bind_class_info)) {
            showMessage('该类目已经存在','','','error');
        }

        $param['commis_rate'] = $commis_rate;
        $result = $model_clic_bind_class->addclicBindClass($param);

        if($result) {
            $this->log('删除店铺经营类目，类目编号:'.$result.',clinic ID:'.$clic_id);
            showMessage(L('nc_common_save_succ'), '');
        } else {
            showMessage(L('nc_common_save_fail'), '');
        }
    }

    /**
     * 删除经营类目
     */
    public function clic_bind_class_delOp() {
        $bid = intval($_POST['bid']);

        $data = array();
        $data['result'] = true;

        $model_clic_bind_class = Model('clic_bind_class');
        $model_doctors = Model('doctors');

        $clic_bind_class_info = $model_clic_bind_class->getclicBindClassInfo(array('bid' => $bid));
        if(empty($clic_bind_class_info)) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
            echo json_encode($data);die;
        }

        // 商品下架
        $condition = array();
        $condition['clic_id'] = $clic_bind_class_info['clic_id'];
        $gc_id = $clic_bind_class_info['class_1'].','.$clic_bind_class_info['class_2'].','.$clic_bind_class_info['class_3'];
        $update = array();
        $update['doctors_stateremark'] = '管理员删除经营类目';
        $condition['gc_id'] = array('in', rtrim($gc_id, ','));
        $model_doctors->editProducesLockUp($update, $condition);

        $result = $model_clic_bind_class->delclicBindClass(array('bid'=>$bid));

        if(!$result) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
        }
        $this->log('删除店铺经营类目，类目编号:'.$bid.',Clinic ID:'.$clic_bind_class_info['clic_id']);
        echo json_encode($data);die;
    }

    public function clic_bind_class_updateOp() {
        $bid = intval($_GET['id']);
        if($bid <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_commis_rate = intval($_GET['value']);
        if ($new_commis_rate < 0 || $new_commis_rate >= 100) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        } else {
            $update = array('commis_rate' => $new_commis_rate);
            $condition = array('bid' => $bid);
            $model_clic_bind_class = Model('clic_bind_class');
            $result = $model_clic_bind_class->editclicBindClass($update, $condition);
            if($result) {
                $this->log('更新店铺经营类目，类目编号:'.$bid);
                echo json_encode(array('result'=>TRUE));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>L('nc_common_op_fail')));
                die;
            }
        }
    }


	/**
	 * 店铺 待审核列表
	 */
	public function clic_joininOp(){
		//店铺列表
		if(!empty($_GET['onwer_and_name'])) {
			$condition['member_name'] = array('like','%'.$_GET['owner_and_name'].'%');
		}
		if(!empty($_GET['clic_name'])) {
			$condition['clic_name'] = array('like','%'.$_GET['clic_name'].'%');
		}
		if(!empty($_GET['grade_id']) && intval($_GET['grade_id']) > 0) {
			$condition['sg_id'] = $_GET['grade_id'];
		}
		if(!empty($_GET['joinin_state']) && intval($_GET['joinin_state']) > 0) {
            $condition['joinin_state'] = $_GET['joinin_state'] ;
        } else {
            $condition['joinin_state'] = array('gt',0);
        }
		$model_clic_joinin = Model('clic_joinin');
		$clic_list = $model_clic_joinin->getList($condition, 10, 'joinin_state asc');
		Tpl::output('clic_list', $clic_list);
        Tpl::output('joinin_state_array', $this->get_clic_joinin_state());

		//店铺等级
		$model_grade = Model('clic_grade');
		$grade_list = $model_grade->getGradeList();
		Tpl::output('grade_list', $grade_list);

		Tpl::output('page',$model_clic_joinin->showpage('2'));
		Tpl::showpage('clic_joinin');
	}

    private function get_clic_joinin_state() {
        $joinin_state_array = array(
            clic_JOIN_STATE_NEW => 'new',
            clic_JOIN_STATE_PAY => 'paied',
            clic_JOIN_STATE_VERIFY_SUCCESS => 'to pay',
            clic_JOIN_STATE_VERIFY_FAIL => 'review failed',
            clic_JOIN_STATE_PAY_FAIL => 'Payment review failed',
            clic_JOIN_STATE_FINAL => 'open successful',
        );
        return $joinin_state_array;
    }

	/**
	 * 审核详细页
	 */
	public function clic_joinin_detailOp(){
		$model_clic_joinin = Model('clic_joinin');
        $joinin_detail = $model_clic_joinin->getOne(array('member_id'=>$_GET['member_id']));
        $joinin_detail_title = 'view';
        if(in_array(intval($joinin_detail['joinin_state']), array(clic_JOIN_STATE_NEW, clic_JOIN_STATE_PAY))) {
            $joinin_detail_title = 'review';
        }
        Tpl::output('joinin_detail_title', $joinin_detail_title);
		Tpl::output('joinin_detail', $joinin_detail);
		Tpl::showpage('clic_joinin.detail');
	}

	/**
	 * 审核
	 */
	public function clic_joinin_verifyOp() {
        $model_clic_joinin = Model('clic_joinin');
        $joinin_detail = $model_clic_joinin->getOne(array('member_id'=>$_POST['member_id']));

        switch (intval($joinin_detail['joinin_state'])) {
            case clic_JOIN_STATE_NEW:
                $this->clic_joinin_verify_pass($joinin_detail);
                break;
            case clic_JOIN_STATE_PAY:
                $this->clic_joinin_verify_open($joinin_detail);
                break;
            default:
                showMessage('Parameter error','');
                break;
        }
	}

    private function clic_joinin_verify_pass($joinin_detail) {
        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? clic_JOIN_STATE_VERIFY_SUCCESS : clic_JOIN_STATE_VERIFY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $param['clic_class_commis_rates'] = implode(',', $_POST['commis_rate']);
        $model_clic_joinin = Model('clic_joinin');
        $model_clic_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        showMessage('The application for clinic entry has been approved','index.php?act=clic&op=clic_joinin');
    }

    private function clic_joinin_verify_open($joinin_detail) {
        $model_clic_joinin = Model('clic_joinin');
        $model_clic	= Model('clic');
        $model_clinicer = Model('clinicer');

        //验证卖家用户名是否已经存在
        if($model_clinicer->isclinicerExist(array('clinicer_name' => $joinin_detail['clinicer_name']))) {
            showMessage('The clinic administrator username already exists','');
        }

        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? clic_JOIN_STATE_FINAL : clic_JOIN_STATE_PAY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $model_clic_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if($_POST['verify_type'] === 'pass') {
            //开店
 			$clinic_array		= array();
            $clinic_array['member_id']	= $joinin_detail['member_id'];
            $clinic_array['member_name']	= $joinin_detail['member_name'];
            $clinic_array['clinicer_name'] = $joinin_detail['clinicer_name'];
			$clinic_array['grade_id']		= $joinin_detail['sg_id'];
			$clinic_array['clic_owner_card']= '';
			$clinic_array['clic_name']	= $joinin_detail['clic_name'];
			$clinic_array['sc_id']		= $joinin_detail['sc_id'];
            $clinic_array['clic_company_name'] = $joinin_detail['company_name'];
			$clinic_array['area_id']		= 0;
			$clinic_array['area_info']	= $joinin_detail['company_address'];
			$clinic_array['clic_address']= $joinin_detail['company_address_detail'];
			$clinic_array['clic_zip']	= '';
			$clinic_array['clic_tel']	= '';
			$clinic_array['clic_zy']		= '';
			$clinic_array['clic_state']	= 1;
            $clinic_array['clic_time']	= time();
			$clic_id = $model_clic->addclic($clinic_array);

            if($clic_id) {
                //写入卖家帐号
                $clinicer_array = array();
                $clinicer_array['clinicer_name'] = $joinin_detail['clinicer_name'];
                $clinicer_array['member_id'] = $joinin_detail['member_id'];
                $clinicer_array['clinicer_group_id'] = 0;
                $clinicer_array['clic_id'] = $clic_id;
                $clinicer_array['is_admin'] = 1;
                $state = $model_clinicer->addclinicer($clinicer_array);
            }

			if($state) {
				// 添加相册默认
				$album_model = Model('album');
				$album_arr = array();
				$album_arr['aclass_name'] = Language::get('clic_save_defaultalbumclass_name');
				$album_arr['clic_id'] = $clic_id;
				$album_arr['aclass_des'] = '';
				$album_arr['aclass_sort'] = '255';
				$album_arr['aclass_cover'] = '';
				$album_arr['upload_time'] = time();
				$album_arr['is_default'] = '1';
				$album_model->addClass($album_arr);

				$model = Model();
				//插入店铺扩展表
				$model->table('clic_extend')->insert(array('clic_id'=>$clic_id));
				$msg = Language::get('clic_save_create_success').($clic_grade['sg_confirm'] == 1 ? Language::get('clic_save_waiting_for_review') : '');

                //插入店铺绑定分类表
                $clic_bind_class_array = array();
                $clic_bind_class = unserialize($joinin_detail['clic_class_ids']);
                $clic_bind_commis_rates = explode(',', $joinin_detail['clic_class_commis_rates']);
                for($i=0, $length=count($clic_bind_class); $i<$length; $i++) {
                    list($class1, $class2, $class3) = explode(',', $clic_bind_class[$i]);
                    $clic_bind_class_array[] = array(
                        'clic_id' => $clic_id,
                        'commis_rate' => $clic_bind_commis_rates[$i],
                        'class_1' => $class1,
                        'class_2' => $class2,
                        'class_3' => $class3,
                    );
                }
                $model_clic_bind_class = Model('clic_bind_class');
                $model_clic_bind_class->addclicBindClassAll($clic_bind_class_array);
                showMessage('Clinic opens successfully','index.php?act=clic&op=clic_joinin');
            } else {
                showMessage('Clinic opens failed','index.php?act=clic&op=clic_joinin');
            }
        } else {
            showMessage('Clinic opens refused','index.php?act=clic&op=clic_joinin');
        }
    }

}
