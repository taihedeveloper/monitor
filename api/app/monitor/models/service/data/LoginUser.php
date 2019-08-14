<?php

/**
 * @name Service_Data_LoginUser
 * @desc
 * @author luohongcang@taihe.com
 */
class Service_Data_LoginUser {
    protected $_daoLoginUser = null;

    public function __construct() {
        $this->_daoLoginUser = new Dao_LoginUser();
    }

    /*
     * @comment 检测用户是否有权限
     * @param username 用户名
     * @return array 
     */
    public function checkUser($username)
    {
        $ret = $this->_daoLoginUser->checkUser($username);
        return $ret;
    }
}
