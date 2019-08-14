<?php
/**
 * @name Dao_CertificateMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_CertificateMember {
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
     * @comment 根据相关条件展示监控项列表
     * @param 
     * @return array
     */
    public function listShow($class_id_arr)
    {
        $query = "SELECT id, class_id, username, is_admin FROM certificate_member WHERE";
        $condition = array();
        $condition[] = " 1 ";
        if(!empty($class_id_arr)){
            $condition[] = "class_id in ('" .  implode("','", $class_id_arr) . "')";
        }
        $query = $query . implode(" AND ", $condition) . " ORDER BY class_id";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 添加新的权限成员
     * @param class_id 权限成员id
     * @param username 权限成员名字
     * @return array
     */
    public function addCertificateMember($class_id, $member_name, $is_admin)
    {
        $query = "INSERT INTO certificate_member (class_id, username, is_admin) VALUES ('$class_id', '$member_name', '$is_admin')";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 删除权限成员
     * @param class_id 权限成员id
     * @return array
     */
    public function deleteCertificateMember($class_id, $member_name)
    {
        $query = "DELETE FROM certificate_member WHERE class_id = '$class_id' AND username = '$member_name'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 更新权限成员记录
     * @param class_id 权限成员id
     * @param username 权限成员名
     * @param is_admin 是否管理员
     * @return array
     */
    public function updateCertificateMember($class_id, $member_name, $is_admin)
    {
        $query = "UPDATE certificate_member SET is_admin = '" . $is_admin . "' WHERE class_id = '" . $class_id . "' AND username = '" . $member_name . "'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 查看一个用户具有哪些权限
     * @param 
     * @return array
     */
    public function getUserCerInfo($cer_arr, $member_name, $perm)
    {
        if ($perm == 0)
        {
            $query = "SELECT * FROM certificate_member WHERE class_id IN (" . implode(",", $cer_arr) . ") AND username = '" . $member_name . "'";
        }
        elseif ($perm == 1)
        {
            $query = "SELECT * FROM certificate_member WHERE class_id != 0 AND username = '" . $member_name . "'";
        }
        else
        {
            $query = "SELECT * FROM certificate_member WHERE username = '" . $member_name . "'";
        }
        $group_ret = $this->_db->query($query);
        if (!$group_ret)
        {
            return false;
        }
        $final_arr = array();
        foreach($group_ret as $val)
        {
            $final_arr[] = $val['class_id'];
        }
        $query = "SELECT * FROM certificate_group WHERE class_id IN (" . implode(",", $final_arr) . ")";
        $ret = $this->_db->query($query);
        $result = array();
        if (in_array("0", $final_arr))
        {
            $result[] = array("class_id" => "0", 'class_name' => '管理员组');
        }
        foreach ($ret as $val)
        {
            $temp = array();
            $temp['class_id'] = $val['class_id'];
            $temp['class_name'] = $val['class_name'];
            $result[] = $temp;
        }
        return $result;
    }
}
