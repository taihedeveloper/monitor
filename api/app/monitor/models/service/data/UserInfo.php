<?php

/**
 * @name Service_Data_UserInfo
 * @desc
 * @author luohongcang@taihe.com
 */
class Service_Data_UserInfo {
    protected $_daoUserInfo = null;

    public function __construct() {
        $this->_daoUserInfo = new Dao_UserInfo();
    }

    /*
     * @comment 获取用户所属权限成员
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoUserInfo->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 增加新用户
     * @param
     * @return array 
     */
    public function addUser($username, $member_name, $email, $tel_no)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val["class_id"] == 0){
                $perm = 1; break;
            }
        }
        if ($perm != 1)
        {
            return false;
        }
        $ret = $this->_daoUserInfo->add_user($member_name, $email, $tel_no);
        return $ret;
    }

    /*
     * @comment 更新用户信息
     * @param
     * @return array 
     */
    public function updateUser($username, $member_name, $email, $tel_no)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val["class_id"] == 0){
                $perm = 1; break;
            }
        }
        if ($perm != 1)
        {
            return false;
        }
        $ret = $this->_daoUserInfo->update_user($member_name, $email, $tel_no);
        return $ret;
    }

    /*
     * @comment 获取用户组列表
     * @param
     * @return array 
     */
    public function getUserList($username)
    {
        $ret = $this->_daoUserInfo->get_user_list($username);
        return $ret;
    }

    /*
     * @comment 删除用户
     * @param
     * @return array 
     */
    public function deleteUser($username, $member_name)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val["class_id"] == 0){
                $perm = 1; break;
            }
        }
        if ($perm != 1)
        {
            return false;
        }
        $ret = $this->_daoUserInfo->delete_user($member_name);
        return $ret;
    }
    
    /*
     * @comment 判断一个用户是否存在
     * @param
     * @return array 
     */
    public function checkUser($username)
    {
        $ret = $this->_daoUserInfo->check_user($username);
        return $ret;
    }
}
