<?php
/**
 * @name Service_Data_RunRecord
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_RunRecord {
    protected $_daoRunRecord = null;

    public function __construct() {
        $this->_daoRunRecord = new Dao_RunRecord();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoRunRecord->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 根据相应条件查询运行记录列表
     * @param run_id 运行记录id
     * @param task_id 监控项id
     * @param item_name 监控项名称
     * @param run_status 运行记录状态
     * @param start_time 运行记录开始时间
     * @param end_time 运行记录结束时间
     * @return array
     */
    public function ListShow($username, $run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time)
    {
        $group_arr = $this->GetGroup($username);
        if (!$group_arr)
        {
            return array();
        }
        $list_ret = $this->_daoRunRecord->list_show($run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time, $group_arr);
        $count_ret = $this->_daoRunRecord->get_count($run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time, $group_arr);
        return array("listdata" => $list_ret, "listcount" => $count_ret);
    }
}
