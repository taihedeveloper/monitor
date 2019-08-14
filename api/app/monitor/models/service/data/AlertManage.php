<?php
/**
 * @name Service_Data_AlertManage
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Data_AlertManage {
    protected $_daoAlertManage = null;

    public function __construct() {
        $this->_daoAlertManage = new Dao_AlertManage();
    }

    /*
     * @comment 获取用户所属报警组
     * @param username 用户名
     * @return array
     */
    public function GetGroup($username)
    {
        $userGroup = $this->_daoAlertManage->get_group($username);
        if (!$userGroup)
        {
            return false;
        }
        return $userGroup;
    }

    /*
     * @comment 管理员获取所有报警组名
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
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoAlertManage->listShow();
        return $list_ret;
    }
    
     /*
     * @comment 添加新的报警组
     * @param class_name 新的报警组名
     * @return array
     */
    public function addAlertManage($username, $class_name)
    {
        // 从idalloc获取相应id，作为唯一标示
        $ch = curl_init();
        $header = array('Content-Type: application/json');
        $curlOptions = array(
            CURLOPT_URL             =>  Monitor_Conf::idalloc_host . "?pid=monitor_alertgrp_id",
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
            if($val == 0){
                $perm = 1; break;
            }
        }
        if($perm == 0){
            return array();
        }
        $list_ret = $this->_daoAlertManage->addAlertManage($class_id, $class_name);
        return $list_ret;
    }

    /*
     * @comment 删除报警组
     * @param username 用户名
     * @param id 所要删除的id
     * @return array
     */
    public function deleteAlertManage($username, $class_id)
    {
        $group_arr = $this->GetGroup($username);
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
        $list_ret = $this->_daoAlertManage->deleteAlertManage($class_id);
        return $list_ret;
    }

    /*
     * @comment 更新报警组
     * @param username 用户名
     * @param class_id 所要更新的报警组id
     * @param class_name 所要更新的报警组名
     * @return array
     */
    public function updateAlertManage($username, $class_id, $class_name)
    {
        $group_arr = $this->GetGroup($username);
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
        $list_ret = $this->_daoAlertManage->updateAlertManage($class_id, $class_name);
        return $list_ret;
    }

}
