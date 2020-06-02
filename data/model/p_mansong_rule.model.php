<?php
/**
 * 满即送活动规则模型 
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
class p_mansong_ruleModel extends Model{

    public function __construct(){
        parent::__construct('p_mansong_rule');
    }

	/**
     * 读取满即送规则列表
	 * @param array $mansong_id 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 所需字段
     * @return array 满即送套餐列表
	 *
	 */
	public function getMansongRuleListByID($mansong_id) {
        $condition = array();
        $condition['mansong_id'] = $mansong_id;
        $mansong_rule_list = $this->where($condition)->appointment('price desc')->select();
        if(!empty($mansong_rule_list)) {
            $model_doctors = Model('doctors');

            for($i =0, $j = count($mansong_rule_list); $i < $j; $i++) {
                $doctors_id = intval($mansong_rule_list[$i]['doctors_id']);
                if(!empty($doctors_id)) {
                    $doctors_info = $model_doctors->getdoctorsOnlineInfo(array('doctors_id'=>$doctors_id));
                    if(!empty($doctors_info)) {
                        if(empty($mansong_rule_list[$i]['mansong_doctors_name'])) {
                            $mansong_rule_list[$i]['mansong_doctors_name'] = $doctors_info['doctors_name'];
                        }
                        $mansong_rule_list[$i]['doctors_image'] = $doctors_info['doctors_image'];
                        $mansong_rule_list[$i]['doctors_image_url'] = cthumb($doctors_info['doctors_image'], $doctors_info['clic_id']);
                        $mansong_rule_list[$i]['doctors_storage'] = $doctors_info['doctors_storage'];
                        $mansong_rule_list[$i]['doctors_id'] = $doctors_id;
                        $mansong_rule_list[$i]['doctors_url'] = urlclinic('doctors', 'index', array('doctors_id' => $doctors_id));
                    }
                }
            }
        }
        return $mansong_rule_list;
	}

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
     *
	 */
    public function addMansongRule($param){
        return $this->insert($param);	
    }

	/*
	 * 批量增加 
	 * @param array $array
	 * @return bool
     *
	 */
    public function addMansongRuleArray($array){
        return $this->insertAll($array);	
    }

	/*
	 * 删除
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delMansongRule($condition){
        return $this->where($condition)->delete();
    }
}
