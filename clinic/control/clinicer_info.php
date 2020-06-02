<?php
/**
 * 店铺基本信息维护
 *
 * @copyright    group
 * liam
 * @license    cdu
 * @since      File available since Release v1.1
 */
defined('InclinicNC') or exit('Access Invalid!');

class clinicer_infoControl extends BaseclinicerControl {

    /**
     * 构造方法
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 店铺基本信息设置
     *
     */
    public function indexOp() {
        Tpl::showpage('index');
    }

}