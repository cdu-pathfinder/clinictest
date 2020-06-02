<?php
/**
 * 商品管理
 *
 * 
 *
 *
 * @copyright  gourp10  (http://www.vimhui.com)
 * @license    http://www.vimhui.com
 * @link       http://www.vimhui.com
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit ('Access Invalid!');
class taobao_importControl extends BaseclinicerControl {
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

	public function indexOp(){
		$lang 	= Language::getLangContent();
		// 生成商店二维码
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_clic.DS.$_SESSION['clic_id'].DS);
		if(!$_POST){
			/**
			 * 获取商品分类
			 */
			$gc	= Model('doctors_class');
			$gc_list	= $gc->getClassList(array('gc_parent_id'=>'0','gc_show'=>1));
			Tpl::output('gc_list',$gc_list);
			
			/**
			 * 获取店铺商品分类
			 */
			$model_clic_class	= Model('my_doctors_class');
			$clic_doctors_class	= $model_clic_class->getClassTree(array('clic_id'=>$_SESSION['clic_id'],'stc_state'=>'1'));
			Tpl::output('clic_doctors_class',$clic_doctors_class);
			
			if($_GET['step'] != ''){
				Tpl::output('step',$_GET['step']);
			}else{
				Tpl::output('step','1');
			}
		}else{
			$file	= $_FILES['csv'];
			/**
			 * 上传文件存在判断
			 */
			if(empty($file['name'])){
				showMessage($lang['clic_doctors_import_choose_file'],'','html','error');
			}
			/**
			 * 文件来源判定
			 */
			if(!is_uploaded_file($file['tmp_name'])){
				showMessage($lang['clic_doctors_import_unknown_file'],'','html','error');
			}
			/**
			 * 文件类型判定
			 */
			$file_name_array	= explode('.',$file['name']);
			if($file_name_array[count($file_name_array)-1] != 'csv'){
				showMessage($lang['clic_doctors_import_wrong_type'].$file_name_array[count($file_name_array)-1],'','html','error');
			}
			/**
			 * 文件大小判定
			 */
			if($file['size'] > intval(ini_get('upload_max_filesize'))*1024*1024){
				showMessage($lang['clic_doctors_import_size_limit'],'','html','error');
			}
			/**
			 * 商品分类判定
			 */
			if(empty($_POST['gc_id'])){
				showMessage($lang['clic_doctors_import_wrong_class'],'','html','error');
			}
			$gc	= Model('doctors_class');
			$gc_row	= $gc->getdoctorsClassLineForTag($_POST['gc_id']);
		
			if(!is_array($gc_row) or count($gc_row) == 0){
				showMessage($lang['clic_doctors_import_wrong_class1'],'','html','error');
			}
			$gc_sub_list	=	$gc->getClassList(array('gc_parent_id'=>intval($_POST['gc_id']),'gc_show'=>1));
			if(is_array($gc_sub_list) and count($gc_sub_list) > 0){
				showMessage($lang['clic_doctors_import_wrong_class2'],'','html','error');
			}
			

			/**
			 * 店铺商品分类判定
			 */
			$sgcate_ids	= array();
			$stc	= Model('clic_doctors_class');
			if(is_array($_POST['sgcate_id']) and count($_POST['sgcate_id']) > 0){
				foreach ($_POST['sgcate_id'] as $sgcate_id) {
					if(!in_array($sgcate_id,$sgcate_ids)){
						$stc_row	= $stc->getOneById($sgcate_id);
						if(is_array($stc_row) and count($stc_row) > 0){
							$sgcate_ids[]	= $sgcate_id;
						}
					}
				}
			}
	
			/**
			 * 上传文件的字符编码转换
			 */
			$csv_string	= unicodeToUtf8(file_get_contents($file['tmp_name']));
			
			/* 兼容淘宝助理5 start */
			$csv_array = explode("\tsyncStatus", $csv_string, 2);
			if(count($csv_array) == 2){
				$csv_string	= $csv_array[1];
			}
			/* 兼容淘宝助理5 end */
			
			/**
			 * 将文件转换为二维数组形式的商品数据
			 */
			$records	= $this->parse_taobao_csv($csv_string);
			if($records === false){
			showMessage($lang['clic_doctors_import_wrong_column'],'','html','error');
			}
			
			/**
			 * 转码
			 */
		   if (strtoupper(CHARSET) == 'GBK'){
		  	$records = Language::getGBK($records);
		}
		
         
			$model_doctorsclass = Model('doctors_class');
			$model_clic_doctors	= Model('doctors');
			$model_type = Model('type');
			// 商品数量
			$doctors_num=$model_clic_doctors->getdoctorsCommonCount(array('clic_id'=>$_SESSION['clic_id']));
			
			/**
			 * 商品数,空间使用，使用期限判断
			 */
			$model_clic	= Model('clic');
			$clic_info		= $model_clic->getclicInfo(array('clic_id'=>$_SESSION['clic_id']));
			$model_clic_grade	= Model('clic_grade');
			$clic_grade	= $model_clic_grade->getOneGrade($clic_info['grade_id']);
			/*商品数判断*/
			$remain_num	= -1;
			if(intval($clic_grade['sg_doctors_limit']) != 0) {
				if($doctors_num >= $clic_grade['sg_doctors_limit']) {
					showMessage($lang['clic_doctors_index_doctors_limit'].$clic_grade['sg_doctors_limit'].$lang['clic_doctors_index_doctors_limit1'],'index.php?act=clic_doctors&op=doctors_list','html','error');
				}
				$remain_num	= $clic_grade['sg_doctors_limit']-$doctors_num;
			}
			/*使用期限判断*/
			if(intval($clic_info['clic_end_time']) != 0) {
				if(time() >= $clic_info['clic_end_time']) {
					showMessage($lang['clic_doctors_index_time_limit'],'index.php?act=clic_doctors&op=doctors_list','html','error');
				}
			}
			/**
			 * 循环添加数据
			 */
		
			if(is_array($records) and count($records) > 0){
				foreach($records as $k=>$record){
					if($remain_num>0 and $k>=$remain_num){
						showMessage($lang['clic_doctors_index_doctors_limit'].$clic_grade['sg_doctors_limit'].$lang['clic_doctors_index_doctors_limit1'].$lang['clic_doctors_import_end'].(count($records)-$remain_num).$lang['clic_doctors_import_docs_no_import'],'index.php?act=clic_doctors&op=taobao_import&step=2','html','error');
					}
					$pic_array	= $this->get_doctors_image($record['doctors_image']);
					
					if(empty($record['doctors_name']))continue;
					$param	= array();
					$param['doctors_name']			= $record['doctors_name'];
					$param['gc_id']					= intval($_POST['gc_id']);
					$param['gc_name']				= $gc_row['gc_tag_name'];
					$param['clic_id']				= $_SESSION['clic_id'];
					$param['type_id']				= '0';
					$param['doctors_image']			= $pic_array['doctors_image'][0];
					$param['doctors_marketprice']		= $record['doctors_clic_price'];
					$param['doctors_price']= $record['doctors_clic_price'];
					//$param['doctors_show']			= '1';
					$param['doctors_commend']			= $record['doctors_commend'];
					$param['doctors_addtime']		=    time();
					$param['doctors_body']			= $record['doctors_body'];
					$param['doctors_state']			= '0';
					$param['doctors_verify']			= '1';
					$param['areaid_1']				= intval($_POST['province_id']);
					$param['areaid_2']			= intval($_POST['city_id']);
				    $param['doctors_stcids']       = $_POST['sgcate_id'];    
				
					$doctors_id	= $model_clic_doctors->adddoctors($param, 'doctors_common');
			        
					//添加库存
			        $param	= array();
				    $param['doctors_commonid']    = $doctors_id;
					$param['doctors_name']			= $record['doctors_name'];
					$param['gc_id']					= intval($_POST['gc_id']);
					$param['clic_id']				= $_SESSION['clic_id'];
					$param['doctors_image']			= $pic_array['doctors_image'][0];
					$param['doctors_marketprice']		= $record['doctors_clic_price'];
					$param['doctors_price']= $record['doctors_clic_price'];
					//$param['doctors_show']			= '1';
					$param['doctors_commend']			= $record['doctors_commend'];
					$param['doctors_addtime']		=    time();
					$param['doctors_state']			= '0';
					$param['doctors_verify']			= '1';
					$param['areaid_1']				= intval($_POST['province_id']);
					$param['areaid_2']			= intval($_POST['city_id']);
				    $param['doctors_stcids']       = $_POST['sgcate_id'];    
					$param['doctors_storage']	= $record['spec_doctors_storage'];
			        $doctors_id1=$model_clic_doctors->adddoctors($param, 'doctors');
				  
			        //规格导入
					// 更新常用分类信息
                    $doctors_class = $model_doctorsclass->getdoctorsClassLineForTag($_POST['gc_id']);
                    $type_id=$doctors_class['type_id'];
				    //添加规格表 （防止BUG暂时不做了）
		           			
				    if($type_id>0){
						//$spec_id =  $model_type->adddoctorsType($doctors_id1, $doctors_id, array('cate_id' => $_POST['gc_id'], 'type_id' => $type_id, 'attr' => $_POST['attr']));
					}
					$doctors_id_str.=",".$doctors_id;
					if($doctors_id){
						/**
						 * 添加商品的店铺分类表
						 */
						
						/**
						 * 商品多图的添加
						 */
						
					  	if(!empty($pic_array['doctors_image']) && is_array($pic_array['doctors_image'])){
							$insert_array = array();
							foreach ($pic_array['doctors_image'] as $pic){
								if($pic	== '')continue;
								$param	= array();
						     	$param['file_name']	= $pic;
								$param['file_thumb']= $pic;
								$param['clic_id']	= $_SESSION['clic_id'];
								$param['upload_time']	= time();
								$param['upload_type']	= '2';
								$param['item_id']	= $doctors_id;
								$insert_array[] = $param;
							}
							$rs = $model_clic_doctors->adddoctorsAll($insert_array, 'upload');
					    }	
					}
				}
				if($doctors_id_str!=""){
					Tpl::output('doctors_id_str',substr($doctors_id_str,1,strlen($doctors_id_str)));
				}
			}
			Tpl::output('step','2');
		}
		
		/**
		 * 相册分类
		 */
		$model_album = Model('album');
		$param = array();
		$param['album_aclass.clic_id']	= $_SESSION['clic_id'];
		$aclass_info = $model_album->getClassList($param);
		Tpl::output('aclass_info',$aclass_info);
		
		
		Tpl::output('PHPSESSID',session_id());
		
		Tpl::output('menu_sign','taobao_import');
		Tpl::showpage('clic_doctors_import');
	}
	
	private function get_doctors_image($pic_string){
		if($pic_string == ''){
			return false;
		}
		$pic_array = explode(';',$pic_string);
		if(!empty($pic_array) && is_array($pic_array)){
			$array	= array();
			$doctors_image	= array();
			$multi_image	= array();
			$i=0;
			foreach($pic_array as $v){
				if($v != ''){
					$line = explode(':',$v);//[0] 文件名tbi [2] 排序
					$doctors_image[] = $line[0];
				}
			}
			$array['doctors_image']	= array_unique($doctors_image);
			return $array;
		}else{
			return false;
		}
	}
	/**
	 * 淘宝数据字段名
	 *
	 * @return array
	 */
	private function taobao_fields()
	{
		return array(
		'doctors_name'		=> '宝贝名称',
		'cid'				=> '宝贝类目',
		'doctors_form'		=> '新旧程度',
		'doctors_clic_price'	=> '宝贝价格',
		'spec_doctors_storage'=> '宝贝数量',
		'doctors_indate'		=> '有效期',
		'doctors_transfee_charge'=>'运费承担',
		'py_price'			=>'平邮',
		'es_price'			=>'EMS',
		'kd_price'			=>'快递',
		//'doctors_show'		=> '放入仓库',
		'spec'			=>'销售属性别名',
		'doctors_commend'		=> '橱窗推荐',
		'doctors_body'		=> '宝贝描述',
		'doctors_image'		=> '新图片'
		);
		/*return array(
		'doctors_name'		=> Language::get('clic_doctors_import_doctorsname'),
		'cid'				=> Language::get('clic_doctors_import_doctorscid'),
		'doctors_clic_price'	=> Language::get('clic_doctors_import_doctorsprice'),
		'spec_doctors_storage'=> Language::get('clic_doctors_import_doctorsnum'),
		//'doctors_show'		=> '放入仓库',
		'doctors_commend'		=> Language::get('clic_doctors_import_doctorstuijian'),
		'doctors_body'		=> Language::get('clic_doctors_import_doctorsdesc'),
		'doctors_image'		=> Language::get('clic_doctors_import_doctorspic'),
		'sale_attr'			=> Language::get('clic_doctors_import_doctorsproperties')
		);*/
	}

	/**
	 * 每个字段所在CSV中的列序号，从0开始算 
	 *
	 * @param array $title_arr
	 * @param array $import_fields
	 * @return array
	 */
	private function taobao_fields_cols($title_arr, $import_fields)
	{
		$fields_cols = array();
		foreach ($import_fields as $k => $field)
		{
			$pos = array_search($field, $title_arr);
			if ($pos !== false)
			{
				$fields_cols[$k] = $pos;
			}
		}
		return $fields_cols;
	}

	/**
	 * 解析淘宝助理CSV数据
	 *
	 * @param string $csv_string
	 * @return string
	 */
	private function parse_taobao_csv($csv_string)
	{
		/* 定义CSV文件中几个标识性的字符的ascii码值 */
		define('ORD_SPACE', 32); // 空格
		define('ORD_QUOTE', 34); // 双引号
		define('ORD_TAB',    9); // 制表符
		define('ORD_N',     10); // 换行\n
		define('ORD_R',     13); // 换行\r

		/* 字段信息 */
		$import_fields = $this->taobao_fields(); // 需要导入的字段在CSV中显示的名称
		$fields_cols = array(); // 每个字段所在CSV中的列序号，从0开始算
		$csv_col_num = 0; // csv文件总列数

		$pos = 0; // 当前的字符偏移量
		$status = 0; // 0标题未开始 1标题已开始
		$title_pos = 0; // 标题开始位置
		$records = array(); // 记录集
		$field = 0; // 字段号
		$start_pos = 0; // 字段开始位置
		$field_status = 0; // 0未开始 1双引号字段开始 2无双引号字段开始
		$line =0; // 数据行号
		while($pos < strlen($csv_string))
		{
			$t = ord($csv_string[$pos]); // 每个UTF-8字符第一个字节单元的ascii码
			$next = ord($csv_string[$pos + 1]);
			$next2 = ord($csv_string[$pos + 2]);
			$next3 = ord($csv_string[$pos + 3]);

			if ($status == 0 && !in_array($t, array(ORD_SPACE, ORD_TAB, ORD_N, ORD_R)))
			{
				$status = 1;
				$title_pos = $pos;
			}
			
			if ($status == 1)
			{
				if ($field_status == 0 && $t== ORD_N)
				{
					static $flag = null;
					if ($flag === null)
					{
						$title_str = substr($csv_string, $title_pos, $pos - $title_pos);
						$title_arr = explode("\t", trim($title_str));
						$fields_cols = $this->taobao_fields_cols($title_arr, $import_fields);
						
						if (count($fields_cols) != count($import_fields))
						{
							return false;
						}
						$csv_col_num = count($title_arr); // csv总列数
						$flag = 1;
					}

					if ($next == ORD_QUOTE)
					{
						$field_status = 1; // 引号数据单元开始
						$start_pos = $pos = $pos + 2; // 数据单元开始位置(相对\n偏移+2)
					}
					else
					{
						$field_status = 2; // 无引号数据单元开始
						$start_pos = $pos = $pos + 1; // 数据单元开始位置(相对\n偏移+1)
					}
					continue;
				}

				if($field_status == 1 && $t == ORD_QUOTE && in_array($next, array(ORD_N, ORD_R, ORD_TAB))) // 引号+换行 或 引号+\t
				{
					$records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
					$field++;
					if ($field == $csv_col_num)
					{
						$line++;
						$field = 0;
						$field_status = 0;
						continue;
					}
					if (($next == ORD_N && $next2 == ORD_QUOTE) || ($next == ORD_TAB && $next2 == ORD_QUOTE) || ($next == ORD_R && $next2 == ORD_QUOTE))
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 3;
						continue;
					}
					if (($next == ORD_N && $next2 != ORD_QUOTE) || ($next == ORD_TAB && $next2 != ORD_QUOTE) || ($next == ORD_R && $next2 != ORD_QUOTE))
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 2;
						continue;
					}
					if ($next == ORD_R && $next2 == ORD_N && $next3 == ORD_QUOTE)
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 4;
						continue;
					}
					if ($next == ORD_R && $next2 == ORD_N && $next3 != ORD_QUOTE)
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 3;
						continue;
					}
				}

				if($field_status == 2 && in_array($t, array(ORD_N, ORD_R, ORD_TAB))) // 换行 或 \t
				{
					$records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
					$field++;
					if ($field == $csv_col_num)
					{
						$line++;
						$field = 0;
						$field_status = 0;
						continue;
					}
					if (($t == ORD_N && $next == ORD_QUOTE) || ($t == ORD_TAB && $next == ORD_QUOTE) || ($t == ORD_R && $next == ORD_QUOTE))
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 2;
						continue;
					}
					if (($t == ORD_N && $next != ORD_QUOTE) || ($t == ORD_TAB && $next != ORD_QUOTE) || ($t == ORD_R && $next != ORD_QUOTE))
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 1;
						continue;
					}
					if ($t == ORD_R && $next == ORD_N && $next2 == ORD_QUOTE)
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 3;
						continue;
					}
					if ($t == ORD_R && $next == ORD_N && $next2 != ORD_QUOTE)
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 2;
						continue;
					}
				}
			}

			if($t > 0 && $t <= 127) {
				$pos++;
			} elseif(192 <= $t && $t <= 223) {
				$pos += 2;
			} elseif(224 <= $t && $t <= 239) {
				$pos += 3;
			} elseif(240 <= $t && $t <= 247) {
				$pos += 4;
			} elseif(248 <= $t && $t <= 251) {
				$pos += 5;
			} elseif($t == 252 || $t == 253) {
				$pos += 6;
			} else {
				$pos++;
			}	
		}
		$return = array();
		foreach ($records as $key => $record)
		{
			foreach ($record as $k => $col)
			{
				$col = trim($col); // 去掉数据两端的空格
				/* 对字段数据进行分别处理 */
				switch ($k)
				{
					case $fields_cols['doctors_body']		: $return[$key]['doctors_body'] = str_replace(array("\\\"\\\"", "\"\""), array("\\\"", "\""), $col); break;
					case $fields_cols['doctors_image']	: $return[$key]['doctors_image'] = trim($col,'"');break;
					//case $fields_cols['doctors_show']		: $return[$key]['doctors_show'] = $col == 1 ? 0 : 1; break;
					case $fields_cols['doctors_name']		: $return[$key]['doctors_name'] = $col; break;
					case $fields_cols['spec_doctors_storage']	: $return[$key]['spec_doctors_storage'] = $col; break;
					case $fields_cols['doctors_clic_price']: $return[$key]['doctors_clic_price'] = $col; break;
					case $fields_cols['doctors_commend']	: $return[$key]['doctors_commend'] = $col; break;
					case $fields_cols['spec']	: $return[$key]['spec'] = $col; break;
					case $fields_cols['sale_attr']		: $return[$key]['sale_attr'] = $col; break;
					case $fields_cols['doctors_form']	: $return[$key]['doctors_form'] = $col; break;
					case $fields_cols['doctors_transfee_charge']		: $return[$key]['doctors_transfee_charge'] = $col; break;
					case $fields_cols['py_price']	: $return[$key]['py_price'] = $col; break;
					case $fields_cols['es_price']		: $return[$key]['es_price'] = $col; break;
					case $fields_cols['kd_price']		: $return[$key]['kd_price'] = $col; break;
					case $fields_cols['kd_price']		: $return[$key]['kd_price'] = $col; break;
//					case $fields_cols['doctors_indate']	: $return[$key]['doctors_indate'] = $col; break;
				}
			}
		}
		return $return;
	}
	/**
	 * 整理数据
	 *
	 */
	public function date_packOp(){
		Language::read('member_clic_doctors_index');
		$lang	= Language::getLangContent();
		if(trim($_GET['doctors_id_str'])==''){
			showMessage($lang['clic_doctors_pack_wrong1'],'','','error');
		}else{
			$doctors_model=Model('doctors');
			$upload_model=Model('upload');
			$gid_arr=explode(',',trim($_GET['doctors_id_str']));
			if(is_array($gid_arr) && !empty($gid_arr)){
				$path=UPLOAD_SITE_URL . '/clinic/clic/doctors' .DS.$_SESSION['clic_id'].DS;
				
				foreach($gid_arr as $v1){
					$insert_array='';
					$upload_list=$upload_model->getUploadList(array('item_id'=>$v1),'upload_id,file_name,file_thumb');
					$doctors_image		= '';			// 商品默认图
					$i=1;
					foreach($upload_list as $k2=>$v2){
						if($k2==0) $doctors_image = $v2['file_name'];
						//上传多图
						$tmp_insert = array();
						$tmp_insert['doctors_commonid']   = $v1;
						$tmp_insert['clic_id']         = $_SESSION['clic_id'];
						$tmp_insert['doctors_image']      = $v2['file_name'];
						if($i==1){ 
							$tmp_insert['is_default']= '1';
						}else{ 
							$tmp_insert['is_default']= '0';
						}
						$i=0;
						$insert_array[] = $tmp_insert;
					}
					$update_where = array();
					$update_array = array();        // 更新商品主图
				   	$update_where['clic_id']       = $_SESSION['clic_id'];
                    $update_array['doctors_image']    = $doctors_image;
                    $update_where['doctors_commonid'] = $v1;
					
                    $doctors_model->editdoctors($update_array, $update_where);
                    $doctors_model->editdoctorsCommon($update_array, $update_where);
                  
					$doctors_model->adddoctorsAll($insert_array, 'doctors_images');
					
					//验证商品内容图片是否存在
					//如果不存在则使用upload的第一张图作为商品内容图
					//如果upload中也不存在则图片内容改为为空
					//更新商品多图
					$upload_model->delByWhere(array('item_id'=>$v1,'clic_id'=>$_SESSION['clic_id']));
				}
				showMessage($lang['clic_doctors_pack_success'],'index.php?act=taobao_import');
			}else{
				showMessage($lang['clic_doctors_pack_wrong2'],'','','error');
			}
		}
	}
	/**
	 * 根据分类id获取TAG
	 * 
	 * @param int $class_id
	 * @return array
	 */
	private function getTagByCache($class_id){
		/**
		 * 实例化模型
		 */
		$model_staple = Model('doctors_class_staple');
		/**
		 * 获取分类TAG缓存
		 */
		$class_tag_array = ($tag = F('class_tag')) ? $tag : H('class_tag',true,'file');
		if(!empty($class_tag_array) && is_array($class_tag_array)){
			foreach ($class_tag_array as $v){
				if($v['gc_id'] == $class_id){
					$param_array = array();
					$param_array['staple_name']	= $v['gc_tag_name'];
					$param_array['gc_id']		= $v['gc_id'];
					$param_array['type_id']		= $v['type_id'];
					$param_array['clic_id']	= $_SESSION['clic_id'];
					$param_array['staple_id']	= $model_staple->addStaple($param_array);
					return $param_array;
				}
			}
		}
		
		//如果缓存中不存在，添加商品分类TAG，并保存到常用分类
		/**
		 * 实例化模型
		 */
		$model_class		= Model('doctors_class');
		$model_class_tag	= Model('doctors_class_tag');
		$gc_list = $model_class->getdoctorsClassLineForTag($class_id);
		$return = $model_class_tag->addOneTag($gc_list);
		
		//添加常用分类
		$param_array = array();
		$param_array['staple_name']	= $gc_list['gc_tag_name'];
		$param_array['gc_id']		= $gc_list['gc_id'];
		$param_array['type_id']		= $gc_list['type_id'];
		$param_array['clic_id']	= $_SESSION['clic_id'];
		$param_array['staple_id']	= $model_staple->addStaple($param_array);
		return $param_array;
	}

}
