<?php
/**
 * @name Dao_UserInfo
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_UserInfo {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 获取用户所属权限成员
     * @param 
     * @return array
     */
    public function get_group($username)
    {
        $group_query = "SELECT class_id, is_admin from certificate_member WHERE username = '" . $username . "'";
        $group_ret = $this->_db->query($group_query);
        if ($group_ret == null || !$group_ret[0])
        {
            return array();
        }
        return $group_ret;
    }

    /*
     * @comment 增加新用户
     * @param 
     * @return array
     */
    public function add_user($member_name, $email, $tel_no)
    {
        $query = "INSERT INTO user_info (username, email, tel_no) VALUES ('" . $member_name . "', '" . $email . "', '" . $tel_no . "')";
        $insert_ret = $this->_db->query($query);

        return $insert_ret;
    }

    /*
     * @comment 更新用户信息
     * @param 
     * @return array
     */
    public function update_user($member_name, $email, $tel_no)
    {
        if ($email === NULL && $tel_no === NULL)
        {
            return true;
        }
        $update_query = "UPDATE user_info SET ";
        $update_arr = array();
        if ($email !== NULL)
        {
            $update_arr[] = " email = '" . $email . "'";
        }
        if ($tel_no !== NULL)
        {
            $update_arr[] = " tel_no = '" . $tel_no . "'";
        }
        $update_query = $update_query . implode(",", $update_arr) . " WHERE username = '" . $member_name . "'";
        $update_ret = $this->_db->query($update_query);
        if (!$update_ret)
        {
            return false;
        }
        $alert_query = "UPDATE alert_member SET ";
        $alert_arr = array();
        if ($email !== NULL)
        {
            $alert_arr[] = " email = '" . $email . "'";
        }
        if ($tel_no !== NULL)
        {
            $alert_arr[] = " telno = '" . $tel_no . "'";
        }
        $alert_query = $alert_query . implode(",", $alert_arr) . " WHERE username = '" . $member_name . "'";
        $alert_update = $this->_db->query($alert_query);
        if (!$alert_update)
        {
            return false;
        }
        return true;
    }

    /*
     * @comment 获取用户列表或用户详情信息
     * @param 
     * @return array
     */
    public function get_user_list($username)
    {
        $query = "SELECT * FROM user_info ";
        if ($username)
        {
            $query = $query . " WHERE username = '" . $username . "'";
        }
        $query = $query . " ORDER BY id desc";
        $select_ret = $this->_db->query($query);
        return $select_ret;
    }

    /*
     * @comment 删除用户
     * @param 
     * @return array
     */
    public function delete_user($member_name)
    {
        $query = "DELETE FROM user_info WHERE username = '" . $member_name . "'";
        $delete_ret = $this->_db->query($query);
        if (!$query)
        {
            return false;
        }
        $alert_query = "DELETE FROM alert_member WHERE username = '" . $member_name . "'";
        $alert_delete = $this->_db->query($alert_query);
        if (!$alert_query)
        {
            return false;
        }
        $cer_query = "DELETE FROM certificate_member WHERE username = '" . $member_name . "'";
        $cer_delete = $this->_db->query($cer_query);
        if (!$cer_delete)
        {
            return false;
        }
        return $delete_ret;
    }

    /*
     * @comment 判断一个用户是否存在
     * @param
     * @return array 
     */
    public function check_user($username)
    {
        $query = "SELECT id FROM user_info WHERE username = '" . $username . "'";
        $check_ret = $this->_db->query($query);
        return $check_ret;
    }
}
