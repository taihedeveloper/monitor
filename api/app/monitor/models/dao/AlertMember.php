<?php
/**
 * @name Dao_AlertMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_AlertMember {
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
        $member_query = "SELECT class_id from certificate_member WHERE username = '" . $username . "'";
        $member_ret = $this->_db->query($member_query);
        if ($member_ret == null || !$member_ret[0])
        {
            return array();
        }
        $ret = array();
        foreach($member_ret as $key => $val){
            $ret[] = $val['class_id'];
        }
        return $ret;
    }

    /*
     * @comment 展示某报警组的成员
     * @param 
     * @return array
     */
    public function listShow($class_id)
    {
        $query = "SELECT id, class_id, username, email, telno FROM alert_member WHERE class_id = '$class_id'";
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
     * @param member_name 报警组名字
     * @return array
     */
    public function addAlertMember($class_id, $member_name, $email, $telno)
    {
        $query = "INSERT INTO alert_member (class_id, username, email, telno) VALUES ('$class_id', '$member_name', '$email', '$telno')";
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
    public function deleteAlertMember($class_id, $member_name)
    {
        $query = "DELETE FROM alert_member WHERE class_id = '$class_id' AND username = '$member_name'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 查看某一个用户所属的报警组
     * @param username 用户名
     * @param member_name 查看的用户名
     * @return array
     */
    public function getUserAlertInfo($member_name)
    {
        $query = "SELECT class_id FROM alert_member WHERE username = '" . $member_name . "' GROUP BY class_id";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return array();
        }
        $class_arr = array();
        foreach ($ret as $val)
        {
            $class_arr[] = $val['class_id'];
        }
        $class_info = "SELECT * FROM alert_group WHERE class_id IN (" . implode(",", $class_arr) . ")";
        $ret = $this->_db->query($class_info);
        if (!$ret)
        {
            return array();
        }
        return $ret;
    }
}
