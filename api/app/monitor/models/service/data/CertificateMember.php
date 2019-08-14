<?php
/**
 * @name Service_Data_CertificateMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_CertificateMember {
    protected $_daoCertificateMember = null;

    public function __construct() {
        $this->_daoCertificateMember = new Dao_CertificateMember();
    }

    /*
     * @comment 获取用户所属权限成员
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoCertificateMember->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 管理员获取所有权限成员名
     * @return array
     */
    public function listDistinctName($username, $group_id)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $class_id_arr = array();
        foreach($group_arr as $key => $val){
            if($val["class_id"] == 0){
                $class_id_arr = array(); break;
            }else{
                $class_id_arr[] = $val["class_id"];
            }	
        }

        if ($group_id !== NULL)
        {
            if ($class_id_arr && !in_array($group_id, $class_id_arr))
            {
                return array();
            }
            $class_id_arr = array();
            $class_id_arr[] = $group_id;
        }
        $list_ret = $this->_daoCertificateMember->listShow($class_id_arr);
        return $list_ret;
    }

    /*
     * @comment 添加新的权限成员
     * @param username 用户名
     * @param class_id 新的权限成员id
     * @param member_name 新的权限成员名
     * @return array
     */
    public function addCertificateMember($username, $class_id, $member_name, $is_admin)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if ($class_id != 0)
            {
                if ($is_admin != 1)
                {
                    if ($val["class_id"] == 0 || ($val["class_id"] == $class_id && $val["is_admin"] == 1)){
                        $perm = 1; break;
                    }
                }
                else
                {
                    if ($val['class_id'] == 0)
                    {
                        $perm = 1; break;
                    }
                }
            }
            else
            {
                if ($val["class_id"] == 0 && $val['is_admin'] == 1){
                    $perm = 1; break;
                }
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoCertificateMember->addCertificateMember($class_id, $member_name, $is_admin);
        return $list_ret;
    }

    /*
     * @comment 删除权限成员
     * @param username 用户名
     * @param id 所要删除的id
     * @return array
     */
    public function deleteCertificateMember($username, $class_id, $member_name)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }

        $perm = 0;
        foreach($group_arr as $key => $val){
            if ($class_id != 0)
            {
                if ($val["class_id"] == 0 || ($val["class_id"] == $class_id && $val["is_admin"] == 1)){
                    $perm = 1; break;
                }
            }
            else
            {
                if ($val["class_id"] == 0 && $val['is_admin'] == 1)
                {
                    $perm = 1; break;
                }
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoCertificateMember->deleteCertificateMember($class_id, $member_name);
        return $list_ret;
    }

    /*
     * @comment 更新权限成员
     * @param username 用户名
     * @param class_id 所要更新的权限成员id
     * @param member_name 所要更新的权限成员名
     * @return array
     */
    public function updateCertificateMember($username, $class_id, $member_name, $is_admin)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }

        $perm = 0;
        foreach($group_arr as $key => $val){
            if ($class_id != 0)
            {
                if($val["class_id"] == 0){
                    $perm = 1; break;
                }
                if ($is_admin != 1)
                {
                    if($val["class_id"] == $class_id && $val["is_admin"] == 1){
                        $perm++;
                    }
                }
                if($perm == 1){
                    break;
                }
            }
            else
            {
                if ($val['class_id'] == 0 and $val['is_admin'] == 1)
                {
                    $perm = 1;
                    break;
                }
            }
        }
        if($perm == 0){ 
            return array();
        }
        $update_ret = $this->_daoCertificateMember->updateCertificateMember($class_id, $member_name, $is_admin);
        return $update_ret;
    }

    /*
     * @comment 查看一个用户具有哪些权限
     * @param username 用户名
     * @param member_name 所要查看的权限成员名
     * @return array
     */
    public function getUserCerInfo($username, $member_name)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return false;
        }
        
        $cer_arr = array();
        $perm = 0;
        
        foreach($group_arr as $key => $val){
            if ($val['class_id'] == 0)
            {
                $perm = 1;
                if ($val['is_admin'] == 1)
                {
                    $perm = 2;
                }
                break;
            }
        }
        if ($perm == 0)
        {
            foreach($group_arr as $val)
            {
                if ($val['is_admin'] == 1)
                {
                    $cer_arr[] = $val['class_id'];
                }
            }
        }

        if ($perm == 0 && empty($cer_arr))
        {
            return false;
        }
        $select_ret = $this->_daoCertificateMember->getUserCerInfo($cer_arr, $member_name, $perm);
        return $select_ret;
    }
}
