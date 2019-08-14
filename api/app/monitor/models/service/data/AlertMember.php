<?php
/**
 * @name Service_Data_AlertMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_AlertMember {
    protected $_daoAlertMember = null;

    public function __construct() {
        $this->_daoAlertMember = new Dao_AlertMember();
        $this->_daoUserInfo = new Dao_UserInfo();
    }

    /*
     * @comment 获取用户所属报警组
     * @param username 用户名
     * @return array
     */
    public function getGroup($username)
    {
        $userGroup = $this->_daoAlertMember->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 获取某报警组成员
     * @return array
     */
    public function listDistinctName($username, $class_id)
    {
        $group_arr = $this->getGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoAlertMember->listShow($class_id);
        return $list_ret;
    }

    /*
     * @comment 添加新的报警组成员
     * @param class_id 新的报警组id
     * @param member_name 新的报警组成员名
     * @return array
     */
    public function addAlertMember($username, $class_id, $member_name)
    {
        $group_arr = $this->getGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $mem_info = $this->_daoUserInfo->get_user_list($member_name, 0, 1);
        if (!$mem_info)
        {
            return false;
        }
        $email = $mem_info[0]['email'];
        $telno = $mem_info[0]['tel_no'];
        $list_ret = $this->_daoAlertMember->addAlertMember($class_id, $member_name, $email, $telno);
        return $list_ret;
    }

    /*
     * @comment 删除报警组成员
     * @param username 用户名
     * @param id 所要删除的id
     * @return array
     */
    public function deleteAlertMember($username, $class_id, $member_name)
    {
        $group_arr = $this->getGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoAlertMember->deleteAlertMember($class_id, $member_name);
        return $list_ret;
    }

    /*
     * @comment 查看某一个用户所属的报警组
     * @param username 用户名
     * @param member_name 查看的用户名
     * @return array
     */
    public function getUserAlertInfo($username, $member_name)
    {
        $group_arr = $this->getGroup($username);
        if(!$group_arr){
            return false;
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return false;
        }
        $list_ret = $this->_daoAlertMember->getUserAlertInfo($member_name);
        return $list_ret;
    }
}
