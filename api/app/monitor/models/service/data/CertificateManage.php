<?php
/**
 * @name Service_Data_CertificateManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_CertificateManage {
    protected $_daoCertificateManage = null;

    public function __construct() {
        $this->_daoCertificateManage = new Dao_CertificateManage();
    }

    /*
     * @comment 获取用户所属权限组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoCertificateManage->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 管理员获取所有权限组名
     * @return array
     */
    public function listDistinctName($username)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val['class_id'] == 0 && $val['is_admin'] == 1){
                $perm = 1; break;
            }
        }

        $list_ret = $this->_daoCertificateManage->listShow();
        if ($list_ret === false)
        {
            return false;
        }
        $final_ret = array();
        if ($perm == 1)
        {
            $temp = array();
            $temp['id'] = '0';
            $temp['class_id'] = '0';
            $temp['class_name'] = '管理员组';
            $final_ret[] = $temp;
        }
        foreach ($list_ret as $val)
        {
            $final_ret[] = $val;
        }
        return $final_ret;
    }

    /*
     * @comment 添加新的权限组
     * @param class_name 新的权限组名
     * @return array
     */
    public function addCertificateManage($username, $class_name)
    {
        // 从idalloc获取相应id，作为唯一标示
        $ch = curl_init();
        $header = array('Content-Type: application/json');
        $curlOptions = array(
            CURLOPT_URL             =>  Monitor_Conf::idalloc_host . "?pid=monitor_cergrp_id",
            CURLOPT_RETURNTRANSFER	=>	true,
            CURLOPT_HEADER			=>	false,
            CURLOPT_FOLLOWLOCATION	=>	true,
            CURLOPT_HTTPHEADER      =>  $header,
        );
        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        if (0 != $errno)
        {
            return false;
        }
        curl_close($ch);
        $response = json_decode($response, 1);
        if ($response['error_no'] != 10000)
        {
            return false;
        }

        $class_id = $response['result_data'];

        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val['class_id'] == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoCertificateManage->addCertificateManage($class_id, $class_name);
        return $list_ret;
    }

    /*
     * @comment 删除权限组
     * @param username 用户名
     * @param id 所要删除的id
     * @return array
     */
    public function deleteCertificateManage($username, $class_id)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val['class_id'] == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoCertificateManage->deleteCertificateManage($class_id);
        return $list_ret;
    }

    /*
     * @comment 更新权限组
     * @param username 用户名
     * @param class_id 所要更新的权限组id
     * @param class_name 所要更新的权限组名
     * @return array
     */
    public function updateCertificateManage($username, $class_id, $class_name)
    {
        $group_arr = $this->GetGroup($username);
        if(!$group_arr){
            return array();
        }
        $perm = 0;
        foreach($group_arr as $key => $val){
            if($val['class_id'] == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoCertificateManage->updateCertificateManage($class_id, $class_name);
        return $list_ret;
    }
}
