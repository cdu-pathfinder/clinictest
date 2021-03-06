<?php
/**
 * 商品管理
 *
 * 
 *
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit ('Access Invalid!');
class clic_doctors_addControl extends BaseclinicerControl {
    /**
     * 三方店铺验证，商品数量，有效期
     */
    private function checkclic(){
        if(!checkPlatformclic()){
            // 是否到达商品数上限
            $doctors_num = Model('doctors')->getdoctorsCommonCount(array('clic_id' => $_SESSION['clic_id']));
            if (intval($this->clic_grade['sg_doctors_limit']) != 0) {
                if ($doctors_num >= $this->clic_grade['sg_doctors_limit']) {
                    showMessage(L('clic_doctors_index_doctors_limit') . $this->clic_grade['sg_doctors_limit'] . L('clic_doctors_index_doctors_limit1'), 'index.php?act=clic_doctors&op=doctors_list', 'html', 'error');
                }
            }
        }
    }
    public function __construct() {
        parent::__construct();
        Language::read('member_clic_doctors_index');
    }
    public function indexOp() {
        $this->checkclic();
        $this->add_step_oneOp();
    }
    
    /**
     * 添加商品
     */
    public function add_step_oneOp() {
        // 实例化商品分类模型
        $model_doctorsclass = Model('doctors_class');
        // 商品分类
        $doctors_class = $model_doctorsclass->getdoctorsClass($_SESSION['clic_id']);

        // 常用商品分类
        $model_staple = Model('doctors_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);
        
        Tpl::output('staple_array', $staple_array);
        Tpl::output('doctors_class', $doctors_class);
        Tpl::showpage('clic_doctors_add.step1');
    }
    
    /**
     * 添加商品
     */
    public function add_step_twoOp() {
        // 实例化商品分类模型
        $model_doctorsclass = Model('doctors_class');
        // 是否能使用编辑器
        if(checkPlatformclic()){ // 平台店铺可以使用编辑器
            $editor_multimedia = true;
        } else {    // 三方店铺需要
            $editor_multimedia = false;
            if ($this->clic_grade['sg_function'] == 'editor_multimedia') {
                $editor_multimedia = true;
            }
        }
        Tpl::output('editor_multimedia', $editor_multimedia);
        
        $gc_id = intval($_GET['class_id']);
        
        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            showDialog(L('clic_doctors_index_again_choose_category1'));
        }
        
        // 三方店铺验证是否绑定了该分类
        if (!checkPlatformclic()) {
            $where['class_1|class_2|class_3'] = $gc_id;
            $where['clic_id'] = $_SESSION['clic_id'];
            $rs = Model('clic_bind_class')->getclicBindClassInfo($where);
            if (empty($rs)) {
                showMessage(L('clic_doctors_index_again_choose_category2'));
            }
        }
        
        // 更新常用分类信息
        $doctors_class = $model_doctorsclass->getdoctorsClassLineForTag($gc_id);
        Tpl::output('doctors_class', $doctors_class);
        Model('doctors_class_staple')->autoIncrementStaple($doctors_class, $_SESSION['member_id']);
        
        // 获取类型相关数据
        if ($doctors_class['type_id'] > 0) {
            $typeinfo = Model('type')->getAttr($doctors_class['type_id'], $_SESSION['clic_id'], $gc_id);
            list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
            Tpl::output('sign_i', count($spec_list));
            Tpl::output('spec_list', $spec_list);
            Tpl::output('attr_list', $attr_list);
            Tpl::output('brand_list', $brand_list);
        }
        
        // 实例化店铺商品分类模型
        $clic_doctors_class = Model('my_doctors_class')->getClassTree(array(
                'clic_id' => $_SESSION ['clic_id'],
                'stc_state' => '1'
        ));
        Tpl::output('clic_doctors_class', $clic_doctors_class);
        
        // 小时分钟显示
        $hour_array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        Tpl::output('hour_array', $hour_array);
        $minute_array = array('05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');
        Tpl::output('minute_array', $minute_array);
        
        // 关联版式
        $plate_list = Model('clic_plate')->getPlateList(array('clic_id' => $_SESSION['clic_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);
        
        Tpl::output('item_id', '');
        Tpl::output('menu_sign', 'add_doctors_stpe2');
        Tpl::showpage('clic_doctors_add.step2');
    }

    /**
     * 保存商品（商品发布第二步使用）
     */
    public function save_doctorsOp() {
        if (chksubmit()) {
            // 验证表单
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array (
                            "input" => $_POST["g_name"],
                            "require" => "true",
                            "message" => L('clic_doctors_index_doctors_name_null')
                    ),
                    array (
                            "input" => $_POST["g_price"],
                            "require" => "true",
                            "validator" => "Double",
                            "message" => L('clic_doctors_index_doctors_price_null')
                    )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(L('error') . $error, urlclinic('clinicer_center'), 'html', 'error');
            }
            $model_doctors = Model('doctors');
            $model_type = Model('type');
        
            $common_array = array();
            $common_array['doctors_name']         = $_POST['g_name'];
            $common_array['doctors_jingle']       = $_POST['g_jingle'];
            $common_array['gc_id']              = intval($_POST['cate_id']);
            $common_array['gc_name']            = $_POST['cate_name'];
            $common_array['brand_id']           = $_POST['b_id'];
            $common_array['brand_name']         = $_POST['b_name'];
            $common_array['type_id']            = intval($_POST['type_id']);
            $common_array['doctors_image']        = $_POST['image_path'];
            $common_array['doctors_price']        = floatval($_POST['g_price']);
            $common_array['doctors_marketprice']  = floatval($_POST['g_marketprice']);
            $common_array['doctors_costprice']    = floatval($_POST['g_costprice']);
            $common_array['doctors_discount']     = floatval($_POST['g_discount']);
            $common_array['doctors_serial']       = $_POST['g_serial'];
            $common_array['doctors_attr']         = serialize($_POST['attr']);
            $common_array['doctors_body']         = $_POST['g_body'];
            $common_array['doctors_commend']      = intval($_POST['g_commend']);
            $common_array['doctors_state']        = ($this->clic_info['clic_state'] != 1) ? 0 : intval($_POST['g_state']);            // 店铺关闭时，商品下架
            $common_array['doctors_addtime']      = TIMESTAMP;
            $common_array['doctors_selltime']     = strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
            $common_array['doctors_verify']       = (C('doctors_verify') == 1) ? 10 : 1;
            $common_array['clic_id']           = $_SESSION['clic_id'];
            $common_array['clic_name']         = $_SESSION['clic_name'];
            $common_array['spec_name']          = is_array($_POST['spec']) ? serialize($_POST['sp_name']) : serialize(null);
            $common_array['spec_value']         = is_array($_POST['spec']) ? serialize($_POST['sp_val']) : serialize(null);
            $common_array['doctors_vat']          = intval($_POST['g_vat']);
            $common_array['areaid_1']           = intval($_POST['province_id']);
            $common_array['areaid_2']           = intval($_POST['city_id']);
            $common_array['transport_id']       = ($_POST['freight'] == '0') ? '0' : intval($_POST['transport_id']); // 运费模板
            $common_array['transport_title']    = $_POST['transport_title'];
            $common_array['doctors_freight']      = floatval($_POST['g_freight']);
            $common_array['doctors_stcids']       = ',' . implode(',', array_unique($_POST['sgcate_id'])) . ',';    // 首尾需要加,
            $common_array['plateid_top']        = intval($_POST['plate_top']) > 0 ? intval($_POST['plate_top']) : '';
            $common_array['plateid_bottom']     = intval($_POST['plate_bottom']) > 0 ? intval($_POST['plate_bottom']) : '';
  
            // 保存数据
            $common_id = $model_doctors->adddoctors($common_array, 'doctors_common');
		   if ($common_id) {
                // 生成商品二维码
                require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
                $PhpQRCode = new PhpQRCode();
                $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$_SESSION['clic_id'].DS);
                // 商品规格
                if (is_array($_POST['spec'])) {
                    foreach ($_POST['spec'] as $value) {
                        $doctors = array();
                        $doctors['doctors_commonid']    = $common_id;
                        $doctors['doctors_name']        = $common_array['doctors_name'] . ' ' . implode(' ', $value['sp_value']);
                        $doctors['doctors_jingle']      = $common_array['doctors_jingle'];
                        $doctors['clic_id']          = $common_array['clic_id'];
                        $doctors['clic_name']        = $_SESSION['clic_name'];
                        $doctors['gc_id']             = $common_array['gc_id'];
                        $doctors['brand_id']          = $common_array['brand_id'];
                        $doctors['doctors_price']       = $value['price'];
                        $doctors['doctors_marketprice'] = $common_array['doctors_marketprice'];
                        $doctors['doctors_serial']      = $value['sku'];
                        $doctors['doctors_spec']        = serialize($value['sp_value']);
                        $doctors['doctors_storage']     = $value['stock'];
                        $doctors['doctors_image']       = $common_array['doctors_image'];
                        $doctors['doctors_state']       = $common_array['doctors_state'];
                        $doctors['doctors_verify']      = $common_array['doctors_verify'];
                        $doctors['doctors_addtime']     = TIMESTAMP;
                        $doctors['doctors_edittime']    = TIMESTAMP;
                        $doctors['areaid_1']          = $common_array['areaid_1'];
                        $doctors['areaid_2']          = $common_array['areaid_2'];
                        $doctors['color_id']          = intval($value['color']);
                        $doctors['transport_id']      = $common_array['transport_id'];
                        $doctors['doctors_freight']     = $common_array['doctors_freight'];
                        $doctors['doctors_vat']         = $common_array['doctors_vat'];
                        $doctors['doctors_commend']     = $common_array['doctors_commend'];
                        $doctors['doctors_stcids']      = $common_array['doctors_stcids'];
                        $doctors_id = $model_doctors->adddoctors($doctors);
                        $model_type->adddoctorsType($doctors_id, $common_id, array('cate_id' => $_POST['cate_id'], 'type_id' => $_POST['type_id'], 'attr' => $_POST['attr']));

                        // 生成商品二维码
                        $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                        $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                        $PhpQRCode->init();
                    }
                } else {
                    $doctors = array();
                    $doctors['doctors_commonid']    = $common_id;
                    $doctors['doctors_name']        = $common_array['doctors_name'];
                    $doctors['doctors_jingle']      = $common_array['doctors_jingle'];
                    $doctors['clic_id']          = $common_array['clic_id'];
                    $doctors['clic_name']        = $_SESSION['clic_name'];
                    $doctors['gc_id']             = $common_array['gc_id'];
                    $doctors['brand_id']          = $common_array['brand_id'];
                    $doctors['doctors_price']       = $common_array['doctors_price'];
                    $doctors['doctors_marketprice'] = $common_array['doctors_marketprice'];
                    $doctors['doctors_serial']      = $common_array['doctors_serial'];
                    $doctors['doctors_spec']        = serialize(null);
                    $doctors['doctors_storage']     = intval($_POST['g_storage']);
                    $doctors['doctors_image']       = $common_array['doctors_image'];
                    $doctors['doctors_state']       = $common_array['doctors_state'];
                    $doctors['doctors_verify']      = $common_array['doctors_verify'];
                    $doctors['doctors_addtime']     = TIMESTAMP;
                    $doctors['doctors_edittime']    = TIMESTAMP;
                    $doctors['areaid_1']          = $common_array['areaid_1'];
                    $doctors['areaid_2']          = $common_array['areaid_2'];
                    $doctors['color_id']          = 0;
                    $doctors['transport_id']      = $common_array['transport_id'];
                    $doctors['doctors_freight']     = $common_array['doctors_freight'];
                    $doctors['doctors_vat']         = $common_array['doctors_vat'];
                    $doctors['doctors_commend']     = $common_array['doctors_commend'];
                    $doctors['doctors_stcids']      = $common_array['doctors_stcids'];
                    $doctors_id = $model_doctors->adddoctors($doctors);
                    $model_type->adddoctorsType($doctors_id, $common_id, array('cate_id' => $_POST['cate_id'], 'type_id' => $_POST['type_id'], 'attr' => $_POST['attr']));

                    // 生成商品二维码
                    $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                    $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                    $PhpQRCode->init();
                }

                // 商品加入上架队列
                if (isset($_POST['starttime'])) {
                    $selltime = strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
                    if ($selltime > TIMESTAMP) {
                        $this->addcron(array('exetime' => $selltime, 'exeid' => $common_id, 'type' => 1));
                    }
                }
                // 记录日志
                $this->recordclinicerLog('添加商品，平台货号:'.$common_id);
                redirect(urlclinic('clic_doctors_add', 'add_step_three', array('commonid' => $common_id)));
            } else {
                showMessage(L('clic_doctors_index_doctors_add_fail'), getReferer(), 'html', 'error');
            }
        }
    }

    /**
     * 第三步添加颜色图片
     */
    public function add_step_threeOp() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlclinic('clinicer_center'), 'html', 'error');
        }
        
        $model_doctors = Model('doctors');
        $img_array = $model_doctors->getdoctorsList(array('doctors_commonid' => $common_id), 'color_id,doctors_image', 'color_id');
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            $colorid_array = array();
            $image_array = array();
            foreach ($img_array as $val) {
                $image_array[$val['color_id']][0]['doctors_image'] = $val['doctors_image'];
                $image_array[$val['color_id']][0]['is_default'] = 1;
                $colorid_array[] = $val['color_id'];
            }
            Tpl::output('img', $image_array);
        }
        
        $common_list = $model_doctors->getdoctoreCommonInfo(array('doctors_commonid' => $common_id), 'spec_value');
        $spec_value = unserialize($common_list['spec_value']);
        Tpl::output('value', $spec_value['1']);
        
        $model_spec = Model('spec');
        $value_array = $model_spec->getSpecValueList(array('sp_value_id' => array('in', $colorid_array), 'clic_id' => $_SESSION['clic_id']), 'sp_value_id,sp_value_name');
        if (empty($value_array)) {
            $value_array[] = array('sp_value_id' => '0', 'sp_value_name' => '无颜色');
        }
        Tpl::output('value_array', $value_array);
        
        Tpl::output('commonid', $common_id);
        Tpl::showpage('clic_doctors_add.step3');
    }
    
    /**
     * 保存商品颜色图片
     */
    public function save_imageOp(){
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            if ($common_id <= 0 || empty($_POST['img'])) {
                showMessage(L('wrong_argument'));
            }
            $model_doctors = Model('doctors');
            // 保存
            $insert_array = array();
            foreach ($_POST['img'] as $key => $value) {
                foreach ($value as $k => $v) {
                    // 商品默认主图
                    $update_array = array();        // 更新商品主图
                    $update_where = array();
                    if ($k == 0 || $v['default'] == 1) {
                        $update_array['doctors_image']    = $v['name'];
                        $update_where['doctors_commonid'] = $common_id;
                        $update_where['color_id']       = $key;
                        // 更新商品主图
                        $model_doctors->editdoctors($update_array, $update_where);
                    }
                    if ($v['name'] == '') {
                        continue;
                    }
                    $tmp_insert = array();
                    $tmp_insert['doctors_commonid']   = $common_id;
                    $tmp_insert['clic_id']         = $_SESSION['clic_id'];
                    $tmp_insert['color_id']         = $key;
                    $tmp_insert['doctors_image']      = $v['name'];
                    $tmp_insert['doctors_image_sort'] = ($v['default'] == 1) ? 0 : intval($v['sort']);
                    $tmp_insert['is_default']       = $v['default'];
                    $insert_array[] = $tmp_insert;
                }
            }
            $rs = $model_doctors->adddoctorsAll($insert_array, 'doctors_images');
            if ($rs) {
                redirect(urlclinic('clic_doctors_add', 'add_step_four', array('commonid' => $common_id)));
            } else {
                showMessage(L('nc_common_save_fail'));
            }
        }
    }
    
    /**
     * 商品发布第四步
     */
    public function add_step_fourOp() {
        // 单条商品信息
        $doctors_info = Model('doctors')->getdoctorsInfo(array('doctors_commonid' => $_GET['commonid']));

        // 自动发布动态
        $data_array = array();
        $data_array['doctors_id'] = $doctors_info['doctors_id'];
        $data_array['clic_id'] = $doctors_info['clic_id'];
        $data_array['doctors_name'] = $doctors_info['doctors_name'];
        $data_array['doctors_image'] = $doctors_info['doctors_image'];
        $data_array['doctors_price'] = $doctors_info['doctors_price'];
        $data_array['doctors_transfee_charge'] = $doctors_info['doctors_freight'] == 0 ? 1 : 0;
        $data_array['doctors_freight'] = $doctors_info['doctors_freight'];
        $this->clicAutoShare($data_array, 'new');

        Tpl::output('doctors_id', $doctors_info['doctors_id']);
        Tpl::showpage('clic_doctors_add.step4');
    }
    
    /**
     * 上传图片
     */
    public function image_uploadOp() {
        // 判断图片数量是否超限
        $model_album = Model('album');
        $album_limit = $this->clic_grade['sg_album_limit'];
        $album_count = $model_album->getCount(array('clic_id' => $_SESSION['clic_id']));
        if ($album_count >= $album_limit) {
            $error = L('clic_doctors_album_climit');
            if (strtoupper(CHARSET) == 'GBK') {
                $error = Language::getUTF8($error);
            }
            exit(json_encode(array('error' => $error)));
        }
        $class_info = $model_album->getOne(array('clic_id' => $_SESSION['clic_id'], 'is_default' => 1), 'album_class');
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_doctorS . DS . $_SESSION ['clic_id'] . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));

        $upload->set('thumb_width', doctorS_IMAGES_WIDTH);
        $upload->set('thumb_height', doctorS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', doctorS_IMAGES_EXT);
        $upload->set('fprefix', $_SESSION['clic_id']);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($_POST['name']);
        if (!$result) {
            if (strtoupper(CHARSET) == 'GBK') {
                $upload->error = Language::getUTF8($upload->error);
            }
            $output = array();
            $output['error'] = $upload->error;
            $output = json_encode($output);
            exit($output);
        }
        
        $img_path = $upload->getSysSetPath() . $upload->file_name;
        
        // 取得图像大小
        list($width, $height, $type, $attr) = getimagesize(UPLOAD_SITE_URL . '/' . ATTACH_doctorS . '/' . $_SESSION['clic_id'] . DS . $img_path);
        
        // 存入相册
        $image = explode('.', $_FILES[$_POST['name']]["name"]);
        $insert_array = array();
        $insert_array['apic_name'] = $image['0'];
        $insert_array['apic_tag'] = '';
        $insert_array['aclass_id'] = $class_info['aclass_id'];
        $insert_array['apic_cover'] = $img_path;
        $insert_array['apic_size'] = intval($_FILES[$_POST['name']]['size']);
        $insert_array['apic_spec'] = $width . 'x' . $height;
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['clic_id'] = $_SESSION['clic_id'];
        $model_album->addPic($insert_array);
        
        $data = array ();
        $data ['thumb_name'] = cthumb($upload->getSysSetPath() . $upload->thumb_image, 240, $_SESSION['clic_id']);
        $data ['name']      = $img_path;

        // 整理为json格式
        $output = json_encode($data);
        echo $output;
        exit();
    }

    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_doctors_classOp() {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);
        if ($gc_id <= 0 || $deep <= 0 || $deep >= 4) {
            exit();
        }
        $model_doctorsclass = Model('doctors_class');
        $list = $model_doctorsclass->getdoctorsClass($_SESSION['clic_id'], $gc_id, $deep);
        if (empty($list)) {
            exit();
        }
        /**
         * 转码
         */
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list = Language::getUTF8 ( $list );
        }
        echo json_encode($list);
    }
    /**
     * ajax删除常用分类
     */
    public function ajax_stapledelOp() {
        Language::read ( 'member_clic_doctors_index' );
        $staple_id = intval($_GET ['staple_id']);
        if ($staple_id < 1) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => Language::get ( 'wrong_argument' ) 
            ) );
            die ();
        }
        /**
         * 实例化模型
         */
        $model_staple = Model('doctors_class_staple');

        $result = $model_staple->delStaple(array('staple_id' => $staple_id, 'member_id' => $_SESSION['member_id']));
        if ($result) {
            echo json_encode ( array (
                    'done' => true 
            ) );
            die ();
        } else {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => '' 
            ) );
            die ();
        }
    }
    /**
     * ajax选择常用商品分类
     */
    public function ajax_show_commOp() {
        $staple_id = intval($_GET['stapleid']);
        
        /**
         * 查询相应的商品分类id
         */
        $model_staple = Model('doctors_class_staple');
        $staple_info = $model_staple->getStapleInfo(array('staple_id' => intval($staple_id), 'gc_id_1,gc_id_2,gc_id_3'));
        if (empty ( $staple_info ) || ! is_array ( $staple_info )) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => '' 
            ) );
            die ();
        }
        
        $list_array = array ();
        $list_array['gc_id'] = 0;
        $list_array['type_id'] = $staple_info['type_id'];
        $list_array['done'] = true;
        $list_array['one'] = '';
        $list_array['two'] = '';
        $list_array['three'] = '';
        
        $gc_id_1 = intval ( $staple_info['gc_id_1'] );
        $gc_id_2 = intval ( $staple_info['gc_id_2'] );
        $gc_id_3 = intval ( $staple_info['gc_id_3'] );
        
        /**
         * 查询同级分类列表
         */
        $model_doctors_class = Model ( 'doctors_class' );
        // 1级
        if ($gc_id_1 > 0) {
            $list_array['gc_id'] = $gc_id_1;
            $class_list = $model_doctors_class->getdoctorsClass($_SESSION['clic_id']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => '' 
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_1) {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 2级
        if ($gc_id_2 > 0) {
            $list_array['gc_id'] = $gc_id_2;
            $class_list = $model_doctors_class->getdoctorsClass($_SESSION['clic_id'], $gc_id_1, 2);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => '' 
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_2) {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 3级
        if ($gc_id_3 > 0) {
            $list_array['gc_id'] = $gc_id_3;
            $class_list = $model_doctors_class->getdoctorsClass($_SESSION['clic_id'], $gc_id_2, 3);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => '' 
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_3) {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 转码
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list_array = Language::getUTF8 ( $list_array );
        }
        echo json_encode ( $list_array );
        die ();
    }
    /**
     * AJAX添加商品规格值
     */
    public function ajax_add_specOp() {
        $name = trim($_GET['name']);
        $gc_id = intval($_GET['gc_id']);
        $sp_id = intval($_GET['sp_id']);
        if ($name == '' || $gc_id <= 0 || $sp_id <= 0) {
            echo json_encode(array('done' => false));die();
        }
        $insert = array(
            'sp_value_name' => $name,
            'sp_id' => $sp_id,
            'gc_id' => $gc_id,
            'clic_id' => $_SESSION['clic_id'],
            'sp_value_color' => null,
            'sp_value_sort' => 0,
        );
        $value_id = Model('spec')->addSpecValue($insert);
        if ($value_id) {
            echo json_encode(array('done' => true, 'value_id' => $value_id));die();
        } else {
            echo json_encode(array('done' => false));die();
        }
    }
}
