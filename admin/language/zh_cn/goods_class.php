<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 * index
 */
$lang['doctors_class_index_choose_edit']		= 'Please select what you want to edit';
$lang['doctors_class_index_in_homepage']		= 'Homepage content ';
$lang['doctors_class_index_display']			= 'Display';
$lang['doctors_class_index_hide']				= 'hide';
$lang['doctors_class_index_succ']				= 'success';
$lang['doctors_class_index_choose_in_homepage']	= '请选择首页内要';
$lang['doctors_class_index_content']				= 'Content!';
$lang['doctors_class_index_class']				= 'Docotr type';
$lang['doctors_class_index_export']				= 'Export';
$lang['doctors_class_index_import']				= 'Import';
$lang['doctors_class_index_tag']					= 'Tag managemnet';
$lang['doctors_class_index_name']					= 'Type name';
//$lang['doctors_class_index_display_in_homepage']	= '首页显示';
$lang['doctors_class_index_recommended']			= 'Type';
$lang['doctors_class_index_ensure_del']			= 'Delete all class will delete all sub class. Are you sure?';
$lang['doctors_class_index_display_tip']			= 'The first page is only displayed to the second class';
$lang['doctors_class_index_help1']				= 'The Clinic owner can select the doctor category when adding the doctor, and the patient can query the doctor list according to the category';
$lang['doctors_class_index_help2']				= 'Click the "+" symbol before the category name to display the subcategories of the current category';
$lang['doctors_class_index_help3'] 				= '<a>After making any changes to the classification, you need to go to Settings -> to clean up the cache to clean up the classification before the new Settings take effect</a>';
/**
 * 批量编辑
 */
$lang['doctors_class_batch_edit_succ']			= 'Batch edit success';
$lang['doctors_class_batch_edit_wrong_content']	= 'Batch edit wrong content';
$lang['doctors_class_batch_edit_batch']	= 'Batch edit';
$lang['doctors_class_batch_edit_keep']	= 'Constant';
$lang['doctors_class_batch_edit_again']	= 'Re-edit this subclass';
$lang['doctors_class_batch_edit_ok']	= 'Edit class success';
$lang['doctors_class_batch_edit_fail']	= 'Edit class failed';
$lang['doctors_class_batch_edit_paramerror']	= 'Parameter illegal';
$lang['doctors_class_batch_appointment_empty_tip']	= ', blank is unchanged';
/**
 * 添加分类
 */
$lang['doctors_class_add_name_null']		= 'The category name cannot be empty';
$lang['doctors_class_add_sort_int']		= 'Sorting can only be done by Numbers';
$lang['doctors_class_add_back_to_list']	= 'Return the list of categories';
$lang['doctors_class_add_again']			= 'Continue to add new categories';
$lang['doctors_class_add_name_exists']	= 'This category name already exists, please change it';
$lang['doctors_class_add_sup_class']		= 'Category parent';
$lang['doctors_class_add_sup_class_notice']	= 'If a superior category is selected, the new category is a subcategory of the selected superior category';
$lang['doctors_class_add_update_sort']	= 'The Numbers range from 0 to 255, with the smaller Numbers getting closer to the front';
$lang['doctors_class_add_display_tip']	= 'Whether the category name is displayed';
$lang['doctors_class_add_type']			= 'type';
$lang['doctors_class_null_type']			= 'no type';
$lang['doctors_class_add_type_desc_one']	= 'If there is no suitable type in the current drop-down option, go';
$lang['doctors_class_add_type_desc_two']	= 'add a new type';
$lang['doctors_class_edit_prompts_one']	= '"type"Related to the addition of time when doctors publish, no type of doctors classified will not be able to add time.';
$lang['doctors_class_edit_prompts_two']	= 'By default, check "association to subcategory" to attach the doctor type to the subcategory. If the subcategory is different from the superior category, you can uncheck the subcategory and edit the specific subcategory separately.';
$lang['doctors_class_edit_prompts_three']	= 'When "type" is edited and "related to sub-category" is checked, the doctors under the category will be "illegally removed", and the doctors can only use it normally after re-editing.<span style="color:#F30">Please operate carefully.</span>。';
$lang['doctors_class_edit_related_to_subclass']	= 'Related to subcategories';
/**
 * 分类导入
 */
$lang['doctors_class_import_csv_null']	= 'Imported csv file cannot be empty';
$lang['doctors_class_import_data']		= 'Import data';
$lang['doctors_class_import_choose_file']	= 'Please select a file';
$lang['doctors_class_import_file_tip']	= 'If the import is slow, it is recommended that you split the file into several small files and import them separately';
$lang['doctors_class_import_choose_code']	= 'Please select the file encoding';
$lang['doctors_class_import_code_tip']	= 'If the file is large, it is recommended that you convert the file to utf-8 first to avoid the time consuming conversion';
$lang['doctors_class_import_file_type']	= 'File type';
$lang['doctors_class_import_first_class']	= 'First class';
$lang['doctors_class_import_second_class']		= 'Second class';
$lang['doctors_class_import_third_class']			= 'Third class';
$lang['doctors_class_import_example_download']	= 'Example download';
$lang['doctors_class_import_example_tip']			= 'Click to download document';
$lang['doctors_class_import_import']				= 'Import';
/**
 * 分类导出
 */
$lang['doctors_class_export_data']		= 'Export data';
$lang['doctors_class_export_if_trans']	= 'Export your doctor class data';
$lang['doctors_class_export_trans_tip']	= '';
$lang['doctors_class_export_export']		= 'export';
$lang['doctors_class_export_help1']		= 'Export .csv file with doctor classification information';
/**
 * TAG index
 */
$lang['doctors_class_tag_name']			= 'Tag name';
$lang['doctors_class_tag_value']			= ' Tag value';
$lang['doctors_class_tag_update']			= 'Update tag name';
$lang['doctors_class_tag_update_prompt']	= 'It takes time to update the TAG name, please be patient';
$lang['doctors_class_tag_reset']			= 'Import / Reset TAG';
$lang['doctors_class_tag_reset_confirm']	= 'Are you sure you want to re-import TAG? Re-import will reset all TAG value information.';
$lang['doctors_class_tag_prompts_two']	= 'TAG value is a keyword for category search, please fill in the TAG value accurately. You can fill in multiple TAG values, and each value needs to be separated by a comma.';
$lang['doctors_class_tag_prompts_three']	= '导入/重置TAG功能可以根据商品分类重新更新TAG，TAG值默认为各级商品分类值。';
$lang['doctors_class_tag_choose_data']	= 'Please select the data item to operate.';
/**
 * 重置TAG
 */
$lang['doctors_class_reset_tag_fail_no_class']	= '重置TAG失败，没查找到任何分类信息。';
/**
 * 更新TAG名称
 */
$lang['doctors_class_update_tag_fail_no_class']	= 'TAG名称更新失败，没查找到任何分类信息。';
/**
 * 删除TAG
 */
$lang['doctors_class_tag_del_confirm']= '你确定要删除商品分类TAG吗?';