<?php
/**
 * 店铺评分模型
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class evaluate_clicModel extends Model {

    public function __construct(){
        parent::__construct('evaluate_clic');
    }

	/**
	 * 查询店铺评分列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getEvaluateclicList($condition, $page=null, $appointment='seval_id desc', $field='*') {
        $list = $this->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $list;
    }

    /**
     * 获取店铺评分信息
     */
    public function getEvaluateclicInfo($condition, $field='*') {
        $list = $this->field($field)->where($condition)->find();
        return $list;
    }

    /**
     * 根据店铺编号获取店铺评分数据
     *
     * @param int @clic_id 店铺编号
     * @param int @sc_id 分类编号，如果传入分类编号同时返回行业对比数据
     */
    public function getEvaluateclicInfoByclicID($clic_id, $sc_id = 0) {
        $prefix = 'evaluate_clic_info';
        $info = rcache($clic_id, $prefix);
        if(empty($info)) {
            $info = array();
            $info['clic_credit'] = $this->_getEvaluateclic(array('seval_clicid' => $clic_id));
            $info['clic_credit_average'] = round((($info['clic_credit']['clic_desccredit']['credit'] + $info['clic_credit']['clic_servicecredit']['credit'] + $info['clic_credit']['clic_deliverycredit']['credit']) / 3), 1);
            $info['clic_credit_percent'] = intval($info['clic_credit_average'] / 5 * 100);
            if($sc_id > 0) {
                $sc_info = $this->getEvaluateclicInfoByScID($sc_id);
                foreach ($info['clic_credit'] as $key => $value) {
                    $info['clic_credit'][$key]['percent'] = intval(($info['clic_credit'][$key]['credit'] - $sc_info[$key]['credit']) / $sc_info[$key]['credit'] * 100);
                    if($info['clic_credit'][$key]['percent'] > 0) {
                        $info['clic_credit'][$key]['percent_class'] = 'high';
                        $info['clic_credit'][$key]['percent_text'] = '高于';
                        $info['clic_credit'][$key]['percent'] .= '%';
                    } elseif ($info['clic_credit'][$key]['percent'] == 0) {
                        $info['clic_credit'][$key]['percent_class'] = 'equal';
                        $info['clic_credit'][$key]['percent_text'] = '持平';
                        $info['clic_credit'][$key]['percent'] = '----';
                    } else {
                        $info['clic_credit'][$key]['percent_class'] = 'low';
                        $info['clic_credit'][$key]['percent_text'] = '低于';
                        $info['clic_credit'][$key]['percent'] = abs($info['clic_credit'][$key]['percent']);
                        $info['clic_credit'][$key]['percent'] .= '%';
                    }
                }
            }
            wcache($clic_id, $info, $prefix);
        }
        return $info;
    }

    /**
     * 根据分类编号获取分类评分数据
     */
    public function getEvaluateclicInfoByScID($sc_id) {
        $prefix = 'sc_evaluate_clic_info';
        $info = rcache($sc_id, $prefix);
        if(empty($info)) {
            $model_clic = Model('clic');
            $clic_id_string = $model_clic->getclicIDString(array('sc_id' => $sc_id));
            $info = $this->_getEvaluateclic(array('seval_clicid' => array('in', $clic_id_string)));
            wcache($sc_id, $info, $prefix);
        }
        return $info;
    }

    /**
     * 获取店铺评分数据
     */
    private function _getEvaluateclic($condition) {
        $result = array();
        $field = 'AVG(seval_desccredit) as clic_desccredit,';
        $field .= 'AVG(seval_servicecredit) as clic_servicecredit,';
        $field .= 'AVG(seval_deliverycredit) as clic_deliverycredit,';
        $field .= 'COUNT(seval_id) as count';
        $info = $this->getEvaluateclicInfo($condition, $field);
        $result['clic_desccredit']['text'] = '描述相符';
        $result['clic_servicecredit']['text'] = '服务态度';
        $result['clic_deliverycredit']['text'] = '发货速度';
        if(intval($info['count']) > 0) {
            $result['clic_desccredit']['credit'] = round($info['clic_desccredit'], 1);
            $result['clic_servicecredit']['credit'] = round($info['clic_servicecredit'], 1);
            $result['clic_deliverycredit']['credit'] = round($info['clic_deliverycredit'], 1);
        } else {
            $result['clic_desccredit']['credit'] = round(5, 1);
            $result['clic_servicecredit']['credit'] = round(5, 1);
            $result['clic_deliverycredit']['credit'] = round(5, 1);
        }
        return $result;
    }


    /**
     * 添加店铺评分
     */
    public function addEvaluateclic($param) {
        return $this->insert($param);	
    }

    /**
     * 删除店铺评分
     */
    public function delEvaluateclic($condition) {
        return $this->where($condition)->delete();
    }
}
