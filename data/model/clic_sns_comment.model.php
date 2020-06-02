<?php
/**
 * 店铺动态评论
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

class clic_sns_commentModel extends Model {
    public function __construct(){
        parent::__construct('clic_sns_comment');
    }

    /**
     * 店铺动态评论列表
     * 
     * @param array $condition
     * @param string $field
     * @param string $appointment
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getclicSnsCommentList($condition, $field = '*', $appointment = 'scomm_id desc', $limit = 0, $page = 0) {
        return $this->where($condition)->field($field)->appointment($appointment)->limit($limit)->page($page)->select();
    }
    
    /**
     * 店铺评论数量
     * @param array $condition
     * @return array
     */
    public function getclicSnsCommentCount($condition) {
        return $this->where($condition)->count();
    }
    
    /**
     * 获取单条评论
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getclicSnsCommentInfo($condition, $field = '*') {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 保存店铺评论
     * 
     * @param array $insert
     * @return boolean
     */
    public function saveclicSnsComment($insert) {
        return $this->insert($insert);
    }
    
    public function editclicSnsComment($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除店铺动态评论
     *
     * @param array $condition
     * @return boolean
     */
    public function delclicSnsComment($condition) {
        return $this->where($condition)->delete();
    }
}