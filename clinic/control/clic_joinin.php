<?php
/**
 * 商家入住
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

class clic_joininControl extends BaseHomeControl {

    private $joinin_detail = NULL;

	public function __construct() {
		parent::__construct();

		Tpl::setLayout('clic_joinin_layout');

        $this->checkLogin();

        $model_clinicer = Model('clinicer');
        $clinicer_info = $model_clinicer->getclinicerInfo(array('member_id' => $_SESSION['member_id']));
		if(!empty($clinicer_info)) {
            @header('location: index.php?act=clinicer_login');
		}

        if($_GET['op'] != 'check_clinicer_name_exist') {
            $this->check_joinin_state();
        }
	}

    private function check_joinin_state() {
        $model_clic_joinin = Model('clic_joinin');
        $joinin_detail = $model_clic_joinin->getOne(array('member_id'=>$_SESSION['member_id']));
        if(!empty($joinin_detail)) {
            $this->joinin_detail = $joinin_detail;
            switch (intval($joinin_detail['joinin_state'])) {
                case clic_JOIN_STATE_NEW:
                    $this->show_join_message('The entry application has been submitted, please wait for the administrator to review');
                    break;
                case clic_JOIN_STATE_PAY:
                    $this->show_join_message('It has been submitted. Please wait for the administrator to check and open the clinic for you', FALSE, 'step4');
                    break;
                case clic_JOIN_STATE_VERIFY_SUCCESS:
                    if(!in_array($_GET['op'], array('pay', 'pay_save'))) {
                        $this->show_join_message('The audit is successful, please complete the payment, and click the next step to submit the payment voucher', clinic_SITE_URL.DS.'index.php?act=clic_joinin&op=pay');
                    }
                    break;
                case clic_JOIN_STATE_VERIFY_FAIL:
                    if(!in_array($_GET['op'], array('step1', 'step2', 'step3', 'step4'))) {
                        $this->show_join_message('Audit failure:'.$joinin_detail['joinin_message'], clinic_SITE_URL.DS.'index.php?act=clic_joinin&op=step1');
                    }
                    break;
                case clic_JOIN_STATE_PAY_FAIL:
                    if(!in_array($_GET['op'], array('pay', 'pay_save'))) {
                        $this->show_join_message('Payment review failed:'.$joinin_detail['joinin_message'], clinic_SITE_URL.DS.'index.php?act=clic_joinin&op=pay');
                    }
                    break;
                case clic_JOIN_STATE_FINAL:
                    @header('location: index.php?act=clinicer_login');
                    break;
            }
        }
    }

	public function indexOp() {
        $this->step0Op();
	}

    public function step0Op() {
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('open_clic');
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', 'step1');
        Tpl::showpage('clic_joinin_apply.step0');
    }

    public function step1Op() {
        Tpl::output('step', 'step2');
        Tpl::output('sub_step', 'step1');
        Tpl::showpage('clic_joinin_apply');
    }

    public function step2Op() {
        if(!empty($_POST)) {
            $param = array();
            $param['member_name'] = $_SESSION['member_name'];   
            $param['company_name'] = $_POST['company_name'];
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            $param['business_licence_number_electronic'] = $this->upload_image('business_licence_number_electronic');
            $param['organization_code'] = $_POST['organization_code'];
            $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
            $param['general_taxpayer'] = $this->upload_image('general_taxpayer');

            $this->step2_save_valid($param);

            $model_clic_joinin = Model('clic_joinin');
            $joinin_info = $model_clic_joinin->getOne(array('member_id' => $_SESSION['member_id']));
            if(empty($joinin_info)) {
                $param['member_id'] = $_SESSION['member_id'];   
                $model_clic_joinin->save($param);
            } else {
                $model_clic_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
            }
        }
        Tpl::output('step', 'step2');
        Tpl::output('sub_step', 'step2');
        Tpl::showpage('clic_joinin_apply');
    }

    private function step2_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['company_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The company name cannot be empty and must be less than 50 words"),
            array("input"=>$param['company_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The company address cannot be empty and must be less than 50 words"),
            array("input"=>$param['company_address_detail'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The company detailed address can't be empty and must be less than 50 words"),
            array("input"=>$param['company_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The company phone can't be empty"),
            array("input"=>$input['company_employee_count'], "require"=>"true","validator"=>"Number","Headcount cannot be empty and must be a number"),
            array("input"=>$input['company_registered_capital'], "require"=>"true","validator"=>"Number","Registered capital cannot be empty and must be a number"),
            array("input"=>$param['contacts_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The contact name cannot be empty and must be less than 20 words"),
            array("input"=>$param['contacts_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"Contact phone cannot be empty"),
            array("input"=>$param['contacts_email'], "require"=>"true","validator"=>"email","message"=>"email address cannot be empty"),
            array("input"=>$param['business_licence_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The business license number cannot be empty and must be less than 20 words"),
            array("input"=>$param['business_licence_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The location of the business license cannot be empty and must be less than 50 words"),
            array("input"=>$param['business_licence_start'], "require"=>"true","message"=>"The validity of the business license shall not be null"),
            array("input"=>$param['business_licence_end'], "require"=>"true","message"=>"The validity of the business license shall not be null"),
            array("input"=>$param['business_sphere'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"500","message"=>"Legal business scope can not be empty and must be less than 50 words"),
            array("input"=>$param['business_licence_number_electronic'], "require"=>"true","message"=>"The electronic version of the business license cannot be empty"),
            array("input"=>$param['organization_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The organization code cannot be empty and must be less than 20 words"),
            array("input"=>$param['organization_code_electronic'], "require"=>"true","message"=>"Organization code electronic version cannot be empty"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function step3Op() {
        if(!empty($_POST)) {
            $param = array();
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
            if(!empty($_POST['is_settlement_account'])) {
                $param['is_settlement_account'] = 1;
                $param['settlement_bank_account_name'] = $_POST['bank_account_name'];
                $param['settlement_bank_account_number'] = $_POST['bank_account_number'];
                $param['settlement_bank_name'] = $_POST['bank_name'];
                $param['settlement_bank_code'] = $_POST['bank_code'];
                $param['settlement_bank_address'] = $_POST['bank_address'];
            } else {
                $param['is_settlement_account'] = 2;
                $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
                $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
                $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
                $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
                $param['settlement_bank_address'] = $_POST['settlement_bank_address'];

            }
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            $param['tax_registration_certificate_electronic'] = $this->upload_image('tax_registration_certificate_electronic');

            $this->step3_save_valid($param);

            $model_clic_joinin = Model('clic_joinin');
            $model_clic_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
        }

        //商品分类
        $gc	= Model('doctors_class');
        $gc_list	= $gc->getClassList(array('gc_parent_id'=>'0'));
        Tpl::output('gc_list',$gc_list);

        //店铺等级
		$grade_list = ($setting = H('clic_grade')) ? $setting : H('clic_grade',true);
		//附加功能
		if(!empty($grade_list) && is_array($grade_list)){
			foreach($grade_list as $key=>$grade){
				$sg_function = explode('|',$grade['sg_function']);
				if (!empty($sg_function[0]) && is_array($sg_function)){
					foreach ($sg_function as $key1=>$value){
						if ($value == 'editor_multimedia'){
							$grade_list[$key]['function_str'] .= 'text editor';
						}
					}
				}else {
					$grade_list[$key]['function_str'] = 'null';
				}
			}
		}
		Tpl::output('grade_list', $grade_list);

        //店铺分类 
        $model_clic = Model('clic_class');
        $clic_class = $model_clic->getTreeClassList(2);
        if (!empty($clic_class) && is_array($clic_class)){
            foreach ($clic_class as $k => $v){
                $clic_class[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
            }
        }
        Tpl::output('clic_class', $clic_class);

        Tpl::output('step', 'step2');
        Tpl::output('sub_step', 'step3');
        Tpl::showpage('clic_joinin_apply');
    }

    private function step3_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The bank account name cannot be empty and must be less than 50 words"),
            array("input"=>$param['bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"Bank account cannot be empty and must be less than 20 words"),
            array("input"=>$param['bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The name of the bank branch cannot be empty and must be less than 50 words"),
            array("input"=>$param['bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The branch affiliate number cannot be empty and must be less than 20 words"),
            array("input"=>$input['bank_address'], "require"=>"true","The location of the opening bank cannot be empty"),
            array("input"=>$input['bank_licence_electronic'], "require"=>"true","The electronic version of bank license cannot be empty"),
            array("input"=>$param['settlement_bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The bank account name cannot be empty and must be less than 50 words"),
            array("input"=>$param['settlement_bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"Bank account cannot be empty and must be less than 20 words"),
            array("input"=>$param['settlement_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The name of the bank branch cannot be empty and must be less than 50 words"),
            array("input"=>$param['settlement_bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The branch affiliate number cannot be empty and must be less than 20 words"),
            array("input"=>$input['settlement_bank_address'], "require"=>"true","The location of the opening bank cannot be empty"),
            array("input"=>$param['tax_registration_certificate'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"The number of tax registration certificate cannot be empty and must be less than 20 words"),
            array("input"=>$param['taxpayer_id'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"Taxpayer identification number"),
            array("input"=>$param['tax_registration_certificate_electronic'], "require"=>"true","message"=>"The electronic version of the tax registration certificate number cannot be empty"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function check_clinicer_name_existOp() {
        $condition = array();
        $condition['clinicer_name'] = $_GET['clinicer_name'];

        $model_clinicer = Model('clinicer');
        $result = $model_clinicer->isclinicerExist($condition);

        if($result) {
            echo 'true';
        } else {
            echo 'false';
        }
    }


    public function step4Op() {
        $clic_class_ids = array();
        $clic_class_names = array();
        if(!empty($_POST['clic_class_ids'])) {
            foreach ($_POST['clic_class_ids'] as $value) {
                $clic_class_ids[] = $value;
            }
        }
        if(!empty($_POST['clic_class_names'])) {
            foreach ($_POST['clic_class_names'] as $value) {
                $clic_class_names[] = $value;
            }
        }
        $param = array();
        $param['clinicer_name'] = $_POST['clinicer_name'];
        $param['clic_name'] = $_POST['clic_name'];
        $param['clic_class_ids'] = serialize($clic_class_ids);
        $param['clic_class_names'] = serialize($clic_class_names);
        $param['sg_name'] = $_POST['sg_name'];
        $param['sg_id'] = $_POST['sg_id'];
        $param['sc_name'] = $_POST['sc_name'];
        $param['sc_id'] = $_POST['sc_id'];
        $param['joinin_state'] = clic_JOIN_STATE_NEW;

        $this->step4_save_valid($param);

        $model_clic_joinin = Model('clic_joinin');
        $model_clic_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));

        @header('location: index.php?act=clic_joinin');

    }

    private function step4_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['clic_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"The clinic name cannot be empty and must be less than 50 words"),
            array("input"=>$param['sg_id'], "require"=>"true","message"=>"clnic level cannot be empty"),
            array("input"=>$param['sc_id'], "require"=>"true","message"=>"The clinic category cannot be empty"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function payOp() {
        Tpl::output('joinin_detail', $this->joinin_detail);
        Tpl::output('step', 'step3');
        Tpl::showpage('clic_joinin_pay');
    }

    public function pay_saveOp() {
        $param = array();
        $param['paying_money_certificate'] = $this->upload_image('paying_money_certificate');
        $param['paying_money_certificate_explain'] = $_POST['paying_money_certificate_explain'];
        $param['joinin_state'] = clic_JOIN_STATE_PAY;

        if(empty($param['paying_money_certificate'])) {
            showMessage('Please upload the payment voucher','','','error');
        }

        $model_clic_joinin = Model('clic_joinin');
        $model_clic_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));

        @header('location: index.php?act=clic_joinin');
    }



    private function show_join_message($message, $btn_next = FALSE, $step = 'step2') {
        Tpl::output('joinin_message', $message);
        Tpl::output('btn_next', $btn_next);
        Tpl::output('step', $step);
        Tpl::output('sub_step', 'step4');
        Tpl::showpage('clic_joinin_apply');
    }

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'clic_joinin'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

	/**
	 * 检查店铺名称是否存在
	 *
	 * @param 
	 * @return 
	 */
	public function checknameOp() {
		if(!$this->checknameinner()) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
	/**
	 * 检查店铺名称是否存在
	 *
	 * @param 
	 * @return 
	 */
	public function checknameinner() {
		/**
		 * 实例化卖家模型
		 */
		$model_clic	= Model('clic');

		$clic_name	= trim($_GET['clic_name']);
		$clic_info	= $model_clic->getclicInfo(array('clic_name'=>$clic_name));
		if($clic_info['clic_name'] != ''&&$clic_info['member_id'] != $_SESSION['member_id']) {			
			return false;
		} else {			
			return true;
		}
	}
}
