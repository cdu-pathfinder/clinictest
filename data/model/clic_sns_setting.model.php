<?php
/**
 * 店铺动态自动发布
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

class clic_sns_settingModel extends Model {
    public function __construct(){
        parent::__construct('clic_sns_setting');
    }

    /**
     * 获取单条动态设置设置信息
     * 
     * @param unknown $condition
     * @param string $field
     * @return array
     */
    public function getclicSnsSettingInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 保存店铺动态设置
     * 
     * @param unknown $insert
     * @return boolean
     */
    public function saveclicSnsSetting($insert) {
        return $this->insert($insert);
    }
}