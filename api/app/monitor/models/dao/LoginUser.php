<?php
/**
 * @name Dao_LoginUser
 * @desc
 * @author luohongcang@taihe.com
 */

class Dao_LoginUser {
    private $_db = null;
    public function __construct()
    {
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    /*
     * @comment 检测用户是否有权限
     * @param username 用户名
     * @return array
     */
    public function checkUser($username)
    {
        $query = "SELECT * from certificate_member where username = '" . $username . "'";
        $result = $this->_db->query($query);
        if ($result == null)
        {
            return array();
        }
        return $result;
    }
}
