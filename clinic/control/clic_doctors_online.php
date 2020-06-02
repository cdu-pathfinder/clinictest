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
class clic_doctors_onlineControl extends BaseclinicerControl {
    public function __construct() {
        parent::__construct ();
        Language::read ('member_clic_doctors_index');
    }
    public function indexOp() {
        $this->doctors_listOp();
    }
    
    /**
     * 出售中的商品列表
     */
    public function doctors_listOp() {
        $model_doctors = Model('doctors');
        
        $where = array();
        $where['clic_id'] = $_SESSION['clic_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['doctors_stcids'] = array('like', '%' . intval($_GET['stc_id']) . '%');
        }
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['doctors_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['doctors_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['doctors_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        $doctors_list = $model_doctors->getdoctorsCommonOnlineList($where);
        Tpl::output('show_page', $model_doctors->showpage());
        Tpl::output('doctors_list', $doctors_list);
        
        // 计算库存
        $storage_array = $model_doctors->calculateStorage($doctors_list, $this->clic_info['clic_storage_alarm']);
        Tpl::output('storage_array', $storage_array);
        
        // 商品分类
        $clic_doctors_class = Model('my_doctors_class')->getClassTree(array(
                                    'clic_id' => $_SESSION['clic_id'],
                                    'stc_state' => '1' 
                                ));
        Tpl::output('clic_doctors_class', $clic_doctors_class);

        $this->profile_menu('doctors_list', 'doctors_list');
        Tpl::showpage('clic_doctors_list.online');
    }
    
    /**
     * 编辑商品页面
     */
    public function edit_doctorsOp() {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }
        $model_doctors = Model('doctors');
        $where = array('doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']);
        $doctorscommon_info = $model_doctors->getdoctoreCommonInfo($where);
        if (empty($doctorscommon_info)) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }
        
        $doctorscommon_info['g_storage'] = $model_doctors->getdoctorsSum($where, 'doctors_storage');
        $doctorscommon_info['spec_name'] = unserialize($doctorscommon_info['spec_name']);
        Tpl::output('doctors', $doctorscommon_info);

        if (intval($_GET['class_id']) > 0) {
            $doctorscommon_info['gc_id'] = intval($_GET['class_id']);
        }
        $doctors_class = Model('doctors_class')->getdoctorsClassLineForTag($doctorscommon_info['gc_id']);
        Tpl::output('doctors_class', $doctors_class);

        $model_type = Model('type');
        // 获取类型相关数据
        if ($doctors_class ['type_id'] > 0) {
            $typeinfo = $model_type->getAttr($doctors_class['type_id'], $_SESSION['clic_id'], $doctorscommon_info['gc_id']);
            list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
            Tpl::output('spec_json', $spec_json);
            Tpl::output('sign_i', count($spec_list));
            Tpl::output('spec_list', $spec_list);
            Tpl::output('attr_list', $attr_list);
            Tpl::output('brand_list', $brand_list);
        }

        // 取得商品规格的输入值
        $doctors_array = $model_doctors->getdoctorsList($where, 'doctors_id, doctors_price,doctors_storage,doctors_serial,doctors_spec');
        $sp_value = array();
        if (is_array($doctors_array) && !empty($doctors_array)) {

            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList ( 'doctors_attr_index', array (
                    'doctors_id' => intval ( $doctors_array[0]['doctors_id'] )
            ), 'attr_value_id' );
            if (is_array ( $attr_checked_l ) && ! empty ( $attr_checked_l )) {
                $attr_checked = array ();
                foreach ( $attr_checked_l as $val ) {
                    $attr_checked [] = $val ['attr_value_id'];
                }
            }
            Tpl::output ( 'attr_checked', $attr_checked );
            
            $spec_checked = array();
            foreach ( $doctors_array as $k => $v ) {
                $a = unserialize($v['doctors_spec']);
                if (!empty($a)) {
                    foreach ($a as $key => $val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    $id = str_replace ( ',', '', implode ( ',', $matchs ) );
                    $sp_value ['i_' . $id . '|price'] = $v['doctors_price'];
                    $sp_value ['i_' . $id . '|id'] = $v['doctors_id'];
                    $sp_value ['i_' . $id . '|stock'] = $v['doctors_storage'];
                    $sp_value ['i_' . $id . '|sku'] = $v['doctors_serial'];
                }
            }
            Tpl::output('spec_checked', $spec_checked);
        }
        Tpl::output ( 'sp_value', $sp_value );
        
        // 实例化店铺商品分类模型
        $clic_doctors_class = Model('my_doctors_class')->getClassTree ( array (
                'clic_id' => $_SESSION ['clic_id'],
                'stc_state' => '1' 
        ) );
        Tpl::output('clic_doctors_class', $clic_doctors_class);
        $doctorscommon_info['doctors_stcids'] = trim($doctorscommon_info['doctors_stcids'], ',');
        Tpl::output('clic_class_doctors', explode(',', $doctorscommon_info['doctors_stcids']));
        
        // 是否能使用编辑器
        if(checkPlatformclic()){ // 平台店铺可以使用编辑器
            $editor_multimedia = true;
        } else {    // 三方店铺需要
            $editor_multimedia = false;
            if ($this->clic_grade['sg_function'] == 'editor_multimedia') {
                $editor_multimedia = true;
            }
        }
        Tpl::output ( 'editor_multimedia', $editor_multimedia );
        
        // 小时分钟显示
        $hour_array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        Tpl::output('hour_array', $hour_array);
        $minute_array = array('05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');
        Tpl::output('minute_array', $minute_array);
        
        // 关联版式
        $plate_list = Model('clic_plate')->getPlateList(array('clic_id' => $_SESSION['clic_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);
        
        $this->profile_menu('edit_detail','edit_detail');
        Tpl::output('edit_doctors_sign', true);
        Tpl::showpage('clic_doctors_add.step2');
    }
    
    /**
     * 编辑商品保存
     */
    public function edit_save_doctorsOp() {
        $common_id = intval ( $_POST ['commonid'] );
        if (!chksubmit() || $common_id <= 0) {
            showDialog(L('clic_doctors_index_doctors_edit_fail'), urlclinic('clic_doctors_online', 'index'));
        }
        // 验证表单
        $obj_validate = new Validate ();
        $obj_validate->validateparam = array (
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
        $error = $obj_validate->validate ();
        if ($error != '') {
            showDialog(L('error') . $error, urlclinic('clic_doctors_online', 'index'));
        }

        $gc_id = intval($_POST['cate_id']);
        
        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = H('doctors_class') ? H('doctors_class') : H('doctors_class', true);
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            showDialog(L('clic_doctors_index_again_choose_category1'));
        }
        
        // 三方店铺验证是否绑定了该分类
        if (!checkPlatformclic()) {
            $where = array();
            $where['class_1|class_2|class_3'] = $gc_id;
            $where['clic_id'] = $_SESSION['clic_id'];
            $rs = Model('clic_bind_class')->getclicBindClassInfo($where);
            if (empty($rs)) {
                showDialog(L('clic_doctors_index_again_choose_category2'));
            }
        }
        
        $model_doctors = Model ( 'doctors' );

        $update_common = array();
        $update_common['doctors_name']         = $_POST['g_name'];
        $update_common['doctors_jingle']       = $_POST['g_jingle'];
        $update_common['gc_id']              = $gc_id;
        $update_common['gc_name']            = $_POST['cate_name'];
        $update_common['brand_id']           = $_POST['b_id'];
        $update_common['brand_name']         = $_POST['b_name'];
        $update_common['type_id']            = intval($_POST['type_id']);
        $update_common['doctors_image']        = $_POST['image_path'];
        $update_common['doctors_price']        = floatval($_POST['g_price']);
        $update_common['doctors_marketprice']  = floatval($_POST['g_marketprice']);
        $update_common['doctors_costprice']    = floatval($_POST['g_costprice']);
        $update_common['doctors_discount']     = floatval($_POST['g_discount']);
        $update_common['doctors_serial']       = $_POST['g_serial'];
        $update_common['doctors_attr']         = serialize($_POST['attr']);
        $update_common['doctors_body']         = $_POST['g_body'];
        $update_common['doctors_commend']      = intval($_POST['g_commend']);
        $update_common['doctors_state']        = ($this->clic_info['clic_state'] != 1) ? 0 : intval($_POST['g_state']);            // 店铺关闭时，商品下架
        $update_common['doctors_selltime']     = strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
        $update_common['doctors_verify']       = (C('doctors_verify') == 1) ? 10 : 1;
        $update_common['spec_name']          = is_array($_POST['spec']) ? serialize($_POST['sp_name']) : serialize(null);
        $update_common['spec_value']         = is_array($_POST['spec']) ? serialize($_POST['sp_val']) : serialize(null);
        $update_common['doctors_vat']          = intval($_POST['g_vat']);
        $update_common['areaid_1']           = intval($_POST['province_id']);
        $update_common['areaid_2']           = intval($_POST['city_id']);
        $update_common['transport_id']       = ($_POST['freight'] == '0') ? '0' : intval($_POST['transport_id']); // 运费模板
        $update_common['transport_title']    = $_POST['transport_title'];
        $update_common['doctors_freight']      = floatval($_POST['g_freight']);
        $update_common['doctors_stcids']       = ',' . implode(',', array_unique($_POST['sgcate_id'])) . ',';    // 首尾需要加,
        $update_common['plateid_top']        = intval($_POST['plate_top']) > 0 ? intval($_POST['plate_top']) : '';
        $update_common['plateid_bottom']     = intval($_POST['plate_bottom']) > 0 ? intval($_POST['plate_bottom']) : '';
        
        $return = $model_doctors->editdoctorsCommon($update_common, array('doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']));
        if ($return) {
            // 清除原有规格数据
            $model_type = Model('type');
            $model_type->deldoctorsAttr(array('doctors_commonid' => $common_id));
            
            // 生成商品二维码
            require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
            $PhpQRCode = new PhpQRCode();
            $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$_SESSION['clic_id'].DS);
                    
            // 更新商品规格
            $doctorsid_array = array();
            $colorid_array = array();
            if (is_array ( $_POST ['spec'] )) {
                foreach ($_POST['spec'] as $value) {
                    $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_id' => $value['doctors_id'], 'doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']), 'doctors_id');
                    if (!empty($doctors_info)) {
                        $doctors_id = $doctors_info['doctors_id'];
                        $update = array ();
                        $update['doctors_commonid']    = $common_id;
                        $update['doctors_name']        = $update_common['doctors_name'] . ' ' . implode(' ', $value['sp_value']);
                        $update['doctors_jingle']      = $update_common['doctors_jingle'];
                        $update['clic_id']          = $_SESSION['clic_id'];
                        $update['clic_name']        = $_SESSION['clic_name'];
                        $update['gc_id']             = $update_common['gc_id'];
                        $update['brand_id']          = $update_common['brand_id'];
                        $update['doctors_price']       = $value['price'];
                        $update['doctors_marketprice'] = $update_common['doctors_marketprice'];
                        $update['doctors_serial']      = $value['sku'];
                        $update['doctors_spec']        = serialize($value['sp_value']);
                        $update['doctors_storage']     = $value['stock'];
                        $update['doctors_state']       = $update_common['doctors_state'];
                        $update['doctors_verify']      = $update_common['doctors_verify'];
                        $update['doctors_edittime']    = TIMESTAMP;
                        $update['areaid_1']          = $update_common['areaid_1'];
                        $update['areaid_2']          = $update_common['areaid_2'];
                        $update['color_id']          = intval($value['color']);
                        $update['transport_id']      = $update_common['transport_id'];
                        $update['doctors_freight']     = $update_common['doctors_freight'];
                        $update['doctors_vat']         = $update_common['doctors_vat'];
                        $update['doctors_commend']     = $update_common['doctors_commend'];
                        $update['doctors_stcids']      = $update_common['doctors_stcids'];
                        $model_doctors->editdoctors($update, array('doctors_id' => $doctors_id));
                        // 生成商品二维码
                        $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                        $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                        $PhpQRCode->init();
                    } else {
                        $insert = array();
                        $insert['doctors_commonid']    = $common_id;
                        $insert['doctors_name']        = $update_common['doctors_name'] . ' ' . implode(' ', $value['sp_value']);
                        $insert['doctors_jingle']      = $update_common['doctors_jingle'];
                        $insert['clic_id']          = $_SESSION['clic_id'];
                        $insert['clic_name']        = $_SESSION['clic_name'];
                        $insert['gc_id']             = $update_common['gc_id'];
                        $insert['brand_id']          = $update_common['brand_id'];
                        $insert['doctors_price']       = $value['price'];
                        $insert['doctors_marketprice'] = $update_common['doctors_marketprice'];
                        $insert['doctors_serial']      = $value['sku'];
                        $insert['doctors_spec']        = serialize($value['sp_value']);
                        $insert['doctors_storage']     = $value['stock'];
                        $insert['doctors_image']       = $update_common['doctors_image'];
                        $insert['doctors_state']       = $update_common['doctors_state'];
                        $insert['doctors_verify']      = $update_common['doctors_verify'];
                        $insert['doctors_addtime']     = TIMESTAMP;
                        $insert['doctors_edittime']    = TIMESTAMP;
                        $insert['areaid_1']          = $update_common['areaid_1'];
                        $insert['areaid_2']          = $update_common['areaid_2'];
                        $insert['color_id']          = intval($value['color']);
                        $insert['transport_id']      = $update_common['transport_id'];
                        $insert['doctors_freight']     = $update_common['doctors_freight'];
                        $insert['doctors_vat']         = $update_common['doctors_vat'];
                        $insert['doctors_commend']     = $update_common['doctors_commend'];
                        $insert['doctors_stcids']      = $update_common['doctors_stcids'];
                        $doctors_id = $model_doctors->adddoctors($insert);
                        
                        // 生成商品二维码
                        $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                        $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                        $PhpQRCode->init();
                    }
                    $doctorsid_array[] = intval($doctors_id);
                    $colorid_array[] = intval($value['color']);
                    $model_type->adddoctorsType($doctors_id, $common_id, array('cate_id' => $_POST['cate_id'], 'type_id' => $_POST['type_id'], 'attr' => $_POST['attr']));
                }
            } else {
                $doctors_info = $model_doctors->getdoctorsInfo(array('doctors_spec' => serialize(null), 'doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']), 'doctors_id');
                if (!empty($doctors_info)) {
                    $doctors_id = $doctors_info['doctors_id'];
                    $update = array ();
                    $update['doctors_commonid']    = $common_id;
                    $update['doctors_name']        = $update_common['doctors_name'];
                    $update['doctors_jingle']      = $update_common['doctors_jingle'];
                    $update['clic_id']          = $_SESSION['clic_id'];
                    $update['clic_name']        = $_SESSION['clic_name'];
                    $update['gc_id']             = $update_common['gc_id'];
                    $update['brand_id']          = $update_common['brand_id'];
                    $update['doctors_price']       = $update_common['doctors_price'];
                    $update['doctors_marketprice'] = $update_common['doctors_marketprice'];
                    $update['doctors_serial']      = $update_common['doctors_serial'];
                    $update['doctors_spec']        = serialize(null);
                    $update['doctors_storage']     = intval($_POST['g_storage']);
                    $update['doctors_state']       = $update_common['doctors_state'];
                    $update['doctors_verify']      = $update_common['doctors_verify'];
                    $update['doctors_edittime']    = TIMESTAMP;
                    $update['areaid_1']          = $update_common['areaid_1'];
                    $update['areaid_2']          = $update_common['areaid_2'];
                    $update['color_id']          = 0;
                    $update['transport_id']      = $update_common['transport_id'];
                    $update['doctors_freight']     = $update_common['doctors_freight'];
                    $update['doctors_vat']         = $update_common['doctors_vat'];
                    $update['doctors_commend']     = $update_common['doctors_commend'];
                    $update['doctors_stcids']      = $update_common['doctors_stcids'];
                    $model_doctors->editdoctors($update, array('doctors_id' => $doctors_id));
                    // 生成商品二维码
                    $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                    $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                    $PhpQRCode->init();
                } else {
                    $insert = array();
                    $insert['doctors_commonid']    = $common_id;
                    $insert['doctors_name']        = $update_common['doctors_name'];
                    $insert['doctors_jingle']      = $update_common['doctors_jingle'];
                    $insert['clic_id']          = $_SESSION['clic_id'];
                    $insert['clic_name']        = $_SESSION['clic_name'];
                    $insert['gc_id']             = $update_common['gc_id'];
                    $insert['brand_id']          = $update_common['brand_id'];
                    $insert['doctors_price']       = $update_common['doctors_price'];
                    $insert['doctors_marketprice'] = $update_common['doctors_marketprice'];
                    $insert['doctors_serial']      = $update_common['doctors_serial'];
                    $insert['doctors_spec']        = serialize(null);
                    $insert['doctors_storage']     = intval($_POST['g_storage']);
                    $insert['doctors_image']       = $update_common['doctors_image'];
                    $insert['doctors_state']       = $update_common['doctors_state'];
                    $insert['doctors_verify']      = $update_common['doctors_verify'];
                    $insert['doctors_addtime']     = TIMESTAMP;
                    $insert['doctors_edittime']    = TIMESTAMP;
                    $insert['areaid_1']          = $update_common['areaid_1'];
                    $insert['areaid_2']          = $update_common['areaid_2'];
                    $insert['color_id']          = 0;
                    $insert['transport_id']      = $update_common['transport_id'];
                    $insert['doctors_freight']     = $update_common['doctors_freight'];
                    $insert['doctors_vat']         = $update_common['doctors_vat'];
                    $insert['doctors_commend']     = $update_common['doctors_commend'];
                    $insert['doctors_stcids']      = $update_common['doctors_stcids'];
                    $doctors_id = $model_doctors->adddoctors($insert);
                    
                    // 生成商品二维码
                    $PhpQRCode->set('date',urlclinic('doctors', 'index', array('doctors_id'=>$doctors_id)));
                    $PhpQRCode->set('pngTempName', $doctors_id . '.png');
                    $PhpQRCode->init();
                }
                $doctorsid_array[] = intval($doctors_id);
                $colorid_array[] = 0;
                $model_type->adddoctorsType($doctors_id, $common_id, array('cate_id' => $_POST['cate_id'], 'type_id' => $_POST['type_id'], 'attr' => $_POST['attr']));
            }
            // 清理商品数据
            $model_doctors->deldoctors(array('doctors_id' => array('not in', $doctorsid_array), 'doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']));
            // 清理商品图片表
            $colorid_array = array_unique($colorid_array);
            $model_doctors->deldoctorsImages(array('doctors_commonid' => $common_id, 'color_id' => array('not in', $colorid_array)));
            // 更新商品默认主图
            $default_image_list = $model_doctors->getdoctorsImageList(array('doctors_commonid' => $common_id, 'is_default' => 1), 'color_id,doctors_image');
            if (!empty($default_image_list)) {
                foreach ($default_image_list as $val) {
                    $model_doctors->editdoctors(array('doctors_image' => $val['doctors_image']), array('doctors_commonid' => $common_id, 'color_id' => $val['color_id']));
                }
            }
            
            // 商品加入上架队列
            if (isset($_POST['starttime'])) {
                $selltime = strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
                if ($selltime > TIMESTAMP) {
                    $this->addcron(array('exetime' => $selltime, 'exeid' => $common_id, 'type' => 1), true);
                }
            }
            // 添加操作日志
            $this->recordclinicerLog('编辑商品，平台货号：'.$common_id);
            showDialog(L('nc_common_op_succ'), $_POST['ref_url'], 'succ');
        } else {
            showDialog(L('clic_doctors_index_doctors_edit_fail'), urlclinic('clic_doctors_online', 'index'));
        }
        
    }
    
    /**
     * 编辑图片
     */
    public function edit_imageOp() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlclinic('clinicer_center'), 'html', 'error');
        }
        
        $model_doctors = Model('doctors');
        
        $image_list = $model_doctors->getdoctorsImageList(array('doctors_commonid' => $common_id));
        $image_list = array_under_reset($image_list, 'color_id', 2);

        $img_array = $model_doctors->getdoctorsList(array('doctors_commonid' => $common_id), 'color_id,doctors_image', 'color_id');
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            foreach ($img_array as $val) {
                if (isset($image_list[$val['color_id']])) {
                    $image_array[$val['color_id']] = $image_list[$val['color_id']];
                } else {
                    $image_array[$val['color_id']][0]['doctors_image'] = $val['doctors_image'];
                    $image_array[$val['color_id']][0]['is_default'] = 1;
                }
                $colorid_array[] = $val['color_id'];
            }
        }
        Tpl::output('img', $image_array);

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
        
        $this->profile_menu('edit_detail', 'edit_image');
        Tpl::output('edit_doctors_sign', true);
        Tpl::showpage('clic_doctors_add.step3');
    }
    
    /**
     * 保存商品图片
     */
    public function edit_save_imageOp() {
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            if ($common_id <= 0 || empty($_POST['img'])) {
                showDialog(L('wrong_argument'), urlclinic('clic_doctors_online', 'index'));
            }
            $model_doctors = Model('doctors');
            // 删除原有图片信息
            $model_doctors->deldoctorsImages(array('doctors_commonid' => $common_id, 'clic_id' => $_SESSION['clic_id']));
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
                        $update_where['clic_id']       = $_SESSION['clic_id'];
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
                    $tmp_insert['doctors_image_sort'] = ($v['default'] == 1) ? 0 : $v['sort'];
                    $tmp_insert['is_default']       = $v['default'];
                    $insert_array[] = $tmp_insert;
                }
            }
            $rs = $model_doctors->adddoctorsAll($insert_array, 'doctors_images');
            if ($rs) {
            // 添加操作日志
            $this->recordclinicerLog('编辑商品，平台货号：'.$common_id);
                showDialog(L('nc_common_op_succ'), $_POST['ref_url'], 'succ');
            } else {
                showDialog(L('nc_common_save_fail'), urlclinic('clic_doctors_online', 'index'));
            }
        }
    }
    
    /**
     * 编辑分类
     */
    public function edit_classOp() {
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
        
        Tpl::output('commonid', $_GET['commonid']);
        $this->profile_menu('edit_class', 'edit_class');
        Tpl::output('edit_doctors_sign', true);
        Tpl::showpage('clic_doctors_add.step1');
    }
    
    /**
     * 删除商品
     */
    public function drop_doctorsOp() {
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        $commonid_array = explode(',', $common_id);
        $model_doctors = Model('doctors');
        $where = array();
        $where['doctors_commonid'] = array('in', $commonid_array);
        $where['clic_id'] = $_SESSION['clic_id'];
        $return = $model_doctors->deldoctorsNoLock($where);
        if ($return) {
            // 添加操作日志
            $this->recordclinicerLog('删除商品，平台货号：'.$common_id);
            showDialog(L('clic_doctors_index_doctors_del_success'), 'reload', 'succ');
        } else {
            showDialog(L('clic_doctors_index_doctors_del_fail'), '', 'error');
        }
    }
    
    /**
     * 商品下架
     */
    public function doctors_unshowOp() {
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        $commonid_array = explode(',', $common_id);
        $model_doctors = Model('doctors');
        $where = array();
        $where['doctors_commonid'] = array('in', $commonid_array);
        $where['clic_id'] = $_SESSION['clic_id'];
        $return = Model('doctors')->editProducesOffline($where);
        if ($return) {
            // 更新优惠套餐状态关闭
            $doctors_list = $model_doctors->getdoctorsList($where, 'doctors_id');
            if (!empty($doctors_list)) {
                $doctorsid_array = array();
                foreach ($doctors_list as $val) {
                    $doctorsid_array[] = $val['doctors_id'];
                }
                Model('p_bundling')->editBundlingCloseBydoctorsIds(array('doctors_id' => array('in', $doctorsid_array)));
            }
            // 添加操作日志
            $this->recordclinicerLog('商品下架，平台货号：'.$common_id);
            showdialog(L('clic_doctors_index_doctors_unshow_success'), getReferer() ? getReferer() : 'index.php?act=clic_doctors&op=doctors_list', 'succ', '', 2);
        } else {
            showdialog(L('clic_doctors_index_doctors_unshow_fail'), '', 'error');
        }
    }

    /**
     * 设置广告词
     */
    public function edit_jingleOp() {
        if (chksubmit()) {
            $common_id = $this->checkRequestCommonId($_POST['commonid']);
            $commonid_array = explode(',', $common_id);
            $where = array('doctors_commonid' => array('in', $commonid_array), 'clic_id' => $_SESSION['clic_id']);
            $update = array('doctors_jingle' => trim($_POST['g_jingle']));
            $return = Model('doctors')->editProducesNoLock($where, $update);
            if ($return) {
                // 添加操作日志
                $this->recordclinicerLog('设置广告词，平台货号：'.$common_id);
                showdialog(L('nc_common_op_succ'), 'reload', 'succ');
            } else {
                showdialog(L('nc_common_op_fail'), 'reload');
            }
        }
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        
        Tpl::showpage('clic_doctors_list.edit_jingle', 'null_layout');
    }

    /**
     * 设置关联版式
     */
    public function edit_plateOp() {
        if (chksubmit()) {
            $common_id = $this->checkRequestCommonId($_POST['commonid']);
            $commonid_array = explode(',', $common_id);
            $where = array('doctors_commonid' => array('in', $commonid_array), 'clic_id' => $_SESSION['clic_id']);
            $update = array();
            $update['plateid_top']        = intval($_POST['plate_top']) > 0 ? intval($_POST['plate_top']) : '';
            $update['plateid_bottom']     = intval($_POST['plate_bottom']) > 0 ? intval($_POST['plate_bottom']) : '';
            $return = Model('doctors')->editdoctorsCommon($update, $where);
            if ($return) {
                // 添加操作日志
                $this->recordclinicerLog('设置关联版式，平台货号：'.$common_id);
                showdialog(L('nc_common_op_succ'), 'reload', 'succ');
            } else {
                showdialog(L('nc_common_op_fail'), 'reload');
            }
        }
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        
        // 关联版式
        $plate_list = Model('clic_plate')->getPlateList(array('clic_id' => $_SESSION['clic_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);
        
        Tpl::showpage('clic_doctors_list.edit_plate', 'null_layout');
    }
    
    /**
     * 验证commonid
     */
    private function checkRequestCommonId($common_ids) {
        if (!preg_match('/^[\d,]+$/i', $common_ids)) {
            showdialog(L('para_error'), '', 'error');
        }
        return $common_ids;
    }
    
    /**
     * ajax获取商品列表
     */
    public function get_doctors_list_ajaxOp() {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            echo 'false';exit();
        }
        $model_doctors = Model('doctors');
        $doctorscommon_list = $model_doctors->getdoctoreCommonInfo(array('clic_id' => $_SESSION['clic_id'], 'doctors_commonid' => $common_id), 'spec_name');
        if (empty($doctorscommon_list)) {
            echo 'false';exit();
        }
        $doctors_list = $model_doctors->getdoctorsList(array('clic_id' => $_SESSION['clic_id'], 'doctors_commonid' => $common_id), 'doctors_id,doctors_spec,clic_id,doctors_price,doctors_serial,doctors_storage,doctors_image');
        if (empty($doctors_list)) {
            echo 'false';exit();
        }
        
        $spec_name = array_values((array)unserialize($doctorscommon_list['spec_name']));
        foreach ($doctors_list as $key => $val) {
            $doctors_spec = array_values((array)unserialize($val['doctors_spec']));
            $spec_array = array();
            foreach ($doctors_spec as $k => $v) {
                $spec_array[] = '<div class="doctors_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
            $doctors_list[$key]['doctors_image'] = thumb($val, '60');
            $doctors_list[$key]['doctors_spec'] = implode('', $spec_array);
            $doctors_list[$key]['alarm'] = ($this->clic_info['clic_storage_alarm'] != 0 && $val['doctors_storage'] <= $this->clic_info['clic_storage_alarm']) ? 'style="color:red;"' : '';
            $doctors_list[$key]['url'] = urlclinic('doctors', 'index', array('doctors_id' => $val['doctors_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($doctors_list);
        }
        echo json_encode($doctors_list);
    }

    /**
     * 用户中心右边，小导航
     * 
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
        	case 'doctors_list':
        	    $menu_array = array(
        	       array('menu_key' => 'doctors_list', 'menu_name' => '出售中的商品', 'menu_url' => urlclinic('clic_doctors_online', 'index'))
        	    );
        	    break;
        	case 'edit_detail':
                $menu_array = array(
                    array('menu_key' => 'edit_detail',    'menu_name' => '编辑商品',   'menu_url' => urlclinic('clic_doctors_online', 'edit_doctors', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                    array('menu_key' => 'edit_image',     'menu_name' => '编辑图片',     'menu_url' => urlclinic('clic_doctors_online', 'edit_image', array('commonid' => $_GET['commonid'], 'ref_url' => ($_GET['ref_url'] ? $_GET['ref_url'] : getReferer()))))
                );
        	    break;
        	case 'edit_class':
                $menu_array = array(
                    array('menu_key' => 'edit_class',     'menu_name' => '选择分类', 'menu_url' => urlclinic('clic_doctors_online', 'edit_class', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                    array('menu_key' => 'edit_detail',    'menu_name' => '编辑商品',   'menu_url' => urlclinic('clic_doctors_online', 'edit_doctors', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                    array('menu_key' => 'edit_image',     'menu_name' => '编辑图片',     'menu_url' => urlclinic('clic_doctors_online', 'edit_image', array('commonid' => $_GET['commonid'], 'ref_url' => ($_GET['ref_url'] ? $_GET['ref_url'] : getReferer()))))
                );
        	    break;
        }
        Tpl::output ( 'member_menu', $menu_array );
        Tpl::output ( 'menu_key', $menu_key );
    }

}