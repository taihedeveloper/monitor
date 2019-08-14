<?php
/**
 * @name Dao_AlertRecord
 * @desc
 * @author chengbiao@taihe.com
 */

class Dao_AlertRecord {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 获取用户所属权限组
     * @param 
     * @return array
     */
    public function get_group($username)
    {
        $group_query = "SELECT class_id from certificate_member WHERE username = '" . $username . "'";
        $group_ret = $this->_db->query($group_query);
        if ($group_ret == null || !$group_ret[0])
        {
            return array();
        }
		$ret = array();
		foreach($group_ret as $key => $val){
			$ret[] = $val['class_id'];
		}
        return $ret;
    }

    /*
     * @comment 根据相关条件展示监控项列表
     * @param 
     * @return array
     */
    public function list_show($alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time, $group_arr)
    { 	
		$days7ago = strtotime("-7 days");
        $query = "SELECT * FROM alert_record WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
  		$condition[] = "alert_time >= '" . $days7ago . "'";
        if ($item_type !== NULL)
        {
            $condition[] = "item_type = '" . $item_type . "'";
        }
		if ($item_level !== NULL){
			$condition[] = "item_level = '" . $item_level . "'";
        }
        if ($start_time !== NULL && $end_time !== NULL){
			$condition[] = "alert_time >= " . $start_time . " AND alert_time <= " . $end_time;
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($alert_id !== NULL)
        {
            $condition[] = "id = '" . $alert_id . "'";
        }
        if ($task_id !== NULL)
        {
            $condition[] = "task_id = '" . $task_id. "'";
        }
        if ($process_status !== NULL)
        {
            $condition[] = "process_status = '" . $process_status . "'";
        }
        if (!in_array(0, $group_arr))
        {
            $condition[] = "cer_mem IN (" . implode(',', $group_arr) . ")";
        }
        $query = $query . implode(' AND ', $condition) . " ORDER BY id desc LIMIT " . $start . ", " . $limit;
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 根据相关条件展示报警个数
     * @param 
     * @return array
     */
    public function get_count($alert_id, $item_type, $item_level, $item_name, $process_status, $start, $limit, $start_time, $end_time, $group_arr)
    {
        $days7ago = strtotime("-7 days");
        $query = "SELECT count(*) as num FROM alert_record WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
  		$condition[] = "alert_time >= '" . $days7ago . "'";
        if ($item_type !== NULL)
        {
            $condition[] = "item_type = '" . $item_type . "'";
        }
		if ($item_level !== NULL){
			$condition[] = "item_level = '" . $item_level . "'";
        }
        if ($start_time !== NULL && $end_time !== NULL){
			$condition[] = "alert_time >= " . $start_time . " AND alert_time <= " . $end_time;
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($alert_id !== NULL)
        {
            $condition[] = "id = '" . $alert_id . "'";
        }
        if ($task_id !== NULL)
        {
            $condition[] = "task_id = '" . $task_id. "'";
        }
        if ($process_status !== NULL)
        {
            $condition[] = "process_status = '" . $process_status . "'";
        }
        if (!in_array(0, $group_arr))
        {
            $condition[] = "cer_mem IN (" . implode(',', $group_arr) . ")";
        }
        $query = $query . implode(' AND ', $condition);
        $ret = $this->_db->query($query);

        if ($ret[0]['num'])
        {
            return $ret[0]['num'];
        }
        else
        {
            return "0";
        }
    }

    public function updateStatus($alert_id, $update, $group_arr){
        $alert_id_arr = explode(",", $alert_id);
        if(in_array(0, $group_arr)){
            $query = "UPDATE alert_record SET process_status = '" . $update . "' WHERE id IN (" . implode(",", $alert_id_arr) . ")";
        }
        else{
            $query = "UPDATE alert_record SET process_status = '" . $update . "' WHERE id IN (" . implode(",", $alert_id_arr) . ") AND 
            cer_mem IN (" . implode(",", $group_arr) . ")";
        }
        var_dump($query);
        $ret = $this->_db->query($query);
        if(empty($ret)){
            return false;
        }
        return true;
    }
}
