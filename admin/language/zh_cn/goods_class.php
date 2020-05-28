<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * index
 */
$lang['goods_class_index_choose_edit']		= 'Please select what you want to edit';
$lang['goods_class_index_in_homepage']		= 'Homepage content ';
$lang['goods_class_index_display']			= 'Display';
$lang['goods_class_index_hide']				= 'hide';
$lang['goods_class_index_succ']				= 'success';
$lang['goods_class_index_choose_in_homepage']	= '请选择首页内要';
$lang['goods_class_index_content']				= 'Content!';
$lang['goods_class_index_class']				= 'Docotr type';
$lang['goods_class_index_export']				= 'Export';
$lang['goods_class_index_import']				= 'Import';
$lang['goods_class_index_tag']					= 'Tag managemnet';
$lang['goods_class_index_name']					= 'Type name';
//$lang['goods_class_index_display_in_homepage']	= '首页显示';
$lang['goods_class_index_recommended']			= 'Type';
$lang['goods_class_index_ensure_del']			= 'Delete all class will delete all sub class. Are you sure?';
$lang['goods_class_index_display_tip']			= 'The first page is only displayed to the second class';
$lang['goods_class_index_help1']				= 'The Clinic owner can select the doctor category when adding the doctor, and the patient can query the doctor list according to the category';
$lang['goods_class_index_help2']				= '点击分类名前“+”符号，显示当前分类的下级分类';
$lang['goods_class_index_help3'] 				= '<a>对分类作任何更改后，都需要到 设置 -> 清理缓存 清理商品分类，新的设置才会生效</a>';
/**
 * 批量编辑
 */
$lang['goods_class_batch_edit_succ']			= 'Batch edit success';
$lang['goods_class_batch_edit_wrong_content']	= 'Batch edit wrong content';
$lang['goods_class_batch_edit_batch']	= 'Batch edit';
$lang['goods_class_batch_edit_keep']	= 'Constant';
$lang['goods_class_batch_edit_again']	= 'Re-edit this subclass';
$lang['goods_class_batch_edit_ok']	= 'Edit class success';
$lang['goods_class_batch_edit_fail']	= 'Edit class failed';
$lang['goods_class_batch_edit_paramerror']	= '参数非法';
$lang['goods_class_batch_order_empty_tip']	= '，留空则保持不变';
/**
 * 添加分类
 */
$lang['goods_class_add_name_null']		= '分类名称不能为空';
$lang['goods_class_add_sort_int']		= '分类排序仅能为数字';
$lang['goods_class_add_back_to_list']	= '返回分类列表';
$lang['goods_class_add_again']			= '继续新增分类';
$lang['goods_class_add_name_exists']	= '该分类名称已经存在了，请您换一个';
$lang['goods_class_add_sup_class']		= '上级分类';
$lang['goods_class_add_sup_class_notice']	= '如果选择上级分类，那么新增的分类则为被选择上级分类的子分类';
$lang['goods_class_add_update_sort']	= '数字范围为0~255，数字越小越靠前';
$lang['goods_class_add_display_tip']	= '分类名称是否显示';
$lang['goods_class_add_type']			= '类型';
$lang['goods_class_null_type']			= '无类型';
$lang['goods_class_add_type_desc_one']	= '如果当前下拉选项中没有适合的类型，可以去';
$lang['goods_class_add_type_desc_two']	= '功能中添加新的类型';
$lang['goods_class_edit_prompts_one']	= '"类型"关系到商品发布时商品规格的添加，没有类型的商品分类的将不能添加规格。';
$lang['goods_class_edit_prompts_two']	= '默认勾选"关联到子分类"将商品类型附加到子分类，如子分类不同于上级分类的类型，可以取消勾选并单独对子分类的特定类型进行编辑选择。';
$lang['goods_class_edit_prompts_three']	= '在编辑"类型"和勾选"关联到子分类"时，涉及分类下的商品将会被进行"违规下架"处理，商品在重新编辑后才能正常使用，<span style="color:#F30">请慎重操作</span>。';
$lang['goods_class_edit_related_to_subclass']	= '关联到子分类';
/**
 * 分类导入
 */
$lang['goods_class_import_csv_null']	= 'Imported csv file cannot be empty';
$lang['goods_class_import_data']		= 'Import data';
$lang['goods_class_import_choose_file']	= 'Please select a file';
$lang['goods_class_import_file_tip']	= '如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入';
$lang['goods_class_import_choose_code']	= '请选择文件编码';
$lang['goods_class_import_code_tip']	= '如果文件较大，建议您先把文件转换为 utf-8 编码，这样可以避免转换编码时耗费时间';
$lang['goods_class_import_file_type']	= 'File type';
$lang['goods_class_import_first_class']	= 'First class';
$lang['goods_class_import_second_class']		= 'Second class';
$lang['goods_class_import_third_class']			= 'Third class';
$lang['goods_class_import_example_download']	= 'Example download';
$lang['goods_class_import_example_tip']			= 'Click to download document';
$lang['goods_class_import_import']				= 'Import';
/**
 * 分类导出
 */
$lang['goods_class_export_data']		= 'Export data';
$lang['goods_class_export_if_trans']	= 'Export your doctor class data';
$lang['goods_class_export_trans_tip']	= '';
$lang['goods_class_export_export']		= 'export';
$lang['goods_class_export_help1']		= 'Export .csv file with doctor classification information';
/**
 * TAG index
 */
$lang['goods_class_tag_name']			= 'Tag name';
$lang['goods_class_tag_value']			= ' Tag value';
$lang['goods_class_tag_update']			= 'Update tag name';
$lang['goods_class_tag_update_prompt']	= 'It takes time to update the TAG name, please be patient';
$lang['goods_class_tag_reset']			= 'Import / Reset TAG';
$lang['goods_class_tag_reset_confirm']	= 'Are you sure you want to re-import TAG? Re-import will reset all TAG value information.';
$lang['goods_class_tag_prompts_two']	= 'TAG value is a keyword for category search, please fill in the TAG value accurately. You can fill in multiple TAG values, and each value needs to be separated by a comma.';
$lang['goods_class_tag_prompts_three']	= '导入/重置TAG功能可以根据商品分类重新更新TAG，TAG值默认为各级商品分类值。';
$lang['goods_class_tag_choose_data']	= 'Please select the data item to operate.';
/**
 * 重置TAG
 */
$lang['goods_class_reset_tag_fail_no_class']	= '重置TAG失败，没查找到任何分类信息。';
/**
 * 更新TAG名称
 */
$lang['goods_class_update_tag_fail_no_class']	= 'TAG名称更新失败，没查找到任何分类信息。';
/**
 * 删除TAG
 */
$lang['goods_class_tag_del_confirm']= '你确定要删除商品分类TAG吗?';