<?php
/**
 * @name Dao_CertificateManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_CertificateManage {
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
        $group_query = "SELECT class_id, is_admin from certificate_member WHERE username = '" . $username . "'";
        $group_ret = $this->_db->query($group_query);
        if ($group_ret == null || !$group_ret[0])
        {
            return array();
        }
        $ret = array();
        foreach($group_ret as $key => $val){
            $ret[] = $val;
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
        $query = "SELECT id, class_id, class_name FROM certificate_group";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 添加新的权限组
     * @param class_id 权限组id
     * @param class_name 权限组名字
     * @return array
     */
    public function addCertificateManage($class_id, $class_name)
    {
        $query = "INSERT INTO certificate_group (class_id, class_name) VALUES ('$class_id', '$class_name')";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 删除权限组
     * @param class_id 权限组id
     * @return array
     */
    public function deleteCertificateManage($class_id)
    {
        $query = "DELETE FROM certificate_group WHERE class_id = '$class_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        $mem_query = "DELETE FROM certificate_member WHERE class_id = '$class_id'";
        $mem_ret = $this->_db->query($mem_query);
        if (!$mem_ret)
        {
            return false;
        }
        return $ret;
    }

    /*
     * @comment 更新权限组记录
     * @param id 记录id
     * @param class_id 权限组id
     * @param class_name 权限组名
     * @return array
     */
    public function updateCertificateManage($class_id, $class_name)
    {
        $query = "UPDATE certificate_group SET class_name = '" . $class_name . "' WHERE class_id = '$class_id'";
        $ret = $this->_db->query($query);
        if (!$ret)
        {
            return false;
        }
        return $ret;
    }
}
