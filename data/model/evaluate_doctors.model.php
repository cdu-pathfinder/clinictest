<?php
/**
 * 商品评价模型
 *
 * @copyright  gourp10 
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');
class evaluate_doctorsModel extends Model {

    public function __construct(){
        parent::__construct('evaluate_doctors');
    }

	/**
	 * 查询评价列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $appointment 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getEvaluatedoctorsList($condition, $page = null, $appointment = 'geval_id desc', $field = '*') {
        $list = $this->field($field)->where($condition)->page($page)->appointment($appointment)->select();
        return $list;
    }

    /**
     * 根据编号查询商品评价 
     */
    public function getEvaluatedoctorsInfoByID($geval_id, $clic_id = 0) {
        if(intval($geval_id) <= 0) {
            return null;
        }

        $info = $this->where(array('geval_id' => $geval_id))->find();

        if($clic_id > 0 && intval($info['geval_clicid']) !== $clic_id) {
            return null;
        } else {
            return $info;
        }
    }

    /**
     * 根据商品编号查询商品评价信息 
     */
    public function getEvaluatedoctorsInfoBydoctorsID($doctors_id) {
        $prefix = 'evaluation_doctors_info';
        $info = rcache($doctors_id, $prefix);
        if(empty($info)) {
            $info = array();
            $doctor = $this->field('count(*) as count')->where(array('geval_doctorsid'=>$doctors_id,'geval_scores' => array('in', '4,5')))->find();
            $info['doctor'] = $doctor['count'];
            $normal = $this->field('count(*) as count')->where(array('geval_doctorsid'=>$doctors_id,'geval_scores' => array('in', '2,3')))->find();
            $info['normal'] = $normal['count']; 
            $bad = $this->field('count(*) as count')->where(array('geval_doctorsid'=>$doctors_id,'geval_scores' => array('in', '1')))->find();
            $info['bad'] = $bad['count']; 
            $info['all'] = $info['doctor'] + $info['normal'] + $info['bad']; 
            if(intval($info['all']) > 0) {
                $info['doctor_percent'] = intval($info['doctor'] / $info['all'] * 100);
                $info['normal_percent'] = intval($info['normal'] / $info['all'] * 100);
                $info['bad_percent'] = intval($info['bad'] / $info['all'] * 100);
                $info['doctor_star'] = ceil($info['doctor'] / $info['all'] * 5);
            } else {
                $info['doctor_percent'] = 100;
                $info['normal_percent'] = 0;
                $info['bad_percent'] = 0;
                $info['doctor_star'] = 5;
            }

            //更新商品表好评星级和评论数
            $model_doctors = Model('doctors');
            $update = array();
            $update['evaluation_doctor_star'] = $info['doctor_star'];
            $update['evaluation_count'] = $info['all'];
            $model_doctors->editdoctors($update, array('doctors_id' => $doctors_id));
            wcache($doctors_id, $info, $prefix);
        }
        return $info;
    }

    /**
     * 批量添加商品评价
     */
    public function addEvaluatedoctorsArray($param) {
        return $this->insertAll($param);	
    }

    /**
     * 更新商品评价
     */
    public function editEvaluatedoctors($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除商品评价
     */
    public function delEvaluatedoctors($condition) {
        return $this->where($condition)->delete();
    }
}
