<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * index
 */
$lang['type_index_related_fail']			= 'Some information failed to be added, please edit the type again.';
$lang['type_index_continue_to_dd']			= 'Continue adding types';
$lang['type_index_return_type_list']		= 'Back to type list';
$lang['type_index_del_succ']				= 'Type deleted successfully.';
$lang['type_index_del_fail']				= 'Type deletion failed.';
$lang['type_index_del_related_attr_fail']	= 'Failed to delete the associated attribute.';
$lang['type_index_del_related_brand_fail']	= 'Failed to delete the associated brand.';
$lang['type_index_del_related_type_fail']	= 'Failed to delete the associated specifications.';
$lang['type_index_type_name']				= 'Type name';
$lang['type_index_no_checked']				= 'Please select the data item to operate.';
$lang['type_index_prompts_one']				= 'When the administrator adds the product category, the type needs to be selected. The product list page under the front desk classification generates product search by type, which is convenient for users to search for the required products.';
/**
 * 新增属性
 */
$lang['type_add_related_brand']				= 'Choose related brands';
$lang['type_add_related_spec']				= 'Select associated specifications';
$lang['type_add_remove']					= 'Remove';
$lang['type_add_name_no_null']				= 'Please fill in the type name';
$lang['type_add_name_max']					= 'Type name length should be between 1-20 characters';
$lang['type_add_sort_no_null']				= 'Please fill in the type order';
$lang['type_add_sort_no_digits']			= 'Please fill in integer';
$lang['type_add_sort_desc']					= 'Please fill in the natural number. The list of types will be sorted and displayed according to sorting.';
$lang['type_add_spec_name']					= 'Specification name';
$lang['type_add_spec_value']				= 'Specification Value';
$lang['type_add_spec_null_one']				= 'No specifications yet';
$lang['type_add_spec_null_two']				= 'Add specifications!';
$lang['type_add_brand_null_one']			= 'No brand yet';
$lang['type_add_brand_null_two']			= 'Addd Brand';
$lang['type_add_attr_add']					= 'Add attributes';
$lang['type_add_attr_add_one']				= 'Add one attribute';
$lang['type_add_attr_add_one_value']		= 'Add one attribute value';
$lang['type_add_attr_name']					= 'Attribute name';
$lang['type_add_attr_value']				= 'Optional value of attribute';
$lang['type_add_prompts_one']				= 'Association rules are not required, it will affect the entry of specifications and prices when the product is released. Not selected as no specifications.';
$lang['type_add_prompts_two']				= 'Associated branding is not a mandatory option, it will affect the brand choice when the product is released.';
$lang['type_add_prompts_three']				= '属性值可以添加多个，每个属性值之间需要使用逗号隔开。';
$lang['type_add_prompts_four']				= 'Multiple attribute values can be added, and each attribute value needs to be separated by a comma.';
$lang['type_add_spec_must_choose']			= 'Please select at least one specification';
$lang['type_common_checked_hide']			= 'Hide unselected';
$lang['type_common_checked_show']			= 'All dsipaly';
$lang['type_common_belong_class']			= 'Belong class';
$lang['type_common_belong_class_tips']		= 'Select a category, a category that can be associated, or a more specific sub-category.';
/**
 * 编辑属性
 */
$lang['type_edit_type_value_null']			= 'No type value information has been added.';
$lang['type_edit_type_value_del_fail']		= 'Failed to delete type value information.';
$lang['type_edit_type_attr_edit']			= 'Edit attributes';
$lang['type_edit_type_attr_is_show']		= 'Whether to show';
$lang['type_edit_type_attr_name_no_null']	= 'Attribute value name cannot be empty';
$lang['type_edit_type_attr_name_max']		= 'Attribute value name cannot exceed 10 characters';
$lang['type_edit_type_attr_sort_no_null']	= 'Sort cannot be empty';
$lang['type_edit_type_attr_sort_no_digits']	= 'Sort value can only be numeric';
$lang['type_edit_type_attr_edit_succ']		= 'Attribute edited successfully';
$lang['type_edit_type_attr_edit_fail']		= 'Attribute editing failed';
$lang['type_attr_edit_name_desc']			= 'Please fill in the names of commonly used doctor attributes; for example: time; price range, etc.';
$lang['type_attr_edit_sort_desc']			= ' Please fill in the natural number. The property list will be sorted and displayed according to sorting';