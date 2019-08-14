<?php
/**
 * @name Dao_RobotAcount
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_RobotAcount {
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
        return $group_ret[0];
    }

    /*
     * @comment 根据相关条件展示机器人账户
     * @param 
     * @return array
     */
    public function list_show()
    {
        $query = "SELECT id, tel_no, acount_name FROM robot_acount";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }
}
