<?php
/**
 * @name Service_Data_AlertRecord
 * @desc
 * @author chengbiao@taihe.com
 */

class Service_Data_AlertRecord {
    protected $_daoAlertRecord = null;

    public function __construct() {
        $this->_daoAlertRecord = new Dao_AlertRecord();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoAlertRecord->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 根据相应条件查询报警记录列表
     * @param username 发出请求的用户名
     * @param alert_id 报警记录id
     * @param item_name 监控项名称
     * @param process_status 处理状态
     * @param start_time 运行记录开始时间
     * @param end_time 运行记录结束时间
     * @return array
     */
    public function ListShow($username, $alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time)
    {
        $group_arr = $this->GetGroup($username);
        if (!$group_arr)
        {
            return array();
        }
        $list_ret = $this->_daoAlertRecord->list_show($alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time, $group_arr);
        $count_ret = $this->_daoAlertRecord->get_count($alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time, $group_arr);
        return array("listdata" => $list_ret, "listcount" => $count_ret);
    }
	
    /*
	 * @comment 根据相应条件查询报警记录列表
     * @param alert_id 报警记录id
     * @param updata 更新报警记录状态
	 */
	public function updateStatus($username, $alert_id, $update){
		$group_arr = $this->GetGroup($username);
        if (!$group_arr)
        {
            return array();
        }
		$update_ret = $this->_daoAlertRecord->updateStatus($alert_id, $update, $group_arr);
		return $update_ret;
	}


}
