<?php
/**
 * @name Service_Page_AlertMember
 * @desc
 * @author luohongcang@taihe.com
 */

class Service_Page_AlertMember {
    private $objServiceDataAlertMember;
    private $objServiceDataUserInfo;

    public function __construct(){
        $this->objServiceDataAlertMember = new Service_Data_AlertMember();
        $this->objServiceDataUserInfo = new Service_Data_UserInfo();
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $type = $arrInput['type'];
        $class_id = $arrInput['class_id'];
        $member_name = $arrInput['member_name'];
        $arrResult = array('error_code' => Monitor_Conf::SUCCESS);
        if($type == 1){
            $check_ret = $this->objServiceDataUserInfo->checkUser($member_name);
            if (!$check_ret)
            {
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
            }

            $add_result = $this->objServiceDataAlertMember->addAlertMember($username, $class_id, $member_name);
            if ($add_result === false)
            {
                $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
            }
            elseif (!$add_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $add_result;
            }
        }else if ($type == 2){
            $delete_result = $this->objServiceDataAlertMember->deleteAlertMember($username, $class_id, $member_name);
            if (!$delete_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $delete_result;
            }
        }elseif ($type == 4){
            $list_result = $this->objServiceDataAlertMember->listDistinctName($username, $class_id);
            if (!$list_result)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $list_result;
            }
        }
        elseif ($type == 5)
        {
            if (!$member_name)
            {
                return array('error_code' => Monitor_Conf::SUCCESS, 'data' => array());
            }
            $list_result = $this->objServiceDataAlertMember->getUserAlertInfo($username, $member_name);
            if ($list_result === false)
            {
                $arrResult['error_code'] = Monitor_Conf::FAILED;
            }
            else
            {
                $arrResult['data'] = $list_result;
            }
        }
        else
        {
            $arrResult['error_code'] = Monitor_Conf::PARAM_ERROR;
            $arrResult['error_msg'] = "type参数错误";
        }
        return $arrResult;
    }
}
