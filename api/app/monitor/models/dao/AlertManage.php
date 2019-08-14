<?php
/**
 * @name Dao_AlertManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_AlertManage {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 获取用户所属报警组
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
    public function listShow()
    {
        $query = "SELECT id, class_id, class_name FROM alert_group";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 添加新的报警组
     * @param class_id 报警组id
     * @param class_name 报警组名字
     * @return array
     */
     public function addAlertManage($class_id, $class_name)
    {
        $query = "INSERT INTO alert_group (class_id, class_name) VALUES ('$class_id', '$class_name')";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 删除报警组
     * @param class_id 报警组id
     * @return array
     */
    public function deleteAlertManage($class_id)
    {
        $query = "DELETE FROM alert_group WHERE class_id = '$class_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        $mem_query = "DELETE FROM alert_member WHERE class_id = '$class_id'";
        $mem_ret = $this->_db->query($mem_query);
        if (!$mem_ret)
        {
            return false;
        }
        return true;
    }

    /*
     * @comment 更新报警组记录
     * @param id 记录id
     * @param class_id 报警组id
     * @param class_name 报警组名
     * @return array
     */
    public function updateAlertManage($class_id, $class_name)
    {
        $query = "UPDATE alert_group SET class_name = '" . $class_name . "' WHERE class_id = '$class_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }
    
}
