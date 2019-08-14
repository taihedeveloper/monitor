<?php
/**
 * @name Dao_RunRecord
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_RunRecord {
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
    public function list_show($run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time, $group_arr)
    {
        $query = "SELECT * FROM run_record WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
        if ($item_type !== NULL)
        {
            $condition[] = "item_type = '" . $item_type . "'";
        }
        if ($start_time !== NULL && $end_time !== NULL){
			$condition[] = "run_time >= " . $start_time . " AND run_time <= " . $end_time;
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($run_id !== NULL)
        {
            $condition[] = "id = '" . $run_id . "'";
        }
        if ($task_id !== NULL)
        {
            $condition[] = "task_id = '" . $task_id. "'";
        }
        if ($run_status !== NULL)
        {
            $condition[] = "run_status = '" . $run_status . "'";
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
     * @comment 根据相关条件获取运行数目
     * @param 
     * @return array
     */
    public function get_count($run_id, $task_id, $item_type, $item_name, $run_status, $start, $limit, $start_time, $end_time, $group_arr)
    {
        $query = "SELECT count(*) as num FROM run_record WHERE ";
        $condition = array();
        $condition[] = "1 = 1";
        if ($item_type !== NULL)
        {
            $condition[] = "item_type = '" . $item_type . "'";
        }
        if ($start_time !== NULL && $end_time !== NULL){
			$condition[] = "run_time >= " . $start_time . " AND run_time <= " . $end_time;
        }
        if ($item_name !== NULL)
        {
            $condition[] = "item_name = '" . $item_name . "'";
        }
        if ($run_id !== NULL)
        {
            $condition[] = "id = '" . $run_id . "'";
        }
        if ($task_id !== NULL)
        {
            $condition[] = "task_id = '" . $task_id. "'";
        }
        if ($run_status !== NULL)
        {
            $condition[] = "run_status = '" . $run_status . "'";
        }
        if (!in_array(0, $group_arr))
        {
            $condition[] = "cer_mem IN (" . implode(',', $group_arr) . ")";
        }
        $query = $query . implode(' AND ', $condition);
        $ret = $this->_db->query($query);

        if ($ret[0]['num'])
        {
            if ($ret[0]['num'] > 4000)
            {
                return "4000";
            }
            return $ret[0]['num'];
        }
        else
        {
            return "0";
        }
    }
}
